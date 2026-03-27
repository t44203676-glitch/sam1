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
            $is_cli = (php_sapi_name() === 'cli');
            $server_name = $_SERVER['SERVER_NAME'] ?? '';
            $whitelist = array('127.0.0.1', '::1', 'localhost');
            
            // تحقق من أن السيرفر محلي (XAMPP) أو عن طريق عنوان IP محلي
            $is_local_ip = preg_match('/^(127\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|10\.)/', $server_name);
            $is_xampp = (strpos(__DIR__, 'xampp') !== false);
            
            if ($is_cli || in_array($server_name, $whitelist) || $is_local_ip || $is_xampp) {
                // Local Environment (XAMPP)
                if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
                if (!defined('DB_NAME')) define('DB_NAME', 'u721293045_sam'); // Default XAMPP DB
                if (!defined('DB_USER')) define('DB_USER', 'root');
                if (!defined('DB_PASS')) define('DB_PASS', '');
            }
            else {
                // Remote Environments
                $server_name = $_SERVER['SERVER_NAME'] ?? '';
                $is_infinity = (strpos($server_name, 'lovestoblog.com') !== false || 
                               strpos($server_name, 'infinityfree') !== false || 
                               strpos($server_name, 'wuaze.com') !== false ||
                               strpos($server_name, 'rf.gd') !== false ||
                               strpos($server_name, 'great-site.net') !== false ||
                               strpos($server_name, 'epizy.com') !== false ||
                               strpos($server_name, 'infinityfreeapp.com') !== false ||
                               strpos($server_name, 'xo.je') !== false ||
                               strpos($server_name, '42web.io') !== false);
                
                if ($is_infinity) {
                    // 1️⃣ InfinityFree (New 'ash' Database)
                    if (!defined('DB_HOST')) define('DB_HOST', 'sql303.infinityfree.com'); 
                    if (!defined('DB_NAME')) define('DB_NAME', 'if0_41328620_ash'); 
                    if (!defined('DB_USER')) define('DB_USER', 'if0_41328620'); 
                    if (!defined('DB_PASS')) define('DB_PASS', 'Vn8NsjjEO9H');
                } else {
                    // 2️⃣ Hostinger or Other (General Default)
                    // Note: If on a remote server that is NOT InfinityFree, we use these as fallback
                    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
                    if (!defined('DB_NAME')) define('DB_NAME', 'u721293045_samm'); 
                    if (!defined('DB_USER')) define('DB_USER', 'u721293045_samm'); 
                    if (!defined('DB_PASS')) define('DB_PASS', 'Engsam100*');
                }
            }

            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
