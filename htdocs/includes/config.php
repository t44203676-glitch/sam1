<?php
/**
 * Dynamic Path Configuration
 * يكتشف المسار تلقائياً سواء في مجلد فرعي أو الدومين الرئيسي
 */

// ======================================
// 1️⃣ تحديد البروتوكول (HTTP أو HTTPS)
// ======================================
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";

// ======================================
// 2️⃣ تحديد اسم النطاق
// ======================================
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// ======================================
// 3️⃣ تحديد المسار الفعلي للمشروع
// ======================================
$project_root  = str_replace('\\', '/', dirname(__DIR__)); 
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''); 

// حساب المسار النسبي من جذر السيرفر إلى جذر المشروع
$relative_path = str_replace($document_root, '', $project_root);

// تأكد من وجود سلاش في البداية والنهاية
$relative_path = '/' . trim($relative_path, '/') . '/';
if ($relative_path === '//') {
    $relative_path = '/';
}

// ======================================
// 4️⃣ الرابط الأساسي النهائي
// ======================================
$base_url = $protocol . "://" . $host . $relative_path;

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
// 5️⃣ تعريف مسارات الصور (مركزي)
// ======================================
require_once __DIR__ . '/uploads_config.php';

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
foreach($whitelist as $domain) {
    if (strpos($server_name, $domain) !== false) {
        $is_debug_host = true;
        break;
    }
}

if (!$is_debug_host) {
    error_reporting(0);
    @ini_set('display_errors', 0);
} else {
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