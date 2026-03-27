<?php
require_once __DIR__ . '/../core/bootstrap.php';


// Ensure $request is available from session if not provided by caller
if (!isset($request) || !is_array($request)) {
    if (isset($_SESSION['inquiry_result']['data'])) {
        $request = $_SESSION['inquiry_result']['data'];
    } else {
        header("Location: " . BASE_URL . "?error=no_data");
        exit();
    }
}

$assetsUrl = getInquiryAsset('');

// Mapping to standardized DB columns
$visaNumber = $request['export_number'] ?? $request['serial_number'] ?? '---';
$sponsorName = $request['applicant_name'] ?? '---';
$sponsorId = $request['national_id'] ?? '---';
$nationality = $request['nationality'] ?? '---';
$address = $request['arrival_place'] ?? '---';
$rawDate = $request['created_at'] ?? date('Y-m-d');
$issueDate = convertToHijri($rawDate);

// دالة تحويل الأرقام إلى العربية (الهندية)
if (!function_exists('convertToArabicNumerals')) {
    function convertToArabicNumerals($number)
    {
        if ($number === null || $number === '---')
            return $number;
        $western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str_replace($western, $arabic, (string) $number);
    }
}

$members = $request['related_partners'] ?? [];
$visitorCount = count($members);
$barcodeData = str_pad($visaNumber ?? '', 12, '0', STR_PAD_LEFT);
$logoPath = getInquiryAsset('images/waza.jpeg');
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تصريح زيادة - <?php echo htmlspecialchars($visaNumber); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="<?php echo $assetsUrl; ?>js/JsBarcode.all.min.js"></script>
    <style>
        @font-face {
            font-family: 'Traditional Arabic';
            src: local('Traditional Arabic');
        }

        @font-face {
            font-family: 'Al-samt-tow';
            src: url('<?php echo $assetsUrl; ?>fonts/Al-samt-tow.ttf') format('truetype');
        }

        @font-face {
            font-family: 'Elgharib Basmala';
            src: url('<?php echo $assetsUrl; ?>fonts/Elgharib-Basmala.ttf') format('truetype');
        }

        body {
            font-family: 'GE SS Two', 'Traditional Arabic', serif;
            background-color: #fff;
            margin: 0;
            padding: 5px;
            color: #000;
        }

        .document-container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            padding: 8mm;
            position: relative;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            body:not(.printing) .document-container {
                padding: 15px !important;
            }

            body:not(.printing) .header {
                flex-direction: column !important;
                gap: 20px !important;
            }

            body:not(.printing) .header-right,
            body:not(.printing) .header-left,
            body:not(.printing) .header-center {
                width: 100% !important;
                text-align: center !important;
            }

            body:not(.printing) .sponsor-details > div {
                flex-direction: column !important;
                gap: 10px !important;
            }

            body:not(.printing) .table-header-box {
                font-size: 18px !important;
                top: -12px !important;
            }

            body:not(.printing) .v-row {
                flex-direction: column !important;
                padding: 15px 0 !important;
            }

            body:not(.printing) .col {
                width: 100% !important;
                padding: 0 !important;
                margin-bottom: 10px !important;
            }

            body:not(.printing) .field {
                justify-content: space-between !important;
                border-bottom: 1px dashed #eee !important;
                padding: 5px 0 !important;
            }

            body:not(.printing) .label {
                min-width: 100px !important;
                font-weight: bold !important;
            }

            body:not(.printing) .vertical-text {
                display: none !important;
            }

            body:not(.printing) .signature-row {
                flex-direction: column !important;
                gap: 20px !important;
                text-align: center !important;
            }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2px;
        }

        .header-right {
            width: 300px;
            text-align: right;
            font-family: 'Al-samt-tow', serif;
            font-size: 21px;
            line-height: 1.1;
            font-weight: normal;
        }

        .header-center {
            text-align: center;
            flex-grow: 1;
        }

        .basmala {
            font-family: 'Elgharib Basmala';
            font-size: 16px;
            margin-bottom: 2px;
            font-weight: normal;
        }

        .logo {
            width: 110px;
            height: auto;
        }

        .permit-wrapper {
            margin-top: 10px;
        }

        .permit-highlight {
            display: inline-block;
            padding: 5px 20px;
            font-weight: bold;
            font-size: 26px;
            letter-spacing: 5px;
            background-color: transparent;
        }

        .header-left {
            width: 250px;
            text-align: left;
            position: relative;
        }

        .page-num {
            text-align: center;
            font-size: 14px;
            margin-bottom: 0px;
            padding-right: 5px;
        }

        .barcode-container {
            text-align: center;
            margin-top: 0;
            padding-right: 0px;
        }

        #barcode-svg {
            width: 100px;
            height: 25px;
        }

        .content-body {
            margin-top: 0px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .blue-text {
            color: #0000ff;
            font-weight: normal;
        }

        .intro-section {
            font-size: 20px;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .sponsor-details {
            font-size: 19px;
            line-height: 1.1;
            margin-bottom: 5px;
        }

        .table-wrapper {
            position: relative;
            margin-top: 15px;
        }

        .vertical-text {
            position: absolute;
            left: -140px;
            top: 50%;
            transform: translateY(-50%) rotate(-90deg);
            width: 250px;
            font-size: 13px;
            text-align: center;
            font-weight: normal;
            color: #444;
        }

        .visitors-table {
            width: 100%;
            margin: 0;
            border: 2.5px solid #a8c6df;
            border-collapse: collapse;
        }

        .table-header-box {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 15px;
            font-weight: normal;
            font-family: 'Traditional Arabic', serif;
            font-size: 24px;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .v-row {
            display: flex;
            border-bottom: 1.5px solid #a8c6df;
            padding: 3px 0;
        }

        .v-row:last-child {
            border-bottom: none;
        }

        .col {
            display: flex;
            flex-direction: column;
            padding: 0 10px;
        }

        .col-right {
            width: 45%;
        }

        .col-mid {
            width: 30%;
        }

        .col-left {
            width: 25%;
        }

        .field {
            display: flex;
            align-items: center;
            margin-bottom: 1px;
            font-size: 18px;
        }

        .label {
            min-width: 80px;
        }

        .footer {
            margin-top: 5px;
            font-size: 18px;
        }

        .signature-row {
            display: flex;
            justify-content: space-around;
            margin-top: 0;
            padding: 0 10%;
        }

        .no-print {
            text-align: center;
            margin-top: 30px;
        }

        .actions-bar {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-finish {
            background: #00AB67;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-pdf {
            background: #00AB67;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            @page {
                margin: 5mm;
                size: A4 portrait;
            }

            .no-print,
            .actions-bar {
                display: none !important;
            }

            body {
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
            }

            .document-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 5mm !important;
                border: none !important;
            }

            /* Force standard layout for print */
            .header, .v-row, .sponsor-details > div {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
            }

            .col-right { width: 45% !important; }
            .col-mid { width: 30% !important; }
            .col-left { width: 25% !important; }

            .header-right { width: 300px !important; }
            .header-center { flex-grow: 1 !important; }
            .header-left { width: 250px !important; }

            .v-row, .header, .sponsor-details {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body>

    <div class="document-container" id="printable-area">
        <!-- Header -->
        <div class="header">
            <div class="header-right"
                style="font-family: 'Diwani Letter', 'MCS Diwani', 'DecoType Thuluth', 'Traditional Arabic', serif;">
                <div>الْمَمْلَكَةُ الْعَرَبِيَّةُ السُّعُـودِيَّـةُ</div>
                <div>وِزَارَةُ الدَّاخِـلِـيَّـةِ</div>
                <div>الْإِسْـتِـقْـدَامُ</div>
                <div>قِـسْـمُ الزِّيَـارَاتِ</div>
            </div>

            <div class="header-center">
                <div class="basmala">﷽</div>
                <img src="<?php echo $logoPath; ?>" alt="Logo" class="logo">
                <div class="permit-wrapper">
                    <div class="permit-highlight">تصريح زيارة</div>
                    <div class="no-print" style="margin-top: 5px;">
                        <?php echo get_status_badge($request['status'] ?? 'تمت الموافقة'); ?>
                    </div>
                </div>
            </div>

            <div class="header-left">
                <div class="page-num">صفحة : <?php echo convertToArabicNumerals(1); ?></div>
                <div class="barcode-container">
                    <svg id="barcode-svg"></svg>
                </div>
                <div style="text-align: center; margin-top: 0px; font-size: 16px;">
                    الرقم : <span class="blue-text"><?php echo convertToArabicNumerals($visaNumber); ?></span><br>
                    التاريخ : <span class="blue-text"><?php echo htmlspecialchars($issueDate); ?></span>
                </div>
            </div>
        </div>

        <!-- Intro -->
        <div class="content-body">
            <div class="intro-section text-right">
                <div style="font-weight: bold;">وزارة الخارجية</div>
                <div>السلام عليكم ورحمة الله وبركاته</div>
                <div style="margin-bottom: 2px;">حيث تقرر الموافقة بالتأشيرة للموضح عنهم أدناه تحت كفالة</div>
                <div class="blue-text" style="font-size: 21px; font-weight: bold; margin-right: 20px;">
                    <?php echo htmlspecialchars($sponsorName); ?></div>
            </div>

            <!-- Sponsor Details -->
            <div class="sponsor-details" style="margin-top: 5px; line-height: 1.5;">
                <div>الجنسية : <span class="blue-text"><?php echo htmlspecialchars($nationality); ?></span></div>
                <div>العنوان : <span class="blue-text"><?php echo htmlspecialchars($address); ?></span></div>

                <div style="display: flex; gap: 50px;">
                    <div>رقم الكفيل : <span class="blue-text"><?php echo convertToArabicNumerals($sponsorId); ?></span>
                    </div>
                    <div>رقم الحاسب : <span class="blue-text"><?php echo convertToArabicNumerals($visaNumber); ?></span>
                    </div>
                    <div>عدد التأشيرات : <span
                            class="blue-text"><?php echo convertToArabicNumerals($visitorCount); ?></span></div>
                </div>

                <div>اسم الكفيل : <span class="blue-text"><?php echo htmlspecialchars($sponsorName); ?></span></div>
            </div>

            <!-- Visitors Table -->
            <div class="table-wrapper">
                <div class="table-header-box">بِـيَـانـاتُ الـمَـطـْلـُوبِ زِيَـارَتُـهُـمْ</div>
                <div class="vertical-text">مع تحيات مركز المعلومات</div>

                <table class="visitors-table">
                    <tbody>
                        <?php
                        $maxRows = max(5, count($members));
                        for ($i = 0; $i < $maxRows; $i++):
                            $m = $members[$i] ?? [];
                            $vName = $m['full_name'] ?? '';
                            $vNat = $m['nationality'] ?? '';
                            $vRel = $m['relationship'] ?? '';
                            $vJob = $m['job_category'] ?? '';
                            $vFrom = $m['country'] ?? $request['arrival_place'] ?? '';
                            $vAge = $m['age'] ?? '---';
                            $vDur = $m['duration'] ?? $m['duration_of_stay'] ?? $request['duration'] ?? '---';

                            // If the row is empty (template row), clear the fields
                            if (empty($vName)) {
                                $vNat = $vRel = $vJob = $vFrom = $vAge = $vDur = '';
                            }
                            ?>
                            <tr class="v-row">
                                <td class="col col-right">
                                    <div class="field"><span class="label">إسم الزائر :</span> <span
                                            class="blue-text"><?php echo htmlspecialchars($vName); ?></span></div>
                                    <div class="field"><span class="label">جنسيته :</span> <span
                                            class="blue-text"><?php echo htmlspecialchars($vNat); ?></span></div>
                                    <div class="field"><span class="label">العلاقة :</span> <span
                                            class="blue-text"><?php echo htmlspecialchars($vRel); ?></span></div>
                                </td>
                                <td class="col col-mid">
                                    <div class="field" style="height: 20px;"></div>
                                    <div class="field"><span class="label" style="min-width: 50px;">مهنته :</span> <span
                                            class="blue-text"><?php echo htmlspecialchars($vJob); ?></span></div>
                                    <div class="field"><span class="label" style="min-width: 50px;">قادم من :</span> <span
                                            class="blue-text"><?php echo htmlspecialchars($vFrom); ?></span></div>
                                </td>
                                <td class="col col-left">
                                    <div class="field" style="height: 20px;"></div>
                                    <div class="field">
                                        <span class="label" style="min-width: 40px;">السن :</span>
                                        <span class="blue-text"
                                            style="min-width: 30px; text-align:center;"><?php echo convertToArabicNumerals($vAge); ?></span>
                                        <span> سنة</span>
                                    </div>
                                    <div class="field">
                                        <span class="label" style="min-width: 40px;">المدة :</span>
                                        <span class="blue-text"
                                            style="min-width: 30px; text-align:center;"><?php echo convertToArabicNumerals($vDur); ?></span>
                                        <span> يوم</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer text-right">
                <div style="font-weight: bold; margin-bottom: 2px;">❃ ملحوظة :</div>
                <div style="line-height: 1.2;">
                    آمل من سعادتكم إبلاغ سفارات خادم الحرمين الشريفين المعنية بالتأشير بالزيارة المحدودة مالم يكن على من
                    سيؤشر بدخولهم ملاحظات
                </div>

                <div class="signature-row" style="margin-top: 0;">
                    <div>رقم الباحث :</div>
                    <div style="color: blue;">..</div>
                    <div>مدير عام الإستقدام</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="actions-bar no-print" id="actions-bar">
        <a href="<?php echo BASE_URL; ?>" class="btn-finish" style="font-family: inherit;"><i
                class="fas fa-check-circle"></i> إنهاء</a>
        <button class="btn-pdf" onclick="window.print()" style="font-family: inherit;"><i class="fas fa-print"></i>
            طباعة</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.location.search.includes('print=true')) {
                document.body.classList.add('printing');
                setTimeout(() => { 
                    window.print(); 
                    document.body.classList.remove('printing');
                }, 500);
            }

            if (typeof JsBarcode !== 'undefined') {
                JsBarcode("#barcode-svg", "<?php echo $barcodeData; ?>", {
                    format: "CODE128",
                    width: 1.0,
                    height: 30,
                    displayValue: false,
                    margin: 0
                });
            }
        });

        window.onbeforeprint = function() {
            document.body.classList.add('printing');
        };
        window.onafterprint = function() {
            document.body.classList.remove('printing');
        };

        function downloadPDF() {
            const originalTitle = document.title;
            const visaNum = '<?= htmlspecialchars($visaNumber) ?>';
            document.title = 'family_visit_result_' + visaNum;

            window.print();

            setTimeout(() => {
                document.title = originalTitle;
            }, 1000);
        }
    </script>

</body>

</html>