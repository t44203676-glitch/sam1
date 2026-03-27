<?php
/**
 * =========================================================================
 * ملف الإعدادات المركزي للصور والمسارات - UPLOADS CONFIGURATION
 * =========================================================================
 * هذا الملف هو المصدر الوحيد المعتمد لمسارات الصور في المشروعين (لوحة التحكم وموقع الاستعلام).
 * أي تعديل هنا سينعكس تلقائياً على كامل المشروع.
 */

// 1. اكتشاف البيئة (Localhost vs Production)
$is_localhost_env = (
    isset($_SERVER['SERVER_NAME']) &&
    (in_array($_SERVER['SERVER_NAME'], ['127.0.0.1', 'localhost', '::1']) || strpos($_SERVER['SERVER_NAME'], 'localhost') !== false)    );

// 2. رابط الصور الأساسي (UPLOADS_BASE_URL)
// -------------------------------------------------------------------------
// [تعديل هنا]: هذا الرابط يستخدم لعرض الصور في المتصفح.
// -------------------------------------------------------------------------
if ($is_localhost_env) {
    // محلياً: نستخدم مسار مجلد htdocs الحالي
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];

    // الحصول على المسار النسبي من جذر السيرفر إلى مجلد htdocs الحالي
    $project_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // استخراج الجزء الذي يسبق 'htdocs' إذا كان موجوداً، أو استخدامه مباشرة
    $base_path = (strpos($project_path, '/htdocs') !== false) ? substr($project_path, 0, strpos($project_path, '/htdocs') + 7) : '/htdocs';

    if (!defined('UPLOADS_BASE_URL')) {
        define('UPLOADS_BASE_URL', $protocol . '://' . $host . rtrim($base_path, '/') . '/uploads/');
    }
}
else {
    // الاستضافة: الرابط المباشر لمجلد uploads
    if (!defined('UPLOADS_BASE_URL')) {
        define('UPLOADS_BASE_URL', 'https://samadmin.xo.je/uploads/');
    }
}

// 3. المسار الفيزيائي للرفع (UPLOADS_ROOT_PATH)
// -------------------------------------------------------------------------
// [تعديل هنا]: هذا المسار الحقيقي على السيرفر (Physical Path) المستخدم في عملية الرفع والحذف.
// -------------------------------------------------------------------------
if (!defined('UPLOADS_ROOT_PATH')) {
    // المجلد الآن أصبح داخل htdocs مباشرة
    // __DIR__ هو htdocs/includes/ لذا نخرج مستوى واحد وندخل لـ uploads
    define('UPLOADS_ROOT_PATH', realpath(__DIR__ . '/../uploads/') . DIRECTORY_SEPARATOR);
}

// 4. المجلد النسبي للتخزين (UPLOADS_REL_PATH)
// -------------------------------------------------------------------------
// [تعديل هنا]: اسم المجلد الذي سيخزن في قاعدة البيانات كبادئة، مثلاً: uploads/filename.jpg
// -------------------------------------------------------------------------
if (!defined('UPLOADS_REL_PATH')) {
    define('UPLOADS_REL_PATH', 'uploads/');
}

// 5. وظيفة جلب الرابط الكامل (Helper)
// -------------------------------------------------------------------------
// وظيفة موحدة لتحويل المسار المخزن في قاعدة البيانات إلى رابط قابل للعرض
// -------------------------------------------------------------------------
if (!function_exists('getProfilePhotoUrl')) {
    function getProfilePhotoUrl(?string $dbPath, ?string $fallback = '')
    {
        // إذا كان المسار فارغاً أو يحتوي على علامة فارغة
        if (empty($dbPath) || $dbPath === '---') {
            return $fallback;
        }

        // إذا كان المسار رابطاً كاملاً بالفعل (مثلاً صورة خارجية)
        if (filter_var($dbPath, FILTER_VALIDATE_URL)) {
            return $dbPath;
        }

        // تنظيف المسار لضمان بقاء اسم الملف فقط (بإزالة uploads/ أو أي مسارات قديمة)
        $filename = basename($dbPath);

        // ربط اسم الملف بالرابط الأساسي الموحد
        return rtrim(UPLOADS_BASE_URL, '/') . '/' . ltrim($filename, '/');
    }
}
