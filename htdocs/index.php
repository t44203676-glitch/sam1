<?php
// مكتب الخدمات - نظام متكامل (ملف التحكم الرئيسي)
ob_start();
session_start();

// تضمين الملفات الأساسية
require_once 'includes/database.php';
require_once 'includes/logger.php'; // تضمين ملف تسجيل الأخطاء
require_once 'includes/functions.php';

// (اختياري) استدعاء دالة إنشاء الجداول عند الحاجة (أثناء التثبيت الأولي)
$message = '';

// التحقق من حالة قاعدة البيانات - منع الشاشة البيضاء
if (USE_DATABASE && !$pdo) {
    $detail = defined('DB_CONNECTION_ERROR') ? "<p style='color:red;'>Detail: " . DB_CONNECTION_ERROR . "</p>" : "";
    die("<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>
            <h2 style='color:#d9534f;'>خطأ في الاتصال بقاعدة البيانات</h2>
            <p>النظام غير قادر على الاتصال بالسيرفر. يرجى التحقق من إعدادات الملف <code>includes/database.php</code></p>
            <p style='color:#666; font-size:13px;'>Host: " . (defined('DB_HOST') ? DB_HOST : 'N/A') . "</p>
            $detail
         </div>");
}

// معالجة تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// معالجة النماذج (POST requests)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (USE_DATABASE && $pdo) {
        try {
            switch ($_POST['action']) {
                case 'admin_login':
                    $username = trim($_POST['username'] ?? '');
                    $password = trim($_POST['password'] ?? '');
                    // التحقق من اسم المستخدم وكلمة المرور مباشرة في قاعدة البيانات
                    $stmt = $pdo->prepare("SELECT * FROM system_users WHERE username = ? AND password = ?");
                    $stmt->execute([$username, $password]);
                    $user = $stmt->fetch();

                    if ($user) { // إذا تم العثور على مستخدم مطابق
                        if (isset($user['is_banned']) && $user['is_banned'] == 1) {
                            $_SESSION['flash_message'] = "هذا الحساب محظور. يرجى مراجعة الإدارة.";
                            $_SESSION['flash_type'] = "error";
                            header('Location: index.php?login=1');
                            exit;
                        }

                        $_SESSION['logged_in'] = true;
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['user_type'] = $user['user_type'];
                        if (isset($user['manager_id'])) {
                            $_SESSION['manager_id'] = $user['manager_id'];
                        }

                        // Log activity
                        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
                        $pdo->prepare("UPDATE system_users SET login_count = login_count + 1, last_login = NOW(), last_ip = ? WHERE user_id = ?")->execute([$ip, $user['user_id']]);
                        $pdo->prepare("INSERT INTO user_logs (user_id, ip_address, user_agent) VALUES (?, ?, ?)")->execute([$user['user_id'], $ip, $ua]);

                        header('Location: index.php?admin=1');
                        exit;
                    }
                    else {

                        $_SESSION['flash_message'] = "خطأ في اسم المستخدم أو كلمة المرور.";
                        $_SESSION['flash_type'] = "error";
                        header('Location: index.php?login=1');
                        exit;
                    }

                case 'subscribe':
                    $stmt = $pdo->prepare("INSERT IGNORE INTO subscribers (email) VALUES (?)");
                    $stmt->execute([$_POST['email']]);

                    $_SESSION['flash_message'] = "شكراً لاشتراكك في النشرة البريدية.";
                    $_SESSION['flash_type'] = "success";
                    header('Location: index.php'); // Redirect to avoid re-submission
                    exit;

                // --- قسم إدارة المستخدمين ---
                case 'add_user':
                    if (in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير', 'Manager'])) {
                        $username = $_POST['username'] ?? '';
                        $password = $_POST['password'] ?? '';
                        $new_user_type = $_POST['user_type'] ?? 'موظف';
                        $req_manager_id = isset($_POST['manager_id']) && !empty($_POST['manager_id']) ? $_POST['manager_id'] : null;

                        // Security scope checking
                        if ($_SESSION['user_type'] === 'Admin') {
                            // Admin can create Managers or Employees
                            if (!in_array($new_user_type, ['مدير', 'Manager', 'موظف', 'Employee'])) {
                                $new_user_type = 'موظف';
                            }
                        }
                        elseif ($_SESSION['user_type'] !== 'Root') {
                            $new_user_type = 'موظف'; // Managers can only create Employees
                            $req_manager_id = $_SESSION['user_id'];
                        }

                        // التحقق من عدم تكرار اسم المستخدم
                        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM system_users WHERE username = ?");
                        $check_stmt->execute([$username]);
                        if ($check_stmt->fetchColumn() > 0) {
                            $_SESSION['flash_message'] = "اسم المستخدم \"$username\" موجود مسبقاً. يرجى اختيار اسم مستخدم آخر.";
                            $_SESSION['flash_type'] = "error";
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                            exit;
                        }

                        $stmt = $pdo->prepare("INSERT INTO system_users (username, password, user_type, manager_id, created_by_user_id) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $password, $new_user_type, $req_manager_id, $_SESSION['user_id']]);
                        $_SESSION['flash_message'] = "تم إضافة المستخدم بنجاح.";
                        $_SESSION['flash_type'] = "success";

                        $redirect_section = (in_array($new_user_type, ['مدير', 'Manager'])) ? 'manage_managers' : 'manage_employees';
                        header('Location: index.php?admin=1&section=' . $redirect_section);
                        exit;
                    }
                    break;

                case 'update_user':
                    if (in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير', 'Manager'])) {
                        $target_user_id = $_POST['user_id'];
                        $username = $_POST['username'];
                        $new_user_type = $_POST['user_type'] ?? 'موظف';
                        $password = $_POST['password'] ?? '';
                        $is_banned = isset($_POST['is_banned']) ? $_POST['is_banned'] : 0;
                        $req_manager_id = isset($_POST['manager_id']) && !empty($_POST['manager_id']) ? $_POST['manager_id'] : null;

                        // Check permissions
                        if (!in_array($_SESSION['user_type'], ['Root', 'Admin'])) {
                            // Managers can only update their own employees or themselves
                            $stmt_check = $pdo->prepare("SELECT user_type, manager_id FROM system_users WHERE user_id = ?");
                            $stmt_check->execute([$target_user_id]);
                            $target_user = $stmt_check->fetch();

                            if ($target_user_id != $_SESSION['user_id'] && $target_user['manager_id'] != $_SESSION['user_id'] && $target_user['created_by_user_id'] != $_SESSION['user_id']) {
                                $_SESSION['flash_message'] = "صلاحيات غير كافية.";
                                header('Location: index.php?admin=1&section=manage_users');
                                exit;
                            }

                            $new_user_type = $target_user['user_type']; // Prevent privilege escalation
                            $req_manager_id = $target_user['manager_id']; // Keep original manager
                        }

                        if (!empty($password)) {
                            // تحديث كلمة المرور
                            $stmt = $pdo->prepare("UPDATE system_users SET username = ?, user_type = ?, manager_id = ?, password = ?, is_banned = ? WHERE user_id = ?");
                            $stmt->execute([$username, $new_user_type, $req_manager_id, $password, $is_banned, $target_user_id]);
                        }
                        else {
                            // تحديث بدون كلمة المرور
                            $stmt = $pdo->prepare("UPDATE system_users SET username = ?, user_type = ?, manager_id = ?, is_banned = ? WHERE user_id = ?");
                            $stmt->execute([$username, $new_user_type, $req_manager_id, $is_banned, $target_user_id]);
                        }
                        $_SESSION['flash_message'] = "تم التحديث بنجاح.";
                        $_SESSION['flash_type'] = "success";

                        $redirect_section = ($new_user_type === 'مدير' || $new_user_type === 'Manager') ? 'manage_managers' : 'manage_employees';
                        header('Location: index.php?admin=1&section=' . $redirect_section);
                        exit;
                    }
                    break;

                case 'delete_user':
                    if (in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير', 'Manager']) && isset($_POST['user_id']) && $_POST['user_id'] != $_SESSION['user_id']) {
                        $target_user_id = $_POST['user_id'];

                        if (!in_array($_SESSION['user_type'], ['Root', 'Admin'])) {
                            $stmt_check = $pdo->prepare("SELECT manager_id FROM system_users WHERE user_id = ?");
                            $stmt_check->execute([$target_user_id]);
                            $tuser = $stmt_check->fetch();
                            if ($tuser['manager_id'] != $_SESSION['user_id'] && $tuser['created_by_user_id'] != $_SESSION['user_id']) {
                                $_SESSION['flash_message'] = "صلاحيات غير كافية.";
                                header('Location: index.php?admin=1');
                                exit;
                            }
                        }

                        $stmt = $pdo->prepare("DELETE FROM system_users WHERE user_id = ?");
                        $stmt->execute([$target_user_id]);
                        $_SESSION['flash_message'] = "تم حذف المستخدم بنجاح.";
                        $_SESSION['flash_type'] = "success";
                        header('Location: index.php?admin=1'); // Back to dashboard since we don't know the exact section easily without checking type
                        exit;
                    }
                    break;
                // --- نهاية قسم إدارة المستخدمين ---

                case 'admin_inquiry':
                    // هذا القسم خاص باستعلام الموظف من لوحة التحكم
                    // Ensure helper functions are available for CAPTCHA validation
                    require_once 'includes/functions.php';

                    // This now uses the marriage_permits table, similar to the public inquiry.
                    $stmt = $pdo->prepare("
                        SELECT 
                            mp.*
                        FROM marriage_permits mp
                        WHERE (mp.husband_id = ? OR mp.wife_id = ?) AND mp.export_number = ?
                    ");
                    $stmt->execute([$_POST['idNum'], $_POST['idNum'], $_POST['issueNum']]);
                    $request = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($request) {
                        // Determine if the national ID matched the husband or wife
                        if ($request['husband_id'] == $_POST['idNum']) {
                            // Fetch related partners for marriage_permits
                            $related_partners = [];
                            $stmt_related = $pdo->prepare("SELECT * FROM related_data WHERE marriage_permit_id = ?");
                            $stmt_related->execute([$request['id']]);
                            $related_partners = $stmt_related->fetchAll(PDO::FETCH_ASSOC);

                            $request['full_name'] = $request['husband_name'];
                            $request['national_id'] = $request['husband_id'];
                        }
                        elseif ($request['wife_id'] == $_POST['idNum']) {
                            $request['full_name'] = $request['wife_name'];
                            $request['national_id'] = $request['wife_id'];
                        }

                        // Map existing fields for consistency with smart_inquiry.php and result view
                        $request['permit_id'] = $request['id']; // Use 'id' from mp.* as permit_id
                        $request['issue_number'] = $request['export_number'];
                        $request['request_date'] = $request['created_at'];
                        $request['service_name'] = 'تصريح زواج'; // Explicitly set service name
                        $request['status'] = $request['status']; // Use status as status


                        // Ensure these fields are available for marriage_inquiry_result.php
                        $request['serial_number'] = $request['serial_number'] ?? '---';
                        $request['wife_name'] = $request['wife_name'] ?? '---'; // Assuming the table row is for the wife
                        $request['remarks'] = $request['remarks'] ?? 'لا يوجد';

                        $related_partners = [];
                        try {
                            $stmt_related = $pdo->prepare("SELECT * FROM related_data WHERE marriage_permit_id = ?");
                            $stmt_related->execute([$request['id']]);
                            $related_partners = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
                            $request['related_partners'] = $related_partners; // Add to request array
                        }
                        catch (PDOException $e) {
                            log_error("Could not fetch related data for marriage_permits in admin_inquiry: " . $e->getMessage(), __FILE__, __LINE__);
                            $request['related_partners'] = [];
                        }

                        // If related partners exist, use the first one's job_category and nationality for the main row
                        if (!empty($related_partners) && is_array($related_partners)) {
                            $firstPartner = $related_partners[0];
                            $request['job_category'] = $firstPartner['job_category'] ?? 'غير متوفر';
                            $request['wife_name'] = $firstPartner['full_name'] ?? $request['wife_name'];
                            $request['nationality'] = $firstPartner['nationality'] ?? 'غير متوفر';

                            $request['arrival_place'] = $firstPartner['country'] ?? 'غير متوفر';
                        }

                        $show_inquiry_result = true; // Set flag to display result page
                    }
                    else {
                        $_SESSION['flash_message'] = "لم يتم العثور على معاملة بالبيانات المدخلة.";
                        $_SESSION['flash_type'] = "error";
                    }
                    break;

                case 'admin_print':
                    if (in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير', 'Manager'])) {
                        $id = $_POST['id'] ?? null;
                        $table = $_POST['table'] ?? null;

                        $table_to_type = [
                            'marriage_permits' => 'تصريح زواج',
                            'family_visits' => 'زيارة عائلية',
                            'tourism_visits' => 'زيارة سياحية',
                            'business_visits' => 'زيارة تجارية',
                            'labor_requests' => 'تعقيب العمالة',
                            'followup_requests' => 'التعقيب',
                            'profession_changes' => 'تعديل مهنة',
                            'civil_affairs_requests' => 'أحوال مدنية',
                            'recruitment_requests' => 'استقدام'
                        ];

                        if ($id && isset($table_to_type[$table])) {
                            // Increment print count and lock if it reaches 2
                            $pdo->prepare("UPDATE `$table` SET printed_count = printed_count + 1, is_locked = CASE WHEN (printed_count + 1) >= 2 THEN 1 ELSE is_locked END WHERE id = ?")->execute([$id]);

                            $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
                            $stmt->execute([$id]);
                            $request = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($request) {
                                // Standardize fields for inquiry result pages (mirrors SmartInquiry logic)
                                $request['full_name'] = $request['applicant_name'] ?? $request['full_name'] ?? 'غير متوفر';
                                $request['issue_number'] = $request['export_number'] ?? $request['issue_number'] ?? '---';
                                $request['request_date'] = $request['created_at'] ?? $request['request_date'] ?? date('Y-m-d');
                                $request['service_name'] = $table_to_type[$table];

                                // Ensure other common fields are available
                                $request['serial_number'] = $request['serial_number'] ?? '---';
                                $request['status'] = $request['status'] ?? '---';
                                $request['remarks'] = $request['remarks'] ?? 'لا يوجد';

                                // Add related partners if needed by the templates
                                $foreignKeyMap = [
                                    'marriage_permits' => 'marriage_permit_id',
                                    'family_visits' => 'family_visit_id',
                                    'business_visits' => 'business_visit_id',
                                    'tourism_visits' => 'tourism_visit_id',
                                    'recruitment_requests' => 'recruitment_request_id',
                                    'labor_requests' => 'labor_request_id',
                                    'civil_affairs_requests' => 'civil_affairs_request_id',
                                    'profession_changes' => 'profession_change_id',
                                    'followup_requests' => 'followup_request_id',
                                ];
                                $fk = $foreignKeyMap[$table] ?? null;
                                if ($fk) {
                                    $stmt_related = $pdo->prepare("SELECT * FROM related_data WHERE `$fk` = ?");
                                    $stmt_related->execute([$id]);
                                    $request['related_partners'] = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
                                }

                                $_SESSION['inquiry_result'] = [
                                    'success' => true,
                                    'type' => $table_to_type[$table],
                                    'data' => $request
                                ];
                                header('Location: index.php?page=inquiry_result');
                                exit;
                            }
                        }
                    }
                    break;

                default:
            }
        }
        catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>حدث خطأ في قاعدة البيانات: " . $e->getMessage() . "</div>";
            $_SESSION['flash_message'] = "حدث خطأ في قاعدة البيانات: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
        }
    }
}


// جلب البيانات للعرض
$services = []; // For home page (limited)
$stats = [];
$all_requests = [];
$all_services = []; // تهيئة المتغير لتجنب التحذير

$is_admin = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

// --- تعديل ديناميكي: التحقق من صحة الجلسة وتحديث النوع آلياً ---
if ($is_admin && USE_DATABASE && $pdo) {
    try {
        $check_stmt = $pdo->prepare("SELECT user_type, is_banned FROM system_users WHERE user_id = ?");
        $check_stmt->execute([$_SESSION['user_id']]);
        $curr_user = $check_stmt->fetch();

        if (!$curr_user || (isset($curr_user['is_banned']) && $curr_user['is_banned'] == 1)) {
            session_destroy();
            header('Location: index.php?login=1');
            exit;
        }
        // تحديث النوع في الجلسة في كل طلب لضمان المزامنة مع قاعدة البيانات
        $_SESSION['user_type'] = $curr_user['user_type'];
    }
    catch (PDOException $e) {
        error_log("Session re-validation error: " . $e->getMessage());
    }
}

$admin_view = isset($_GET['admin']) && $is_admin;
$login_view = isset($_GET['login']) || !$is_admin; // Force login if not authenticated

// If not logged in and not currently processing a login action, ensure we are in login view
if (!$is_admin && (!isset($_POST['action']) || $_POST['action'] !== 'admin_login')) {
    $login_view = true;
    $admin_view = false;
}

if (USE_DATABASE && $pdo) {
    if (!$admin_view && !$login_view) { // If not admin or login view
        // Fetch limited services for home page
        try {
            $stmt_home_services = $pdo->prepare("SELECT * FROM services WHERE is_active = TRUE LIMIT 3");
            $stmt_home_services->execute();
            $services = $stmt_home_services->fetchAll(PDO::FETCH_ASSOC);

            // Fetch all services for the dedicated services page
            $stmt_all_services = $pdo->prepare("SELECT * FROM services WHERE is_active = TRUE");
            $stmt_all_services->execute();
            $all_services = $stmt_all_services->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            // If services table doesn't exist, use mock data
            error_log("Services table error: " . $e->getMessage());
            $services = [];
            $all_services = [];
        }
    }

    if ($admin_view) {
        try {
            $user_type = $_SESSION['user_type'] ?? null;
            $user_id = $_SESSION['user_id'] ?? null;

            // Fetch a list of all tables in the database to dynamically build queries
            $stmt = $pdo->query("SHOW TABLES");
            $all_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $services_tables = [
                'marriage_permits', 'family_visits', 'business_visits', 'tourist_visits',
                'recruitment_requests', 'labor_requests', 'civil_affairs_requests',
                'profession_changes', 'followup_requests',
            ];

            // Filter the list to only include tables that actually exist
            $existing_services_tables = array_intersect($services_tables, $all_tables);

            $union_queries = [];
            $stats_params = [];

            // Build the UNION ALL query only for existing tables
            foreach ($existing_services_tables as $table) {
                // Dynamically check for columns to prevent SQL errors if a table has a different schema
                $stmt_cols = $pdo->query("DESCRIBE `$table`");
                $available_columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);

                // Cast 'status' to utf8 to avoid collation errors in UNION
                $status_col = in_array('status', $available_columns) ? 'CAST(status AS CHAR CHARACTER SET utf8) as status' : 'NULL as status';
                $created_at_col = in_array('created_at', $available_columns) ? 'created_at' : 'NULL as created_at';
                $updated_at_col = in_array('updated_at', $available_columns) ? 'updated_at' : 'NULL as updated_at';
                $created_by_user_id_col = in_array('created_by_user_id', $available_columns) ? 'created_by_user_id' : 'NULL as created_by_user_id';
                $export_number_col = in_array('export_number', $available_columns) ? 'export_number' : "NULL as export_number";
                $applicant_name_col = in_array('applicant_name', $available_columns) ? 'applicant_name' : (in_array('husband_name', $available_columns) ? 'husband_name' : "NULL as applicant_name");
                $national_id_col = in_array('national_id', $available_columns) ? 'national_id' : (in_array('husband_id', $available_columns) ? 'husband_id' : "NULL as national_id");
                $printed_count_col = in_array('printed_count', $available_columns) ? 'printed_count' : "0 as printed_count";

                $is_locked_col = in_array('is_locked', $available_columns) ? 'is_locked' : "0 as is_locked";
                $locked_by_col = in_array('locked_by_user_id', $available_columns) ? 'locked_by_user_id' : "NULL as locked_by_user_id";

                $union_queries[] = "SELECT id, '$table' as source_table, $export_number_col, $applicant_name_col, $national_id_col, $status_col, $created_at_col, $updated_at_col, $created_by_user_id_col, $printed_count_col, $is_locked_col, $locked_by_col FROM `$table`";
            }

            if (empty($union_queries)) {
                // If no service tables exist, initialize stats to zero to prevent errors
                $stats = ['new_requests' => 0, 'pending_approval' => 0, 'rejected_requests' => 0, 'total_requests' => 0, 'today_requests' => 0];
                $requests_by_status = [];
                $weekly_stats = ['labels' => [], 'data' => []];
                $all_statuses = [];
                $all_requests = []; // Also initialize this for other admin views
            }
            else {
                $full_query = implode(" UNION ALL ", $union_queries);
                $base_sql = "FROM ({$full_query}) AS all_requests ";

                $where_clause = '';
                if ($user_type === 'Root') {
                    $where_clause = ""; // Root sees everything
                }
                elseif ($user_type === 'Admin') {
                    // Fetch branch: Admin + their managers + their managers' employees
                    $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users 
                                           WHERE created_by_user_id = ? 
                                           OR manager_id IN (SELECT user_id FROM system_users WHERE created_by_user_id = ?)
                                           OR user_id = ?");
                    $emp_stmt->execute([$user_id, $user_id, $user_id]);
                    $emps = $emp_stmt->fetchAll(PDO::FETCH_COLUMN);
                    $in_placeholders = str_repeat('?,', count($emps) - 1) . '?';
                    $where_clause = "WHERE created_by_user_id IN ($in_placeholders)";
                    $stats_params = $emps;
                }
                elseif ($user_type === 'مدير' || $user_type === 'Manager') {
                    // Fetch manager and their employees
                    $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users WHERE manager_id = ?");
                    $emp_stmt->execute([$user_id]);
                    $emps = $emp_stmt->fetchAll(PDO::FETCH_COLUMN);
                    $emps[] = $user_id; // Add the manager's own ID
                    $in_placeholders = str_repeat('?,', count($emps) - 1) . '?';
                    $where_clause = "WHERE created_by_user_id IN ($in_placeholders)";
                    $stats_params = $emps;
                }
                else { // Employee
                    $where_clause = "WHERE created_by_user_id = ?";
                    $stats_params[] = $user_id;
                }

                // 1. Fetch General Stats Efficiently
                $today_date = date('Y-m-d');
                $stats_sql = "SELECT 
                COUNT(*) AS total_requests,
                SUM(CASE WHEN status = 'جديد' THEN 1 ELSE 0 END) AS new_requests,
                SUM(CASE WHEN status = 'بانتظار موافقة المدير' THEN 1 ELSE 0 END) AS pending_approval,
                SUM(CASE WHEN status = 'مرفوض' THEN 1 ELSE 0 END) AS rejected_requests,
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) AS today_requests
            " . $base_sql . $where_clause;

                $stmt_stats = $pdo->prepare($stats_sql);
                // Prepend today's date to the params array for the `today_requests` calculation
                $current_stats_params = array_merge([$today_date], $stats_params);
                $stmt_stats->execute($current_stats_params);
                $stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

                // 2. Fetch Requests by Status for Pie Chart
                $status_dist_sql = "SELECT status, COUNT(*) as count " . $base_sql . (empty($where_clause) ? 'WHERE status IS NOT NULL' : $where_clause . " AND status IS NOT NULL") . " GROUP BY status";
                $stmt_status_dist = $pdo->prepare($status_dist_sql);
                $stmt_status_dist->execute($stats_params);
                $requests_by_status = $stmt_status_dist->fetchAll(PDO::FETCH_ASSOC);

                // 3. Fetch Weekly Stats for Line Chart
                $date_filter_sql = "created_at >= CURDATE() - INTERVAL 6 DAY";
                $weekly_stats_sql = "SELECT DATE(created_at) as date, COUNT(*) as count " . $base_sql . " WHERE " . $date_filter_sql . " " . (empty($where_clause) ? '' : 'AND' . substr($where_clause, 5)) . " GROUP BY DATE(created_at) ORDER BY date ASC";

                $stmt_weekly = $pdo->prepare($weekly_stats_sql);
                $stmt_weekly->execute($stats_params);
                $weekly_data_from_db = $stmt_weekly->fetchAll(PDO::FETCH_KEY_PAIR);

                $weekly_stats = ['labels' => [], 'data' => []];
                $today = new DateTime();
                for ($i = 6; $i >= 0; $i--) {
                    $date = (clone $today)->modify("-$i days");
                    $day_str_key = $date->format('Y-m-d');
                    $weekly_stats['labels'][] = $date->format('D'); // e.g., "Sat"
                    $weekly_stats['data'][] = $weekly_data_from_db[$day_str_key] ?? 0;
                }

                // 4. Fetch all unique statuses for the filter dropdown
                $all_statuses_sql = "SELECT DISTINCT status FROM ({$full_query}) as all_req WHERE status IS NOT NULL AND status != '' ORDER BY status";
                $stmt_statuses = $pdo->query($all_statuses_sql);
                $db_statuses = $stmt_statuses->fetchAll(PDO::FETCH_COLUMN);

                // دمج الحالات المحددة مسبقاً مع الحالات الموجودة في قاعدة البيانات
                $predefined_statuses = ['بانتظار موافقة المدير', 'تمت الموافقة', 'قيد المراجعة', 'تم تعليق المعاملة', 'مرفوض', 'تمت المراجعة', 'معلق'];
                $all_statuses = array_values(array_unique(array_merge($predefined_statuses, $db_statuses)));

                // This part is for other admin views like pending_requests and rejected_cases
                // It's not used by the main dashboard but is needed for those specific pages.
                // We use a different query here to get all columns needed for the detailed lists.
                $all_requests_queries = [];
                foreach ($existing_services_tables as $table) {
                    // Cast all text columns to CHAR CHARACTER SET utf8 to avoid "Illegal mix of collations"
                    // Using standard columns: id, export_number, status, created_at, created_by_user_id, applicant_name, national_id

                    $export_col = "CAST(export_number AS CHAR CHARACTER SET utf8) as export_number";
                    $status_col = "CAST(status AS CHAR CHARACTER SET utf8) as status";

                    // All tables, including marriage_permits, use 'applicant_name' and 'national_id'
                    $name_col = "CAST(applicant_name AS CHAR CHARACTER SET utf8) as applicant_name";
                    $nid_col = "CAST(national_id AS CHAR CHARACTER SET utf8) as national_id";

                    $is_locked_col = "CAST(is_locked AS UNSIGNED) as is_locked";
                    $locked_by_col = "CAST(locked_by_user_id AS UNSIGNED) as locked_by_user_id";

                    $all_requests_queries[] = "SELECT id, $export_col, $status_col, created_at, created_by_user_id, $name_col, $nid_col, printed_count, $is_locked_col, $locked_by_col, '{$table}' as source_table FROM `{$table}`";
                }
                $all_requests_union_sql = implode(" UNION ALL ", $all_requests_queries);
                $all_requests_sql = "SELECT * FROM ({$all_requests_union_sql}) as requests " . $where_clause . " ORDER BY created_at DESC";

                $stmt_all_req = $pdo->prepare($all_requests_sql);
                $stmt_all_req->execute($stats_params);
                $all_requests = $stmt_all_req->fetchAll(PDO::FETCH_ASSOC);
            }

        }
        catch (PDOException $e) {
            log_error("Admin data fetch error: " . $e->getMessage(), __FILE__, __LINE__);
            $message = "<div class='alert alert-danger'>حدث خطأ أثناء جلب البيانات للوحة التحكم.</div>";
            // Initialize with empty data to prevent frontend errors
            $stats = ['new_requests' => 0, 'pending_approval' => 0, 'rejected_requests' => 0, 'total_requests' => 0, 'today_requests' => 0];
            $requests_by_status = [];
            $weekly_stats = ['labels' => [], 'data' => []];
            $all_statuses = [];
            $all_requests = [];
        }

        // Fetch user data for 'manage_users' and 'manage_managers' section
        $created_by_col = ", created_by_user_id";
        if ($_SESSION['user_type'] === 'Root') {
            $stmt_users = $pdo->query("SELECT user_id, username as email, password, user_type, created_at, manager_id, created_by_user_id, is_banned, login_count, last_login, last_ip FROM system_users ORDER BY created_at DESC");
            $all_users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        }
        elseif ($_SESSION['user_type'] === 'Admin') {
            // Admin sees managers they created AND employees of those managers
            $sql = "SELECT user_id, username as email, password, user_type, created_at, manager_id, created_by_user_id, is_banned, login_count, last_login, last_ip 
                FROM system_users 
                WHERE created_by_user_id = ? 
                OR manager_id IN (SELECT user_id FROM system_users WHERE created_by_user_id = ? AND user_type = 'مدير')
                OR user_id = ?
                ORDER BY created_at DESC";
            $stmt_users = $pdo->prepare($sql);
            $stmt_users->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
            $all_users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        }
        elseif ($_SESSION['user_type'] === 'مدير' || $_SESSION['user_type'] === 'Manager') {
            $stmt_users = $pdo->prepare("SELECT user_id, username as email, password, user_type, created_at, manager_id, created_by_user_id, is_banned, login_count, last_login, last_ip FROM system_users WHERE manager_id = ? OR user_id = ? ORDER BY created_at DESC");
            $stmt_users->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
            $all_users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        }    }
}
// عرض الواجهات
$services_page_view = isset($_GET['page']) && $_GET['page'] === 'services';
$inquiry_page_view = isset($_GET['page']) && $_GET['page'] === 'inquiry';
$inquiry_result_page_view = isset($_GET['page']) && $_GET['page'] === 'inquiry_result';
$add_data_page_view = isset($_GET['page']) && $_GET['page'] === 'add_data';
// تحقق مما إذا كان يجب عرض صفحة نتائج الاستعلام
$show_inquiry_result = $show_inquiry_result ?? false;

// --- تعديل: فصل منطق عرض الصفحات عن تضمين الهيدر والفوتر ---

// تحديد المحتوى الرئيسي بناءً على المتغيرات
if ($login_view && !$is_admin) {
    // المستخدم غير مسجل - عرض صفحة تسجيل الدخول فقط
    require_once 'views/header.php';
    require_once 'views/login.php';
}
elseif ($admin_view && isset($_GET['section']) && $_GET['section'] === 'view_request' && isset($_GET['id'])) {
    require_once 'views/header.php';
    
    $table = $_GET['table'] ?? $_GET['source_table'] ?? 'marriage_permits';
    $id = $_GET['id'];
    
    // Global Locking Check
    $stmt_lock = $pdo->prepare("SELECT is_locked, locked_by_user_id FROM `$table` WHERE id = ?");
    $stmt_lock->execute([$id]);
    $lock_info = $stmt_lock->fetch(PDO::FETCH_ASSOC);
    
    if ($lock_info && ($lock_info['is_locked'] ?? 0) == 1) {
        echo "<div class='container mt-5'>
                <div class='alert alert-dark text-center shadow-lg border-0 py-5 rounded-4 animate__animated animate__pulse'>
                    <i class='fas fa-lock fa-4x mb-4 text-warning'></i>
                    <h2 class='fw-bold mb-3'>هذه المعاملة معلقة</h2>
                    <p class='fs-5 mb-4'>لا يمكن عرض أو تعديل بيانات هذه المعاملة حالياً لأنها في حالة تعليق (قفل).</p>
                    <div class='d-flex justify-content-center gap-3'>
                        <a href='index.php?admin=1&section=requests' class='btn btn-light px-4 rounded-pill shadow-sm fw-bold'>
                            <i class='fas fa-arrow-right me-2'></i>العودة لقائمة الطلبات
                        </a>
                    </div>
                </div>
              </div>";
    } else {
        $is_employee = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['موظف', 'Employee']);
        
        // محرك العرض الذكي: التحقق من وجود ملف عرض خاص بالخدمة
        $view_folder = $is_employee ? "admin/serves/views/employee/" : "admin/serves/views/";
        $view_suffix = $is_employee ? "_employee_view.php" : "_view.php";
        $custom_view_path = $view_folder . $table . $view_suffix;
        
        // Fallback check: if employee file doesn't exist yet, use the standard one (temporary)
        if ($is_employee && !file_exists($custom_view_path)) {
            $custom_view_path = "admin/serves/views/{$table}_view.php";
        }

        if (file_exists($custom_view_path)) {
            require_once $custom_view_path;
        } else {
            echo "<div class='alert alert-danger m-4 text-center'>
                    <i class='fas fa-exclamation-triangle me-2'></i>
                    خطأ: ملف العرض المخصص غير موجود لهذا النوع من الطلبات ($table).
                  </div>";
        }
    }
}
elseif ($admin_view && isset($_GET['section']) && $_GET['section'] === 'print_view' && isset($_GET['id'])) {
    // عرض نموذج الاستعلام (النتيجة) للطباعة حسب نوع الخدمة
    if (isset($_SESSION['user_type'])) {
        $table = $_GET['table'] ?? 'marriage_permits';
        $id = $_GET['id'];
        try {
            // 1. جلب بيانات الطلب الأساسية
            $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
            $stmt->execute([$id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($request && ($request['is_locked'] ?? 0) == 1) {
                echo "<div class='container mt-5'>
                        <div class='alert alert-dark text-center shadow-lg border-0 py-5 rounded-4'>
                            <i class='fas fa-print fa-4x mb-4 text-warning opacity-50'></i>
                            <h2 class='fw-bold mb-3'>الطباعة غير متاحة</h2>
                            <p class='fs-5 mb-4'>لا يمكن طباعة هذه المعاملة وهي في حالة تعليق (قفل).</p>
                            <a href='index.php?admin=1&section=requests' class='btn btn-light px-4 rounded-pill shadow-sm fw-bold'>العودة</a>
                        </div>
                      </div>";
                exit;
            }

            if ($request) {
                // 2. تحديد اسم العمود الذي يربط البيانات المرتبطة (Foreign Key)
                $fk_mapping = [
                    'marriage_permits' => 'marriage_permit_id',
                    'visit_visa_family' => 'family_visit_id',
                    'family_visits' => 'family_visit_id',
                    'visit_visa_business' => 'business_visit_id',
                    'business_visits' => 'business_visit_id',
                    'tourism_visa' => 'tourism_visit_id',
                    'tourism_visits' => 'tourism_visit_id',
                    'profession_changes' => 'profession_change_id',
                    'profession_change' => 'profession_change_id',
                    'civil_affairs_requests' => 'civil_affairs_request_id',
                    'civil_affairs' => 'civil_affairs_request_id',
                    'labor_office' => 'labor_request_id',
                    'labor_requests' => 'labor_request_id',
                    'recruitment_requests' => 'recruitment_request_id',
                    'recruitment' => 'recruitment_request_id',
                    'followup_requests' => 'followup_request_id',
                    'followup' => 'followup_request_id'
                ];

                $fk_column = $fk_mapping[$table] ?? 'request_id';

                // جلب بيانات المرافقين من جدول related_data
                try {
                    $stmtPartners = $pdo->prepare("SELECT * FROM related_data WHERE `$fk_column` = ?");
                    $stmtPartners->execute([$id]);
                    $request['related_partners'] = $stmtPartners->fetchAll(PDO::FETCH_ASSOC);
                }
                catch (PDOException $e_inner) {
                    // في حال عدم وجود الجدول أو خطأ، نتركها مصفوفة فارغة
                    $request['related_partners'] = [];
                }

                // 3. توحيد أسماء الحقول لتتوافق مع ملفات الاستعلام (Inquiry Results)
                // حيث أن بعض الملفات تتوقع full_name و البعض يتوقع applicant_name
                $request['full_name'] = $request['full_name'] ?? $request['applicant_name'] ?? '---';
                $request['issue_number'] = $request['issue_number'] ?? $request['export_number'] ?? $request['issuance_number'] ?? '---';
                $request['request_date'] = $request['request_date'] ?? $request['approval_date'] ?? $request['created_at'] ?? date('Y-m-d');
                $request['id_number'] = $request['id_number'] ?? $request['national_id'] ?? '---';

                // 4. تحديد ملف النتيجة المناسب بناءً على اسم الجدول
                $mapping = [
                    'marriage_permits' => 'marriage_inquiry_result.php',
                    'visit_visa_business' => 'business_visit_inquiry_result.php',
                    'business_visits' => 'business_visit_inquiry_result.php',
                    'visit_visa_family' => 'family_visa_result.php',
                    'family_visits' => 'family_visa_result.php',
                    'tourism_visa' => 'tourist_visit_inquiry_result.php',
                    'tourism_visits' => 'tourist_visit_inquiry_result.php',
                    'profession_changes' => 'change_profession_inquiry_result.php',
                    'profession_change' => 'change_profession_inquiry_result.php',
                    'civil_affairs_requests' => 'civil_affairs_inquiry_result.php',
                    'civil_affairs' => 'civil_affairs_inquiry_result.php',
                    'labor_office' => 'labor_inquiry_result.php',
                    'labor_requests' => 'labor_inquiry_result.php',
                    'recruitment_requests' => 'recruitment_inquiry_result.php',
                    'recruitment' => 'recruitment_inquiry_result.php',
                    'followup_requests' => 'followup_inquiry_result.php',
                    'followup' => 'followup_inquiry_result.php'
                ];

                $view_file = $mapping[$table] ?? 'followup_inquiry_result.php';
                $full_path = 'InquiryModule/pages/' . $view_file;

                if (file_exists($full_path)) {
                    // لا نحتاج لهيدر index.php لأن صفحات الاستعلام لها هيدر خاص بها
                    require_once $full_path;
                    exit; // ننهي التنفيذ هنا لأن صفحة الاستعلام كاملة
                }
                else {
                    require_once 'views/header.php';
                    echo "<div class='alert alert-danger text-center m-4'>ملف العرض غير موجود: " . htmlspecialchars($view_file) . "</div>";
                }
            }
            else {
                require_once 'views/header.php';
                echo "<div class='alert alert-danger text-center m-4'>الطلب غير موجود.</div>";
            }
        }
        catch (PDOException $e) {
            require_once 'views/header.php';
            echo "<div class='alert alert-danger text-center m-4'>خطأ: " . $e->getMessage() . "</div>";
        }
    }
    else {
        echo "<div class='alert alert-danger text-center'>غير مصرح لك بالوصول هذه الصفحة.</div>";
    }
}
elseif (isset($_GET['page']) && $_GET['page'] === 'add_data') {
    if ($is_admin) {
        require_once 'views/header.php';
        require_once 'admin/serves/add_data.php';
    }
    else {
        header('Location: index.php');
        exit;
    }
}
elseif ($inquiry_result_page_view && $is_admin) {
    // صفحات النتائج المستقلة
    require_once 'views/inquiry_result.php';
    exit;
}
elseif (isset($_GET['page']) && $_GET['page'] === 'permit_new' && $is_admin) {
    require_once 'views/permit_view_new.php';
    exit;
}
elseif ($is_admin) {
    // المستخدم مسجل - عرض لوحة التحكم مباشرة
    require_once 'views/header.php';
    if ($_SESSION['user_type'] === 'Root' || $_SESSION['user_type'] === 'Admin') {
        require_once 'admin/root/dashboard.php';
    }
    elseif ($_SESSION['user_type'] === 'مدير' || $_SESSION['user_type'] === 'Manager') {
        require_once 'admin/manager/dashboard.php';
    }
    else {
        require_once 'admin/employee/dashboard.php';
    }
}
else {
    // أي حالة أخرى: توجيه لصفحة تسجيل الدخول
    require_once 'views/header.php';
    require_once 'views/login.php';
}

// تضمين الفوتر دائماً في النهاية
require_once 'views/footer.php';

?>