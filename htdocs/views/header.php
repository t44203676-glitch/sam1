<?php
// views/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$version = time(); // Cache busting
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مكتب الخدمات - نظام إدارة المعاملات</title>    
    <base href="<?php echo APP_URL; ?>">
    <link rel="stylesheet" href="public/css/bootstrap.min.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/fonts.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/all.min.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/style.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/hijri-picker.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/forms.css?v=<?php echo $version; ?>">
    <link rel="stylesheet" href="public/css/toast-notifications.css?v=<?php echo $version; ?>">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- JavaScript Files -->
    <script src="public/js/common.js?v=<?php echo $version; ?>"></script>
    <script src="public/js/hijri-picker.js?v=<?php echo $version; ?>"></script>
    <script src="public/js/toast-notifications.js?v=<?php echo $version; ?>"></script>
    <script src="public/js/confirmation-modals.js?v=<?php echo $version; ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/main.js?v=<?php echo $version; ?>"></script>
    <script src="public/js/bootstrap.bundle.min.js" defer></script>
    <script src="public/js/toast-notifications.js?v=<?php echo $version; ?>"></script>
<body>

<?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
<!-- شريط التنقل - يظهر فقط في صفحة تسجيل الدخول -->
<nav class="navbar navbar-expand-lg fixed-top" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-bottom: 1px solid rgba(99,102,241,0.15); box-shadow: 0 2px 15px rgba(0,0,0,0.2);">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="color:#a5b4fc; font-weight:800; font-size:1.05rem;">
            <i class="fas fa-building me-2" style="color:#818cf8;"></i>مكتب الخدمات
        </a>
    </div>
</nav>
<?php else: ?>
<!-- شريط علوي موحد للجوال فقط - مكتب الخدمات -->
<div class="mobile-header d-md-none" style="background: linear-gradient(135deg, #1e293b, #0f172a); position: sticky; top: 0; z-index: 1040; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <!-- زر القائمة على اليسار -->
        <button class="btn text-white p-0" type="button" id="mobileMenuToggle" style="font-size:1.3rem; border:none; background:none; order:2;" aria-label="فتح القائمة">
            <i class="fas fa-bars"></i>
        </button>
        <!-- اسم المكتب على اليمين -->
        <a href="index.php?admin=1" style="color:#a5b4fc; font-weight:bold; font-size:1.05rem; text-decoration:none; order:1;">
            <i class="fas fa-building me-1" style="font-size:0.9rem;"></i>مكتب الخدمات
        </a>
    </div>
</div>

<!-- خلفية معتمة عند فتح القائمة -->
<div id="sidebarOverlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:1045;"></div>

<style>
body { padding-top: 0 !important; }
@media (max-width: 767.98px) {
    /* القائمة الجانبية تفتح من اليسار */
    .admin-sidebar {
        display: none !important;
    }
    .admin-sidebar.mobile-open {
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        right: auto;
        bottom: 0;
        width: 255px;
        z-index: 1050;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
        box-shadow: 5px 0 20px rgba(0,0,0,0.4);
        overflow-y: auto;
        animation: slideInLeft 0.25s ease-out;
    }
    @keyframes slideInLeft {
        from { transform: translateX(-100%); }
        to { transform: translateX(0); }
    }

    /* تصغير الأيقونات والنصوص في القائمة الجانبية */
    .admin-sidebar .nav-link {
        font-size: 0.82rem !important;
        padding: 0.55rem 0.8rem !important;
    }
    .admin-sidebar .nav-link i {
        font-size: 0.8rem !important;
        width: 18px !important;
    }
    .admin-sidebar h4,
    .admin-sidebar h5 {
        font-size: 0.95rem !important;
    }

    /* تحسين الهيدر الرئيسي لكل صفحة */
    .h2-responsive {
        font-size: 1.1rem !important;
    }
    #main-header i {
        font-size: 1rem !important;
    }

    /* تصغير أيقونات البطاقات والإحصائيات */
    .stat-card {
        padding: 1rem 0.6rem !important;
    }
    .stat-icon {
        font-size: 1.1rem !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 8px !important;
        margin-bottom: 0.3rem !important;
    }
    .stat-number {
        font-size: 1.3rem !important;
    }
    .stat-label {
        font-size: 0.75rem !important;
    }

    /* تصغير الأيقونات في الجداول والقوائم الشجرية */
    .list-group-item i {
        font-size: 0.85rem !important;
        width: 16px !important;
    }
    .admin-tree-container .list-group-item {
        padding: 0.6rem 0.8rem !important;
        font-size: 0.85rem !important;
    }

    /* تصغير أيقونات Font Awesome الكبيرة */
    .fas, .far, .fab, .fa {
        font-size: inherit;
    }
    .fa-2x { font-size: 1.25em !important; }
    .fa-3x { font-size: 1.6em !important; }
    .fa-4x { font-size: 2em !important; }
    
    /* تصغير أزرار الأكشن في الجدول */
    .btn-sm i {
        font-size: 0.75rem !important;
    }
    .btn-group .btn {
        padding: 0.3rem 0.5rem !important;
    }
    
    /* تصغير الأزرار بشكل عام والأيقونات لتتناسب ديناميكياً مع الجوال */
    .btn {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.8rem !important;
    }
    .btn i, .btn-icon, .action-btn i, .action-icon {
        font-size: 0.75rem !important;
    }
    table .btn-sm, .table .btn-sm, .table-action-btn {
        padding: 0.2rem 0.4rem !important;
        font-size: 0.75rem !important;
    }
    table .btn-sm i, .table .btn-sm i {
        font-size: 0.7rem !important;
    }
    
    /* منع الجداول من تجاوز الشاشة عرضياً */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }
}
@media (min-width: 768px) {
    .mobile-header { display: none !important; }
    #sidebarOverlay { display: none !important; }
}
/* تحسين جداول بيانات الأفراد (الخطوة الثانية) لسهولة التعديل */
.table-responsive #partners-table {
    min-width: 800px; /* تقليل الحد الأدنى للسماح بالتمرير في نطاق معقول */
    border-collapse: separate !important;
    border-spacing: 0 !important;
}
@media (max-width: 768px) {
    .table-responsive #partners-table {
        min-width: auto !important; /* السماح للنظام بالتكيف في الجوال */
    }
}
#partners-table th, 
#partners-table td {
    border: 1px solid #f1f5f9 !important;
    padding: 8px 6px !important;
    vertical-align: middle !important;
    background-color: #fff !important;
}
#partners-table thead th {
    background-color: #f8fafc !important;
    color: #475569 !important;
    font-size: 0.85rem !important;
    white-space: nowrap !important;
    border-bottom: 2px solid #f1f5f9 !important;
}
#partners-table input.form-control, 
#partners-table select.form-select {
    min-width: 130px !important;
    height: 34px !important;
    padding: 0.25rem 0.5rem !important;
    font-size: 0.85rem !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    background-color: #fff !important;
    color: #1e293b !important;
}
#partners-table td[data-field-name="full_name"] input {
    min-width: 250px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('mobileMenuToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');
    
    if (!toggleBtn || !sidebar) return;

    function openMenu() {
        sidebar.classList.add('mobile-open');
        sidebar.classList.remove('collapse');
        overlay.style.display = 'block';
        toggleBtn.querySelector('i').className = 'fas fa-times';
        document.body.style.overflow = 'hidden'; // منع السكرول عند فتح القائمة
    }

    function closeMenu() {
        sidebar.classList.remove('mobile-open', 'show');
        sidebar.classList.add('collapse');
        overlay.style.display = 'none';
        toggleBtn.querySelector('i').className = 'fas fa-bars';
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', function() {
        if (sidebar.classList.contains('mobile-open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // إغلاق عند الضغط على الخلفية المعتمة
    overlay.addEventListener('click', closeMenu);

    // إغلاق عند الضغط على زر X داخل القائمة الجانبية
    sidebar.querySelectorAll('.sidebar-close-btn').forEach(function(btn) {
        btn.addEventListener('click', closeMenu);
    });

    // إغلاق عند الضغط على أي رابط في القائمة
    sidebar.querySelectorAll('a.nav-link').forEach(function(link) {
        link.addEventListener('click', function() {
            setTimeout(closeMenu, 150);
        });
    });
});
</script>
<?php endif; ?>