<?php
// admin/api/delete_all_user_requests.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Security Check - Only Root can perform this action
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Root') {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.']);
    exit;
}

// 2. Check Input
$input = json_decode(file_get_contents('php://input'), true);
$user_ids = $input['user_ids'] ?? [];

if (empty($user_ids) || !is_array($user_ids)) {
    echo json_encode(['success' => false, 'message' => 'لم يتم تحديد مستخدمين لحذف طلباتهم.']);
    exit;
}

if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'فشل الاتصال بقاعدة البيانات.']);
    exit;
}

try {
    // List of all service tables
    $service_tables = [
        'marriage_permits',
        'civil_affairs_requests',
        'business_visits',
        'tourism_visits',
        'family_visits',
        'labor_requests',
        'profession_changes',
        'followup_requests',
        'recruitment_requests'
    ];

    $placeholders = implode(',', array_fill(0, count($user_ids), '?'));
    
    $pdo->beginTransaction();
    
    $total_deleted = 0;
    foreach ($service_tables as $table) {
        $sql = "DELETE FROM `{$table}` WHERE created_by_user_id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($user_ids);
        $total_deleted += $stmt->rowCount();
    }
    
    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => "تم حذف $total_deleted طلب بنجاح لجميع المستخدمين في هذا المسار."
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false, 
        'message' => 'حدث خطأ أثناء محاولة الحذف: ' . $e->getMessage()
    ]);
}
