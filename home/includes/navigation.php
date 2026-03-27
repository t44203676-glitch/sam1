<?php
$active_page = isset($active_page) ? $active_page : 'home';

// Initializing dynamic navigation data
$nav_items = include __DIR__ . '/data_nav.php';
?>

<div class="mainnav">
    <div class="nav-holder">
        <ul class="nav-items">
            <?php foreach ($nav_items as $key => $item): ?>
                <?php
    $isActive = ($active_page == $key) ? 'active' : '';
    $mouseEvents = '';
    if (isset($item['sub_id'])) {
        $mouseEvents = 'onmouseover="setVisibility(\'' . $item['sub_id'] . '\',\'inline\');" onmouseout="setVisibility(\'' . $item['sub_id'] . '\',\'none\');"';
    }
?>
                <li class="<?php echo($item['class'] ?? '') . ' ' . $isActive; ?>" id="nav-<?php echo $key; ?>" <?php echo $mouseEvents; ?> style="<?php echo $item['style'] ?? ''; ?>">
                    <a class="parent" href="<?php echo BASE_URL . $item['link']; ?>">
                        <i class="homesprite <?php echo $item['icon']; ?>"></i> <span><?php echo $item['title']; ?></span>
                    </a>
                    
                    <?php if (isset($item['items']) && $key != 'eservices' && !isset($item['is_complex_sectors'])): ?>
                        <div id="<?php echo $item['sub_id'] ?? 'sub-' . $key; ?>" class="sub-nav sub-navSectors" style="display: none;" <?php echo $mouseEvents; ?>>
                            <ul class="sector-levelSectors">
                                <?php foreach ($item['items'] as $sub): ?>
                                    <li class="<?php echo $sub['class'] ?? ''; ?>">
                                        <?php 
                                            $final_link = $sub['link'];
                                        ?>
                                        <?php if (isset($sub['icon'])): ?>
                                            <a href="<?php echo BASE_URL . $final_link; ?>" style="display: flex; align-items: center; gap: 10px; justify-content: flex-start; text-decoration: none;">
                                                <img src="<?php echo BASE_URL . $sub['icon']; ?>" style="width: 28px; height: 28px; object-fit: contain;">
                                                <span style="flex: 1; text-align: right;"><?php echo $sub['title']; ?></span>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL . $final_link; ?>"><?php echo $sub['title']; ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php
        endforeach; ?>
                            </ul>
                        </div>
                    <?php
    elseif ($key == 'eservices'): ?>
                        <div id="sub3" class="sub-nav eservices-mega" style="display: none;" onmouseover="setVisibility('sub3','inline');" onmouseout="setVisibility('sub3','none');">
                            <div class="es-mega-container" role="menu">
                                <!-- Right Panel: Main Categories (42%) -->
                                <div class="es-mega-right">
                                    <ul class="es-main-list" role="menu">
                                        <?php foreach ($item['categories'] as $index => $cat): ?>
                                            <li class="es-main-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                onmouseover="switchEsPanel(<?php echo $index; ?>, this)" 
                                                onfocus="switchEsPanel(<?php echo $index; ?>, this)" 
                                                tabindex="0" role="menuitem">
                                                <!-- نفس أيقونات الجوال من $cat['icon'] -->
                                                <img src="<?php echo BASE_URL . $cat['icon']; ?>" class="es-cat-img" alt="">
                                                <span class="es-main-title"><?php echo $cat['title']; ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <!-- Left Panel: Services (58%) -->
                                <div class="es-mega-left">
                                    <?php foreach ($item['categories'] as $index => $cat): ?>
                                        <div id="es-panel-<?php echo $index; ?>" class="es-mega-panel" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;" role="menuitem">
                                            <ul class="es-sub-list">
                                                <?php if (isset($cat['services']) && count($cat['services']) > 0): ?>
                                                    <?php foreach ($cat['services'] as $svc): ?>
                                                        <li>
                                                            <a href="<?php echo (strpos($svc['link'], 'http') === 0 ? '' : BASE_URL) . $svc['link']; ?>">
                                                                <img src="<?php echo BASE_URL . $cat['icon']; ?>" class="es-cat-img" alt="">
                                                                <span><?php echo $svc['title']; ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <li><span class="es-no-services">لا توجد خدمات فرعية حالياً</span></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif (isset($item['is_complex_about'])): ?>
                        <div id="<?php echo $item['sub_id'] ?? 'sub-about'; ?>" class="sub-nav sub-navSectors" style="display: none; padding: 30px; right: 0; min-width: 1100px; background: #fff; box-shadow: 0px 5px 25px rgba(0,0,0,0.15); border: 1px solid #e0e0e0; direction: rtl;" <?php echo $mouseEvents; ?>>
                            <ul class="sector-levelSectors" style="display: flex; flex-direction: row-reverse; list-style: none; padding: 0; margin: 0; justify-content: space-between; direction: rtl;">
                                <?php foreach ($item['complex_cols'] as $colIndex => $col): ?>
                                    <li class="sec-col" style="flex: 1; min-width: 240px; padding: 0 0 0 30px; <?php echo $colIndex > 0 ? 'border-left: 1px solid #eee;' : ''; ?>">
                                        <ul class="sectorColumn" style="list-style: none; padding: 0; margin: 0; text-align: right; direction: rtl;">
                                            <?php foreach ($col['items'] as $li): ?>
                                                <li style="margin-bottom: 25px;">
                                                    <a href="<?php echo BASE_URL . $li['link']; ?>" style="display: flex; align-items: flex-start; text-decoration: none; color: #4C4C4C; font-size: 15px; font-weight: bold; transition: color 0.2s; gap: 12px; justify-content: flex-start; direction: rtl; width: 100%;">
                                                        <?php if (isset($li['icon'])): ?>
                                                            <img src="<?php echo BASE_URL . $li['icon']; ?>" style="width: 32px; height: 32px; object-fit: contain; order: 1; flex-shrink: 0;">
                                                        <?php endif; ?>
                                                        <span style="order: 2; text-align: right; line-height: 1.4; display: block; flex: 1;"><?php echo $li['title']; ?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php elseif (isset($item['is_complex_sectors'])): ?>
                        <div id="<?php echo $item['sub_id'] ?? 'sub-sectors'; ?>" class="sub-nav sub-navSectors" style="display: none;" <?php echo $mouseEvents; ?>>
                            <ul class="sector-levelSectors">
                                <?php foreach ($item['complex_sectors'] as $idx => $group): ?>
                                    <?php if ($idx == 0): // First group (Diwan) has specific structure ?>
                                        <li class="<?php echo $group['class']; ?>" style="height:auto"><a href="pages/sectors/index.php"><?php echo $group['title']; ?></a>
                                            <ul class="menuSubLevel2">
                                                <?php foreach ($group['items'] as $li): ?>
                                                    <li><a href="<?php echo BASE_URL; ?>pages/sectors/index.php"><?php echo $li['title']; ?></a></li>
                                                <?php
                endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php
            else: ?>
                                        <li class="sec-col">
                                            <ul class="sectorColumn">
                                                <?php foreach ($group['items'] as $li): ?>
                                                    <li class="<?php echo $li['class'] ?? ''; ?>">
                                                        <a href="<?php echo BASE_URL; ?>pages/sectors/index.php"><?php echo $li['title']; ?></a>
                                                        <?php if (isset($li['sub_items'])): ?>
                                                            <ul class="menuSubLevel2">
                                                                 <?php foreach ($li['sub_items'] as $sli): ?>
                                                                    <li><a href="<?php echo BASE_URL; ?>pages/sectors/index.php"><?php echo $sli['title']; ?></a></li>
                                                                <?php
                        endforeach; ?>
                                                            </ul>
                                                        <?php
                    endif; ?>
                                                    </li>
                                                <?php
                endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php
            endif; ?>
                                <?php
        endforeach; ?>
                            </ul>
                        </div>
                    <?php
    endif; ?>
                </li>
            <?php
endforeach; ?>
        </ul>
    </div>
</div>

<?php 
// Enable and render mobile menu
$show_mobile_trigger = true;
$show_mobile_drawer = true;
include __DIR__ . '/mobile_menu.php';
?>

<style>
/* =========================================
   E-Services Mega Menu — Navigation.php
   Uses same icon style as mobile menu
   ========================================= */

/* Mega Menu category icon images — fixed 28px */
.es-cat-img {
    width: 28px;
    height: 28px;
    object-fit: contain;
    flex-shrink: 0;
}

/* Parent <li> must allow overflow so dropdown shows below tabs */
li#nav-eservices {
    position: relative;
    overflow: visible !important;
}

/* Outer dropdown container — positioned below navber */
.eservices-mega {
    position: absolute;
    top: 100% !important;     /* directly below the nav tab */
    right: 0 !important;
    left: auto !important;
    width: 700px !important;
    padding: 0 !important;
    background: #ffffff !important;
    border: 1px solid #d0d0d0;
    border-top: 2px solid #00a862;
    box-shadow: 0 6px 18px rgba(0,0,0,0.13) !important;
    direction: rtl;
    z-index: 9999 !important;
}

/* Two-column flex container */
.es-mega-container {
    display: flex;
    width: 100%;
    direction: rtl;
    min-height: 350px;
}

/* ---- Right column: Main category list (42%) ---- */
.es-mega-right {
    width: 42%;
    background-color: #f5f5f5;
    border-left: 1px solid #dedede;
    flex-shrink: 0;
}

.es-main-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Each main category row — matches mobile item height */
.es-main-item {
    display: flex;
    align-items: center;
    justify-content: flex-start; /* RTL: flex-start = right side first */
    flex-direction: row;
    direction: rtl;
    padding: 0 10px 0 8px;
    height: 46px;
    cursor: pointer;
    border-bottom: 1px solid #e8e8e8;
    color: #333333;
    font-size: 13px;
    font-weight: bold;
    gap: 10px;
    white-space: nowrap;
    transition: background-color 0.15s, color 0.15s;
    box-sizing: border-box;
    outline: none;
    text-align: right;
}

.es-main-item:hover,
.es-main-item.active {
    background-color: #ffffff;
    color: #00a862;
    border-right: 3px solid #00a862;
    padding-right: 7px; /* 10 - 3 border = 7 */
}

/* Sprite icon in right panel: 40px, NO circle border, NO background */
.es-sprite-icon {
    display: inline-block !important;
    width: 40px !important;
    height: 40px !important;
    margin: 0 !important;
    background-color: transparent !important;
    border-radius: 0 !important;
    flex-shrink: 0;
}

/* Category text — fills remaining space */
.es-main-item .es-main-title {
    flex: 1;
    text-align: right;
}

/* Sprite icon in sub-panel: smaller 20px version */
.es-sub-sprite {
    display: inline-block !important;
    width: 20px !important;
    height: 20px !important;
    background-size: 280px auto !important; /* half of original to get proportional 20px icons */
    margin: 0 !important;
    background-color: transparent !important;
    border-radius: 0 !important;
    flex-shrink: 0;
    filter: invert(39%) sepia(85%) saturate(500%) hue-rotate(113deg) brightness(97%) contrast(101%);
}

/* ---- Left column: Sub-services panel (58%) ---- */
.es-mega-left {
    flex: 1;
    background-color: #ffffff;
    padding: 15px 18px;
    min-width: 0;
    direction: rtl;
}

.es-mega-panel {
    width: 100%;
}

.es-sub-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.es-sub-list li {
    border-bottom: 1px solid #f0f0f0;
}

.es-sub-list li:last-child {
    border-bottom: none;
}

/* Sub-item link — icon on right, text next to it (RTL) */
.es-sub-list a {
    display: flex;
    align-items: center;
    flex-direction: row;
    direction: rtl;
    text-align: right;
    padding: 10px 5px;
    color: #333333;
    text-decoration: none;
    font-size: 13px;
    font-weight: bold;
    gap: 8px;
    transition: color 0.15s;
}

.es-sub-list a .es-cat-img {
    width: 24px;
    height: 24px;
    object-fit: contain;
    flex-shrink: 0;
}

.es-sub-list a span {
    flex: 1;
    text-align: right;
}

.es-sub-list a:hover {
    color: #00a862;
}

/* No services placeholder */
.es-no-services {
    color: #999;
    padding: 10px 0;
    display: block;
    font-size: 12px;
}
</style>


<script>
if (typeof setVisibility !== 'function') {
    function setVisibility(id, visibility) {
        var el = document.getElementById(id);
        if (el) el.style.display = visibility;
    }
}

function switchEsPanel(index, element) {
    // Hide all service panels
    document.querySelectorAll('.es-mega-panel').forEach(function(panel) {
        panel.style.display = 'none';
    });
    
    // Show target service panel
    var target = document.getElementById('es-panel-' + index);
    if (target) {
        target.style.display = 'block';
    }
    
    // Update active class on categories
    document.querySelectorAll('.es-main-item').forEach(function(item) {
        item.classList.remove('active');
    });
    if (element) {
        element.classList.add('active');
    }
}
</script>
