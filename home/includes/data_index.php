<?php
/**
 * Dynamic Content for Index Page
 * This file acts as a local data source (simulate database)
 */

return [
    'banners' => [
        ['src' => 'images/MOI.jpg', 'link' => 'https://www.absher.sa/awareness', 'target' => '_blank'],
        ['src' => 'images/MOI.jpg', 'link' => '#'],
        ['src' => 'images/report+banner.jpg', 'link' => '#'],
        ['src' => 'images/banner.png', 'link' => '#'],
        ['src' => 'images/88888.png', 'link' => '#'],
        ['src' => 'images/bannar.jpeg', 'link' => '#'],
    ],

    'service_boxes' => [
        ['title' => 'الأخبار', 'link' => 'pages/about/news.php', 'sprite' => 'sprite-news', 'blk' => 'blk2'],
        ['title' => 'الاستقدام', 'link' => 'pages/eservices/inquiry.php?service=recruitment', 'sprite' => 'sprite-photos', 'blk' => 'blk3', 'class' => 'box-2of4'],
        ['title' => 'العمالة', 'link' => 'pages/eservices/inquiry.php?service=labor', 'sprite' => 'sprite-videos', 'blk' => 'blk4'],
        ['title' => 'بيانات إحصائية', 'link' => 'pages/about/news.php', 'sprite' => 'sprite-statements', 'blk' => 'blk1', 'class' => 'box-4of4'],
    ],

    'mid_banners' => [
        ['src' => 'images/National_Center_Security_Operations_banner.png', 'link' => 'pages/sectors/index.php'],
        ['src' => 'images/1.png', 'link' => 'index.php'],
    ],

    'small_banners' => [
        ['src' => 'images/Hajj+A.jpeg', 'link' => '#', 'target' => '_blank'],
        ['src' => 'images/img2.png', 'link' => '#', 'target' => '_blank', 'class' => 'box-2of4'],
        ['src' => 'images/Caneras+banner11.png', 'link' => '#', 'class' => 'umrah1444'],
        ['src' => 'images/Makkah_Road_2025.jpeg', 'link' => '#', 'class' => 'box-4of4'],
    ],

    'sidebar_news' => [
        [
            'id' => 1,
            'title' => 'وكيل وزارة الداخلية يرأس اجتماع وكلاء إمارات المناطق الـ(60)',
            'date' => 'الخميس 10 شعبان 1447',
            'img' => 'images/MOI.jpg',
            'desc' => 'رأس معالي وكيل وزارة الداخلية الدكتور خالد بن محمد البتال، اليوم، اجتماع وكلاء إمارات المناطق الـ(60)، الذي عقد بمقر ديوان الوزارة بمدينة الرياض.'
        ],
        [
            'id' => 2,
            'title' => 'عقد الاجتماع الثالث لمجموعة عمل الأمن السعودي الهندي',
            'date' => 'الخميس 10 شعبان 1447',
            'img' => 'images/MOI.jpg',
            'desc' => 'رأس مدير عام الشؤون القانونية والتعاون الدولي بوزارة الداخلية الأستاذ أحمد بن سليمان العيسى الاجتماع الثالث لمجموعة عمل الأمن.'
        ],
        [
            'id' => 3,
            'title' => 'الحملات الميدانية المشتركة تضبط (18200) مخالف',
            'date' => 'السبت 5 شعبان 1447',
            'img' => 'images/5.jpg',
            'desc' => 'أسفرت الحملات الميدانية المشتركة لمتابعة وضبط مخالفي أنظمة الإقامة والعمل وأمن الحدود عن ضبط 18200 مخالف.'
        ],
    ],

    'statements' => [
        [
            'title' => 'المملكة ممثلة بوزارة الداخلية تُسهم في إحباط محاولة تهريب كوكايين',
            'date' => 'الأحد 4 جمادى الأول 1447',
            'img' => 'images/A277.jpg',
            'desc' => 'صرح المتحدث الأمني لوزارة الداخلية العميد طلال بن عبدالمحسن بن شلهوب، بأن المتابعة الأمنية الاستباقية أسفرت عن إحباط التهريب.'
        ]
    ],

    'events' => [
        [
            'title' => 'رئيس الإنتربول يقلد الأمين العام لمجلس وزراء الداخلية العرب وسام المنظمة',
            'date' => 'الأربعاء 21 جمادى الأول 1447',
            'img' => 'images/99.jpg'
        ],
        [
            'title' => 'وزارة الداخلية تشارك في ملتقى إعلام الحج',
            'date' => 'الاثنين 6 ذو الحجة 1446',
            'img' => 'images/MOI.jpg'
        ]
    ]
];
