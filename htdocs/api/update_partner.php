<?php
header('Content-Type: application/json');
// api/update_partner.php

require_once '../includes/logger.php'; // تضمين ملف تسجيل الأخطاء

require_once '../includes/database.php';

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $partnerId = $data['id'] ?? null;

    if ($partnerId && $pdo) {
        try {
            $stmt = $pdo->prepare("
                UPDATE related_data SET
                    full_name = :full_name,
                    passport_number = :passport_number,
                    relationship = :relationship,
                    birth_date = :birth_date,
                    age = :age,
                    nationality = :nationality,
                    country = :country
                WHERE id = :id
            ");

            $stmt->execute([
                ':id' => $partnerId,
                ':full_name' => $data['full_name'],
                ':passport_number' => $data['passport_number'],
                ':relationship' => $data['relationship'],
                ':birth_date' => $data['birth_date'],
                ':age' => $data['age'],
                ':nationality' => $data['nationality'],
                ':country' => $data['country']
            ]);

            $response['success'] = true;
            $response['message'] = 'تم تحديث بيانات الشريك بنجاح.';

        } catch (PDOException $e) {
            log_error("Failed to update partner data for partner ID $partnerId: " . $e->getMessage(), __FILE__, __LINE__);
            $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'معرف الشريك غير متوفر.';
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>