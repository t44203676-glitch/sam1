<?php
// api/search_requests.php
session_start(); // Start the session to access user credentials

require_once '../includes/database.php';
require_once '../includes/logger.php';
require_once '../includes/functions.php';

header('Content-Type: text/html; charset=utf-8');

if (!$pdo) {
    echo '<tr><td colspan="7" class="text-center">لا يمكن الاتصال بقاعدة البيانات.</td></tr>';
    exit;
}

try {
    $search = $_GET['search'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    $service_filter = $_GET['service'] ?? '';

    $user_type = $_SESSION['user_type'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    // Fetch all users to map created_by_user_id to email/username
    $users_map = [];
    $stmt_users = $pdo->query("SELECT user_id, username, user_type FROM system_users");
    while ($u = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
        $users_map[$u['user_id']] = [
            'username' => $u['username'],
            'user_type' => $u['user_type']
        ];
    }

    // Define service table names and their user-facing names
    // KEYS must match the 'service' GET parameter from the admin view
    // VALUES must be the exact database table names
    $services_tables_map = [
        'marriage_permits' => ['table' => 'marriage_permits', 'label' => 'تصريح زواج'],
        'family_visits' => ['table' => 'family_visits', 'label' => 'زيارة عائلية'],
        'business_visits' => ['table' => 'business_visits', 'label' => 'زيارة أعمال'],
        'tourist_visits' => ['table' => 'tourism_visits', 'label' => 'زيارة سياحية'],
        'recruitment' => ['table' => 'recruitment_requests', 'label' => 'استقدام'],
        'labor_office' => ['table' => 'labor_requests', 'label' => 'مكتب العمل'],
        'civil_affairs' => ['table' => 'civil_affairs_requests', 'label' => 'أحوال مدنية'],
        'profession_change' => ['table' => 'profession_changes', 'label' => 'تغيير مهنة'],
        'absconding_report_cancellation' => ['table' => 'runaway_cancellations', 'label' => 'إلغاء بلاغ هروب'],
        'followup' => ['table' => 'followup_requests', 'label' => 'التعقيب'],
    ];

    // If a service is filtered, only query that table. Otherwise, query all.
    $tables_to_query = [];
    if (!empty($service_filter) && isset($services_tables_map[$service_filter])) {
        $map = $services_tables_map[$service_filter];
        $tables_to_query[$map['table']] = $map['label'];
    }
    else {
        foreach ($services_tables_map as $key => $map) {
            $tables_to_query[$map['table']] = $map['label'];
        }
    }

    // Check which tables actually exist in the database
    $stmt = $pdo->query("SHOW TABLES");
    $all_db_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $existing_tables = array_intersect(array_keys($tables_to_query), $all_db_tables);

    $union_parts = [];
    // Define the columns we need to select from each table
    $common_columns = "id, export_number, applicant_name, national_id, created_at, status, created_by_user_id, is_locked";

    foreach ($existing_tables as $table_name) {
        // Standard columns for all tables
        // Cast text columns to CHAR CHARACTER SET utf8 to avoid "Illegal mix of collations" error
        $union_parts[] = "SELECT 
                            id, 
                            CAST(export_number AS CHAR CHARACTER SET utf8) as export_number, 
                            CAST(applicant_name AS CHAR CHARACTER SET utf8) as applicant_name, 
                            CAST(national_id AS CHAR CHARACTER SET utf8) as national_id, 
                            created_at, 
                            CAST(status AS CHAR CHARACTER SET utf8) as status, 
                            created_by_user_id, 
                            is_locked,
                            locked_by_user_id,
                            '{$table_name}' as source_table
                          FROM `{$table_name}`";
    }

    if (empty($union_parts)) {
        echo '<tr><td colspan="7" class="text-center">لا توجد خدمات مهيأة أو لم يتم العثور على طلبات.</td></tr>';
        exit;
    }

    $base_query = implode(" UNION ALL ", $union_parts);
    $sql = "SELECT * FROM ({$base_query}) AS all_requests";

    $params = [];
    $where_clauses = [];

    // Add search filters for name, national ID, or export number
    if (!empty($search)) {
        $where_clauses[] = "(applicant_name LIKE ? OR national_id LIKE ? OR export_number LIKE ?)";
        $search_param = "%{$search}%";
        $params = array_merge($params, [$search_param, $search_param, $search_param]);
    }

    // Add status filter
    if (!empty($status_filter)) {
        $where_clauses[] = "status = ?";
        $params[] = $status_filter;
    }

    // Hierarchy-based visibility filtering
    if ($user_type === 'Root') {
        // Root sees everything
    } elseif ($user_type === 'Admin') {
        // Fetch branch: Admin + their managers + their managers' employees
        $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users 
                               WHERE created_by_user_id = ? 
                               OR manager_id IN (SELECT user_id FROM system_users WHERE created_by_user_id = ?)
                               OR user_id = ?");
        $emp_stmt->execute([$user_id, $user_id, $user_id]);
        $branch_ids = $emp_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($branch_ids)) {
            $placeholders = str_repeat('?,', count($branch_ids) - 1) . '?';
            $where_clauses[] = "created_by_user_id IN ($placeholders)";
            $params = array_merge($params, $branch_ids);
        } else {
            $where_clauses[] = "1=0"; // No access if no branch (unlikely)
        }
    } elseif ($user_type === 'مدير' || $user_type === 'Manager') {
        // Fetch manager and their employees
        $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users WHERE manager_id = ? OR user_id = ?");
        $emp_stmt->execute([$user_id, $user_id]);
        $branch_ids = $emp_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!empty($branch_ids)) {
            $placeholders = str_repeat('?,', count($branch_ids) - 1) . '?';
            $where_clauses[] = "created_by_user_id IN ($placeholders)";
            $params = array_merge($params, $branch_ids);
        } else {
            $where_clauses[] = "1=0";
        }
    } else {
        // Employee or Other: See only self
        $where_clauses[] = "created_by_user_id = ?";
        $params[] = $user_id;
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(' AND ', $where_clauses);
    }

    $sql .= " ORDER BY created_at DESC LIMIT 100"; // Add a LIMIT to prevent excessively large results sets

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($requests)) {
        echo '<tr><td colspan="9" class="text-center">لا توجد طلبات تطابق معايير البحث.</td></tr>';
    }
    else {
        $total_reqs = count($requests);
        // تلوين أول 3 صفوف (الأحدث) بالألوان الباهتة
        $last3_colors = [
            0 => '#fffacc',  // الأحدث   - أصفر فاتح
            1 => '#d4f8d4',  // ثاني أحدث - أخضر فاتح
            2 => '#d0f0ff',  // ثالث أحدث - أزرق فاتح
        ];

        foreach ($requests as $row_idx => $request) {
            $status_class = 'status-' . str_replace([' ', '_'], '-', strtolower($request['status'] ?? ''));
            // Use the tables_to_query array to get the service label since it's indexed by table name
            $service_name = $tables_to_query[$request['source_table']] ?? 'غير معروف';

            // Map source_table to the appropriate form name for editing/viewing
            $form_name_map = [
                'marriage_permits' => 'marriage',
                'family_visits' => 'family_visit',
                'tourism_visits' => 'tourism',
                'business_visits' => 'business',
                'recruitment_requests' => 'recruitment',
                'labor_requests' => 'labor',
                'civil_affairs_requests' => 'civil_affairs',
                'profession_changes' => 'profession_change',
                'followup_requests' => 'followup',
            ];
            $form_name = $form_name_map[$request['source_table']] ?? '';
            $view_url = "index.php?admin=1&section=view_request&id={$request['id']}&table={$request['source_table']}";

            $row_bg  = isset($last3_colors[$row_idx]) ? ' style="--bs-table-bg:' . $last3_colors[$row_idx] . '; background-color:' . $last3_colors[$row_idx] . ' !important;"' : '';
            // الترقيم من الأسفل: أقدم طلب = 1، أحدث = الأعلى
            $row_num = $total_reqs - $row_idx;
            // التاريخ والوقت بتوقيت 12 ساعة بالعربية
            $ts_req   = strtotime($request['created_at']);
            $ampm_ar  = date('A', $ts_req) === 'AM' ? 'ص' : 'م';
            $date_str = date('Y-m-d', $ts_req) . ' ' . date('h:i', $ts_req) . ' ' . $ampm_ar;

            echo '<tr data-id="' . htmlspecialchars($request['id']) . '" data-source-table="' . htmlspecialchars($request['source_table']) . '"' . $row_bg . '>';
            echo '<td class="fw-bold text-muted">' . $row_num . '</td>';
            echo '<td data-field="export_number" data-label="رقم الصادر">' . htmlspecialchars(toWesternDigits($request['export_number'] ?? 'N/A')) . '</td>';
            echo '<td data-field="applicant_name" data-label="اسم مقدم الطلب">' . htmlspecialchars($request['applicant_name'] ?? 'N/A') . '</td>';
            echo '<td data-field="national_id" data-label="رقم الهوية">' . htmlspecialchars(toWesternDigits($request['national_id'] ?? 'N/A')) . '</td>';
            echo '<td data-label="نوع الطلب" class="small">' . htmlspecialchars($service_name) . '</td>';
            $creator_info = $users_map[$request['created_by_user_id']] ?? ['username' => 'Unknown', 'user_type' => 'Employee'];
            $creator_name = $creator_info['username'];
            echo '<td data-label="مدخل بواسطة"><div class="small fw-bold text-muted">' . htmlspecialchars($creator_name) . '</div></td>';
            echo '<td data-label="التاريخ" class="small">' . htmlspecialchars($date_str) . '</td>';
            echo '<td data-label="الحالة" data-field="status">' . get_status_badge($request['status'] ?? '') . '</td>';
            $is_locked = ($request['is_locked'] ?? 0) == 1;

            echo '<td data-label="الإجراءات" class="actions-cell">
                    <div class="btn-group btn-group-sm">';
            
            if ($is_locked) {
                // If locked, show different options based on role
                if (in_array($user_type, ['Root', 'Admin'])) {
                    $locker_id = $request['locked_by_user_id'];
                    $locker_info = $users_map[$locker_id] ?? ['user_type' => 'Root'];
                    $locker_role = $locker_info['user_type'];
                    
                    $can_unlock = false;
                    if ($user_type === 'Root') $can_unlock = true;
                    elseif ($user_type === 'Admin' && $locker_role !== 'Root') $can_unlock = true;

                    if ($can_unlock) {
                        echo ' <button class="btn btn-success btn-unlock-request" data-id="' . htmlspecialchars($request['id']) . '" data-table="' . htmlspecialchars($request['source_table']) . '" title="فك تعليق المعاملة">
                                    <i class="fas fa-lock-open"></i> فك التعليق
                                </button>';
                    } else {
                        echo ' <span class="badge bg-dark p-2"><i class="fas fa-lock me-1"></i> معلقة</span>';
                    }
                } else {
                    // Subordinates see only a badge
                    echo ' <span class="badge bg-dark p-2"><i class="fas fa-lock me-1"></i> معلقة</span>';
                }
            } else {
                // Not locked - show all normal actions
                echo ' <a href="' . $view_url . '" class="btn btn-outline-primary" title="عرض التفاصيل">
                            <i class="fas fa-eye"></i>
                        </a>';
            
                // Edit button visibility
                if ($user_type !== 'موظف') {
                    echo ' <button class="btn btn-outline-warning btn-edit" title="تعديل سريع">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-success btn-save" title="حفظ" style="display: none;">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-secondary btn-cancel" title="إلغاء" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>';
                }

                // Print button visibility
                if (in_array($user_type, ['Root', 'Admin', 'مدير', 'Manager'])) {
                    echo ' <a href="index.php?admin=1&section=print_view&id=' . htmlspecialchars($request['id']) . '&table=' . htmlspecialchars($request['source_table']) . '" target="_blank" class="btn btn-outline-secondary" title="طباعة">
                                <i class="fas fa-print"></i>
                            </a>';
                }

                // Lock button for Admins/Root
                if (in_array($user_type, ['Root', 'Admin'])) {
                    echo ' <button class="btn btn-outline-dark btn-lock-request" data-id="' . htmlspecialchars($request['id']) . '" data-table="' . htmlspecialchars($request['source_table']) . '" title="تعليق المعاملة (قفل)">
                                <i class="fas fa-user-lock"></i>
                            </button>';
                }

                // Delete button (Root only)
                if ($user_type === 'Root') {
                    echo ' <button class="btn btn-outline-danger btn-delete-request" data-id="' . htmlspecialchars($request['id']) . '" data-table="' . htmlspecialchars($request['source_table']) . '" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>';
                }
            }

            echo '  </div>
                  </td>';
            echo '</tr>';
        }
    }
}
catch (PDOException $e) {
    log_error("Search query failed in api/search_requests.php: " . $e->getMessage());
    echo '<tr><td colspan="7" class="text-center">حدث خطأ أثناء البحث. يرجى مراجعة سجل الأخطاء.</td></tr>';
}
