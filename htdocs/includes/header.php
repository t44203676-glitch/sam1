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

	<title><?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?></title>

	<meta name="description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
	<meta name="author" content="<?php echo SITE_NAME; ?>">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?php echo $current_url; ?>">

	<!-- Open Graph (Facebook & Social Media) -->
	<meta property="og:title" content="<?php echo htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:description" content="<?php echo htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $current_url; ?>">
	<meta property="og:site_name" content="<?php echo SITE_NAME; ?>">

	<script type="text/javascript">
		var BASE_URL = '<?php echo BASE_URL; ?>';
	</script>

<!-- Favicon & Mobile Icons -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>images/favicon.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URL; ?>images/favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>images/favicon.png">
<link rel="icon" sizes="192x192" href="<?php echo BASE_URL; ?>images/favicon.png">
<link rel="icon" sizes="512x512" href="<?php echo BASE_URL; ?>images/favicon.png">

<!-- للهواتف أندرويد -->
<meta name="theme-color" content="#ffffff">

	<!-- Core CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/dataTables.responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/slider.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/fontello.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>public/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/menu-custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/tabs.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/base.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/eservices_style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/style_arabic.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/font_arabic.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/global-extras.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/auto-rotate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/mobile_menu.css">

	<!-- Core JS -->
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/browserValidator.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/responsive-switch.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.sliderTabs.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.slides.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/slidesjs.initialize.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/custom.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/themeBuilder.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/calendar_moi_dateConverter.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/app-setup.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/url-ar.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/auto-rotate.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>js/mobile_menu.js"></script>

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
    <div class="top-utility-bar mobile-top-bar" id="topUtilityBar" style="border-bottom: 1px solid #f0f0f0; padding: 5px 0;">
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
                        $d = (int)$parts[0]; $m = (int)$parts[1]; $y = (int)$parts[2];
                        $months = [1=>"محرم", 2=>"صفر", 3=>"ربيع الأول", 4=>"ربيع الآخر", 5=>"جمادى الأولى", 6=>"جمادى الثانية", 7=>"رجب", 8=>"شعبان", 9=>"رمضان", 10=>"شوال", 11=>"ذو القعدة", 12=>"ذو الحجة"];
                        echo "$d " . ($months[$m] ?? $m) . " $y";
                    } else {
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
                <img src="<?php echo BASE_URL; ?>images/ministry_of_interior.jpg" alt="وزارة الداخلية" class="header-logo">
            </a>
        </div>
    </div>
</div>
