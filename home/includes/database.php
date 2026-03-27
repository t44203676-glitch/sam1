<?php
require_once __DIR__ . '/config.php';
// تضمين ملف تسجيل الأخطاء
// إعدادات قاعدة البيانات - SQLite للاختبار المحلي
define('DB_TYPE', 'mysql'); // sqlite أو mysql
define('DB_PATH', __DIR__ . '/../office_service.db'); // مسار ملف SQLite

// متغير لتفعيل أو تعطيل استخدام قاعدة البيانات
define('USE_DATABASE', true);

// تم استبدال ملف logger.php بدالة فارغة هنا لتجنب الأخطاء
if (!function_exists('log_error')) {
    function log_error($message, $file = null, $line = null)
    {
    // Logging disabled
    }
}
$pdo = null;
if (USE_DATABASE) {
    try {
        if (DB_TYPE === 'sqlite') {
            // الاتصال بـ SQLite
            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // تفعيل المفاتيح الأجنبية في SQLite
            $pdo->exec('PRAGMA foreign_keys = ON');
            // تعيين الترميز
            $pdo->exec("PRAGMA encoding = 'UTF-8'");
        }
        else {
            // الاتصال بـ MySQL (النسخة الأصلية)
            // التحقق من البيئة (Local vs Production)
            $whitelist = array('127.0.0.1', '::1', 'localhost');
            if (in_array($_SERVER['SERVER_NAME'], $whitelist)) {
                // Local Environment
                define('DB_HOST', 'localhost');
                define('DB_NAME', 'u721293045_sam');
                define('DB_USER', 'root');
                define('DB_PASS', '');
            }
            else {
                // المحرك الرئيسي للاتصال (Hostinger أو InfinityFree)
                $server_name = $_SERVER['SERVER_NAME'] ?? '';
                
                if (strpos($server_name, 'lovestoblog.com') !== false || strpos($server_name, 'infinityfree') !== false || strpos($server_name, 'wuaze.com') !== false || strpos($server_name, 'xo.je') !== false || strpos($server_name, 'rf.gd') !== false || strpos($server_name, 'great-site.net') !== false || strpos($server_name, 'epizy.com') !== false || strpos($server_name, 'infinityfreeapp.com') !== false || strpos($server_name, '42web.io') !== false) {
                    // 1️⃣ الإعدادات الاحتياطية (InfinityFree / Backup)
                    // ملاحظة: تأكد من إنشاء قاعدة بيانات بهذا الاسم في لوحة التحكم أولاً
                    if (!defined('DB_HOST')) define('DB_HOST', 'sql303.infinityfree.com');
                    if (!defined('DB_NAME')) define('DB_NAME', 'if0_41328620_ash');
                    if (!defined('DB_USER')) define('DB_USER', 'if0_41328620');
                    if (!defined('DB_PASS')) define('DB_PASS', 'Vn8NsjjEO9H');
                } else {
                    // 2️⃣ الإعدادات الأساسية (Hostinger / Original)
                    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
                    if (!defined('DB_NAME')) define('DB_NAME', 'u721293045_samm'); 
                    if (!defined('DB_USER')) define('DB_USER', 'u721293045_samm'); 
                    if (!defined('DB_PASS')) define('DB_PASS', 'Engsam100*');
                }
            }

            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("set names utf8");
        }
    }
    catch (PDOException $e) {
        $pdo = null;
        $error_msg = $e->getMessage();
        error_log("Database connection failed: " . $error_msg);

        // إظهار الخطأ للمساعدة في التصحيح إذا كنا على دومين المعاينة
        $server_name = $_SERVER['SERVER_NAME'] ?? '';
        if (strpos($server_name, 'lovestoblog.com') !== false) {
            define('DB_CONNECTION_ERROR', $error_msg);
        }
    }
}
