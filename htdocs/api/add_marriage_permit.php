<?php
header('Content-Type: application/json');

require_once '../includes/logger.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';
session_start();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// تحقق من وجود هوية المستخدم في الجلسة
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not authenticated.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $created_by_user_id = $_SESSION['user_id'];

    if (USE_DATABASE && $pdo) {
        try {
            // توليد رقم صادر إذا لم يكن موجوداً
            $export_number = !empty($data['exportNumber']) ? $data['exportNumber'] : generate_export_number('0', 'marriage_permits');
            
            // توليد رقم تسلسلي عشوائي
            $serial_number = generate_serial_number();
            
            $stmt = $pdo->prepare("
                INSERT INTO marriage_permits 
                (serial_number, export_number, applicant_name, national_id, phone, service_number, service_desc, 
                 hijri_date, permit_type, emirate, approval_date, approval_time, attachments, record_number, 
                 issuance_number, submission_date, area, area_code, remarks, issuing_authority, 
                 status, created_by_user_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $serial_number,
                $export_number,
                $data['applicantName'] ?? '',
                $data['nationalId'] ?? '',
                $data['phone'] ?? '',
                $data['serviceNumber'] ?? '',
                $data['serviceDesc'] ?? '',
                $data['hijriDate'] ?? '',
                $data['permitType'] ?? '',
                $data['emirate'] ?? '',
                empty($data['approvalDate']) ? null : $data['approvalDate'],
                empty($data['approvalTime']) ? null : $data['approvalTime'],
                $data['attachments'] ?? 0,
                $data['recordNumber'] ?? '',
                $data['issuanceNumber'] ?? '',
                $data['submissionDate'] ?? '',
                $data['area'] ?? '',
                $data['areaCode'] ?? '',
                $data['remarks'] ?? '',
                $data['issuingAuthority'] ?? 'وزارة الداخلية-الي',
                'قيد المراجعة', // الحالة الافتراضية
                $created_by_user_id
            ]);

            $response['status'] = 'success';
            $response['message'] = 'تم حفظ نموذج تصريح الزواج بنجاح.';
            $response['export_number'] = $export_number;
            $response['request_id'] = $pdo->lastInsertId();

        } catch (PDOException $e) {
            log_error("Failed to add marriage permit via API: " . $e->getMessage(), __FILE__, __LINE__);
            $response['message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Database connection not available.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>