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

// Related Items (Visitors)
$related_data = $request['related_partners'] ?? $request['related_data'] ?? [];
$visitor = !empty($related_data) ? $related_data[0] : [];

// --- STEP 1 DATA (Main Request) ---
$visaNumber = $request['visa_no'] ?? $request['export_number'] ?? '---';
$rawIssueDate = $request['issue_date'] ?? $request['created_at'] ?? date('Y-m-d');
$issueDate = (strtotime($rawIssueDate)) ? date('d/m/Y', strtotime($rawIssueDate)) : $rawIssueDate;

// Expiry Logic
$validUntil = $request['valid_until'] ?? '---';
if ($validUntil !== '---' && strpos($validUntil, '-') !== false) {
    $validUntil = date('d/m/Y', strtotime($validUntil));
}

$durationDays = $request['duration_of_stay'] ?? '90';
if (preg_match('/(\d+)/', (string) $durationDays, $matches)) {
    $durationDays = $matches[1];
}
$durationTextAr = $durationDays . ' يوم';
$durationTextEn = $durationDays . ' Days';

$visaType = $request['visa_type'] ?? 'Business Visit';
$entryType = $request['entry_type'] ?? 'Multiple';

$residenceNo = $request['visa_residence_no'] ?? $visaNumber;
$expiryDate = $request['expiry_date'] ?? $validUntil;
if ($expiryDate !== '---' && strpos($expiryDate, '-') !== false && $expiryDate !== $validUntil) {
    $expiryDate = date('d/m/Y', strtotime($expiryDate));
}

// --- STEP 2 DATA (Visitor) ---
$fullName = $visitor['full_name'] ?? $request['applicant_name'] ?? '---';
$passportNumber = $visitor['passport_number'] ?? '---';
$birthDate = $visitor['birth_date'] ?? '---';
if ($birthDate !== '---' && strtotime($birthDate)) {
    $birthDate = date('d/m/Y', strtotime($birthDate));
}

$nationalityInput = $visitor['nationality'] ?? '---';
$nationalityData = getNationalityData($nationalityInput);
$nationalityAr = $nationalityData['ar'];
$nationalityEn = $nationalityData['en'];

$arrivalPlaceInput = $visitor['arrival_place'] ?? $visitor['country'] ?? $request['arrival_place'] ?? 'Saudi Digital Embassy';
// We can use a helper or just display it
$issuePlaceAr = $arrivalPlaceInput;
$issuePlaceEn = 'Saudi Digital Embassy';

$entryTypeAr = ($entryType === 'Multiple' || $entryType === 'متعددة' || $entryType === '3') ? 'متعددة' : 'سفرة واحدة';
$entryTypeEn = ($entryType === 'Multiple' || $entryType === 'متعددة' || $entryType === '3') ? 'Multiple' : 'Single';

// Purpose Logic
if (stripos($visaType, 'Tourism') !== false || stripos($visaType, 'Tourist') !== false || stripos($visaType, 'سياح') !== false) {
    $purposeAr = 'زيارة سياحية';
    $purposeEn = 'Tourism Visit';
} else {
    $purposeAr = $visaType;
    $purposeEn = 'Business Visit';
}

// Personal Photo - read from main request table first, then visitor record
$rawPhotoPath = $request['profile_photo_path'] ?? $visitor['profile_photo_path'] ?? '';
$fallbackImg = getInquiryAsset('images/default-avatar.png');
$photoPath = getProfilePhotoUrl($rawPhotoPath, $fallbackImg);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Local Assets -->
    <script src="<?php echo $assetsUrl; ?>js/tailwindcss.js"></script>
    <link rel="stylesheet" href="<?php echo $assetsUrl; ?>css/fonts.css">
    <script>
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');

        body {
            font-family: 'GE SS Two', 'Tajawal', 'Noto Sans Arabic', 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #000000;
            font-weight: bold !important;
            font-size: 1.1rem;
        }

        * {
            font-weight: bold !important;
        }

        .arabic-text {
            font-family: 'Tajawal', 'Noto Kufi Arabic', sans-serif;
            font-weight: 700;
            color: #000000;
        }

        .english-text {
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            font-weight: 600;
            color: #000000;
        }

        .checkmarks-container {
            font-size: 0;
            text-align: center;
            margin-top: 10px;
            white-space: nowrap;
            overflow: hidden;
        }

        .checkmark {
            font-size: 40px;
            font-weight: 900;
            margin: 0;
            padding: 0;
            display: inline-block;
            line-height: 0.8;
        }

        .color1 {
            color: #9370DB;
        }

        .color2 {
            color: #DDA0DD;
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

            body {
                background-color: white !important;
                display: block;
                margin: 0 !important;
                padding: 0 !important;
            }

            #printable-area {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 5mm !important;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Responsive improvements for small screens */

    </style>
</head>

<body class="min-h-screen bg-gray-100 py-6 flex flex-col items-center justify-center p-4 print:block print:p-0">

    <div class="bg-white w-full max-w-4xl mx-auto relative text-gray-800 print:w-full print:max-w-none print:p-0 my-4 p-6 shadow-sm"
        id="printable-area">

        <div class="mb-4 flex justify-between items-center px-4 pb-2">
            <div class="text-left w-1/3">
                <div class="text-sm font-black text-black font-mono tracking-widest"><?php echo date('n/j/y, g:i A'); ?>
                </div>
            </div>
            <div class="text-center w-1/3">
                <div class="text-base font-black text-black font-mono tracking-wider">Visa platform</div>
                <div class="no-print" style="margin-top: 5px;">
                    <?php echo get_status_badge($request['status'] ?? 'تمت الموافقة'); ?>
                </div>
            </div>
            <div class="w-1/3"></div>
        </div>

        <div class="w-full mb-1">
            <div class="flex justify-between items-center px-4">
                <div class="w-1/3 text-left">
                    <img src="<?php echo $assetsUrl; ?>images/ksa_visa_logo1.png" alt="KSA VISA - EVISA" loading="lazy"
                        class="h-24 object-contain">
                </div>
                <div class="w-1/3 text-center"></div>
                <div class="w-1/3 text-right flex justify-end">
                    <img src="<?php echo $assetsUrl; ?>images/Kingdom_of_Saudi_Arabia_logo_HQ.png" alt="KSA Logo" loading="lazy"
                        alt="Kingdom of Saudi Arabia" class="h-28 object-contain">
                </div>
            </div>
        </div>

        <div class="px-4 md:px-8">

            <div class="flex gap-4 mb-1 flex-row-reverse top-info-row">
                <div class="flex-grow flex flex-col">
                    <div class="flex items-center justify-between border-b-2 border-gray-300 py-0.5">
                        <div class="w-[35%] text-center pl-1 text-sm font-black text-black arabic-text">رقم التأشيرة
                        </div>
                        <div class="w-[30%] text-center font-black text-black text-lg">
                            <?php echo htmlspecialchars($visaNumber); ?></div>
                        <div class="w-[35%] text-center pr-1 text-sm font-black text-black english-text">Visa No.</div>
                    </div>
                    <div class="flex items-center justify-between border-b-2 border-gray-300 py-0.5">
                        <div class="w-[35%] text-center pl-1 text-sm font-black text-black arabic-text">تاريخها</div>
                        <div class="w-[30%] text-center font-black text-black text-lg">
                            <?php echo htmlspecialchars($issueDate); ?></div>
                        <div class="w-[35%] text-center pr-1 text-sm font-black text-black english-text">Date of Issue
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-b-2 border-gray-300 py-0.5">
                        <div class="w-[35%] text-center pl-1 text-sm font-black text-black arabic-text">صالحة لغاية
                        </div>
                        <div class="w-[30%] text-center font-black text-black text-lg">
                            <?php echo htmlspecialchars($validUntil); ?></div>
                        <div class="w-[35%] text-center pr-1 text-sm font-black text-black english-text">Valid until
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-b-2 border-gray-300 py-0.5">
                        <div class="w-[35%] text-center pl-1 text-sm font-black text-black arabic-text">مدة الإقامة
                        </div>
                        <div class="w-[30%] text-center font-black text-black text-lg flex justify-center gap-2"><span
                                class="arabic-text"><?php echo $durationTextAr; ?></span><span
                                class="english-text"><?php echo $durationTextEn; ?></span></div>
                        <div class="w-[35%] text-center pr-1 text-sm font-black text-black english-text">Duration of
                            Stay</div>
                    </div>
                    <div class="flex items-center justify-between border-b-2 border-gray-300 py-0.5">
                        <div class="w-[35%] text-center pl-1 text-sm font-black text-black arabic-text">رقم جواز السفر
                        </div>
                        <div class="w-[30%] text-center font-black text-black text-lg">
                            <?php echo htmlspecialchars($passportNumber); ?></div>
                        <div class="w-[35%] text-center pr-1 text-sm font-black text-black english-text">Passport No.
                        </div>
                    </div>

                    <div class="checkmarks-container opacity-50 mt-1">
                        <?php for ($i = 0; $i < 18; $i++): ?>
                            <span
                                class="checkmark color<?php echo ($i % 2 == 0) ? '1' : '2'; ?> text-[30px] line-height-[0.6]">✔</span>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="w-[180px] flex-shrink-0 photo-container">
                    <div
                        class="w-full h-[220px] border-2 border-gray-300 bg-white p-0.5 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="Visa Holder" loading="lazy"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <div class="flex flex-col w-full">
                <div class="flex items-center justify-between border-b border-gray-300 py-2 bg-gray-50">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">مصدر التأشيرة</div>
                    <div
                        class="w-[30%] text-center font-black text-black text-sm flex justify-center gap-2 whitespace-nowrap">
                        <span class="english-text"><?php echo htmlspecialchars($issuePlaceEn); ?></span> - <span
                            class="arabic-text"><?php echo htmlspecialchars($issuePlaceAr); ?></span>
                    </div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Place of issue
                    </div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-300 py-2 responsive-row">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">الاسم</div>
                    <div
                        class="w-[30%] text-center font-black text-black text-xl arabic-text name-text whitespace-nowrap">
                        <?php echo htmlspecialchars($fullName); ?></div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Name</div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-300 py-2 responsive-row">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">الجنسية</div>
                    <div
                        class="w-[30%] text-center font-black text-black text-xl flex justify-center gap-2 whitespace-nowrap">
                        <span class="english-text"><?php echo htmlspecialchars($nationalityEn); ?></span> - <span
                            class="arabic-text"><?php echo htmlspecialchars($nationalityAr); ?></span>
                    </div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Nationality</div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-300 py-2 responsive-row">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">تاريخ الميلاد</div>
                    <div class="w-[30%] text-center font-black text-black text-xl whitespace-nowrap">
                        <?php echo htmlspecialchars($birthDate); ?></div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Birth Date</div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-300 py-2 responsive-row">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">نوع التأشيرة</div>
                    <div
                        class="w-[30%] text-center font-black text-black text-xl flex justify-center gap-2 whitespace-nowrap">
                        <span class="english-text"><?php echo htmlspecialchars($purposeEn); ?></span> - <span
                            class="arabic-text"><?php echo htmlspecialchars($purposeAr); ?></span>
                    </div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Type Of Visa</div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-300 py-2 responsive-row">
                    <div class="w-[35%] text-center pl-2 text-sm font-black text-black arabic-text">عدد مرات الدخول
                    </div>
                    <div
                        class="w-[30%] text-center font-black text-black text-xl flex justify-center gap-2 whitespace-nowrap">
                        <span class="english-text"><?php echo htmlspecialchars($entryTypeEn); ?></span> - <span
                            class="arabic-text"><?php echo htmlspecialchars($entryTypeAr); ?></span>
                    </div>
                    <div class="w-[35%] text-center pr-2 text-sm font-black text-black english-text">Entry Type</div>
                </div>
            </div>

            <div class="my-4">
                <div class="flex items-center justify-between">
                    <div class="w-[35%] text-center pl-2 text-xs font-extrabold text-black english-text">Visa No.</div>
                    <div class="flex flex-col items-center w-[30%]">
                        <svg id="barcode"></svg>
                        <script src="<?php echo $assetsUrl; ?>js/JsBarcode.all.min.js"></script>
                        <script>
                            JsBarcode("#barcode", "<?php echo $visaNumber; ?>", {
                                format: "CODE128",
                                lineColor: "#000",
                                width: 1.5,
                                height: 35,
                                displayValue: true,
                                fontSize: 14,
                                fontOptions: "bold",
                                margin: 2
                            });
                        </script>
                    </div>
                    <div class="w-[35%] text-center pr-2 text-xs font-extrabold text-black arabic-text">رقم التأشيرة
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-3 mb-4 rounded border border-red-100 text-center">
                <p class="text-sm font-black text-red-950 mb-1 arabic-text">
                    غير مصرح بالحج أو الإقامة في مكة المكرمة خلال فترة موسم الحج (من 15 ذي القعدة إلى 15 ذي الحجة)
                </p>
                <p class="text-xs font-black text-red-900 english-text">
                    Not permitted to perform Hajj or to stay in Makkah during Hajj season (from 15 Dhu al-Qadah to 15
                    Dhu al-Hijjah)
                </p>
            </div>

            <div class="p-3 mb-4 text-center">
                <p class="text-xs font-bold text-red-600 mb-1 arabic-text">
                    اتعهد بأني حاصل على إقامة "سارية المفعول" لمدة لا تقل عن ثلاثة أشهر من (
                    <?php echo htmlspecialchars($visitor['country'] ?? $request['arrival_place'] ?? 'Bahrain'); ?> )، و
                    بياناتها كالآتي
                </p>
                <p class="text-[10px] font-semibold text-red-500 english-text">
                    I undertake that i had a "valid" residence for no less than three months from (
                    <?php echo htmlspecialchars($visitor['country'] ?? $request['arrival_place'] ?? 'Bahrain'); ?> )
                    ,and its details as follows
                </p>

                <div class="mt-4 flex flex-col gap-2 border-t border-red-50 pt-3">
                    <div class="flex justify-between items-center responsive-row">
                        <div class="w-1/3 text-left text-sm font-black text-black arabic-text">رقم التأشيرة / الإقامة
                        </div>
                        <div class="flex-grow text-center">
                            <div
                                class="bg-gray-100 py-1 px-4 font-black text-xl text-black inline-block rounded border border-gray-200 min-w-[150px]">
                                <?php echo htmlspecialchars($residenceNo); ?></div>
                        </div>
                        <div class="w-1/3 text-right text-sm font-black text-black english-text">Residence / Visa Number
                        </div>
                    </div>
                    <div class="flex justify-between items-center responsive-row">
                        <div class="w-1/3 text-left text-sm font-black text-black arabic-text">تاريخ الانتهاء</div>
                        <div class="flex-grow text-center">
                            <div
                                class="bg-gray-100 py-1 px-4 font-black text-xl text-black inline-block rounded border border-gray-200 min-w-[150px]">
                                <?php echo htmlspecialchars($expiryDate); ?></div>
                        </div>
                        <div class="w-1/3 text-right text-sm font-black text-black english-text">Expiry Date</div>
                    </div>
                </div>
            </div>

            <div class="checkmarks-container opacity-60 mb-4">
                <?php for ($i = 0; $i < 31; $i++): ?>
                    <span class="checkmark color<?php echo ($i % 2 == 0) ? '1' : '2'; ?>">✔</span>
                <?php endfor; ?>
            </div>
        </div>

        <div class="mt-4 text-left">
            <a href="https://visa.mofa.gov.sa"
                class="text-sm font-bold text-gray-700 english-text hover:text-blue-600 transition tracking-tight">
                https://visa.mofa.gov.sa
            </a>
        </div>

        <div class="no-print mt-6 text-center flex justify-center gap-3" id="actions-bar">
            <button onclick="window.print()"
                class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> طباعة
            </button>
            <button onclick="window.location.href='<?php echo BASE_URL; ?>'"
                class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                <i class="fas fa-check-circle"></i> إنهاء
            </button>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if (window.location.search.includes('print=true')) {
                    setTimeout(() => { window.print(); }, 500);
                }
            });

            async function downloadPDF() {
                const { jsPDF } = window.jspdf;
                const element = document.getElementById('printable-area');
                const actionsBar = document.getElementById('actions-bar');
                const visaNum = '<?= $visaNumber ?>';

                if (actionsBar) actionsBar.style.display = 'none';

                try {
                    const canvas = await html2canvas(element, { scale: 2, useCORS: true });
                    const imgData = canvas.toDataURL('image/png', 1.0);
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const imgHeight = (canvas.height * pdfWidth) / canvas.width;
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, imgHeight);
                    pdf.save('business_visit_' + visaNum + '.pdf');
                } catch (error) {
                    console.error('PDF Error:', error);
                    window.print();
                } finally {
                    if (actionsBar) actionsBar.style.display = 'flex';
                }
            }
        </script>
</body>

</html>