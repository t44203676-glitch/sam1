<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once '../includes/database.php';
require_once '../includes/logger.php';

// التحقق من أن المستخدم مسجل دخوله
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك. يرجى تسجيل الدخول.']);
    exit;
}

$response = ['success' => false, 'message' => 'طلب غير صالح.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_profile_photo') {
    $requestId = $_POST['request_id'] ?? null;
    $formType = $_POST['formType'] ?? null;
    $photo = $_FILES['profile_photo'] ?? null;

    $allowedTables = [
        'civil_affairs' => 'civil_affairs_requests',
        'business_visit' => 'business_visits',
        'tourism' => 'tourism_visits'
    ];

    if (!$requestId || !$formType || !$photo || !isset($allowedTables[$formType])) {
        $response['message'] = 'بيانات الطلب غير مكتملة.';
        echo json_encode($response);
        exit;
    }

    $tableName = $allowedTables[$formType];

    // التحقق من صلاحية الصورة
    if ($photo['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'حدث خطأ أثناء رفع الصورة.';
        echo json_encode($response);
        exit;
    }

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($photo['type'], $allowedMimeTypes)) {
        $response['message'] = 'نوع الملف غير مسموح به. يرجى رفع صورة (JPG, PNG, GIF).';
        echo json_encode($response);
        exit;
    }

    // إنشاء مجلدات التخزين باستخدام المسار الموحد
    $uploadDir = UPLOADS_ROOT_PATH;
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // إنشاء اسم فريد للملف
    $fileExtension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
    $newFileName = 'profile_' . $requestId . '_' . time() . '.' . $fileExtension;
    
    // تخزين اسم الملف فقط في قاعدة البيانات (بدون بادئة uploads/)
    $dbPath = $newFileName;
    
    // نقل الملف المرفوع
    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadDir . $newFileName)) {
        // تحديث قاعدة البيانات
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("UPDATE `{$tableName}` SET profile_photo_path = ? WHERE id = ?");
                $stmt->execute([$dbPath, $requestId]);
                
                // Use the helper function to get the correct URL for the frontend
                $fullUrl = getProfilePhotoUrl($dbPath);
                
                $response = ['success' => true, 'message' => 'تم حفظ الصورة بنجاح!', 'filePath' => $fullUrl];
            } catch (PDOException $e) {
                log_error("Failed to update profile photo path for request ID {$requestId} in table {$tableName}: " . $e->getMessage(), __FILE__, __LINE__);
                $response['message'] = 'فشل تحديث قاعدة البيانات.';
            }
        } else {
            $response['message'] = 'لا يوجد اتصال بقاعدة البيانات.';
        }
    } else {
        $response['message'] = 'فشل نقل الملف إلى المجلد المخصص.';
    }
}

echo json_encode($response);
?>