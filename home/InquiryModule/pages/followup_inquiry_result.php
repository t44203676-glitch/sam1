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

// Mapping to standardized DB columns
$fullName       = $request['applicant_name'] ?? '---';
$nationalId     = $request['national_id'] ?? $request['sponsor_id'] ?? '---';
$exportNumber   = $request['export_number'] ?? '---';
$lastModified   = str_replace('-', '/', $request['last_modified_date'] ?? $request['updated_at'] ?? $request['created_at'] ?? date('Y/m/d'));
$issuingAuth    = $request['source_entity'] ?? $request['issuing_authority'] ?? 'وزارة الداخلية';
$status         = $request['status'] ?? 'جديد';

// Related data for Follow-up
$related_data = $request['related_data'] ?? $request['related_partners'] ?? [];
$assetsUrl = getInquiryAsset('');

$page_title = "وزارة الداخلية - المملكة العربية السعودية - تفاصيل العمالة الوافدة";
$breadcrumbs = [
    ['label' => 'الاستعلامات الإلكترونية', 'url' => '#'],
    ['label' => 'الجوازات', 'url' => '#'],
    ['label' => 'الاستفسار عن صلاحية الاقامة للعمالة الوافدة']
];

include __DIR__ . '/../core/inquiry_header.php';
?>

    <!-- Main Content: PermitDetails Component -->
    <main class="w-full bg-white flex justify-center">
        <div class="w-full max-w-[<?= (string)($containerWidth ?? '1300px') ?>] mx-auto mt-2 mb-4 px-4">
          
          <!-- Page Title Bar -->
          <div class="border border-gray-200 bg-[#fbfbfb] px-4 py-2 mb-2 text-[#444444] font-ge-light text-base-custom shadow-[0_1px_2px_rgba(0,0,0,0.02)] no-print">
               الاستفسار عن صلاحية الاقامة للعمالة الوافدة
          </div>

          <div class="bg-white border border-gray-300 shadow-sm rounded-t-lg overflow-hidden" id="printable-area">
            
            <!-- Card Header -->
            <div class="bg-[#f5f5f5] px-4 py-3 border-b border-gray-200">
                <h3 class="text-black font-ge-light text-lg-custom">
                    تفاصيل العمالة الوافدة  | |  <?= htmlspecialchars($fullName) ?>
                </h3>
            </div>

            <!-- Top Info Grid -->
            <div class="p-6 grid grid-cols-1 print:grid-cols-2 md:grid-cols-2 gap-y-4 gap-x-12 text-base-custom text-gray-700 font-ge-light border-b border-gray-200">
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">رقم الهوية | السجل :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($nationalId) ?></span>
                </div>
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">رقم الصادر :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($exportNumber) ?></span>
                </div>
                <!-- Handle Issue Date: Check if standard date format or hijri conversion needed -->
                <!-- The original used convertToHijri($request['request_date']), assuming that function is available globally or we print raw -->
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">تاريخ الإصدار :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($lastModified) ?></span>
                </div>
                 <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">جهة الصدور :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($issuingAuth) ?></span>
                </div>
            </div>


            <!-- Data Table -->
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-center border-collapse text-xs ">
                    <thead>
                        <tr class="bg-[#f9f9f9] text-gray-600 font-ge-medium border-b border-t border-gray-200">
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الرقم التسلسلي</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">فئة المهنة</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الجنسية</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">النوع</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">مكان القدوم</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الحالة</th>
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">رقم الملف المرسل للبنك</th>
                             <th class="py-3 px-2 font-ge-medium">تاريخ الارسال للبنك</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($related_data)): ?>
                            <?php foreach ($related_data as $item): ?>
                                <?php 
                                $status = trim((string)($item['status'] ?? '---'));
                                ?>
                             <tr class="border-b border-gray-200 hover:bg-gray-50 text-gray-700 font-ge-light">
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="الرقم التسلسلي"><?= htmlspecialchars(toWesternDigits($request['serial_number'] ?? '---')) ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="فئة المهنة"><?= (trim((string)($item['job_category'] ?? '')) !== '' ? htmlspecialchars($item['job_category']) : '---') ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="الجنسية"><?= (trim((string)($item['nationality'] ?? '')) !== '' ? htmlspecialchars($item['nationality']) : '---') ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="النوع"><?= htmlspecialchars($item['visa_type'] ?? 'تعقيب وجوازات') ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="مكان القدوم"><?= (trim((string)($item['country'] ?? $item['arrival_place'] ?? '')) !== '' ? htmlspecialchars($item['country'] ?? $item['arrival_place']) : '---') ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="الحالة">
                                     <?= get_status_badge($status) ?>
                                 </td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="رقم الملف"><?= htmlspecialchars(toWesternDigits('0')) ?></td>
                                 <td class="py-4 px-2 whitespace-normal break-words" data-label="تاريخ الارسال"></td>
                             </tr>
                             <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="border-b border-gray-200">
                                 <td colspan="8" class="py-4">لا توجد بيانات</td>                            </tr>
                        <?php endif; ?>
                        
                        <tr class="border-b border-gray-400">
                            <td colSpan="7" class="h-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

                        <div class="flex justify-center gap-3 py-4 pb-6 no-print font-ge-light text-base-custom" id="actions-bar">
                <button onclick="window.print()" id="btn-print"
                    class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <!-- Legend Table -->
            <div class="border-t border-gray-300">
                 <div class="grid grid-cols-12 bg-[#f5f5f5] text-black text-sm-custom font-ge-light py-2 px-4 border-b border-gray-300">
                     <div class="col-span-3 text-right pr-4 font-ge-medium">حالة الإقامة</div>
                     <div class="col-span-9 text-center border-r border-[#ccc]">التفاصيل</div>
                 </div>
                 
                 <!-- Row 1: Valid -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         سارية المفعول
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها إحدى الحالات التالية:<br>
                         1- لا يوجد أي قيود أو بلاغ على الإقامة.<br>
                         2- الوافد المقيم يعمل بشكل نظامي.
                     </div>
                 </div>

                 <!-- Row 2: Approved -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         تمت الموافقة
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أن التأشيرة صالحة ويمكن الاستقدام عليها وإذا رغبت في إلغائها يلزم مراجعة مكتب الاستقدام أو مكتب العمل التابع لوزارة العمل لإلغائها
                     </div>
                 </div>

                 <!-- Row 3: Expired -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         انتهت صلاحيتها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها انتهاء مدة التأشيرة (سنتين)، وبعد مضي 100 يوم من انتهاء صلاحيتها ستتغير العبارة إلى (تم استعادة رسومها). وفي حالة عدم تغير العبارة يمكنك تسجيل طلب عبر (آمر) على الرقم (199099) أو الدخول على موقعهم على الإنترنت.
                     </div>
                 </div>

                 <!-- Row 4: Cancelled -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         تم إلغاؤها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم إلغاؤها من مكتب الاستقدام أو مكتب العمل، وينبغي الانتظار ومتابعتها حتى تتغير حالتها إلى (تم استعادة رسومها). وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 5: Cancellation Sent -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         تم إرسال طلب الإلغاء لوزارة الخارجية ولم يصل الرد
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم إرسال طلب الإلغاء لوزارة الخارجية وما يزال تحت الإجراء، وينبغي الانتظار ومتابعتها على موقع وزارة الخارجية حتى تتغير حالتها إلى (تم استعادة رسومها)، وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 6: Refunded -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start border-b sm:border-b-0 border-gray-100">
                         تم استعادة رسومها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم استرجاع المبلغ إلى الحساب المسدد منه، وإذا رغبت في الحصول على (رقم هوية المسدد، واسم البنك، وتاريخ رجوع المبلغ) قم بالاتصال بمركز (آمر) على الرقم (199099) مع تجهيز رقم الهوية ورقم التأشيرة. أما في حالة سداد مبلغ التأشيرة نقدا فيتم مراجعة البنك مباشرة لاستلامه.
                     </div>
                 </div>
            </div>
�راء، وينبغي الانتظار ومتابعتها على موقع وزارة الخارجية حتى تتغير حالتها إلى (تم استعادة رسومها)، وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 6: Refunded -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
                         تم استعادة رسومها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم استرجاع المبلغ إلى الحساب المسدد منه، وإذا رغبت في الحصول على (رقم هوية المسدد، واسم البنك، وتاريخ رجوع المبلغ) قم بالاتصال بمركز (آمر) على الرقم (199099) مع تجهيز رقم الهوية ورقم التأشيرة. أما في حالة سداد مبلغ التأشيرة نقدا فيتم مراجعة البنك مباشرة لاستلامه.
                     </div>
                 </div>
            </div>

          </div>
        </div></main>

        <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.location.search.includes('print=true')) {
            setTimeout(() => { window.print(); }, 500);
        }
    });

    async function downloadPDF() {
        const element = document.getElementById('printable-area');
        const actionsBar = document.getElementById('actions-bar');
        if (!element) return;

        if (actionsBar) actionsBar.style.display = 'none';

        try {
            const { jsPDF } = window.jspdf;
            const canvas = await html2canvas(element, { scale: 2, useCORS: true });
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, imgHeight);
            pdf.save('followup_result.pdf');
        } catch (err) {
            console.error('PDF Error:', err);
            window.print();
        } finally {
            if (actionsBar) actionsBar.style.display = 'flex';
        }
    }
    </script>
<?php include __DIR__ . '/../core/inquiry_footer.php'; ?>
<?php include __DIR__ . '/../core/inquiry_end.php'; ?>

