<?php
/**
 * image_server.php
 * يعمل كجسر لقراءة ومعالجة الصور من خارج مجلد htdocs في بيئة التطوير المحلية
 */

require_once 'config.php';

// التأكد من أننا في بيئة محلية ومن وجود الثابت الفيزيائي
if (!defined('UPLOADS_PHYSICAL_PATH')) {
    die("Access Denied: Path not defined.");
}

$requestedFile = $_GET['file'] ?? '';

if (empty($requestedFile)) {
    die("No file specified.");
}

// تنظيف المسار لمنع الوصول لملفات النظام الحساسة (Security)
// نسمح فقط بالوصول للملفات داخل المجلد المعمد
$safeFile = str_replace(['../', '..\\'], '', $requestedFile);
$fullPath = UPLOADS_PHYSICAL_PATH . $safeFile;

if (file_exists($fullPath) && is_file($fullPath)) {
    // تحديد نوع الصورة (MIME Type)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fullPath);
    finfo_close($finfo);

    // إرسال الهيدر الصحيح للمتصفح
    header("Content-Type: $mimeType");
    header("Content-Length: " . filesize($fullPath));
    
    // قراءة وإرسال محتوى الصورة
    readfile($fullPath);
    exit;
} else {
    // إذا لم توجد الصورة، يمكن إرسال صورة افتراضية أو خطأ 404
    header("HTTP/1.0 404 Not Found");
    echo "Image not found.";
}
