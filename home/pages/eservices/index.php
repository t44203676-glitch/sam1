<?php
$page_title = "الخدمات الإلكترونية";
$active_page = "eservices";
include '../../includes/header.php';
include '../../includes/navigation.php';

// Get navigation data to use as source for services
$nav_data = include '../../includes/data_nav.php';
$categories = $nav_data['eservices']['categories'] ?? [];

include '../../includes/breadcrumb.php';
?>

<div class="container row">
    <div class="eservices-portal">
        <div class="portal-header">
            <h1>منصة الخدمات الإلكترونية</h1>
            <p>اختر التصنيف المناسب للوصول إلى الخدمة المطلوبة</p>
        </div>

        <div class="services-grid">
            <?php foreach ($categories as $cat): ?>
                <div class="category-card">
                    <div class="category-header">
                        <?php
    // Map icons to local versions if needed
    $icon = BASE_URL . $cat['icon'];
    if (strpos($cat['id'], 'diwan') !== false)
        $icon = BASE_URL . 'images/icon_diwan.png';
    if (strpos($cat['id'], 'civil') !== false)
        $icon = BASE_URL . 'images/icon_civil_affairs.png';
    if (strpos($cat['id'], 'passports') !== false)
        $icon = BASE_URL . 'images/icon_passports.png';
    if (strpos($cat['id'], 'traffic') !== false)
        $icon = BASE_URL . 'images/icon_public_security.png';
    if (strpos($cat['id'], 'emirates') !== false)
        $icon = BASE_URL . 'images/emarat-icon-green.svg';
?>
                        <img src="<?php echo $icon; ?>" alt="<?php echo $cat['title']; ?>">
                        <h3><?php echo $cat['title']; ?></h3>
                    </div>
                    <ul class="services-sublist">
                        <?php foreach ($cat['services'] as $svc): ?>
                            <li>
                                <a href="<?php echo BASE_URL . $svc['link']; ?>">
                                    <?php echo $svc['title']; ?>
                                </a>
                            </li>
                        <?php
    endforeach; ?>
                    </ul>
                </div>
            <?php
endforeach; ?>
        </div>
    </div>
</div>

<style>
    .eservices-portal {
        direction: rtl;
        padding: 30px 0;
        width: 100%;
    }

    .portal-header {
        text-align: right;
        margin-bottom: 40px;
        border-bottom: 2px solid #00ab67;
        padding-bottom: 15px;
    }

    .portal-header h1 {
        color: #00ab67;
        font-size: 28px;
        margin-bottom: 5px;
    }

    .portal-header p {
        color: #666;
        font-size: 16px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .category-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .category-header {
        background: #f9f9f9;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #eee;
    }

    .category-header img {
        width: 45px;
        height: 45px;
        object-fit: contain;
    }

    .category-header h3 {
        margin: 0;
        font-size: 18px;
        color: #333;
    }

    .services-sublist {
        list-style: none;
        padding: 15px 20px;
        margin: 0;
    }

    .services-sublist li {
        padding: 8px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .services-sublist li:last-child {
        border-bottom: none;
    }

    .services-sublist li a {
        text-decoration: none;
        color: #00ab67;
        font-size: 14px;
        font-weight: bold;
        display: block;
        padding-right: 15px;
        position: relative;
    }

    .services-sublist li a:before {
        content: '←';
        position: absolute;
        right: 0;
        top: 8px;
        font-size: 12px;
    }

    .services-sublist li a:hover {
        color: #008f56;
        padding-right: 20px;
    }
</style>

<?php include '../../includes/footer.php'; ?>
