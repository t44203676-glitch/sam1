<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/database.php';

// api/manage_related_data.php
// إدارة البيانات المرتبطة (إضافة وحذف الأشخاص) لجميع الخدمات

ini_set('display_errors', 0);
error_reporting(E_ALL);

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        if ($action === 'add') {
            $mainTable = $_POST['main_table'] ?? '';
            $mainId = $_POST['main_id'] ?? '';
            $personsJson = $_POST['persons'] ?? '[]';
            $persons = json_decode($personsJson, true);

            if (empty($mainTable) || empty($mainId) || empty($persons)) {
                throw new Exception('بيانات الإضافة غير مكتملة.');
            }

            // خريطة المفاتيح الأجنبية لكل جدول رئيسي
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
                'runaway_cancellations' => 'runaway_cancellation_id',
            ];

            if (!isset($related_mapping[$mainTable])) {
                throw new Exception('نوع الطلب غير مدعوم للإضافة.');
            }

            $fk = $related_mapping[$mainTable];
            
            // جلب أعمدة الجدول للتحقق من وجود الحقول
            $stmtPartnerCols = $pdo->prepare("DESCRIBE `related_data`");
            $stmtPartnerCols->execute();
            $partnerColumns = $stmtPartnerCols->fetchAll(PDO::FETCH_COLUMN);

            $pdo->beginTransaction();
            foreach ($persons as $person) {
                $fields = ["`$fk`"];
                $placeholders = ["?"];
                $params = [$mainId];

                foreach ($person as $key => $value) {
                    if (in_array($key, $partnerColumns) && $key !== 'id' && $key !== $fk) {
                        $fields[] = "`$key`";
                        $placeholders[] = "?";
                        $params[] = ($value === '') ? null : $value;
                    }
                }

                $sql = "INSERT INTO related_data (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
            }
            $pdo->commit();
            $response = ['success' => true, 'message' => 'تمت إضافة البيانات بنجاح!'];

        } elseif ($action === 'delete') {
            $idsJson = $_POST['ids'] ?? '[]';
            $ids = json_decode($idsJson, true);

            if (empty($ids)) {
                throw new Exception('لم يتم اختيار أشخاص للحذف.');
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "DELETE FROM related_data WHERE id IN ($placeholders)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($ids)) {
                $response = ['success' => true, 'message' => 'تم حذف المحددين بنجاح!'];
            } else {
                throw new Exception('فشل عملية الحذف من قاعدة البيانات.');
            }
        }
    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
