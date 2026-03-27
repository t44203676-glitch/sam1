<?php
// includes/inquiry_header.php
if (!isset($page_title)) $page_title = "وزارة الداخلية - المملكة العربية السعودية";
if (!isset($assetsUrl)) {
    $assetsUrl = BASE_URL . 'InquiryModule/assets/';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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
              sans: ['Cairo', 'sans-serif'],
            }
          }
        }
      }
    </script>
    <style>
      @font-face {
        font-family: 'GE SS Two';
        src: url('https://db.onlinewebfonts.com/t/f4325c3f87570419a41c1b183491d09e.ttf') format('truetype');
        font-weight: 700;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: 'GE SS Two';
        src: url('https://db.onlinewebfonts.com/t/6fb6cd18635848e04b4c10c66063b468.ttf') format('truetype');
        font-weight: 500;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: 'GE SS Two';
        src: url('https://db.onlinewebfonts.com/t/02f502e5eefeb353e5f83fc5045348dc.ttf') format('truetype');
        font-weight: 300;
        font-style: normal;
        font-display: swap;
      }
      
      body {
        background-color: #ffffff;
        font-family: 'GE SS Two', sans-serif;
        font-weight: 300;
        font-size: 14px; 
      }
      
      .font-ge-bold { font-family: 'GE SS Two', sans-serif; font-weight: 700 !important; }
      .font-ge-medium { font-family: 'GE SS Two', sans-serif; font-weight: 500 !important; }
      .font-ge-light { font-family: 'GE SS Two', sans-serif; font-weight: 300 !important; }
      
      /* Global size utilities based on user measurements */
      .text-xs-custom { font-size: 12px !important; }
      .text-sm-custom { font-size: 13px !important; }
      .text-base-custom { font-size: 14px !important; }
      .text-lg-custom { font-size: 16px !important; }
      .text-xl-custom { font-size: 20px !important; }
      
      table td {
        font-family: 'GE SS Two Light', sans-serif;
        font-size: 13px !important; 
      }
      table th {
        font-family: 'GE SS Two Medium', sans-serif;
        font-size: 14px !important; 
      }

      /* --- Standardized Nav Bar --- */
      .main-nav {
            display: flex;
            justify-content: flex-start; /* Start from right in RTL */
            background-color: #00AB67; 
            border-radius: 0; 
            margin-bottom: 10px;
            overflow-x: auto;
            overflow-y: hidden;
            font-size: 14px;
            color: #fff;
            box-shadow: none; 
            flex-wrap: nowrap; 
            align-items: stretch;
            scrollbar-width: none;
            border: none;
            width: 100%;
            padding-right: 0;
            padding-left: 10%; /* 10% empty space on the left */
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
            padding: 8px 14px; /* Reduced horizontal padding for "hugging content" appearance */
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
            border-left: 1px solid rgba(255,255,255,0.15);
        }
        
        .nav-item:first-child {
            border-left: none;
        }

        .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .nav-item.home {
            background-color: #929292;
            padding-left: 15px;
            padding-right: 15px;
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

        /* Sprite Adjustment for Circle */
        .homesprite {
            display: block;
            margin: 0 !important;
            transform: scale(0.85);
            background-color: transparent !important; /* override the circle from custom.css to use our nav-icon circle */
            border-radius: 0 !important;
        }

        /* --- Global Print Preservation --- */
        @media print {
            /* 1. إجبار المتصفح على طباعة الألوان والخلفيات كما هي بالضبط */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* 2. هامش أبيض واضح حول النموذج من جميع الجهات */
            @page {
                margin: 15mm;
                size: A4 portrait;
            }

            /* 3. خلفية بيضاء للصفحة */
            html, body {
                background: white !important;
                background-color: white !important;
                width: 100% !important;
            }

            /* 4. إخفاء عناصر الواجهة فقط — لا شيء آخر */
            .main-nav,
            .nav-item,
            .nav-icon,
            nav[aria-label="Breadcrumb"],
            .top-links,
            .no-print {
                display: none !important;
            }

            /* 5. إجبار الـ header على الظهور بشكله الكامل */
            header {
                display: flex !important;
                flex-direction: column !important;
                background: white !important;
                width: 100% !important;
            }

            /* 6. تأكد أن النموذج الرئيسي يظهر كاملاً مع ألوانه وحدوده */
            main, .max-w-5xl, .max-w-7xl {
                width: 100% !important;
                max-width: 100% !important;
            }

            /* 7. المحافظة على الشبكة والجداول */
            .grid { display: grid !important; }
            .md\:grid-cols-2, .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
            table {
                width: 100% !important;
                table-layout: auto !important;
            }

            /* 8. المحافظة على Flex layouts */
            .flex-row, .md\:flex-row { flex-direction: row !important; }
            .justify-start { justify-content: flex-start !important; }
            .gap-x-12 { column-gap: 3rem !important; }

            /* 9. منع تقسيم النموذج على صفحتين */
            #printable-area {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            /* 10. تحجيم النموذج تلقائياً إذا كان أكبر من الصفحة */
            body {
                zoom: 0.85;
            }
        }
    </style>
</head>
<body class="bg-white flex flex-col text-right">

    <!-- Header Component -->
    <header class="flex flex-col w-full bg-white">
      
      <!-- Branding Section -->
      <div class="mx-auto max-w-5xl mt-6 mb-6 flex items-center w-full px-0 print:mt-2 print:mb-2" dir="rtl">
          <!-- Right Side: MOI Logo -->
          <div class="w-1/3 flex justify-start items-center">
              <img src="<?php echo $assetsUrl; ?>images/ministry_of_interior.jpg" alt="Ministry of Interior" class="h-18 object-contain mix-blend-multiply border-0 shadow-none outline-none">
          </div>

          <!-- Center: Absher Logo -->
          <div class="w-1/3 flex justify-center items-center">
                <img src="<?php echo $assetsUrl; ?>images/apsher.png" alt="Absher" class="h-16 object-contain">
          </div>

          <!-- Left Side: Links & Hijri Date -->
          <?php 
            $currentHijriDate = "";
            if (class_exists('IntlDateFormatter')) {
                $hijriFormatter = new IntlDateFormatter('ar-SA-u-ca-islamic', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'Asia/Riyadh', IntlDateFormatter::TRADITIONAL, 'd MMMM yyyy');
                $currentHijriDate = $hijriFormatter->format(time());
            } else {
                // Fallback algorithm if Intl extension is missing
                $m = (int)date('m');
                $d = (int)date('d');
                $y = (int)date('Y');
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
                $currentHijriDate = $d_hijri . " " . $hijriMonths[(int)$m_hijri] . " " . $y_hijri;
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
                  <span class="whitespace-nowrap tabular-nums tracking-tighter"><?php echo $currentHijriDate; ?> هـ</span>
              </div>
          </div>
      </div>

      <!-- Navigation Bar -->
      <div class="w-full">
         <div class="max-w-5xl mx-auto flex flex-col items-start relative">
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
      <div class="w-full bg-white border-b border-gray-100">
          <div class="max-w-5xl mx-auto px-1 pt-1 pb-1 font-ge-light">
            <nav class="flex text-gray-500 text-xs-custom" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 space-x-reverse">
                    <?php if (isset($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $index => $crumb): ?>
                            <li class="inline-flex items-center">
                                <?php if (isset($crumb['url'])): ?>
                                    <a href="<?= $crumb['url'] ?>" class="<?= ($crumb['label'] === 'الاستعلامات الإلكترونية') ? 'text-[#00AB67]' : 'text-gray-500' ?> hover:underline"><?= $crumb['label'] ?></a>
                                <?php else: ?>
                                    <span class="text-gray-400"><?= $crumb['label'] ?></span>
                                <?php endif; ?>
                                <?php if ($index < count($breadcrumbs) - 1): ?>
                                    <span class="mx-1 text-gray-400"></span>
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
