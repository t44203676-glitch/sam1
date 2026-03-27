<?php
// includes/inquiry_header.php
if (!isset($page_title)) $page_title = "وزارة الداخلية - المملكة العربية السعودية";
if (!isset($assetsUrl)) {
    $assetsUrl = BASE_URL . 'inquiries/assets/';
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
    
    <!-- Lucide Icons & PDF Lib -->
    <script src="<?php echo $assetsUrl; ?>js/lucide.min.js"></script>
    <script src="<?php echo $assetsUrl; ?>js/html2pdf.bundle.min.js"></script>

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
      body {
        background-color: #ffffff;
        font-weight: bold !important;
        font-size: 14px; 
      }
      * {
        font-weight: bold !important;
      }
      table td, table th {
        font-size: 0.85rem !important; 
      }

      /* --- Standardized Nav Bar --- */
      .main-nav {
            display: flex;
            background-color: #009672;
            border-radius: 4px;
            margin-bottom: 25px;
            overflow-x: auto;
            overflow-y: hidden;
            font-size: 12px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            flex-wrap: nowrap; 
            align-items: stretch;
            scrollbar-width: none;
        }
        .main-nav::-webkit-scrollbar {
            display: none;
        }
        .nav-item {
            flex: 0 0 auto;
            width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-item:not(:last-child)::after {
            content: "";
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background: rgba(255,255,255,0.2);
        }
        .nav-item:hover {
            background-color: rgba(255,255,255,0.08);
        }
        .nav-item.home {
            background-color: #929292;
            width: 100px;
        }
        .nav-icon {
            width: 48px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
        }
        .nav-label {
            white-space: nowrap;
            font-weight: bold;
            opacity: 1;
        }

        /* Sprite Adjustment for Circle */
        .homesprite {
            display: block;
            margin: 0 !important;
            transform: scale(0.9);
        }
    </style>
</head>
<body class="bg-white font-sans flex flex-col text-right font-bold">

    <!-- Header Component -->
    <header class="flex flex-col w-full bg-white font-sans">
      
      <!-- Top Bar Section -->
      <div class="container mx-auto max-w-7xl px-4 sm:px-6 relative h-10">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center gap-2 text-[11px] font-bold z-20 text-[#444444]">
            <span><?= convertToHijri(date('Y-m-d')) ?></span>
            <span class="text-gray-300 mx-1">|</span>
            <a href="#" class="hover:text-[#009672] transition-colors">الاتصال بنا</a>
            <span class="text-gray-300 mx-1">|</span>
            <a href="#" class="text-[#009672] hover:underline">English</a>
        </div>
      </div>

      <!-- Branding Section (Logo) -->
      <div class="container mx-auto max-w-7xl px-4 sm:px-6 relative">
          <div class="flex justify-start items-end pb-1 mt-4">
              <div class="flex items-center gap-4">
                   <div class="w-[220px] h-[110px] flex items-center justify-center select-none">
                      <img src="<?php echo $assetsUrl; ?>images/ministry_of_interior.jpg" alt="Ministry of Interior" class="w-full h-full object-contain">
                   </div>
              </div>
              <div class="absolute left-1/2 transform -translate-x-1/2 -top-6 z-10">
                   <img src="<?php echo $assetsUrl; ?>images/apsher.png" alt="Absher" class="w-40 object-contain">
              </div>
          </div>
      </div>

      <!-- Navigation Bar -->
      <div class="w-full">
         <div class="container mx-auto max-w-6xl px-6 sm:px-4">
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
          <div class="container mx-auto max-w-5xl px-1 pt-4 pb-1">
             <div class="flex flex-wrap gap-0 text-[15px] font-bold items-center text-[#555555]">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index > 0): ?>
                        <span class="px-1 text-gray-300 font-normal">|</span>
                    <?php endif; ?>
                    <?php if (isset($crumb['url'])): ?>
                        <a href="<?= $crumb['url'] ?>" class="text-[#009672] hover:underline"><?= $crumb['label'] ?></a>
                    <?php else: ?>
                        <span class="text-[#333333]"><?= $crumb['label'] ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
             </div>
          </div>
      </div>
      <?php endif; ?>

    </header>
