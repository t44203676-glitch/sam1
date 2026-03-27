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

    // Define service table names and their user-facing names
    // KEYS must match the 'service' GET parameter from the admin view
    // VALUES must be the exact database table names
    $services_tables_map = [
        'marriage_permits' => ['table' => 'marriage_permits', 'label' => 'تصريح زواج'],
        'family_visits' => ['table' => 'family_visits', 'label' => 'زيارة عائلية'],
        'business_visits' => ['table' => 'business_visits', 'label' => 'زيارة أعمال'],
        'tourist_visits' => ['table' => 'tourism_visits', 'label' => 'زيارة سياحية'], // Note: View uses 'tourist_visits', DB uses 'tourism_visits'
        'recruitment' => ['table' => 'recruitment_requests', 'label' => 'استقدام'],
        'labor_office' => ['table' => 'labor_requests', 'label' => 'مكتب العمل'],
        'civil_affairs' => ['table' => 'civil_affairs_requests', 'label' => 'أحوال مدنية'],
        'nationality' => ['table' => 'civil_affairs_requests', 'label' => 'الجنسية'], // Assuming nationality is part of civil affairs or handled similarly if no specific table existed
        'profession_change' => ['table' => 'profession_changes', 'label' => 'تغيير مهنة'],
        'absconding_report_cancellation' => ['table' => 'runaway_cancellations', 'label' => 'إلغاء بلاغ هروب'],
    ];

    // If a service is filtered, only query that table. Otherwise, query all.
    $tables_to_query = [];
    if (!empty($service_filter) && isset($services_tables_map[$service_filter])) {
        $map = $services_tables_map[$service_filter];
        $tables_to_query[$map['table']] = $map['label'];
    } else {
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
    $common_columns = "id, export_number, applicant_name, national_id, created_at, status, created_by_user_id";

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

    // IMPORTANT: Add security filter for non-manager users ('موظف')
    if ($user_type === 'موظف' && $user_id) {
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
        echo '<tr><td colspan="7" class="text-center">لا توجد طلبات تطابق معايير البحث.</td></tr>';
    } else {
        foreach ($requests as $request) {
            $status_class = 'status-' . str_replace([' ', '_'], '-', strtolower($request['status'] ?? ''));
            // Use the tables_to_query array to get the service label since it's indexed by table name
            $service_name = $tables_to_query[$request['source_table']] ?? 'غير معروف';

            echo '<tr data-id="' . htmlspecialchars($request['id']) . '" data-source-table="' . htmlspecialchars($request['source_table']) . '">';
            echo '<td data-field="export_number">' . htmlspecialchars($request['export_number'] ?? 'N/A') . '</td>';
            echo '<td data-field="applicant_name">' . htmlspecialchars($request['applicant_name'] ?? 'N/A') . '</td>';
            echo '<td data-field="national_id">' . htmlspecialchars($request['national_id'] ?? 'N/A') . '</td>';
            echo '<td data-field="permit_type">' . htmlspecialchars($service_name) . '</td>'; // Show the main service name
            echo '<td>' . htmlspecialchars(convertToHijri($request['created_at'])) . '</td>';
            echo '<td><span class="request-status ' . $status_class . '" data-field="status">' . htmlspecialchars($request['status']) . '</span></td>';
            echo '<td>
                    <div class="btn-group">
                        <a href="index.php?admin=1&section=view_request&id=' . htmlspecialchars($request['id']) . '&source_table=' . htmlspecialchars($request['source_table']) . '" class="btn btn-sm btn-outline-primary" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-warning btn-edit" title="تعديل" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-primary btn-save" title="حفظ" style="display: none;" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-save me-1"></i> حفظ
                        </button>
                        <a href="index.php?admin=1&section=print_templates&id=' . htmlspecialchars($request['id']) . '&source_table=' . htmlspecialchars($request['source_table']) . '" target="_blank" class="btn btn-sm btn-secondary" title="طباعة" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-print"></i>
                        </a>
                        <button class="btn btn-sm btn-danger btn-delete" title="حذف" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                  </td>';
            echo '</tr>';
        }
    }
} catch (PDOException $e) {
    log_error("Search query failed in api/search_requests.php: " . $e->getMessage());
    echo '<tr><td colspan="7" class="text-center">حدث خطأ أثناء البحث. يرجى مراجعة سجل الأخطاء.</td></tr>';
}
