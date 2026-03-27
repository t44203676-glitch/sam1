<?php
// api/update_related_item.php
// تحديث بيانات الشركاء أو العناصر المرتبطة (الخطوة الثانية)

header('Content-Type: application/json');
require_once '../includes/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'بيانات غير مكتملة.']);
    exit;
}

$id = $data['id'];
$table = $data['source_table'] ?? 'related_data'; 

// التحقق من الجدول لضمان الأمان
if (!in_array($table, ['related_data', 'civil_affairs_requests', 'marriage_permits'])) {
    // نسمح فقط بـ related_data حالياً للخطوة الثانية
    if ($table !== 'related_data') {
        echo json_encode(['success' => false, 'message' => 'جدول غير صالح.']);
        exit;
    }
}

unset($data['id']);
unset($data['source_table']);

try {
    $fields = [];
    $values = [];
    foreach ($data as $key => $value) {
        $fields[] = "`$key` = ?";
        $values[] = $value;
    }
    
    if (empty($fields)) {
        echo json_encode(['success' => false, 'message' => 'لا توجد بيانات للتحديث.']);
        exit;
    }
    
    $values[] = $id;
    $sql = "UPDATE `$table` SET " . implode(', ', $fields) . " WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    // --- NEW: Sync status with parent request if status was updated ---
    if (isset($data['status']) && $table === 'related_data') {
        // Fetch the row to find the parent ID
        $stmt_fetch = $pdo->prepare("SELECT * FROM related_data WHERE id = ?");
        $stmt_fetch->execute([$id]);
        $row = $stmt_fetch->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $parent_mapping = [
                'marriage_permit_id' => 'marriage_permits',
                'family_visit_id' => 'family_visits',
                'tourism_visit_id' => 'tourism_visits',
                'business_visit_id' => 'business_visits',
                'labor_request_id' => 'labor_requests',
                'followup_request_id' => 'followup_requests',
                'profession_change_id' => 'profession_changes',
                'civil_affairs_request_id' => 'civil_affairs_requests',
                'recruitment_request_id' => 'recruitment_requests',
            ];
            
            foreach ($parent_mapping as $fk => $parent_table) {
                if (!empty($row[$fk])) {
                    $stmt_parent = $pdo->prepare("UPDATE `{$parent_table}` SET status = ? WHERE id = ?");
                    $stmt_parent->execute([$data['status'], $row[$fk]]);
                    break; // Only one parent expected
                }
            }
        }
    }
    // ------------------------------------------------------------------

    echo json_encode(['success' => true, 'message' => 'تم تحديث البيانات بنجاح!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}
