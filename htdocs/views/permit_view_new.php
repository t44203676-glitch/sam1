
<?php
// Ensure database connection is available
if (!isset($pdo)) {
    require_once __DIR__ . '/../includes/database.php';
    require_once __DIR__ . '/../includes/config.php';
}
if (!isset($pdo)) {
    die("Database connection failed.");
}

// Default empty data to prevent undefined variable warnings
$PERMIT_DATA = [
    'name' => '---',
    'idNumber' => '---',
    'sourceNumber' => '---',
    'issueDate' => '---',
    'sourceEntity' => '---'
];

$VISA_TABLE_DATA = [
    'serialNumber' => '---',
    'professionCategory' => '---', 
    'nationality' => '---',
    'type' => '---',
    'arrivalPlace' => '---',
    'visaStatus' => '---',
    'bankFileNumber' => '---',
    'bankDate' => '---'
];

// Get parameters from URL
$id_number = $_GET['id_number'] ?? null;
$issue_number = $_GET['issue_number'] ?? null; // using issue_number to match simple naming
$service_type = $_GET['service'] ?? 'marriage_permits'; // Default to marriage

$table_map = [
    'marriage_permits' => [
        'table' => 'marriage_permits', 
        'title' => 'تصريح زواج',
        'breadcrumb_title' => 'الاستفسار عن طلبات تصاريح الزواج'
    ],
    'family_visits' => [
        'table' => 'family_visits', 
        'title' => 'زيارة عائلية',
        'breadcrumb_title' => 'الاستعلام عن طلبات الزيارة العائلية'
    ],
    'business_visits' => [
        'table' => 'business_visits', 
        'title' => 'زيارة أعمال',
        'breadcrumb_title' => 'الاستعلام عن طلبات زيارة الأعمال'
    ],
    'tourism_visits' => [
        'table' => 'tourism_visits', 
        'title' => 'زيارة سياحية',
        'breadcrumb_title' => 'الاستعلام عن طلبات الزيارة السياحية'
    ],
    'recruitment_requests' => [
        'table' => 'recruitment_requests', 
        'title' => 'خدمة الاستقدام',
        'breadcrumb_title' => 'الاستعلام عن طلبات الاستقدام'
    ],
    'labor_requests' => [
        'table' => 'labor_requests', 
        'title' => 'مكتب العمل',
        'breadcrumb_title' => 'الاستعلام عن طلبات مكتب العمل'
    ],
    'civil_affairs_requests' => [
        'table' => 'civil_affairs_requests', 
        'title' => 'أحوال مدنية',
        'breadcrumb_title' => 'الاستعلام عن طلبات الأحوال المدنية'
    ],
    'profession_changes' => [
        'table' => 'profession_changes', 
        'title' => 'تغيير مهنة',
        'breadcrumb_title' => 'الاستعلام عن طلب تغيير مهنة'
    ],
    'followup_requests' => [
        'table' => 'followup_requests', 
        'title' => 'التعقيب',
        'breadcrumb_title' => 'الاستعلام عن طلبات التعقيب'
    ]
];

if (!array_key_exists($service_type, $table_map)) {
    // If not found, use whatever service was passed as the table directly
    $current_service = [
        'table' => $service_type,
        'title' => 'تصريح',
        'breadcrumb_title' => 'تفاصيل التصريح'
    ];
} else {
    $current_service = $table_map[$service_type];
}

$current_service = $table_map[$service_type];
$db_table = $current_service['table'];
$service_title = $current_service['title'];
$service_breadcrumb_title = $current_service['breadcrumb_title'];

if ($id_number && $issue_number) {
    try {
        // 1. Fetch Permit Data
        // Try searching by national_id/applicant_name columns which seem standard
        $stmt = $pdo->prepare("
            SELECT * FROM `$db_table` 
            WHERE national_id = ? AND export_number = ?
            LIMIT 1
        ");
        $stmt->execute([$id_number, $issue_number]);
        $permit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($permit) {
            $PERMIT_DATA = [
                'name' => $permit['applicant_name'] ?? '---',
                'idNumber' => $permit['national_id'] ?? '---',
                'sourceNumber' => $permit['export_number'] ?? '---',
                'issueDate' => $permit['hijri_date'] ?? $permit['created_at'] ?? '---',
                'sourceEntity' => $permit['issuing_authority'] ?? 'وزارة الداخلية-الي'
            ];

            // 2. Fetch Related Data (Partner/Visa Info)
            // Need to know the correct foreign key column. 
            // marriage_permits -> marriage_permit_id
            // recruitment_requests -> recruitment_request_id ??
            // profession_changes -> profession_change_id ??
            
            // Heuristic to find the foreign key column: look for [singular_table_name]_id
            // e.g. marriage_permits -> marriage_permit_id
            $foreign_key_col = substr($db_table, 0, -1) . '_id'; 
            if ($db_table == 'marriage_permits') $foreign_key_col = 'marriage_permit_id'; // Explicit fix if needed

            // Or just check if related_data is even used for others. Assuming yes for now.
            // If the column doesn't exist, this might fail, but we can try generic approach or specific switch.
            // Get the prefix for the table to find the related column
            $related_col_name = rtrim($db_table, 's') . '_id'; // Default: remove 's' and add '_id'
            // Special cases
            if ($db_table === 'family_visits') $related_col_name = 'family_visit_id';
            if ($db_table === 'business_visits') $related_col_name = 'business_visit_id';
            if ($db_table === 'tourism_visits') $related_col_name = 'tourism_visit_id';
            if ($db_table === 'labor_requests') $related_col_name = 'labor_request_id';
            if ($db_table === 'civil_affairs_requests') $related_col_name = 'civil_affairs_request_id';
            if ($db_table === 'followup_requests') $related_col_name = 'followup_request_id';

            // Check if column exists in related_data is tricky without schema check.
            // Let's assume standard naming based on previous patterns.
             
            $stmt_rel = $pdo->prepare("
                SELECT * FROM related_data 
                WHERE `$related_col_name` = ? 
                LIMIT 1
            ");
            // If this fails (column not found), catch block will handle it.
            $stmt_rel->execute([$permit['id']]);
            $related = $stmt_rel->fetch(PDO::FETCH_ASSOC);

            if ($related) {
                $VISA_TABLE_DATA = [
                    'serialNumber' => $permit['serial_number'] ?? '---',
                    'professionCategory' => $related['job_category'] ?? '---',
                    'nationality' => $related['nationality'] ?? '---',
                    'type' => $service_title, // Use dynamic title
                    'arrivalPlace' => $related['country'] ?? '---',
                    'visaStatus' => $permit['status'] ?? '---',
                    'bankFileNumber' => '---',
                    'bankDate' => '---'
                ];
            } else {
                 $VISA_TABLE_DATA['serialNumber'] = $permit['serial_number'] ?? '---';
                 $VISA_TABLE_DATA['visaStatus'] = $permit['status'] ?? '---';
                 $VISA_TABLE_DATA['type'] = $service_title;
            }
        }
    } catch (PDOException $e) {
        error_log("Error fetching permit data: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>وزارة الداخلية - المملكة العربية السعودية</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'moi-green': '#008764',
              'moi-green-dark': '#006c50',
              'moi-header': '#009b77',
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
        font-size: 16px;
      }
      * {
        font-weight: bold !important;
      }
      table td, table th {
        font-size: 1.1rem !important;
      }
    </style>
</head>
<body class="bg-white font-sans flex flex-col text-right font-bold">

    <!-- Header Component -->
    <header class="flex flex-col w-full bg-white font-sans">
      
      <!-- Top Bar Section -->
      <div class="container mx-auto max-w-7xl px-4 sm:px-6 relative h-10">
        
        <!-- Left Side: Date | Contact | English -->
        <div class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center gap-2 text-[11px] font-bold z-20 text-[#444444]">
            <span>8 ربيع أول 1447</span>
            <span class="text-gray-300 mx-1">|</span>
            <a href="#" class="hover:text-[#008764] transition-colors">الاتصال بنا</a>
            <span class="text-gray-300 mx-1">|</span>
            <a href="#" class="text-[#008764] hover:underline">English</a>
        </div>
      </div>

        <!-- Branding Section (Logo) -->
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 relative">
            <div class="flex justify-start items-end pb-1 mt-4">
                <!-- Logo Group: Emblem on Right -->
                <div class="flex items-center gap-4">
                     <!-- KSA Emblem -->
                     <div class="w-[220px] h-[110px] flex items-center justify-center select-none">
                        <img src="<?php echo BASE_URL; ?>public/images/ministry_of_interior.jpg?v=<?= time() ?>" alt="Ministry of Interior" class="w-full h-full object-contain">
                     </div>
                </div>

                 <!-- Center: Absher Logo (Elevated) -->
                <div class="absolute left-1/2 transform -translate-x-1/2 -top-6 z-10">
                     <img src="<?php echo BASE_URL; ?>public/images/apsher.png" alt="Absher" class="w-40 object-contain">
                </div>
            </div>
        </div>

      <!-- Navigation Bar -->
      <div class="w-full">
         <div class="container mx-auto max-w-7xl px-4 sm:px-6 bg-[#009b77] border-b-[5px] border-[#008764] shadow-sm">
            <ul class="flex flex-wrap items-stretch text-white text-[13px] font-medium">
                <!-- Home Item -->
                <li class="bg-[#9c9c9c] px-3 py-2 md:py-3 flex flex-col items-center justify-center cursor-pointer border-l border-white/20 min-w-[75px] hover:bg-[#888888] transition-colors">
                     <i data-lucide="home" class="mb-1.5 opacity-100 w-[22px] h-[22px]"></i>
                     <span class="text-[12px]">الرئيسية</span>
                </li>
                
                <?php
                $navItems = [
                    ['icon' => 'info', 'label' => 'عن الوزارة'],
                    ['icon' => 'globe', 'label' => 'الاستعلامات الإلكترونية'],
                    ['icon' => 'search', 'label' => 'الخدمات الإلكترونية'],
                    ['icon' => 'user', 'label' => 'المواطنين'],
                    ['icon' => 'users', 'label' => 'المقيمون'],
                    ['icon' => 'building-2', 'label' => 'الإمارات'],
                    ['icon' => 'share-2', 'label' => 'القطاعات'],
                    ['icon' => 'briefcase', 'label' => 'الأعمال'],
                    ['icon' => 'users-round', 'label' => 'التوظيف'],
                ];
                foreach ($navItems as $item): ?>
                <li class="px-2 md:px-3 py-2 md:py-3 flex flex-col items-center justify-center cursor-pointer hover:bg-black/10 transition-colors border-l border-white/20 min-w-[85px] text-center group">
                    <div class="mb-1.5 opacity-90 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-[22px] h-[22px]"></i>
                    </div>
                    <span class="text-[11px] whitespace-nowrap"><?= $item['label'] ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
         </div>
      </div>

      <!-- Breadcrumbs -->
      <div class="container mx-auto max-w-7xl px-4 sm:px-6 py-3 border-b border-gray-200 bg-white">
         <div class="flex flex-wrap gap-1 text-[11px] font-bold items-center">
            <a href="#" class="text-[#009b77] hover:underline">الاستعلامات الإلكترونية</a>
            
            <span class="px-1"> </span>
            
            <a href="#" class="text-[#333333] hover:underline">التصاريح</a>
            
            <span class="px-1"> </span>
            
            <span class="text-[#333333]"><?= $service_breadcrumb_title ?></span>
         </div>
      </div>

    </header>

    <!-- Main Content: PermitDetails Component -->
    <main class="w-full px-2 sm:px-6 container mx-auto max-w-7xl">
        <div class="max-w-6xl mx-auto my-8 p-1">
          
          <!-- Main Container -->
          <div class="bg-white border border-gray-300 shadow-sm rounded-t-lg overflow-hidden">
            
            <!-- Card Header -->
            <div class="bg-[#f5f5f5] px-4 py-3 border-b border-gray-200">
                <h3 class="text-black font-bold text-sm">
                    تفاصيل || <?= isset($service_title) ? htmlspecialchars($service_title) : 'تصريح زواج' ?>
                </h3>
            </div>

            <!-- Top Info Grid -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-xs text-gray-700 font-semibold border-b border-gray-200">
                <div class="flex justify-between md:justify-start gap-2">
                    <span class="text-black">رقم الهوية | السجل :</span>
                    <span><?= htmlspecialchars($PERMIT_DATA['idNumber']) ?></span>
                </div>
                <div class="flex justify-between md:justify-start gap-2">
                    <span class="text-black">رقم الصادر :</span>
                    <span><?= htmlspecialchars($PERMIT_DATA['sourceNumber']) ?></span>
                </div>
                <div class="flex justify-between md:justify-start gap-2">
                    <span class="text-black">تاريخ الإصدار :</span>
                    <span><?= htmlspecialchars($PERMIT_DATA['issueDate']) ?></span>
                </div>
                 <div class="flex justify-between md:justify-start gap-2">
                    <span class="text-black">الجهة المصدرة :</span>
                    <span><?= htmlspecialchars($PERMIT_DATA['sourceEntity']) ?></span>
                </div>
            </div>

            <!-- Data Table -->
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-center border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#f9f9f9] text-gray-600 font-bold border-b border-t border-gray-200">
                            <th class="py-3 px-2 border-l border-gray-100">الرقم التسلسلي</th>
                            <th class="py-3 px-2 border-l border-gray-100">فئة المهنة</th>
                            <th class="py-3 px-2 border-l border-gray-100">الجنسية</th>
                            <th class="py-3 px-2 border-l border-gray-100">النوع</th>
                            <th class="py-3 px-2 border-l border-gray-100">مكان القدوم</th>
                            <th class="py-3 px-2 border-l border-gray-100">حالة التأشيرة</th>
                            <th class="py-3 px-2 border-l border-gray-100">رقم الملف<br/>المرسل للبنك</th>
                            <th class="py-3 px-2">تاريخ الارسال<br/>للبنك للاسترداد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 text-gray-700 font-medium">
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['serialNumber']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['professionCategory']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['nationality']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['type']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['arrivalPlace']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['visaStatus']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['bankFileNumber']) ?></td>
                            <td class="py-4 px-2"><?= htmlspecialchars($VISA_TABLE_DATA['bankDate']) ?></td>
                        </tr>
                        <tr class="border-b border-gray-400">
                            <td colSpan="8" class="h-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-2 py-4 pb-6">
                <button class="bg-[#009b77] hover:bg-[#008764] text-white px-8 py-1.5 rounded text-sm font-bold shadow-sm transition-colors min-w-[100px]">
                    إنهاء
                </button>
                <button class="bg-[#009b77] hover:bg-[#008764] text-white px-8 py-1.5 rounded text-sm font-bold shadow-sm transition-colors min-w-[100px]">
                    طباعة
                </button>
            </div>

            <!-- Legend Table (Static) -->
            <div class="border-t border-gray-300">
                 <!-- Table Header -->
                 <div class="grid grid-cols-12 bg-[#e6e6e6] text-gray-700 text-xs font-bold py-2 px-4 border-b border-gray-300">
                     <div class="col-span-3 text-right pr-4">حالة التصريح</div>
                     <div class="col-span-9 text-center">التفاصيل</div>
                 </div>
                 
                 <!-- Row 1: Used -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-white">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         تم استخدامها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها إحدى الحالات التالية :تم استخدام هذا التصريح<br>
                         لم يتم الإستخدام وقد تم إنهاء إجراءات الدخول إلى المملكة من السفارة ويلزم مراجعة وزارة الخارجية لتصحيح وضعها أو إلغائها
                     </div>
                 </div>

                 <!-- Row 2: Approved -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-[#f4f8fa]">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         تمت الموافقة
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها أن التصاريح صالحة ويمكن الإستفادة منها وإذا رغبت في إلغائها يلزم مراجعة وزارة الداخلية لإلغائها
                     </div>
                 </div>

                 <!-- Row 3: Expired -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-white">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         انتهت صلاحيتها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها انتهاء مدة الصلاحية(سنتين)، وبعد مضي 100 يوم من انتهاء صلاحيتها ستتغير العبارة إلى (تم استعادة رسومها). وفي حالة عدم تغير العبارة يمكنك تسجيل طلب عبر (آمر) على الرقم (199099) أو الدخول على موقعهم على الإنترنت.
                     </div>
                 </div>

                 <!-- Row 4: Cancelled -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-[#f4f8fa]">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         تم إلغاؤها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها أنه تم إلغاؤها وينبغي الانتظار ومتابعتها حتى تتغير حالتها إلى (تم استعادة رسومها). وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 5: Cancellation Sent -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-white">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         تم إرسال طلب الإلغاء لوزارة الخارجية ولم يصل الرد
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها أنه تم إرسال طلب الإلغاء لوزارة الخارجية وما يزال تحت الإجراء، وينبغي الانتظار ومتابعتها على موقع وزارة الخارجية حتى تتغير حالتها إلى (تم استعادة رسومها)، وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 6: Refunded -->
                 <div class="grid grid-cols-12 text-xs border-b border-gray-200 bg-[#f4f8fa]">
                     <div class="col-span-3 py-3 pr-4 font-bold text-gray-800 flex items-start">
                         تم استعادة رسومها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-gray-200">
                         يقصد بها أنه تم استرجاع المبلغ إلى الحساب المسدد منه، وإذا رغبت في الحصول على (رقم هوية المسدد، واسم البنك، وتاريخ رجوع المبلغ) قم بالاتصال بمركز (آمر) على الرقم (199099) مع تجهيز رقم الهوية ورقم التأشيرة. أما في حالة سداد مبلغ التأشيرة نقدا فيتم مراجعة البنك مباشرة لاستلامه.
                     </div>
                 </div>
            </div>

          </div>
        </div>
    </main>

    <!-- Footer Component -->
    <footer class="mt-6 text-[#444444]">
      <div class="container mx-auto px-4 max-w-6xl bg-[#fcfcfc] pb-2 border-t border-gray-200">
        
        <!-- Top Footer Content -->
        <div class="flex flex-col md:flex-row items-center mb-2 gap-2 relative">
            
            <!-- Right: Logo Area -->
            <div class="flex items-center gap-3 order-1">
               <div class="w-77 h-23 flex items-center justify-center">
                  <img src="<?php echo BASE_URL; ?>public/images/ministry_of_interior.jpg?v=<?= time() ?>" alt="Ministry of Interior" class="w-full h-full object-contain mix-blend-multiply">
               </div>
            </div>

            <!-- Center: Links -->
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-1 text-sm font-bold text-[#555555] order-2 flex-grow">
                <a href="#" class="hover:text-[#008764] transition-colors">الأسئلة الشائعة</a>
                <a href="#" class="hover:text-[#008764] transition-colors">الأخبار</a>
                <a href="#" class="hover:text-[#008764] transition-colors">خريطة الموقع</a>
                <a href="#" class="hover:text-[#008764] transition-colors">شروط الإستخدام</a>
                <a href="#" class="hover:text-[#008764] transition-colors">سياسة الخصوصية</a>
            </div>
        </div>

        <!-- Middle: Disclaimer -->
        <div class="text-center text-[15px] text-[#777777] mb-2 px-4 leading-relaxed font-medium">
            الوصلات الخارجية الموجودة في البوابة هي لأغراض مرجعية، وزارة الداخلية ليست مسؤولة عن محتويات المواقع الخارجية.
            <br />
            جميع الحقوق محفوظة لوزارة الداخلية، المملكة العربية السعودية © 1445هـ - 2025م
                   <div class="flex items-center gap-3 order-2 md:order-1">
                <span class="text-[#444444] text-sm">تحميل تطبيق أبشر</span>
                <a href="#" class="text-[#008764] hover:opacity-80 transition-opacity">
                    <!-- Apple Icon -->
                    <svg viewBox="0 0 384 512" class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 46.9 101.4 82.7 100.8 24.3-.4 36.1-18.7 67.8-18.7 35.5 0 49.9 18.5 67.8 18.5 26 0 62.6-67.9 83.3-98.3 5.3-7.7 20-33.1 23-41-35.1-10.4-55.5-30.8-55.5-56.1l.1-1.1Zm-85.4-125c19-22.7 32-54.6 27-84.9-24.3 0-54.7 17.6-70.3 39-16.8 21.6-30.8 54-26.6 84.4 27.2 2 54.2-16.1 70-38.5Z"/></svg>
                </a>
                <a href="#" class="text-[#008764] hover:opacity-80 transition-opacity">
                    <!-- Android Icon -->
                    <svg viewBox="0 0 576 512" class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M420.55,301.93a24,24,0,1,1,24-24,24,24,0,0,1-24,24m-265.1,0a24,24,0,1,1,24-24,24,24,0,0,1-24,24m273.7-144.48,47.94-83a10,10,0,1,0-17.27-10h0l-48.54,84.07a301.25,301.25,0,0,0-246.56,0L116.18,64.45a10,10,0,1,0-17.27,10h0l47.94,83C64.53,202.22,8.24,285.55,0,384H576c-8.24-98.45-64.54-181.78-146.85-226.55"/></svg>
                </a>
            </div>
       
        </div>

        <div class="border-t-2 border-[#008764] w-full my-2"></div>

        <!-- Bottom Bar: NIC & Social -->
        <div class="relative flex items-center h-12 w-full mt-2">
            
            <!-- Center: NIC -->
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-2 text-gray-600 text-xs font-semibold whitespace-nowrap">
                 <div class="w-8 h-8 flex items-center justify-center">
                    <img src="<?php echo BASE_URL; ?>public/images/icon.jpeg" alt="NIC" class="w-full h-full object-contain rounded-full">
                 </div>
                 <span>تطوير وتشغيل مركز المعلومات الوطني</span>
            </div>

            <!-- Left: Social & Complaints -->
            <div class="w-full flex justify-end items-center gap-2">
                 <div class="flex gap-2">
                    <a href="#" class="w-7 h-7 bg-[#ff0000] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="#" class="w-7 h-7 bg-[#3b5998] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-7 h-7 bg-black text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-white"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932L18.901 1.153zM17.61 20.644h2.039L6.486 3.24H4.298l13.312 17.403z"/></svg>
                    </a>
                 </div>
            </div>
        </div>

        </div>
      </div>
    </footer>
    
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
