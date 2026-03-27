<?php
/**
 * Dynamic Path Configuration
 * يكتشف المسار تلقائياً سواء في مجلد فرعي أو الدومين الرئيسي
 */

// ======================================
// 1️⃣ تحديد البروتوكول (HTTP أو HTTPS)
// ======================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? '') == 443) ? "https" : "http";

// ======================================
// 2️⃣ تحديد اسم النطاق
// ======================================
$host = $_SERVER['HTTP_HOST'];

// ======================================
// 3️⃣ تحديد المسار الفعلي للمشروع
// ======================================
$project_root = str_replace('\\', '/', dirname(__DIR__));

$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);


// حساب المسار النسبي من جذر السيرفر إلى جذر المشروع بطريقة تدعم Windows (تجاهل حالة الأحرف في المسارات)
if (strncasecmp($project_root, $document_root, strlen($document_root)) === 0) {
    $relative_path = substr($project_root, strlen($document_root));
} else {
    $relative_path = $project_root; 
}

// تأكد من وجود سلاش في البداية والنهاية
$relative_path = '/' . trim($relative_path, '/') . '/';
if ($relative_path === '//') {
    $relative_path = '/';
}

// ======================================
// 4️⃣ الرابط الأساسي النهائي (Root-Relative Paths)
// ======================================
$base_url = $relative_path;

// ======================================
// 5️⃣ تعريف الثوابت العامة
// ======================================
if (!defined('APP_URL')) {
    define('APP_URL', $base_url);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

// ======================================
// مسار الصور المرفوعة (مشترك بين SOSO وpublic_html)
// الصور تُحفظ في SOSO/uploads/ وتُقرأ من هناك
// ======================================
if (!defined('UPLOADS_BASE_URL')) {
    $sn = $_SERVER['SERVER_NAME'] ?? '';
    if (in_array($sn, ['127.0.0.1', 'localhost', '::1']) || strpos($sn, 'localhost') !== false) {
        // محلي: المسار الذي طلبته لحفظ الصور
        define('UPLOADS_PHYSICAL_PATH', 'C:/Users/sO377/Pictures/updata/');
        
        // حساب المسار الويب النسبي للجذر
        $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
        // نرجع 3 مستويات للوراء من InquiryModule/core للوصول للجذر (home)
        $projRoot = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
        $webRoot = '/' . trim(str_replace($docRoot, '', $projRoot), '/') . '/';
        if ($webRoot === '//') $webRoot = '/';
        
        define('UPLOADS_BASE_URL', $protocol . '://' . $host . $webRoot . 'includes/image_server.php?file=');
    }
    else {
        // استضافة
        define('UPLOADS_PHYSICAL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');
        // استخدام عنوان النطاق الحالي ديناميكياً
        define('UPLOADS_BASE_URL', $protocol . '://' . $host . '/uploads/');
    }
}

if (!function_exists('getProfilePhotoUrl')) {
    function getProfilePhotoUrl(?string $pathOrFilename, ?string $fallback = '')
    {
        if (!$pathOrFilename || trim($pathOrFilename) === '' || $pathOrFilename === '---') {
            return $fallback;
        }
        if (filter_var($pathOrFilename, FILTER_VALIDATE_URL)) {
            return $pathOrFilename;
        }

        // إذا كان المسار يحتوي على مجلد معين (مثل uploads/profile_photos/...)
        $cleanPath = ltrim(str_replace('uploads/', '', $pathOrFilename), '/');
        
        // التحقق الفيزيائي فقط في البيئة المحلية إذا كان الثابت مفعلاً
        if (defined('UPLOADS_PHYSICAL_PATH')) {
             $physPath = UPLOADS_PHYSICAL_PATH . $cleanPath;
             if (!file_exists($physPath)) {
                 // جرب البحث في profile_photos
                 if (file_exists(UPLOADS_PHYSICAL_PATH . 'profile_photos/' . $cleanPath)) {
                     return UPLOADS_BASE_URL . 'profile_photos/' . $cleanPath;
                 }
                 // إذا لم يوجد، ابحث في المجلد الأصل htdocs/uploads/
                 $htdocsUploads = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $cleanPath;
                 if (file_exists($htdocsUploads)) {
                     // الحصول على رابط htdocs
                     $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                     return $protocol . '://' . $_SERVER['HTTP_HOST'] . '/uploads/' . $cleanPath;
                 }
                 return $fallback;
             }
        }

        return UPLOADS_BASE_URL . $cleanPath;
    }
}

// ======================================
// 6️⃣ معلومات الموقع (مركزية)
// ======================================
if (!defined('SITE_NAME')) {
    define('SITE_NAME', '.');
}

if (!defined('SITE_DESCRIPTION')) {
    define('SITE_DESCRIPTION', 'بوابة الخدمات الإلكترونية للاستعلام العام عن المعاملات - وزارة الداخلية.');
}

// ======================================
// 7️⃣ إعدادات الأمان (Security & Sessions)
// ======================================
// إعدادات الكوكيز والجلسات لزيادة الأمان (فقط إذا لم تبدأ الجلسة بعد)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1); // منع الوصول للجلسة عبر Javascript (حماية من XSS)
    ini_set('session.use_only_cookies', 1);

    // إذا كان الموقع يعمل بـ HTTPS، نفعّل خاصية Secure Cookie
    if ($protocol === 'https') {
        ini_set('session.cookie_secure', 1);
    }
}

// إخفاء الأخطاء في بيئة التوزيع (Production) للحفاظ على الخصوصية والأمان
$whitelist = array('127.0.0.1', '::1', 'localhost', 'lovestoblog.com');
$server_name = $_SERVER['SERVER_NAME'] ?? '';

$is_debug_host = false;
foreach ($whitelist as $domain) {
    if (strpos($server_name, $domain) !== false) {
        $is_debug_host = true;
        break;
    }
}

if (!$is_debug_host) {
    error_reporting(0);
    @ini_set('display_errors', 0);
}
else {
    error_reporting(E_ALL);
    @ini_set('display_errors', 1);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ======================================
// يمكنك إضافة إعدادات أخرى هنا
// مثل إعدادات قاعدة البيانات لاحقاً
// ======================================