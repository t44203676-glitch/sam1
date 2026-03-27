<?php
/**
 * Strict Enforcement: Convert ALL Eastern Arabic digits (٠-٩) to Western Arabic digits (0-9)
 * Applied globally at the bootstrap level to ensure consistency across all pages.
 */
ob_start(function($buffer) {
    if (empty($buffer)) return $buffer;
    // Arabic-Indic digits U+0660-U+0669 (encoding-safe via Unicode regex)
    $buffer = preg_replace_callback('/[\x{0660}-\x{0669}]/u', function($m) {
        return (string)(mb_ord($m[0], 'UTF-8') - 0x0660);
    }, $buffer);
    // Extended Arabic-Indic (Persian) digits U+06F0-U+06F9
    $buffer = preg_replace_callback('/[\x{06F0}-\x{06F9}]/u', function($m) {
        return (string)(mb_ord($m[0], 'UTF-8') - 0x06F0);
    }, $buffer);
    return $buffer;
});
register_shutdown_function(function() {
    if (ob_get_level() > 0) ob_end_flush();
});

/**
 * =====================================================
 * InquiryModule / core / bootstrap.php
 * =====================================================
 * الملف الأساسي الوحيد للوحدة — يحمّل كل شيء تلقائياً:
 *   - المسارات والثوابت
 *   - الإعدادات وقاعدة البيانات
 *   - الدوال المشتركة وخريطة الجنسيات
 *   - دوال الأصول والصور المرفوعة
 *   - دوال الطباعة والـ PDF
 * =====================================================
 */

// ─────────────────────────────────────────────────────
// 1. تعريف مسارات المجلد
// ─────────────────────────────────────────────────────
if (!defined('INQUIRY_MODULE_ROOT')) {
    define('INQUIRY_MODULE_ROOT', dirname(__DIR__));              // .../InquiryModule
}
if (!defined('INQUIRY_CORE_PATH')) {
    define('INQUIRY_CORE_PATH',   INQUIRY_MODULE_ROOT . '/core'); // .../InquiryModule/core
}
if (!defined('INQUIRY_PAGES_PATH')) {
    define('INQUIRY_PAGES_PATH',  INQUIRY_MODULE_ROOT . '/pages');
}
if (!defined('INQUIRY_ASSETS_PATH')) {
    define('INQUIRY_ASSETS_PATH', INQUIRY_MODULE_ROOT . '/assets');
}

// ─────────────────────────────────────────────────────
// 2. تشغيل الجلسة إذا لم تكن مشغّلة
// ─────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─────────────────────────────────────────────────────
// 3. تحميل الإعدادات (config.php)
// ─────────────────────────────────────────────────────
if (file_exists(INQUIRY_CORE_PATH . '/config.php')) {
    require_once INQUIRY_CORE_PATH . '/config.php';
} else {
    // Fallback: اكتشاف URL تلقائياً إذا لم يوجد config.php
    if (!defined('BASE_URL') || !defined('APP_URL')) {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $root     = str_replace('\\', '/', dirname(INQUIRY_MODULE_ROOT));
        $docRoot  = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
        $rel      = '/' . trim(str_replace($docRoot, '', $root), '/') . '/';
        if ($rel === '//') $rel = '/';
        $baseUrl = $protocol . '://' . $host . $rel;
        if (!defined('APP_URL'))  define('APP_URL',  $baseUrl);
        if (!defined('BASE_URL')) define('BASE_URL', $baseUrl);
    }
}

// ─────────────────────────────────────────────────────
// 4. تحميل قاعدة البيانات (database.php)
// ─────────────────────────────────────────────────────
if (file_exists(INQUIRY_CORE_PATH . '/database.php')) {
    require_once INQUIRY_CORE_PATH . '/database.php';
}

// ─────────────────────────────────────────────────────
// 5. تحميل الدوال المشتركة (functions.php)
// ─────────────────────────────────────────────────────
if (file_exists(INQUIRY_CORE_PATH . '/functions.php')) {
    require_once INQUIRY_CORE_PATH . '/functions.php';
}

// ─────────────────────────────────────────────────────
// 6. تحميل خريطة الجنسيات (nationality_map.php)
// ─────────────────────────────────────────────────────
if (file_exists(INQUIRY_CORE_PATH . '/nationality_map.php')) {
    require_once INQUIRY_CORE_PATH . '/nationality_map.php';
}

// ─────────────────────────────────────────────────────
// 7. دالة جلب مسار الأصول داخل المجلد
//    تحسب المسار تلقائياً من موقع الملف الحالي
//    الاستخدام: getInquiryAsset('images/logo.png')
// ─────────────────────────────────────────────────────
if (!function_exists('getInquiryAsset')) {
    function getInquiryAsset(string $path): string {
        // نحسب المسار النسبي لمجلد assets من جذر السيرفر تلقائياً
        $protocol   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host       = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $assetsDir  = str_replace('\\', '/', INQUIRY_ASSETS_PATH);
        $docRoot    = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
        $relAssets  = str_replace($docRoot, '', $assetsDir);
        $relAssets  = '/' . trim($relAssets, '/') . '/';
        return $protocol . '://' . $host . $relAssets . ltrim($path, '/');
    }
}

// getProfilePhotoUrl is now centralized in includes/uploads_config.php

// ─────────────────────────────────────────────────────
// 9. دوال الطباعة والـ PDF
// ─────────────────────────────────────────────────────

/**
 * getInquiryPrintStyles()
 * يعيد وسم <style> بالـ CSS الخاص بالطباعة (يُضاف في <head>)
 */
if (!function_exists('getInquiryPrintStyles')) {
    function getInquiryPrintStyles(): string {
        return '
<style id="inquiry-print-styles">
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    @page { margin: 15mm; size: A4 portrait; }
    html, body { background: white !important; }
    .main-nav, nav[aria-label="Breadcrumb"], .no-print, .top-links {
        display: none !important;
    }
    #printable-area { page-break-inside: avoid; break-inside: avoid; }
    body { zoom: 0.9; }
}
</style>';
    }
}

/**
 * getInquiryPDFScript($visaNo, $filename)
 * يعيد الـ HTML الكامل لتحميل مكتبة html2pdf والدالة downloadAsPDF()
 *
 * الاستخدام: echo getInquiryPDFScript($visaNo, 'result.pdf');  في نهاية الـ page
 */
if (!function_exists('getInquiryPDFScript')) {
    function getInquiryPDFScript(string $visaNo = '', string $filename = 'inquiry_result.pdf'): string {
        $lib = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
        return "
<script src=\"{$lib}\"></script>
<script>
function downloadAsPDF() {
    var actionsBar = document.getElementById('actions-bar');
    var noPrintEls = document.querySelectorAll('.no-print');
    noPrintEls.forEach(function(el){ el.style.display='none'; });

    var element = document.getElementById('printable-area') || document.body;
    var opt = {
        margin: 5,
        filename: '{$filename}',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
        jsPDF: { unit: 'px', format: [794, 1123], orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save().then(function() {
        noPrintEls.forEach(function(el){ el.style.display=''; });
    });
}
</script>";
    }
}

/**
 * getInquiryBarcodeScript()
 * يعيد الـ script tag لمكتبة JsBarcode
 */
if (!function_exists('getInquiryBarcodeScript')) {
    function getInquiryBarcodeScript(): string {
        return '<script src="' . getInquiryAsset('js/JsBarcode.all.min.js') . '"></script>';
    }
}
