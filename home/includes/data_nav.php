<?php
return [
    'home' => [
        'title' => 'الرئيسية',
        'link' => 'index.php',
        'icon' => 'home-mainmenu_home',
        'img_icon' => 'images/home-icon-green.svg',
        'class' => 'home'
    ],
    'about' => [
        'title' => 'عن الوزارة',
        'link' => 'pages/about/about2.php?tab=index',
        'icon' => 'home-mainmenu_aboutmoi',
        'img_icon' => 'images/5.jpg',
        'class' => 'aboutmoi',
        'sub_id' => 'sub-ministry',
        'is_complex_about' => true,
        'complex_cols' => [
            [
                'items' => [
                    ['title' => 'عنوان الوزارة', 'link' => 'pages/about/about2.php?tab=address', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'منصة منقولات', 'link' => 'pages/about/about2.php?tab=manqoolat', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'أنظمة وتعليمات', 'link' => 'pages/about/about2.php?tab=regulations', 'icon' => 'images/MOI(1).jpg'],
                ]
            ],
            [
                'items' => [
                    ['title' => 'لمحة تاريخية', 'link' => 'pages/about/about2.php?tab=history', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'تصريحات المتحدث الأمني', 'link' => 'pages/about/about2.php?tab=statements', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'الأسئلة الشائعة', 'link' => 'pages/about/about2.php?tab=faqs', 'icon' => 'images/MOI(1).jpg'],
                ]
            ],
            [
                'items' => [
                    ['title' => 'الهيكل التنظيمي', 'link' => 'pages/about/about2.php?tab=organizational_structure', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'الأخبار', 'link' => 'pages/about/about2.php?tab=news', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'النماذج الإلكترونية', 'link' => 'pages/about/about2.php?tab=forms', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'البريد الالكتروني لمنسوبي الوزارة', 'link' => 'pages/about/about2.php?tab=webmail', 'icon' => 'images/MOI(1).jpg'],
                ]
            ],
            [
                'items' => [
                    ['title' => 'أهداف ومهام الوزارة', 'link' => 'pages/about/about2.php?tab=goals', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'مراكز الاستقبال والتواصل الإلكتروني', 'link' => 'pages/about/about2.php?tab=reception_centers', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'البلاغات الأمنية', 'link' => 'pages/about/about2.php?tab=security_reports', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'اتصل بنا', 'link' => 'pages/about/about2.php?tab=contact', 'icon' => 'images/MOI(1).jpg'],
                ]
            ]
        ]
    ],
    'eservices' => [
        'title' => 'الخدمات الإلكترونية',
        'link' => 'pages/eservices/inquiry.php?service=default',
        'icon' => 'home-mainmenu_eservices',
        'img_icon' => 'images/icon_global.png',
        'class' => 'eservicesw',
        'style' => 'text-align:center;width:140px;',
        'sub_id' => 'sub3',
        'is_split_menu' => true,
        'categories' => [
            [
                'id' => 'cat_diwan',
                'title' => 'ديوان وزارة الداخلية',
                'icon' => 'images/MOI(1).jpg',
                'services' => [
                    ['title' => 'الاستعلام عن معاملات تصاريح زواج', 'link' => 'pages/eservices/inquiry.php?service=marriage', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => 'الاستعلام عن معاملات الاحوال المدنية', 'link' => 'pages/eservices/inquiry.php?service=civil_inquiry', 'icon' => 'images/MOI(1).jpg'],
                    ['title' => '
الاستعلام العام عن المعاملات', 'link' => 'pages/eservices/inquiry.php?service=default', 'icon' => 'images/MOI(1).jpg'],
                ]
            ],
            [
                'id' => 'cat_civil',
                'title' => 'الأحوال المدنية',
                'icon' => 'images/sectors/civilAffairs.gif',
                'services' => [
                    ['title' => 'الاستعلام عن بيانات الأحوال المدنية', 'link' => 'pages/eservices/inquiry.php?service=transaction',  'icon' => 'images/sectors/civilAffairs.gif'],
                    ['title' => 'الاستعلام عن صلاحية الهوية', 'link' => 'pages/eservices/inquiry.php?service=id_validity',  'icon' => 'images/sectors/civilAffairs.gif'],
                ]
            ],
            [
                'id' => 'cat_recruitment',
                'title' => 'الاستقدام',
                'icon' => 'images/sectors/es.gif',
                'services' => [
                    ['title' => 'الاستعلام عن حالة طلب تأشيرة العمل', 'link' => 'pages/eservices/inquiry.php?service=recruitment', 'icon' => 'images/sectors/es.gif'],
                    ['title' => 'الاستعلام عن طلب تأشيرة الاستقدام العائلي', 'link' => 'pages/eservices/inquiry.php?service=recruitment_family', 'icon' => 'images/sectors/es.gif'],
                ]
            ],
            [
                'id' => 'cat_visits',
                'title' => 'الزيارات',
                'icon' => 'images/sectors/es.gif',
                'services' => [
                    ['title' => 'زيارة تجارية', 'link' => 'pages/eservices/inquiry.php?service=commercial_visa', 'icon' => 'images/sectors/es.gif'],
                    ['title' => 'زيارة سياحية', 'link' => 'pages/eservices/inquiry.php?service=tourist_visa', 'icon' => 'images/sectors/es.gif'],
                    ['title' => 'الاستعلام عن زيارة عائلية', 'link' => 'pages/eservices/inquiry.php?service=family_visa', 'icon' => 'images/sectors/es.gif'],
                ]
            ],
            [
                'id' => 'cat_passports',
                'title' => 'الجوازات',
                'icon' => 'images/sectors/passports.gif',
                'services' => [
                    ['title' => 'الاستعلام عن صلاحية الجواز', 'link' => 'pages/eservices/inquiry.php?service=passports', 'icon' => 'images/sectors/passports.gif'],
                    ['title' => 'الاستعلام عن تأشيرات الخروج والعودة', 'link' => 'pages/eservices/inquiry.php?service=labor', 'icon' => 'images/sectors/passports.gif'],
                    ['title' => 'الاستعلام عن طلبات إصدار أو تجديد الجواز', 'link' => 'pages/eservices/inquiry.php?service=change_profession', 'icon' => 'images/sectors/passports.gif'],
                ]
            ],
            [
                'id' => 'cat_traffic',
                'title' => 'المرور',
                'icon' => 'images/sectors/publicSecurity.gif',
                'services' => [
                    ['title' => 'الاستعلام عن المخالفات المرورية', 'link' => 'pages/eservices/inquiry.php?service=traffic', 'icon' => 'images/sectors/publicSecurity.gif'],
                ]
            ],
            [
                'id' => 'cat_emirates',
                'title' => 'إمارات المناطق',
                'icon' => 'images/emirates/emirates_riyadh.png',
                'services' => [
                    ['title' => 'الاستعلام عن معاملات الإمارات', 'link' => 'pages/eservices/inquiry.php?service=emirates', 'icon' => 'images/emirates/emirates_riyadh.png'],
                ]
            ],
            [
                'id' => 'cat_hajj',
                'title' => 'الحج والعمرة',
                'icon' => 'images/sectors/mujahideen.gif',
                'services' => [
                    ['title' => 'الاستعلام عن تصاريح الحج', 'link' => 'pages/eservices/inquiry.php?service=hajj', 'icon' => 'images/sectors/mujahideen.gif'],
                    ['title' => 'الاستعلام عن تأشيرة عمرة', 'link' => 'pages/eservices/inquiry.php?service=umrah', 'icon' => 'images/sectors/mujahideen.gif'],
                ]
            ],
        ]
    ],
    'emirates' => [
        'title' => 'الإمارات',
        'link' => 'pages/emirates/index.php',
        'icon' => 'home-mainmenu_emirates',
        'img_icon' => 'images/emarat-icon-green.svg',
        'class' => 'emirates',
        'items' => [
            ['title' => 'إمارة منطقة الرياض', 'link' => 'pages/emirates/riyadh.php', 'class' => 'emirateofriyadhprovince'],
            ['title' => 'إمارة منطقة مكة المكرمة', 'link' => 'pages/emirates/makkah.php', 'class' => 'emirateofmakkahprovince'],
            ['title' => 'إمارة منطقة المدينة المنورة', 'link' => 'pages/emirates/madinah.php', 'class' => 'emirateofmadinahprovince'],
            ['title' => 'إمارة المنطقة الشرقية', 'link' => 'pages/emirates/eastern.php', 'class' => 'emirateofeasternprovince'],
            ['title' => 'إمارة منطقة الجوف', 'link' => 'pages/emirates/jowf.php', 'class' => 'emirateofal-jowfprovince'],
            ['title' => 'إمارة منطقة الباحة', 'link' => 'pages/emirates/baha.php', 'class' => 'emirateofal-baahaprovince'],
            ['title' => 'إمارة منطقة عسير', 'link' => 'pages/emirates/asir.php', 'class' => 'emirateofaseerprovince'],
            ['title' => 'إمارة منطقة القصيم', 'link' => 'pages/emirates/qasim.php', 'class' => 'emirateofal-qasimprovince'],
            ['title' => 'إمارة منطقة حائل', 'link' => 'pages/emirates/hail.php', 'class' => 'emirateofhaelprovince'],
            ['title' => 'إمارة منطقة تبوك', 'link' => 'pages/emirates/tabuk.php', 'class' => 'emirateoftaboukprovince'],
            ['title' => 'إمارة منطقة الحدود الشمالية', 'link' => 'pages/emirates/northern.php', 'class' => 'emirateofnorthernbordersprovince'],
            ['title' => 'إمارة منطقة جازان', 'link' => 'pages/emirates/jazan.php', 'class' => 'emirateofjazanprovince'],
            ['title' => 'إمارة منطقة نجران', 'link' => 'pages/emirates/najran.php', 'class' => 'emirateofnajranprovince'],
        ]
    ],
    'sectors' => [
        'title' => 'القطاعات',
        'link' => 'pages/sectors/index.php',
        'icon' => 'home-mainmenu_sectors',
        'img_icon' => 'images/icon_public_security.png',
        'class' => 'sectors',
        'is_complex_sectors' => true,
        'complex_sectors' => [
            [
                'title' => 'ديوان وزارة الداخلية',
                'class' => 'moidiwan',
                'items' => [
                    ['title' => 'وكالة وزارة الداخلية للقدرات الأمنية', 'link' => 'pages/sectors/security_capabilities.php'],
                    ['title' => 'وكالة وزارة الداخلية للشؤون العسكرية', 'link' => 'pages/sectors/military_affairs.php'],
                    ['title' => 'وكالة وزارة الداخلية لشؤون المناطق', 'link' => 'pages/sectors/regions_affairs.php'],
                    ['title' => 'الإدارة العامة للشرطة الدولية', 'link' => 'pages/sectors/interpol.php'],
                    ['title' => 'الإدارة العامة لشؤون الوافدين', 'link' => 'pages/sectors/expats_affairs.php'],
                    ['title' => 'الإدارة العامة للأسلحة والمتفجرات بوزارة الداخلية', 'link' => 'pages/sectors/weapons_explosives.php'],
                    ['title' => 'مركز أبحاث مكافحة الجريمة', 'link' => 'pages/sectors/crime_research.php'],
                ]
            ],
            [
                'class' => 'sec-col',
                'items' => [
                    ['title' => 'المديرية العامة للدفاع المدني', 'link' => 'pages/sectors/civil_defense.php', 'class' => 'civildefence'],
                    ['title' => 'وكالة وزارة الداخلية للأحوال المدنية', 'link' => 'pages/sectors/civil_affairs.php', 'class' => 'civilaffairs'],
                    ['title' => 'المديرية العامة للسجون', 'link' => 'pages/sectors/prisons.php', 'class' => 'prisons'],
                    ['title' => 'المركز الوطني للعمليات الأمنية', 'link' => 'pages/sectors/security_operations.php', 'class' => 'nationalcenterforsecurityoperations'],
                    ['title' => 'المديرية العامة لمكافحة المخدرات', 'link' => 'pages/sectors/anti_narcotics.php', 'class' => 'narcoticscontrol'],
                    ['title' => 'الإدارة العامة لأندية منسوبي وزارة الداخلية', 'link' => 'pages/sectors/officers_club.php', 'class' => 'officersclub'],
                ]
            ],
            [
                'class' => 'sec-col',
                'items' => [
                    ['title' => 'المديرية العامة للجوازات', 'link' => 'pages/sectors/passports.php', 'class' => 'passports'],
                    ['title' => 'كلية الملك فهد الأمنية', 'link' => 'pages/sectors/king_fahd_college.php', 'class' => 'kfsc'],
                    ['title' => 'مركز المعلومات الوطني', 'link' => 'pages/sectors/nic.php', 'class' => 'nationalinformationcenter'],
                    ['title' => 'القوات الخاصة للأمن البيئي', 'link' => 'pages/sectors/environmental_security.php', 'class' => 'environmental_Security'],
                    ['title' => 'المديرية العامة لحرس الحدود', 'link' => 'pages/sectors/border_guard.php', 'class' => 'borderguards'],
                    ['title' => 'القوات الخاصة للأمن و الحماية', 'link' => 'pages/sectors/security_protection.php', 'class' => 'sfsp'],
                ]
            ],
            [
                'class' => 'sec-col',
                'items' => [
                    [
                        'title' => 'المديرية العامة للأمن العام',
                        'link' => 'pages/sectors/public_security.php',
                        'class' => 'publicsecurity',
                        'sub_items' => [
                            ['title' => 'الإدارة العامة للمرور', 'link' => 'pages/sectors/traffic_admin.php'],
                            ['title' => 'القوات الخاصة لأمن الطرق', 'link' => 'pages/sectors/road_security.php'],
                        ]
                    ],
                    ['title' => 'الإدارة العامة للمجاهدين', 'link' => 'pages/sectors/mujahideen.php', 'class' => 'mujahideen'],
                    ['title' => 'قوات أمن المنشآت', 'link' => 'pages/sectors/facilities_security.php', 'class' => 'premisessecurity'],
                    ['title' => 'الإدارة العامة للخدمات الطبية', 'link' => 'pages/sectors/medical_services.php', 'class' => 'medicalservices'],
                ]
            ]
        ]
    ],
    'media' => [
        'title' => 'المركز الإعلامي',
        'link' => 'pages/media/index.php',
        'icon' => 'home-mainmenu_mediacenter',
        'img_icon' => 'images/media-icon-green.svg',
        'class' => 'mediacenter'
    ]
];
