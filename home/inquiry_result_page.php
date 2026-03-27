<?php
header('Content-Type: text/html; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// حماية الصفحة من الوصول المباشر بدون استعلام (أمان 100%)
if (!isset($_SESSION['inquiry_result']) || !is_array($_SESSION['inquiry_result'])) {
    header('Location: pages/eservices/inquiry.php?service=default');
    exit;
}

$result = $_SESSION['inquiry_result'];
unset($_SESSION['inquiry_result']); // Now it will redirect back on refresh

if (!isset($result['success']) || $result['success'] === false) {
    $_SESSION['inquiry_error'] = $result['message'] ?? 'حدث خطأ غير متوقع أثناء البحث.';
    header('Location: pages/eservices/inquiry.php?service=default');
    exit;
}

if ($result['success']) {
    $request = $result['data'];
    $type = $result['type'];

    // Link to the InquiryModule entry point for constants and setup
    require_once __DIR__ . '/InquiryModule/core/bootstrap.php';
    
    // Use the path constant defined in the module bootstrap
    $basePath = INQUIRY_PAGES_PATH . '/';

    switch ($type) {
        case 'تصريح زواج':
            require_once $basePath . 'marriage_inquiry_result.php';
            break;
        case 'زيارة عائلية':
            require_once $basePath . 'family_visa_result.php';
            break;
        case 'زيارة سياحية':
            require_once $basePath . 'tourist_visit_inquiry_result.php';
            break;
        case 'زيارة تجارية':
            require_once $basePath . 'business_visit_inquiry_result.php';
            break;
        case 'عمالة':
            require_once $basePath . 'labor_inquiry_result.php';
            break;
        case 'متابعة الطلبات':
            require_once $basePath . 'followup_inquiry_result.php';
            break;
        case 'تغيير مهنة':
            require_once $basePath . 'change_profession_inquiry_result.php';
            break;
        case 'nationality_issuance':
        case 'أحوال مدنية':
            require_once $basePath . 'civil_affairs_inquiry_result.php';
            break;
        case 'استقدام':
            require_once $basePath . 'recruitment_inquiry_result.php';
            break;
        default:
            echo "<div style='padding:20px; text-align:center;'>نوع الخدمة '{$type}' غير معروف.</div>";
            break;
    }
}
else {
    $_SESSION['inquiry_error'] = $result['message'];
    header('Location: pages/eservices/inquiry.php?service=default');
    exit;
}
?>
