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
// 2️⃣ تحديد اسم النطاق وهل هو محلي
// ======================================
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$sn = $_SERVER['SERVER_NAME'] ?? '';
$is_localhost = (in_array($sn, ['127.0.0.1', 'localhost', '::1']) || strpos($sn, 'localhost') !== false);

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
// ======================================
// 5️⃣ تعريف مسارات الصور (مركزي)
// ======================================
require_once __DIR__ . '/../../includes/uploads_config.php';

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