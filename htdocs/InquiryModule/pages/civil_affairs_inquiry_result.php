<?php
require_once __DIR__ . '/../core/bootstrap.php';


// Ensure $request is available from session if not provided by caller
if (!isset($request) || !is_array($request)) {
    if (isset($_SESSION['inquiry_result']['data'])) {
        $request = $_SESSION['inquiry_result']['data'];
    }
    else {
        header("Location: " . BASE_URL . "?error=no_data");
        exit();
    }
}

// Ensure empty variables in request default to '---'
foreach ($request as $key => $value) {
    if (is_array($value))
        continue;
    if ($value === null || trim((string)$value) === '' || $value === ' ') {
        $request[$key] = '---';
    }
}

$assetsUrl = getInquiryAsset('');

// Mapping to standardized DB columns
$fullName = $request['applicant_name'] ?? '---';
$nationalId = $request['national_id'] ?? '---';
$exportNumber = $request['export_number'] ?? '---';
$rawIssueDate = $request['issue_date'] ?? $request['created_at'] ?? date('Y-m-d');
$hijriDate = convertToHijri($rawIssueDate);
$phone = $request['phone'] ?? '---';
$transactionNumber = $request['transaction_number'] ?? '---';
$nationality = $request['nationality'] ?? '---';
$issuingAuthority = $request['issuing_authority'] ?? 'وزارة الداخلية - الأحوال المدنية';

// Build photo path
$rawPhotoPath = $request['profile_photo_path'] ?? '';
$fallbackImg = getInquiryAsset('images/default-avatar.png');
$photoPath = getProfilePhotoUrl($rawPhotoPath, $fallbackImg);

$related_persons = $request['related_data'] ?? $request['related_items'] ?? $request['related_partners'] ?? [];

// دالة تحويل الأرقام إلى عربية
if (!function_exists('convertToArabicNumerals')) {
    function convertToArabicNumerals($number)
    {
        if ($number === null || $number === '---' || trim((string)$number) === '') {
            return '---';
        }
        $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str_replace($western, $arabic, $number);
    }
}

$page_title = "وزارة الداخلية - الاستعلام عن معاملات الأحوال المدنية";
$breadcrumbs = [
    ['label' => 'الاستعلامات الإلكترونية', 'url' => 'index.php?page=inquiry'],
    ['label' => 'الأحوال المدنية', 'url' => '#'],
    ['label' => 'الاستعلام عن معاملات التجنيس']
];
?>

<style>
    .page-wrap {
        width: 100%;
        max-width: 580px;
        margin: 0 auto;
        background: #fff;
        padding: 28px 14px 22px;
    }

    .page-certificate {
        background: #fff;
        border: 1px solid #8f8f8f;
        min-height: 500px;
        padding: 18px 16px 24px;
        position: relative;
    }

    /* الهيدر */
    .top-section {
        direction: ltr;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        min-height: 170px;
    }

    .profile-box {
        width: 100px;
        flex: 0 0 100px;
        padding-top: 10px;
    }

    .profile-box img {
        width: 100px;
        height: 125px;
        object-fit: cover;
        border: 1px solid #ccc;
        display: block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .info-box {
        direction: rtl;
        flex: 1;
        text-align: right;
        padding-top: 5px;
    }

    .info-box .title-line {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .logo-row {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 12px;
    }

    .logo-row img {
        width: 120px;
        height: auto;
        display: block;
    }

    .meta-cert {
        font-size: 9px;
        line-height: 1.7;
    }

    /* أدوات الجدول */
    .controls-table {
        margin-top: 26px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        font-size: 11px;
        direction: rtl;
    }

    .search-box-cert {
        display: flex;
        align-items: center;
        gap: 6px;
        direction: rtl;
    }

    .search-box-cert input {
        width: 140px;
        height: 24px;
        border: 2px solid #57c9a7;
        border-radius: 4px;
        background: #fff;
        outline: none;
        padding: 0 5px;
    }

    /* الجدول */
    .cert-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 11px;
        text-align: center;
        direction: rtl;
    }

    .cert-table thead th {
        font-weight: normal;
        padding: 5px 4px;
        border-bottom: 1.5px solid #444;
    }

    .cert-table tbody td {
        padding: 8px 4px;
        border-bottom: 1px solid #444;
        vertical-align: middle;
        line-height: 1.3;
    }

    .pagination-line-cert {
        margin: 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        direction: rtl;
    }

    /* المربع السفلي */
    .notice-box-cert {
        margin-top: 40px;
        border: 1px solid #c4c4c4;
        background: #fff;
        padding: 0;
        page-break-inside: avoid;
    }

    .notice-inner-cert {
        border-top: 1px solid #d7d7d7;
        margin: 15px 10px 10px;
        padding: 12px 15px;
        font-size: 11px;
        line-height: 1.8;
        text-align: right;
    }


    
    @media print {
        @page {
            margin: 0;
            size: A4 portrait;
        }
        .page-wrap { 
            padding: 0; 
            width: 100% !important; 
            max-width: 100% !important; 
        }
        .page-certificate {
            margin: 0 !important;
            padding: 12mm 10mm !important;
            border: none !important;
        }
        .controls-table, .pagination-line-cert, #actions-bar { display: none !important; }
    }
</style>

<main class="w-full px-2 sm:px-6 container mx-auto max-w-7xl">
    <div class="page-wrap" id="printable-area">
        <div class="page-certificate">
            <div class="top-section">
                <!-- الصورة تبقى في اليسار -->
                <div class="profile-box">
                    <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="صورة شخصية">
                </div>

                <!-- كل المحتوى هذا في اليمين -->
                <div class="info-box">
                    <div class="title-line font-ge-medium">تفاصيل / <?php echo htmlspecialchars($fullName); ?></div>
                    <div class="logo-row">
                        <img src="<?php echo $assetsUrl; ?>images/civil.png" alt="الأحوال المدنية">
                    </div>

            
                    <div style="display: inline-block; margin-bottom: 10px; margin-top: -2px; text-align: center;">
                        <div style="font-size: 14px; font-weight: 900; color: #000; margin-bottom: 1px; white-space: nowrap; text-align: center;">تمت الموافقة على منحة الجنسية السعودية</div>
                        <div style="width: 100%; margin: 0; padding: 0; text-align: center;">
                            <img id="barcode_cert" style="max-width: 150px; height: auto; display: block; margin: 0 auto;" />
                        </div>
                    </div>

                    <div style="font-size: 13px; color: #333; font-weight: bold; line-height: 1.8;">رقم المعاملة : <span style="color: #000;"><?php echo htmlspecialchars($transactionNumber); ?></span></div>
                    <div style="font-size: 13px; color: #333; font-weight: bold; line-height: 1.8;">تاريخ الإصدار : <span style="color: #000;"><?php echo htmlspecialchars($hijriDate); ?></span></div>
                    <div style="font-size: 13px; color: #333; font-weight: bold; line-height: 1.8;">الجهة المصدرة: <span style="color: #000;"><?php echo htmlspecialchars($issuingAuthority); ?></span></div>
                    </div>
                </div>


            <div class="controls-table">
                <div class="search-box-cert">
                    <label>بحث سريع :</label>
                    <input type="text" id="cert-search" />
                </div>
                <div style="display:flex;align-items:center;gap:4px;direction:rtl;">إظهار <span style="border:1px solid #aaa;padding:1px 6px;background:#fff;">10</span> <span style="color:#57c9a7;">✓</span> لكل صفحة</div>
            </div>

            <div class="table-area">
                <table class="cert-table" id="cert-data-table">
                    <thead>
                        <tr>
                            <th>الرقم التسلسلي</th>
                            <th>المهنة</th>
                            <th>الحالة</th>
                            <th>حجز موعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($related_persons)): ?>
                            <?php foreach ($related_persons as $person): ?>
                               <tr>
                                  <td style="position: relative;" data-label="الرقم التسلسلي">
                                      <?php echo convertToArabicNumerals('11' . str_pad($person['id'] % 100000000, 8, '0', STR_PAD_LEFT)); ?>
                                  </td>
                                  <td data-label="المهنة"><?php echo htmlspecialchars($person['job_category'] ?? '---'); ?></td>
                                  <td style="text-align: center; vertical-align: middle;" data-label="الحالة">
                                      <?php echo get_status_badge($person['status'] ?? '---'); ?>
                                  </td>
                                  <td data-label="حجز موعد"><?php echo convertToArabicNumerals(htmlspecialchars($person['appointment_date'] ?? 'إلى تاريخ غير محدد')); ?></td>
                              </tr>
                            <?php
    endforeach; ?>
                        <?php
else: ?>
                            <tr><td colspan="4" style="padding:20px;">لا توجد سجلات مرتبطة</td></tr>
                        <?php
endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination-line-cert">
                <div>السجلات الظاهرة : 1 إلى <?php echo count($related_persons); ?> من أصل <?php echo count($related_persons); ?></div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span>السابق</span>
                    <span style="border:1px solid #ccc;padding:1px 8px;background:#fff;">1</span>
                    <span>التالي</span>
                </div>
            </div>

            <div class="notice-box-cert">
                <div class="notice-inner-cert" style="direction: rtl; text-align: right;">
                    <div style="font-weight: 700; margin-bottom: 14px; font-size: 11px;">
                        يجوز منح الجنسية العربية السعودية للأجنبي الذي تتوفر فيه الشروط الآتية:
                    </div>

                    <table style="width:100%; border-collapse:collapse; font-size:11px; line-height:1.9; direction:rtl;">
                        <tr>
                            <td style="width:30px; vertical-align:top; padding:2px 0; text-align:center;">١</td>
                            <td style="vertical-align:top; padding:2px 4px;">أن يكون عند تقديم الطلب قد بلغ سن الرشد</td>
                        </tr>
                        <tr>
                            <td style="width:30px; vertical-align:top; padding:2px 0; text-align:center;">٢</td>
                            <td style="vertical-align:top; padding:2px 4px;">أن يكون غير محجور أو مختل عقلياً</td>
                        </tr>
                        <tr>
                            <td style="width:30px; vertical-align:top; padding:2px 0; text-align:center;">٣</td>
                            <td style="vertical-align:top; padding:2px 4px;">أن يكون حين تقديم الطلب</td>
                        </tr>
                    </table>

                    <table style="width:100%; border-collapse:collapse; font-size:11px; line-height:1.9; direction:rtl; margin-top:2px;">
                        <tr>
                            <td style="width:30px;"></td>
                            <td style="width:14px; vertical-align:top; padding:2px 0;">•</td>
                            <td style="vertical-align:top; padding:2px 4px;">أ - قد اكتسب صفة الإقامة الذاتية العادية في المملكة العربية السعودية بمقتضى أحكام نظامها الجاري العمل لمدة لا تقل عن خمس سنوات متواليات</td>
                        </tr>
                        <tr>
                            <td style="width:30px;"></td>
                            <td style="width:14px; vertical-align:top; padding:2px 0;">•</td>
                            <td style="vertical-align:top; padding:2px 4px;">ب - أن يكون حسن السير والسلوك</td>
                        </tr>
                        <tr>
                            <td style="width:30px;"></td>
                            <td style="width:14px; vertical-align:top; padding:2px 0;">•</td>
                            <td style="vertical-align:top; padding:2px 4px;">ج - أن لا يكون قد صدر عليه حكم جنائي بالسجن لجريمة أخلاقية لمدة تزيد عن ستة أشهر</td>
                        </tr>
                        <tr>
                            <td style="width:30px;"></td>
                            <td style="width:14px; vertical-align:top; padding:2px 0;">•</td>
                            <td style="vertical-align:top; padding:2px 4px;">د - أن يثبت ارتزاقه بطريقة مشروعة.</td>
                        </tr>
                    </table>

                    <div style="margin-top: 15px; font-size: 11px; line-height: 1.8;">
                        ويشفع طالب التجنس بطلبه تصريح الإقامة الدائمة وجواز سفره القانوني أو أية وثيقة تجديد المسلكات المختصة قائمة مقام الجواز القانوني وكل وثيقة تتعلق بالجنسية التي يتنازع منها وكل ورقة لورد ما هو مطالب إثباته بمقتضى أحكام هذا النظام.
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center gap-3 py-6 no-print" id="actions-bar">
                <button onclick="window.print()" class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm flex items-center gap-2">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>'" class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> إنهاء
                </button>
            </div>
        </div>
    </div>
</main>

<script src="<?php echo $assetsUrl; ?>js/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Search functionality
        const searchInput = document.getElementById('cert-search');
        const dataTable = document.getElementById('cert-data-table');
        const rows = dataTable.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            for (let i = 1; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(filter) ? '' : 'none';
            }
        });

        // Barcode
        const barcodeValue = "<?php echo str_replace(['---', ' '], '', $nationalId); ?>";
        if (barcodeValue && typeof JsBarcode !== 'undefined') {
            JsBarcode("#barcode_cert", barcodeValue, {
                format: "CODE128",
                width: 3,
                height: 25,
                displayValue: false,
                margin: 0
            });
        }
    });

    function downloadPDF() {
        window.print();
    }
</script>

