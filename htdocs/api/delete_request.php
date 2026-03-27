<?php
header('Content-Type: application/json');
require_once '../includes/database.php';
require_once '../includes/logger.php';
require_once '../includes/functions.php';

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

// 1. Check Permissions - Only Root can delete
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Root') {
    $response['message'] = 'غير مصرح لك بحذف المعاملات.';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    $table = $data['source_table'] ?? null;

    $allowed_tables = [
        'marriage_permits', 'business_visits', 'civil_affairs_requests', 
        'family_visits', 'labor_requests', 'profession_changes', 
        'recruitment_requests', 'runaway_cancellations', 'tourism_visits',
        'followup_requests'
    ];

    if ($id && $table && in_array($table, $allowed_tables) && $pdo) {
        try {
            // اسم الجدول يتم التحقق منه من القائمة المسموح بها، لذا هو آمن للاستخدام مباشرة
            $stmt = $pdo->prepare("DELETE FROM `{$table}` WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $response = ['success' => true, 'message' => 'تم حذف الطلب بنجاح.'];
            } else {
                $response['message'] = 'لم يتم العثور على الطلب لحذفه أو قد تم حذفه بالفعل.';
            }
        } catch (PDOException $e) {
            log_error("Failed to delete request ID {$id} from table {$table}: " . $e->getMessage(), __FILE__, __LINE__);
            $response['message'] = 'خطأ في قاعدة البيانات عند محاولة الحذف.';
        }
    } else {
        if (!$id) $response['message'] = 'معرف الطلب مفقود.';
        elseif (!$table) $response['message'] = 'مصدر الطلب (الجدول) مفقود.';
        elseif (!in_array($table, $allowed_tables)) $response['message'] = 'نوع الطلب المحدد غير صالح.';
    }
}

echo json_encode($response);
