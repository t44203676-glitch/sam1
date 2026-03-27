<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/database.php';
require_once '../includes/logger.php';

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'يجب استخدام طريقة POST.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$formType = $data['formType'] ?? $_POST['formType'] ?? null;
$itemId = $data['itemId'] ?? $data['item_id'] ?? $_POST['itemId'] ?? $_POST['item_id'] ?? null;

if (!$formType || !$itemId) {
    $response['message'] = 'بيانات ناقصة: لم يتم توفير نوع النموذج أو معرف العنصر.';
    echo json_encode($response);
    exit;
}

// Map formType to table name
$relatedTableMap = [
    'marriage' => 'related_data',
    'family_visit' => 'related_data',
    'tourism' => 'related_data',
    'business_visit' => 'related_data',
    'labor' => 'related_data',
    'civil_affairs' => 'related_data',
    'recruitment' => 'related_data',
    'profession_change' => 'related_data',
    'runaway_cancellation' => 'related_data',
    'followup' => 'related_data',
];

if (!isset($relatedTableMap[$formType])) {
    $response['message'] = 'نوع نموذج غير صالح.';
    echo json_encode($response);
    exit;
}

$tableName = $relatedTableMap[$formType];

if (USE_DATABASE && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `$tableName` WHERE id = ?");
        $success = $stmt->execute([$itemId]);

        if ($success && $stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'تم حذف العنصر بنجاح!';
        } else {
            $response['message'] = 'لم يتم العثور على العنصر للحذف أو فشل الحذف.';
        }
    } catch (PDOException $e) {
        log_error("Failed to delete item ID $itemId from table $tableName: " . $e->getMessage(), __FILE__, __LINE__);
        $response['message'] = 'حدث خطأ في قاعدة البيانات.';
    }
} else {
    $response['message'] = 'قاعدة البيانات غير متصلة.';
}

echo json_encode($response);
exit;
?>
