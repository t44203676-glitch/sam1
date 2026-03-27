<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/database.php';
require_once '../includes/functions.php';
require_once '../includes/logger.php';

// التحقق من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك. يرجى تسجيل الدخول.']);
    exit;
}

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

// [تعديل صارم]: تم إيقاف الرفع من موقع الاستعلام. الرفع يتم فقط من لوحة التحكم.
echo json_encode(['success' => false, 'message' => 'عذراً، يتم رفع الصور وتعديلها من لوحة التحكم فقط.']);
exit;
?>