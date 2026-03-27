
<?php
/**
 * Breadcrumb Navigation System
 * Usage: Set $breadcrumb_items array in each page
 * Example: $breadcrumb_items = [
 *     ['title' => 'القطاعات', 'link' => 'sectors.php'],
 *     ['title' => 'ديوان وزارة الداخلية']
 * ];
 */

if (!isset($breadcrumb_items)) {
    // Default fallback based on active page
    $breadcrumb_items = [];
    if (isset($active_page)) {
        if ($active_page == 'home') {
            $breadcrumb_items[] = ['title' => 'الرئيسية'];
        }
        elseif ($active_page == 'sectors') {
            $breadcrumb_items[] = ['title' => 'القطاعات', 'link' => BASE_URL . 'pages/sectors/index.php'];
        }
        elseif ($active_page == 'about') {
            $breadcrumb_items[] = ['title' => 'عن الوزارة', 'link' => BASE_URL . 'pages/about/index.php'];
        }
        elseif ($active_page == 'eservices') {
            $breadcrumb_items[] = ['title' => 'الخدمات الإلكترونية', 'link' => BASE_URL . 'pages/eservices/index.php'];
        }
    }
}
?>

<style>
    .breadcrumb-container {
        background: #f5f5f5;
        border-bottom: 1px solid #ddd;
        padding: 10px 0;
        margin-bottom: 20px;
    }

    .breadcrumb {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        font-size: 13px;
        color: #666;
    }

    .breadcrumb a {
        color: #009b5a;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #007a45;
        text-decoration: underline;
    }

    .breadcrumb-separator {
        margin: 0 8px;
        color: #999;
    }

    .breadcrumb-current {
        color: #333;
        font-weight: bold;
    }
</style>

<div class="breadcrumb-container" id="breadcrumb-wrapper">
    <div class="breadcrumb" id="dynamic-breadcrumb">
        <?php
$count = count($breadcrumb_items);
foreach ($breadcrumb_items as $index => $item):
    $is_last = ($index === $count - 1);
?>
            <?php if ($is_last): ?>
                <span class="breadcrumb-current" id="bc-item-<?php echo $index; ?>"><?php echo htmlspecialchars($item['title']); ?></span>
            <?php
    else: ?>
                <a href="<?php echo htmlspecialchars($item['link'] ?? '#'); ?>" id="bc-link-<?php echo $index; ?>"><?php echo htmlspecialchars($item['title']); ?></a>
                <span class="breadcrumb-separator" id="bc-sep-<?php echo $index; ?>"> > </span>
            <?php
    endif; ?>
        <?php
endforeach; ?>
    </div>
</div>
