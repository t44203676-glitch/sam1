<?php
ob_start(); // منع أي مخرجات غير مقصودة (مثل التحذيرات) من إفساد الـ JSON
header('Content-Type: application/json');
require_once '../includes/logger.php';
require_once '../includes/database.php';
// session_start(); // تم استدعاؤه بالفعل في config.php المضمن عبر database.php
$response = ['success' => false, 'message' => 'طلب غير صالح.'];

// التحقق من الصلاحيات - فقط الجذر والمدراء يمكنهم تغيير الحالات
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['Root', 'مدير', 'Manager'])) {
    $response['message'] = 'غير مصرح لك بتغيير الحالة.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// التأكد من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $requestId = $data['id'] ?? null;
    $newStatus = $data['status'] ?? null;
    $rejectionReason = $data['rejection_reason'] ?? null;
    $tableName = $data['table'] ?? 'marriage_permits'; // الجدول الافتراضي

    // التحقق من صحة البيانات
    if ($requestId && $newStatus) {
        if (USE_DATABASE && $pdo) {
            try {
                // 1. التحقق من أن اسم الجدول صحيح (لمنع SQL Injection)
                $allowed_tables = [
                    'marriage_permits', 'family_visits', 'tourism_visits', 
                    'business_visits', 'recruitment_requests', 'labor_requests',
                    'civil_affairs_requests', 'profession_changes', 'runaway_cancellations',
                    'followup_requests'
                ];
                
                if (!in_array($tableName, $allowed_tables)) {
                    $tableName = 'marriage_permits';
                }

                // 2. التحقق من حالة القفل والتسلسل الهرمي
                $stmt_check = $pdo->prepare("SELECT is_locked, created_by_user_id FROM `$tableName` WHERE id = ?");
                $stmt_check->execute([$requestId]);
                $record = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if (!$record) {
                    $response['message'] = 'السجل غير موجود.';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit;
                }

                // منع التعديل إذا كانت المعاملة مقفلة (إلا للـ Root)
                if ($record['is_locked'] == 1 && $_SESSION['user_type'] !== 'Root') {
                    $response['message'] = 'هذه المعاملة مقفلة نهائياً ولا يمكن تغيير حالتها.';
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                    exit;
                }

                // تحقق الرتبة (التسلسل الهرمي)
                $user_id = $_SESSION['user_id'];
                $user_type = $_SESSION['user_type'];
                
                if ($user_type !== 'Root') {
                    if ($user_type === 'Admin') {
                        $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users WHERE created_by_user_id = ? OR manager_id IN (SELECT user_id FROM system_users WHERE created_by_user_id = ?) OR user_id = ?");
                        $emp_stmt->execute([$user_id, $user_id, $user_id]);
                    } else { // Manager
                        $emp_stmt = $pdo->prepare("SELECT user_id FROM system_users WHERE manager_id = ? OR user_id = ?");
                        $emp_stmt->execute([$user_id, $user_id]);
                    }
                    $branch_ids = $emp_stmt->fetchAll(PDO::FETCH_COLUMN);

                    if (!in_array($record['created_by_user_id'], $branch_ids)) {
                        $response['message'] = 'غير مصرح لك بالوصول لهذا الطلب.';
                        echo json_encode($response, JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }

                // إذا كان هناك سبب/ملاحظات، قم بتحديث حقل سبب الرفض أيضاً
                if ($rejectionReason && in_array($newStatus, ['مرفوض', 'قيد المراجعة من الموظف', 'قيد المراجعة', 'تم تعليق المعاملة'])) {
                    $stmt = $pdo->prepare("UPDATE `$tableName` SET status = ?, rejection_reason = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $rejectionReason, $requestId]);
                } else {
                    // تحديث الحالة فقط
                    $stmt = $pdo->prepare("UPDATE `$tableName` SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $requestId]);
                }

                // مزامنة الحالة مع الجداول المرتبطة (مثل related_data لمكتب العمل)
                if ($tableName === 'labor_requests') {
                    $stmt_related = $pdo->prepare("UPDATE `related_data` SET status = ? WHERE labor_request_id = ?");
                    $stmt_related->execute([$newStatus, $requestId]);
                }
                // يمكنك إضافة جداول أخرى هنا إذا كانت تتبع نفس النمط 
                // مثل related_members لطلب الاستقدام وغيره
                if ($tableName === 'recruitment_requests') {
                    $stmt_related = $pdo->prepare("UPDATE `related_members` SET status = ? WHERE recruitment_request_id = ?");
                    $stmt_related->execute([$newStatus, $requestId]);
                }
                if ($tableName === 'family_visits') {
                    $stmt_related = $pdo->prepare("UPDATE `family_members` SET status = ? WHERE family_visit_id = ?");
                    $stmt_related->execute([$newStatus, $requestId]);
                }

                if ($stmt->rowCount() > 0) {
                    $response['success'] = true;
                    $response['message'] = 'تم تحديث حالة الطلب بنجاح.';
                } else {
                    $response['message'] = 'لم يتم العثور على الطلب أو أن الحالة لم تتغير.';
                }
            } catch (PDOException $e) {
                log_error("Failed to update status for request ID $requestId in table $tableName: " . $e->getMessage(), __FILE__, __LINE__);
                $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'قاعدة البيانات غير متصلة.';
        }
    } else {
        $response['message'] = 'البيانات المطلوبة (ID والحالة) غير متوفرة.';
    }
}

ob_end_clean(); // مسح أي مخرجات أخرى (مثل تحذيرات PHP) قبل عرض الـ JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>