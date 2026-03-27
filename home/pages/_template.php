\u003c?php
/**
 * Page Template
 * استخدم هذا القالب لإنشاء صفحات جديدة
 * 
 * المتغيرات المطلوبة:
 * - $page_title: عنوان الصفحة
 * - $active_page: الصفحة النشطة في القائمة (home, about, eservices, emirates, sectors, media)
 * - $breadcrumb_items: مصفوفة عناصر التنقل
 */

// تعيين عنوان الصفحة
$page_title = isset($page_title) ? $page_title : "صفحة";
$active_page = isset($active_page) ? $active_page : "";

// تضمين الهيدر
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?\u003e

\u003c!-- محتوى الصفحة --\u003e
\u003cdiv class="container row"\u003e
    \u003cdiv class="main-content"\u003e
        \u003c!-- المحتوى هنا --\u003e
        \u003cdiv class="page-content" style="padding: 40px 20px; min-height: 400px; background: #fff;"\u003e
            \u003ch1 style="color: #00a651; margin-bottom: 20px; font-size: 28px;"\u003e\u003c?php echo $page_title; ?\u003e\u003c/h1\u003e
            \u003cp style="color: #666; line-height: 1.8; font-size: 16px;"\u003eمحتوى الصفحة سيتم إضافته هنا.\u003c/p\u003e
        \u003c/div\u003e
    \u003c/div\u003e
\u003c/div\u003e

\u003c?php
// تضمين الفوتر
include '../../includes/footer.php';
?\u003e
