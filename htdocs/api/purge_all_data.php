<?php
header('Content-Type: application/json; charset=utf-8');
// api/purge_all_data.php
// حذف جميع بيانات الخدمات نهائياً (يحتاج صلاحية مدير)

session_start();

// التحقق من صلاحية المدير
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['user_type'] !== 'مدير') {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.']);
    exit;
}

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صالحة.']);
    exit;
}

// التحقق من رمز التأكيد
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['confirm']) || $input['confirm'] !== true) {
    echo json_encode(['success' => false, 'message' => 'لم يتم تأكيد عملية الحذف.']);
    exit;
}

require_once __DIR__ . '/../includes/database.php';

if (!USE_DATABASE || !$pdo) {
    echo json_encode(['success' => false, 'message' => 'قاعدة البيانات غير متوفرة.']);
    exit;
}

try {
    // قائمة جداول الخدمات التي سيتم حذف بياناتها
    $services_tables = [
        'related_data',              // بيانات مرتبطة (حذف أولاً)
        'marriage_permits',
        'family_visits',
        'business_visits',
        'tourism_visits',            // الاسم الفعلي في قاعدة البيانات
        'recruitment_requests',
        'labor_requests',
        'civil_affairs_requests',
        'profession_changes',
        'followup_requests',
        'runaway_cancellations',     // إلغاء بلاغ هروب
    ];

    // جلب الجداول الموجودة فعلياً في قاعدة البيانات
    $stmt = $pdo->query("SHOW TABLES");
    $existing_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $deleted_count = 0;
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->beginTransaction();

    foreach ($services_tables as $table) {
        if (in_array($table, $existing_tables)) {
            $pdo->exec("DELETE FROM `$table`");
            $deleted_count++;
        }
    }

    $pdo->commit();
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo json_encode([
        'success' => true,
        'message' => "تم حذف جميع بيانات الخدمات بنجاح من {$deleted_count} جداول."
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
    ]);
}
?>
