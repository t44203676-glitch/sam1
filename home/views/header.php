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

<!-- شريط التنقل -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-building me-2"></i>مكتب الخدمات
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php#home">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=services">الخدمات</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=inquiry">استعلام</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="?login=1">
                        <i class="fas fa-user-shield me-1"></i>دخول الموظفين
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>