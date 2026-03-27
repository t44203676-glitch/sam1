<?php
// Load data from data_nav.php to ensure consistency
$nav_data = include __DIR__ . '/data_nav.php';

// Prepare main tabs for mobile
$main_tabs = [
    ['id' => 'about', 'title' => 'عن الوزارة', 'link' => BASE_URL . $nav_data['about']['link'], 'img_icon' => BASE_URL . 'images/5.jpg'],
    ['id' => 'eservices', 'title' => 'الخدمات الإلكترونية', 'link' => BASE_URL . $nav_data['eservices']['link'], 'img_icon' => BASE_URL . 'images/icon_global.png'],
    ['id' => 'emirates', 'title' => 'الإمارات', 'link' => BASE_URL . $nav_data['emirates']['link'], 'img_icon' => BASE_URL . 'images/emarat-icon-green.svg'],
    ['id' => 'sectors', 'title' => 'القطاعات', 'link' => BASE_URL . $nav_data['sectors']['link'], 'icon' => 'home-mainmenu_sectors', 'img_icon' => BASE_URL . 'images/icon_public_security.png'],
    ['id' => 'media', 'title' => 'المركز الإعلامي', 'link' => BASE_URL . $nav_data['media']['link'], 'img_icon' => BASE_URL . 'images/media-icon-green.svg'],
];

// Submenu items from data_nav
$ministry_items = [];
if (isset($nav_data['about']['is_complex_about'])) {
    foreach ($nav_data['about']['complex_cols'] as $col) {
        foreach ($col['items'] as $item) {
            $ministry_items[] = [
                'title' => $item['title'],
                'link' => BASE_URL . $item['link'],
                'img_icon' => BASE_URL . 'images/MOI(1).jpg',
                'icon' => 'home-mainmenu_aboutmoi'
            ];
        }
    }
} elseif (isset($nav_data['about']['items'])) {
    $ministry_items = $nav_data['about']['items'];
    foreach ($ministry_items as &$item) {
        $item['link'] = BASE_URL . $item['link'];
        $item['img_icon'] = BASE_URL . 'images/MOI(1).jpg';
        $item['icon'] = 'home-mainmenu_aboutmoi';
    }
}

// Sectors items mapping
$sectors_items = [];
foreach ($nav_data['sectors']['complex_sectors'] as $group) {
    foreach ($group['items'] as $item) {
        $sectors_items[] = [
            'title' => $item['title'],
            'link' => BASE_URL . $item['link'],
            'img_icon' => BASE_URL . 'images/MOI(1).jpg' // Default icons can be refined later
        ];
    }
}

$emirates_items = $nav_data['emirates']['items'];
foreach ($emirates_items as &$item) {
    $img_name = basename($item['link'], '.php');
    $item['img_icon'] = BASE_URL . 'images/emirates/emirates_' . $img_name . '.png';
    $item['link'] = BASE_URL . $item['link'];
    if ($item['title'] == 'إمارة منطقة الرياض')
        $item['img_icon'] = BASE_URL . 'images/emirates/emirates_riyadh.png';}

?>

<div class="mobile-menu-trigger-bar">


    <div class="container-fluid">
        <button class="mobile-menu-toggle-btn" id="mobileMenuOpen">
            <span class="toggle-icon">☰</span>
            <span class="toggle-text">القائمة</span>
        </button>
    </div>
</div>

<?php // Mobile drawer is always rendered but controlled via trigger
?>
<div class="mobile-menu-overlay"></div>
<div class="mobile-menu-drawer" id="mobileDrawer">
    <div class="mobile-menu-header" id="menuHeader">
        <div class="header-right-action">
             <button class="btn-header btn-back hidden" id="menuBackBtn">
                <span class="back-arrows" style="font-weight: bold; font-style: normal; font-size: 24px;">&gt;</span>
            </button>
        </div>
        <div class="header-title-container">
            <span id="menuCurrentTitle">الرئيسية</span>
        </div>
        <div class="header-left-action">
            <a href="<?php echo BASE_URL; ?>index.php" class="btn-header" style="color: #fff; text-decoration: none;">
                <i class="fas fa-home" style="font-size: 26px; margin-top: -3px;"></i>
            </a>
        </div>
    </div>

    <div class="mobile-menu-viewport">
        <div class="menu-level active" id="main-level">
            <ul class="mobile-menu-list">
                <?php foreach ($main_tabs as $tab):
        $has_sub = ($tab['id'] !== 'media'); // Media is a direct link
?>
                    <li class="menu-item">
                        <a href="<?php echo $tab['link']; ?>" 
                           class="menu-link <?php echo $has_sub ? 'has-submenu' : ''; ?>" 
                           <?php if ($has_sub): ?>
                           data-target="submenu-<?php echo $tab['id']; ?>" 
                           data-title="<?php echo $tab['title']; ?>"
                           <?php
        endif; ?>>
                             <span class="item-icon-circle">
                                <?php if (isset($tab['img_icon'])): ?>
                                    <img src="<?php echo $tab['img_icon']; ?>" class="item-img-icon">
                                <?php
        else: ?>
                                    <i class="homesprite <?php echo $tab['icon']; ?>"></i>
                                <?php
        endif; ?>
                            </span>
                            <span class="item-text"><?php echo $tab['title']; ?></span>
                            <?php if ($has_sub): ?>
                                <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                            <?php
        endif; ?>
                        </a>
                    </li>
                <?php
    endforeach; ?>
            </ul>
        </div>

        <div class="menu-level submenu" id="submenu-about">
            <ul class="mobile-menu-list">
                <?php foreach ($ministry_items as $item): ?>
                    <li class="menu-item">
                        <a href="<?php echo $item['link']; ?>" class="menu-link">
                             <span class="item-icon-circle">
                                <?php if (isset($item['img_icon'])): ?>
                                    <img src="<?php echo $item['img_icon']; ?>" class="item-img-icon">
                                <?php
        else: ?>
                                    <i class="homesprite <?php echo $item['icon']; ?>"></i>
                                <?php
        endif; ?>
                            </span>
                            <span class="item-text"><?php echo $item['title']; ?></span>
                            <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                        </a>
                    </li>
                <?php
    endforeach; ?>
            </ul>
        </div>

        <div class="menu-level submenu" id="submenu-eservices">
            <ul class="mobile-menu-list">
                <?php foreach ($nav_data['eservices']['categories'] as $cat): ?>
                    <li class="menu-item">
                        <a href="#" class="menu-link has-submenu" 
                           data-target="sublevel-<?php echo $cat['id']; ?>" 
                           data-title="<?php echo $cat['title']; ?>">
                             <span class="item-icon-circle">
                                <img src="<?php echo BASE_URL . $cat['icon']; ?>" class="item-img-icon">
                            </span>
                            <span class="item-text"><?php echo $cat['title']; ?></span>
                            <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <?php foreach ($nav_data['eservices']['categories'] as $cat): ?>
            <div class="menu-level submenu" id="sublevel-<?php echo $cat['id']; ?>">
                <ul class="mobile-menu-list">
                    <?php foreach ($cat['services'] as $svc): ?>
                        <li class="menu-item">
                            <a href="<?php echo BASE_URL . $svc['link']; ?>" class="menu-link">
                                <?php if (isset($svc['icon'])): ?>
                                    <span class="item-icon-circle">
                                        <img src="<?php echo BASE_URL . $svc['icon']; ?>" class="item-img-icon">
                                    </span>
                                <?php endif; ?>
                                <span class="item-text"><?php echo $svc['title']; ?></span>
                                <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <div class="menu-level submenu" id="submenu-sectors">
            <ul class="mobile-menu-list">
                <?php foreach ($sectors_items as $item): ?>
                    <li class="menu-item">
                        <a href="<?php echo $item['link']; ?>" class="menu-link">
                             <span class="item-icon-circle">
                                <?php if (isset($item['img_icon'])): ?>
                                    <img src="<?php echo $item['img_icon']; ?>" class="item-img-icon">
                                <?php
        endif; ?>
                            </span>
                            <span class="item-text"><?php echo $item['title']; ?></span>
                            <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                        </a>
                    </li>
                <?php
    endforeach; ?>
            </ul>
        </div>

        <div class="menu-level submenu" id="submenu-emirates">
            <ul class="mobile-menu-list">
                <?php foreach ($emirates_items as $item): ?>
                    <li class="menu-item">
                        <a href="<?php echo $item['link']; ?>" class="menu-link">
                             <span class="item-icon-circle">
                                <?php if (isset($item['img_icon'])): ?>
                                    <img src="<?php echo $item['img_icon']; ?>" class="item-img-icon">
                                <?php
        endif; ?>
                            </span>
                            <span class="item-text"><?php echo $item['title']; ?></span>
                            <span class="item-arrow-left"><i class="fas fa-chevron-left"></i></span>
                        </a>
                    </li>
                <?php
    endforeach; ?>
            </ul>
        </div>
        
    </div>
</div>
