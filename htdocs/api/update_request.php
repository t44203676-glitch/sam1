<?php
header('Content-Type: application/json');

// Include necessary files
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/logger.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

// 1. Check Permissions
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير', 'Manager'])) {
    $response['message'] = 'غير مصرح لك بالوصول.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        $response['message'] = 'بيانات غير صالحة.';
        echo json_encode($response);
        exit;
    }

    $id = $data['id'] ?? null;
    $table = $data['source_table'] ?? $data['table'] ?? null;

    if (!$id || !$table) {
        $response['message'] = 'معرف الطلب أو الجدول مفقود.';
        echo json_encode($response);
        exit;
    }

    // Whitelist of allowed tables
    $allowed_tables = [
        'marriage_permits', 'business_visits', 'civil_affairs_requests', 
        'family_visits', 'labor_requests', 'profession_changes', 
        'recruitment_requests', 'runaway_cancellations', 'tourism_visits',
        'followup_requests'
    ];

    if (!in_array($table, $allowed_tables)) {
        $response['message'] = 'نوع الطلب المحدد غير صالح.';
        echo json_encode($response);
        exit;
    }

    try {
        // التحقق من الأعمدة وحالة القفل
        $stmt_check = $pdo->prepare("SELECT is_locked, created_by_user_id, locked_by_user_id FROM `$table` WHERE id = ?");
        $stmt_check->execute([$id]);
        $current_record = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (!$current_record) {
            $response['message'] = 'السجل غير موجود.';
            echo json_encode($response);
            exit;
        }

        $user_type = $_SESSION['user_type'];
        $user_id = $_SESSION['user_id'];

        // 1. منطق القفل وفك القفل الهرمي
        if (isset($data['is_locked'])) {
            $new_lock_state = (int)$data['is_locked'];
            
            if ($current_record['is_locked'] == 1 && $new_lock_state == 0) {
                // محاولة فك القفل
                $locker_id = $current_record['locked_by_user_id'];
                
                // جلب رتبة الشخص الذي قام بالقفل
                $stmt_locker = $pdo->prepare("SELECT user_type FROM system_users WHERE user_id = ?");
                $stmt_locker->execute([$locker_id]);
                $locker_type = $stmt_locker->fetchColumn() ?: 'Root'; // افتراض Root إذا لم يوجد (قديم)

                if ($locker_type === 'Root' && $user_type !== 'Root') {
                    $response['message'] = 'هذه المعاملة قفلها الـ Root ولا يمكن فكها إلا من قبله.';
                    echo json_encode($response);
                    exit;
                }
                
                if ($locker_type === 'Admin' && !in_array($user_type, ['Root', 'Admin'])) {
                    $response['message'] = 'ليس لديك صلاحية لفك قفل هذه المعاملة.';
                    echo json_encode($response);
                    exit;
                }
                
                // إذا وصلنا هنا، فك القفل مسموح
                $data['locked_by_user_id'] = null;
            } 
            elseif ($new_lock_state == 1) {
                // محاولة القفل
                if (!in_array($user_type, ['Root', 'Admin'])) {
                    $response['message'] = 'ليس لديك صلاحية لقفل المعاملات.';
                    echo json_encode($response);
                    exit;
                }
                $data['locked_by_user_id'] = $user_id;
            }
        }

        // 2. منع التعديل العادي إذا كانت المعاملة مقفلة (إلا للـ Root أو إذا كان الطلب هو فك القفل نفسه)
        $is_unlocking_only = isset($data['is_locked']) && (int)$data['is_locked'] == 0 && count($data) <= 3; // (id, table, is_locked)
        if ($current_record['is_locked'] == 1 && $user_type !== 'Root' && !$is_unlocking_only) {
            $response['message'] = 'هذه المعاملة مقفلة نهائياً ولا يمكن تعديلها.';
            echo json_encode($response);
            exit;
        }

        $stmt_cols = $pdo->query("DESCRIBE `$table` ");
        $existing_cols = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);

        // 2. القائمة البيضاء للحقول وتخصيصها حسب الرتبة
        $user_type = $_SESSION['user_type'];
        $allowed_fields = [];
        
        if (in_array($user_type, ['Root', 'Admin', 'مدير', 'Manager'])) {
            $allowed_fields = ['export_number', 'status', 'created_at', 'applicant_name', 'national_id', 'is_locked', 'locked_by_user_id'];
        } else {
            // الموظفون (في حال وصولهم) لا يعدلون الحقول الحساسة
            $allowed_fields = ['status']; 
        }

        $fields_to_update = [];
        $params = [':id' => $id];

        require_once __DIR__ . '/../includes/functions.php';

        foreach ($allowed_fields as $field) {
            if (isset($data[$field]) && in_array($field, $existing_cols)) {
                if ($field === 'created_at' && empty($data[$field])) continue;
                
                $fields_to_update[] = "`{$field}` = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }

        if (empty($fields_to_update)) {
            $response['message'] = 'لا توجد حقول صالحة للتحديث في هذا الجدول.';
            echo json_encode($response);
            exit;
        }

        // إضافة updated_at إذا كان موجوداً
        if (in_array('updated_at', $existing_cols)) {
            $fields_to_update[] = "updated_at = NOW()";
        }

        $sql = "UPDATE `{$table}` SET " . implode(', ', $fields_to_update) . " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // --- NEW: Sync status with related_data if status was updated ---
        if (isset($data['status'])) {
            $related_mapping = [
                'marriage_permits' => 'marriage_permit_id',
                'family_visits' => 'family_visit_id',
                'tourism_visits' => 'tourism_visit_id',
                'business_visits' => 'business_visit_id',
                'labor_requests' => 'labor_request_id',
                'followup_requests' => 'followup_request_id',
                'profession_changes' => 'profession_change_id',
                'civil_affairs_requests' => 'civil_affairs_request_id',
                'recruitment_requests' => 'recruitment_request_id',
            ];
            
            if (isset($related_mapping[$table])) {
                $fk = $related_mapping[$table];
                $stmt_related = $pdo->prepare("UPDATE related_data SET status = ? WHERE `{$fk}` = ?");
                $stmt_related->execute([$data['status'], $id]);
            }
        }
        // ----------------------------------------------------------------

        $response = [
            'success' => true, 
            'message' => 'تم تحديث البيانات بنجاح.',
            'updated_values' => $data
        ];

    } catch (PDOException $e) {
        log_error("Failed to update request ID {$id} in table {$table}: " . $e->getMessage(), __FILE__, __LINE__);
        $response['message'] = 'عذراً، حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.';
    }
}

echo json_encode($response);
