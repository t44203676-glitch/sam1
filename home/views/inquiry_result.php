<?php 
// views/inquiry_result.php - موجه عرض نتائج الاستعلام

// التأكد من بدء الجلسة
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق من وجود نتيجة استعلام في الجلسة
if (!isset($_SESSION['inquiry_result']) || !is_array($_SESSION['inquiry_result'])) {
    // إذا لم تكن هناك نتيجة على الإطلاق، أعد التوجيه إلى صفحة الاستعلام
    header('Location: index.php?page=inquiry');
    exit;
}

// استخراج البيانات من الجلسة
$result = $_SESSION['inquiry_result'];

// مسح بيانات الجلسة بعد استخدامها لمنع عرضها مرة أخرى عند تحديث الصفحة
unset($_SESSION['inquiry_result']);

// التحقق من نجاح الاستعلام قبل المتابعة
if (!isset($result['success']) || $result['success'] === false) {
    // إذا فشل الاستعلام، أعد التوجيه إلى صفحة الاستعلام مع رسالة الخطأ
    $_SESSION['inquiry_error'] = $result['message'] ?? 'حدث خطأ غير متوقع أثناء البحث.';
    header('Location: index.php?page=inquiry');
    exit;
}

if ($result['success']) {
    $request = $result['data'];
    $type = $result['type'];

    // تضمين ملف عرض النتيجة المناسب بناءً على نوع الخدمة
    $basePath = __DIR__ . '/../inquiries/';
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
        case 'إلغاء بلاغ هروب':
            require_once $basePath . 'cancel_absconding_report_inquiry_result.php';
            break;
        case 'تغيير مهنة':
            require_once $basePath . 'change_profession_inquiry_result.php';
            break;
        case 'nationality_issuance': // معالجة النوع الخاص
        case 'أحوال مدنية':
            require_once $basePath . 'civil_affairs_inquiry_result.php';
            break;
        case 'استقدام': // إضافة حالة جديدة
            require_once $basePath . 'recruitment_inquiry_result.php';
            break;
        default:
            // في حالة عدم تطابق أي نوع، يمكن عرض رسالة خطأ أو صفحة عامة
            echo "<div class='container my-5'><div class='alert alert-danger'>خطأ: نوع الخدمة '{$type}' غير معروف ولا يمكن عرض نتائجه.</div></div>";
            break;
    }
} else {
    // عرض رسالة الخطأ من نظام الاستعلام
    $_SESSION['inquiry_error'] = $result['message'];
    header('Location: index.php?page=inquiry');
    exit;
}

?>