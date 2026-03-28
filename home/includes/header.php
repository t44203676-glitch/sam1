<?php

include_once __DIR__ . '/config.php';

include_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="ar">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">

	<!-- Resource Hints -->
	<link rel="preconnect" href="<?php echo BASE_URL; ?>">
	<link rel="dns-prefetch" href="<?php echo BASE_URL; ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

	<?php
$full_title = isset($page_title)
	? $page_title . " - " . SITE_NAME
	: SITE_NAME;

$meta_description = isset($site_description)
	? $site_description
	: SITE_DESCRIPTION;

$current_url = htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
	. "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
?>

	<!-- SEO & Discovery -->
	<meta name="description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="keywords" content="<?php echo defined('SITE_KEYWORDS') ? SITE_KEYWORDS : ''; ?>">
	<meta name="author" content="<?php echo SITE_NAME; ?>">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?php echo $current_url; ?>">

	<!-- Open Graph (Facebook & Social Media) -->
	<meta property="og:title" content="<?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $current_url; ?>">
	<meta property="og:image" content="<?php echo BASE_URL; ?>images/favicon.png">
	<meta property="og:site_name" content="<?php echo SITE_NAME; ?>">

	<!-- Twitter Cards -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="twitter:description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="twitter:image" content="<?php echo BASE_URL; ?>images/favicon.png">

	<script type="text/javascript">
		var BASE_URL = '<?php echo BASE_URL; ?>';
	</script>

<!-- Favicon & Mobile Icons -->
<?php

// استخدام مسار يبدأ من الجذر لضمان الأولوية القصوى وتجاوز أيقونة XAMPP
$v = "1.1";
$rel_fav = rtrim($relative_path, '/') . "/images/favicon.png?v=" . $v;

?>
<link rel="icon" type="image/png" href="<?php echo $rel_fav; ?>">
<link rel="shortcut icon" href="<?php echo $rel_fav; ?>" type="image/x-icon">
<link rel="apple-touch-icon" href="<?php echo $rel_fav; ?>">

<!-- للهواتف أندرويد -->
<meta name="theme-color" content="#ffffff">

	<?php $v = "1.1"; // Asset Versioning for Cache Busting ?>
	<!-- Core CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/jquery.dataTables.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/dataTables.responsive.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/slider.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/fontello.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/css/all.min.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/menu-custom.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/tabs.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/base.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/custom.min.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/eservices_style.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/jquery-ui.min.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/style_arabic.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/font_arabic.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/global-extras.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/auto-rotate.css?v=<?php echo $v; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/mobile_menu.css?v=<?php echo $v; ?>">

	<!-- Core JS -->
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.min.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/browserValidator.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/responsive-switch.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.sliderTabs.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.slides.min.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/slidesjs.initialize.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/custom.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/themeBuilder.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/calendar_moi_dateConverter.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/app-setup.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/url-ar.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/auto-rotate.js?v=<?php echo $v; ?>"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/mobile_menu.js?v=<?php echo $v; ?>"></script>

	<style id="antiClickjack">body {display:none !important;}</style>
	<script type="text/javascript">
		if (self === top) {
			var antiClickjack = document.getElementById("antiClickjack");
			if (antiClickjack) antiClickjack.parentNode.removeChild(antiClickjack);
		} else {
			alert("Content not allowed in iframe");
		}
	</script>

</head>

<body class="rtl <?php echo isset($body_class) ? $body_class : ''; ?>">
<div class="wrapper">
<div class="header-moi" style="background: #fff;">
    <!-- Top Utility Bar (Left Aligned) -->
    <div class="top-utility-bar mobile-top-bar" id="topUtilityBar" style="padding: 5px 0;">
        <div class="row">
            <div class="header-utility-links mobile-links-container" id="headerUtilityLinks" style="font-size: 7px; color: #444; direction: ltr; display: flex; gap: 5px;">
                <a href="#" style="color: #008764; text-decoration: none;">English</a>
                <span style="font-weight: normal; color: #ccc;">|</span>
                <a href="#" style="color: #444; text-decoration: none;">الاتصال بنا</a>
                <span style="font-weight: normal; color: #ccc;">|</span>
                <a href="#" style="color: #444; text-decoration: none;">منسوبو الوزارة</a>
                <span style="font-weight: normal; color: #ccc;">|</span>
                <span id="header-hijri-date" style="direction: rtl; display: inline-block; white-space: nowrap;">
                    <?php
$hijri = convertToHijri(date('Y-m-d'));
$parts = explode('/', $hijri);
if (count($parts) === 3) {
	$d = (int)$parts[0];
	$m = (int)$parts[1];
	$y = (int)$parts[2];
	$months = [1 => "محرم", 2 => "صفر", 3 => "ربيع الأول", 4 => "ربيع الآخر", 5 => "جمادى الأولى", 6 => "جمادى الثانية", 7 => "رجب", 8 => "شعبان", 9 => "رمضان", 10 => "شوال", 11 => "ذو القعدة", 12 => "ذو الحجة"];
	echo "$d " . ($months[$m] ?? $m) . " $y";
}
else {
	echo $hijri;
}
?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Header Area (Logo on Right) -->
    <div class="row" style="padding-top: 5px; padding-bottom: 20px; display: flex; justify-content: flex-start;">
        <div class="moi-logo-area" style="margin-right: 0; margin-left: auto;">
            <a href="<?php echo BASE_URL; ?>index.php">
                <img src="<?php echo BASE_URL; ?>images/moi_logo_rtl.png" alt="وزارة الداخلية" class="header-logo">
            </a>
        </div>
    </div>
</div>
