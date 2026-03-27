<?php
// InquiryModule/core/inquiry_header.php
if (!isset($page_title))
    $page_title = "وزارة الداخلية - المملكة العربية السعودية";
if (!isset($containerWidth))
    $containerWidth = "1300px";


// مسار الأصول دائماً من داخل InquiryModule
$assetsUrl = rtrim(BASE_URL, '/') . '/';
if (strpos($assetsUrl, '/InquiryModule/') === false) {
    $assetsUrl .= 'InquiryModule/assets/';
} else {
    $assetsUrl .= 'assets/';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $page_title ?></title>
    <base href="<?php echo APP_URL; ?>">

    <!-- Project Legacy CSS for Navigation Icons -->
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsUrl; ?>css/custom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsUrl; ?>css/style_arabic.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsUrl; ?>css/font_arabic.css">

    <!-- Tailwind CSS -->
    <script src="<?php echo $assetsUrl; ?>js/tailwindcss.js"></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo $assetsUrl; ?>css/fonts.css">
    <link rel="stylesheet" href="<?php echo $assetsUrl; ?>css/all.min.css">

    <!-- Lucide Icons & PDF Lib (CDN) -->
    <script src="<?php echo $assetsUrl; ?>js/lucide.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'moi-green': '#009672',
                        'moi-green-dark': '#008764',
                        'moi-header': '#009672',
                        'moi-footer-bg': '#fcfcfc',
                        'moi-text': '#444444',
                    },
                    fontFamily: {
                        sans: ['"GE SS Two"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* استخدام الخطوط المحلية المتوفرة داخل مجلد المشروع (Light و Medium) لضمان التوافق المطلق */
        @font-face {
            font-family: 'GE SS Two';
            src: url('<?php echo $assetsUrl; ?>fonts/GE_SS_Two_Light.otf') format('opentype');
            font-weight: 300;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0000-002F, U+003A-10FFFF;
        }

        @font-face {
            font-family: 'GE SS Two';
            src: url('<?php echo $assetsUrl; ?>fonts/GE_SS_Two_Medium.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0000-002F, U+003A-10FFFF;
        }

        @font-face {
            font-family: 'GE SS Two';
            src: url('<?php echo $assetsUrl; ?>fonts/GE_SS_Two_Medium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0000-002F, U+003A-10FFFF;
        }

        @font-face {
            font-family: 'GE SS Two';
            src: url('<?php echo $assetsUrl; ?>fonts/GE_SS_Two_Medium.otf') format('opentype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
            unicode-range: U+0000-002F, U+003A-10FFFF;
        }

        html,
        body {
            background-color: #ffffff !important;
            margin: 0;
            padding: 0;
            font-family: 'GE SS Two';
            font-weight: 300;
            font-size: 14px;
        }

        .font-ge-bold {
            font-family: 'GE SS Two';
            font-weight: 700 !important;
        }

        .font-ge-medium {
            font-family: 'GE SS Two';
            font-weight: 500 !important;
        }

        .font-ge-light {
            font-family: 'GE SS Two';
            font-weight: 300 !important;
        }

        .text-xs-custom {
            font-size: 12px !important;
        }

        .text-sm-custom {
            font-size: 13px !important;
        }

        .text-base-custom {
            font-size: 14px !important;
        }

        .text-lg-custom {
            font-size: 16px !important;
        }

        .text-xl-custom {
            font-size: 20px !important;
        }

        .font-preload {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            z-index: -1;
        }

        table td {
            font-family: 'GE SS Two';
            font-weight: 300;
            font-size: 13px !important;
        }

        table th {
            font-family: 'GE SS Two';
            font-weight: 500;
            font-size: 14px !important;
        }

        /* --- CRITICAL FULL-PAGE PRINT CSS --- */
        @media print {

            /* 1. إعداد الصفحة للطباعة */
            @page {
                size: A4 portrait;
                margin: 8mm;
            }

            /* 2. إجبار المتصفح على عرض كل شيء واستغلال كامل الصفحة */
            html,
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                zoom: 83%;
                /* مقياس يضمن صفحة واحدة مع الحفاظ على وضوح الخط وكافة البيانات */
            }

            /* 3. الخطوط والألوان العامة */
            * {
                font-family: 'GE SS Two', sans-serif !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* 4. إخفاء الأزرار فقط (التصميم الأصلي يظهر الهيدر والفوتر والمنيو) */
            .no-print-btn,
            #actions-bar,
            button,
            #btn-print,
            .btn-finish,
            .no-print-print {
                display: none !important;
                visibility: hidden !important;
            }

            /* 5. إظهار كافة الأقسام الرئيسية والحفاظ على مرونتها */
            header,
            nav,
            main,
            footer,
            .main-nav,
            .top-links,
            .breadcrumb {
                display: flex !important;
                visibility: visible !important;
                width: 100% !important;
            }

            header,
            main,
            footer {
                flex-direction: column !important;
            }

            main {
                margin-bottom: 20px !important;
                /* مسافة بين المحتوى والفوتر */
            }

            /* 6. القائمة الخضراء (الأساسية) */
            .main-nav {
                background-color: #00AB67 !important;
                flex-direction: row !important;
            }

            .nav-item {
                display: flex !important;
                flex-direction: column !important;
                color: white !important;
                border-left: 1px solid rgba(255, 255, 255, 0.2) !important;
            }

            /* 7. توسيع الحاويات والجداول */
            .container,
            #pdf-capture-wrapper,
            #printable-area,
            .w-full,
            [class*="max-w-"] {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            /* تحسين محاذاة الحقول والبيانات لتكون متقاربة ومنظمة (زي الأساسي) */
            .grid .flex {
                justify-content: flex-start !important;
                gap: 8px !important;
                margin-bottom: 4px !important;
            }

            /* منع تقسيم الفوتر أو البطاقة بين صفحتين */
            #printable-area,
            footer,
            .bg-white.border-2 {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }


            /* إجبار الفوتر على النزول لأسفل الصفحة (التوقيع) */
            #pdf-capture-wrapper {
                display: flex !important;
                flex-direction: column !important;
                min-height: 315mm !important;
                /* طول متناسق مع الحفاظ على مسافة بيضاء في النهاية */
                padding-bottom: 30mm !important;
                /* المسافة البيضاء المطلوبة في نهاية الملف */
            }



            main {
                flex-grow: 1 !important;
            }

            footer {
                margin-top: auto !important;
                padding-bottom: 30px !important;
            }

            /* ضبط الفوتر ليكون على اليمين في الطباعة */
            footer .flex-col.items-center,
            /* الهيدر الأصلي للفوتر */
            footer div[class*="items-center"].relative {
                /* حاوية البيانات الرئيسية */
                align-items: flex-start !important;
                /* محاذاة لليمين في RTL */
                padding-right: 20px !important;
                display: flex !important;
            }



            table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: auto !important;
            }


            img,
            svg,
            i {
                display: inline-block !important;
                opacity: 1 !important;
            }



        }

        /* --- Standardized Nav Bar (Screen) --- */

        .main-nav {
            display: flex;
            justify-content: flex-start;
            background-color: #00AB67;
            border-radius: 0;
            margin-bottom: 10px;
            overflow-x: auto;
            overflow-y: hidden;
            font-size: 14px;
            color: #fff;
            flex-wrap: nowrap;
            align-items: stretch;
            width: 100%;
            border: none;
        }

        .main-nav::-webkit-scrollbar {
            display: none;
        }

        .nav-item {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            cursor: pointer;
            transition: background 0.2s;
            border-left: 1px solid rgba(255, 255, 255, 0.15);
        }

        .nav-item:first-child {
            border-left: none;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item.home {
            background-color: #929292;
            padding: 8px 15px;
        }

        .nav-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
        }

        .nav-label {
            white-space: nowrap;
            font-size: 13px;
        }

        .homesprite {
            display: block;
            margin: 0 !important;
            transform: scale(0.85);
            background-color: transparent !important;
        }


        /* --- InquiryModule: No mobile responsive changes --- */
        /* Desktop layout is preserved on all screen sizes as requested */
    </style>
</head>

<body class="bg-white flex flex-col text-right">
    <!-- Hidden element to ensure fonts are loaded for canvas capture -->
    <div class="font-preload">
        <span style="font-family: 'GE SS Two'; font-weight: 300;">L</span>
        <span style="font-family: 'GE SS Two'; font-weight: 500;">M</span>
        <span style="font-family: 'GE SS Two'; font-weight: 700;">B</span>
    </div>
    <div id="pdf-capture-wrapper" class="flex flex-col min-h-screen bg-white" dir="rtl">

        <!-- Header Component -->
        <header class="flex flex-col w-full bg-white">

            <!-- Branding Section -->
            <div class="w-full max-w-[<?= $containerWidth ?>] mx-auto mt-6 mb-6 flex items-center px-4 print:mt-2 print:mb-2 print:px-0"
                dir="rtl">
                <!-- Right Side: MOI Logo -->
                <div class="w-1/3 flex justify-start items-center">
                    <img src="<?php echo $assetsUrl; ?>images/ministry_of_interior.jpg" alt="Ministry of Interior"
                        class="h-18 object-contain mix-blend-multiply border-0 shadow-none outline-none">
                </div>

                <!-- Center: Absher Logo (تم تكبير الشعار وإضافة خيارات تحكم) -->
                <div class="w-1/3 flex justify-center items-center">
                    <img src="<?php echo $assetsUrl; ?>images/apsher.png" alt="Absher"
                        class="h-28 object-contain mt-2 transition-transform duration-300">
                    <!-- 
                     أكواد التحكم:
                     h-28 : الحجم (يمكنك تغييره لـ h-32 للتكبير أكثر)
                     mt-2 : النزول للأسفل (يمكنك جرب mt-4 أو mt-6 للنزول أكثر)
                -->
                </div>

                <!-- Left Side: Links & Hijri Date -->
                <?php
                $currentHijriDate = "";
                if (class_exists('IntlDateFormatter')) {
                    // ar-SA للحصول على أسماء الشهور بالعربي، ثم تحويل الأرقام لإنجليزي
                    $hijriFormatter = new IntlDateFormatter('ar-SA-u-ca-islamic', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'Asia/Riyadh', IntlDateFormatter::TRADITIONAL, 'd MMMM yyyy');
                    $currentHijriDate = $hijriFormatter->format(time());
                    // تحويل الأرقام العربية إلى إنجليزية
                    if (function_exists('toWesternDigits')) {
                        $currentHijriDate = toWesternDigits($currentHijriDate);
                    } else {
                        $currentHijriDate = preg_replace_callback('/[\x{0660}-\x{0669}]/u', function ($m) {
                            return (string) (mb_ord($m[0], 'UTF-8') - 0x0660);
                        }, $currentHijriDate);
                    }
                } else {
                    // Fallback algorithm if Intl extension is missing
                    $m = (int) date('m');
                    $d = (int) date('d');
                    $y = (int) date('Y');
                    if (($y > 1582) || (($y == 1582) && ($m > 10)) || (($y == 1582) && ($m == 10) && ($d > 14))) {
                        $jd = floor((1461 * ($y + 4800 + floor(($m - 14) / 12))) / 4) + floor((367 * ($m - 2 - 12 * (floor(($m - 14) / 12)))) / 12) - floor((3 * (floor(($y + 4900 + floor(($m - 14) / 12)) / 100))) / 4) + $d - 32075;
                    } else {
                        $jd = 367 * $y - floor((7 * ($y + 5001 + floor(($m - 9) / 7))) / 4) + floor((275 * $m) / 9) + $d + 1729777;
                    }
                    $l = $jd - 1948440 + 10632;
                    $n = floor(($l - 1) / 10631);
                    $l = $l - 10631 * $n + 354;
                    $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
                    $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
                    $m_hijri = floor((24 * $l) / 709);
                    $d_hijri = $l - floor((709 * $m_hijri) / 24);
                    $y_hijri = 30 * $n + $j - 30;
                    $y_hijri = floor((($jd - 1948439.5) / 354.36707) + 1);
                    $hijriMonths = ["", "محرم", "صفر", "ربيع الأول", "ربيع الآخر", "جمادى الأولى", "جمادى الآخرة", "رجب", "شعبان", "رمضان", "شوال", "ذو القعدة", "ذو الحجة"];
                    $currentHijriDate = $d_hijri . " " . $hijriMonths[(int) $m_hijri] . " " . $y_hijri;
                }
                ?>
                <div class="w-1/3 flex justify-end items-center">
                    <div class="flex flex-row items-center gap-2 text-[11px] font-ge-bold text-[#059669]">
                        <div class="top-links flex items-center gap-2">
                            <a href="#" class="hover:underline uppercase transition-all duration-300">ENGLISH</a>
                            <span class="text-gray-300">|</span>
                            <a href="#" class="hover:underline transition-all duration-300">الاتصال بنا</a>
                            <span class="text-gray-300">|</span>
                        </div>
                        <span class="whitespace-nowrap tabular-nums tracking-tighter"><?php echo $currentHijriDate; ?>
                            هـ</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Bar -->
            <div class="w-full">
                <div class="w-full max-w-[<?= $containerWidth ?>] mx-auto flex flex-col items-start relative px-4">
                    <nav class="main-nav">
                        <div class="nav-item home">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_home"></i></div>
                            <div class="nav-label">الرئيسية</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_aboutmoi"></i></div>
                            <div class="nav-label">عن الوزارة</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_publiceservices"></i></div>
                            <div class="nav-label">الاستعلامات الإلكترونية</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_eservices"></i></div>
                            <div class="nav-label">الخدمات الإلكترونية</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_nationals"></i></div>
                            <div class="nav-label">المواطنون</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_expats"></i></div>
                            <div class="nav-label">المقيمون</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_emirates"></i></div>
                            <div class="nav-label">الإمارات</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_sectors"></i></div>
                            <div class="nav-label">القطاعات</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_business"></i></div>
                            <div class="nav-label">الأعمال</div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-icon"><i class="homesprite home-mainmenu_employment"></i></div>
                            <div class="nav-label">التوظيف</div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Breadcrumbs -->
            <?php if (isset($breadcrumbs)): ?>
                <div class="w-full bg-white relative z-30">
                    <div class="w-full max-w-[<?= $containerWidth ?>] mx-auto px-4 py-3 font-ge-medium">
                        <nav class="flex text-gray-500 text-xs-custom" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 space-x-reverse">
                                <?php if (isset($breadcrumbs)): ?>
                                    <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                        <li class="inline-flex items-center">
                                            <?php if (isset($crumb['url'])): ?>
                                                <a href="<?= $crumb['url'] ?>"
                                                    class="<?= ($crumb['label'] === 'الاستعلامات الإلكترونية') ? 'text-[#00AB67]' : 'text-gray-500' ?> hover:underline"><?= $crumb['label'] ?></a>
                                            <?php else: ?>
                                                <span class="text-gray-400"><?= $crumb['label'] ?></span>
                                            <?php endif; ?>
                                            <?php if ($index < count($breadcrumbs) - 1): ?>
                                                <span class="mx-2 text-gray-300 text-[10px]"><i class="fas fa-chevron-left"></i></span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>

        </header>
        <?php // End of includes/inquiry_header.php ?>