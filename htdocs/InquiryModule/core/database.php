<?php
// InquiryModule/core/database.php
// توحيد الاتصال بقاعدة البيانات عبر الملف الرئيسي

$main_db_file = dirname(__DIR__, 2) . '/includes/database.php';

if (file_exists($main_db_file)) {
    require_once $main_db_file;
} else {
    // Fallback logic if main file is missing (though it shouldn't be)
    require_once __DIR__ . '/config.php';
    if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
    if (!defined('DB_NAME')) define('DB_NAME', 'u721293045_sam');
    if (!defined('DB_USER')) define('DB_USER', 'root');
    if (!defined('DB_PASS')) define('DB_PASS', '');
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $pdo = null;
    }
}
