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
$nationalId     = $request['national_id'] ?? '---';
$exportNumber   = $request['export_number'] ?? '---';
$rawIssueDate   = $request['created_at'] ?? date('Y-m-d');
$hijriDate      = convertToHijri($rawIssueDate);
$issuingAuth    = $request['issuing_authority'] ?? 'وزارة الداخلية';
$status         = $request['status'] ?? '---';

$related_partners = $request['related_partners'] ?? $request['related_members'] ?? [];

$assetsUrl = getInquiryAsset('');

$page_title = "وزارة الداخلية - المملكة العربية السعودية - تفاصيل تصريح الزواج";
$breadcrumbs = [
    ['label' => 'الاستعلامات الإلكترونية', 'url' => '#'],
    ['label' => 'تصاريح الزواج', 'url' => '#'],
    ['label' => 'الاستفسار عن طلبات تصاريح الزواج']
];

$containerWidth = "1170px";
include __DIR__ . '/../core/inquiry_header.php';
?>

    <!-- Main Content: PermitDetails Component -->
    <main class="w-full bg-white flex justify-center">
        <div class="w-full max-w-[<?= $containerWidth ?>] mx-auto mt-0 mb-4 px-4">
          
          <!-- Page Title Bar -->
          <div class="border border-gray-200 bg-[#fbfbfb] px-4 py-2 mt-4 mb-4 text-[#444444] font-ge-medium text-base-custom shadow-[0_1px_2px_rgba(0,0,0,0.02)] no-print">
               الاستفسار عن طلبات تصاريح الزواج
          </div>

          <div class="bg-white border-2 border-gray-200 shadow-sm rounded-t-lg overflow-hidden" id="printable-area" style="border: 1px solid #e5e7eb !important;">
            
            <!-- Card Header -->
            <div class="bg-[#f5f5f5] px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-black font-ge-light text-lg-custom">
                    تفاصيل  | |  <?= htmlspecialchars($fullName) ?>
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
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">تاريخ الإصدار :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($hijriDate) ?></span>
                </div>
                 <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">الجهة المصدرة :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($issuingAuth) ?></span>
                </div>
            </div>

            <!-- Data Table -->
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm-custom table-stack-moi">
                    <thead>
                        <tr class="bg-[#f9f9f9] text-gray-600 font-ge-medium border-b border-t border-gray-200">
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium" style="width: 100px;">الرقم التسلسلي</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium" style="min-width: 150px; white-space: normal; word-wrap: break-word;">اسم الزوج/ة</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium" style="white-space: normal; word-wrap: break-word;">فئة المهنة</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الجنسية</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">النوع</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium" style="white-space: normal; word-wrap: break-word;">مكان القدوم</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium text-nowrap">الحالة</th>
                            <th class="py-3 px-2 font-ge-medium" style="white-space: normal; word-wrap: break-word;">رقم الملف المرسل للبنك</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($related_partners)): ?>
                            <?php foreach ($related_partners as $item): ?>
                             <tr class="border-b border-gray-200 hover:bg-gray-50 text-gray-700 font-ge-light">
                                 <td class="py-4 px-2" data-label="الرقم التسلسلي"><?= htmlspecialchars($request['serial_number'] ?? $request['id'] ?? '---') ?></td>
                                 <td class="py-4 px-2" style="font-weight: bold;" data-label="اسم الزوج/ة"><?= htmlspecialchars($item['full_name'] ?? '---') ?></td>
                                 <td class="py-4 px-2" data-label="فئة المهنة"><?= (trim((string)($item['job_category'] ?? '')) !== '' ? htmlspecialchars($item['job_category']) : '---') ?></td>
                                 <td class="py-4 px-2" data-label="الجنسية"><?= (trim((string)($item['nationality'] ?? '')) !== '' ? htmlspecialchars($item['nationality']) : '---') ?></td>
                                 <td class="py-4 px-2" data-label="النوع"><?= (trim((string)($item['permit_type'] ?? '')) !== '' ? htmlspecialchars($item['permit_type']) : ($request['permit_type'] ?? '---')) ?></td>
                                 <td class="py-4 px-2" data-label="مكان القدوم"><?= (trim((string)($item['country'] ?? '')) !== '' ? htmlspecialchars($item['country']) : '---') ?></td>
                                 <td class="py-4 px-2" data-label="الحالة">
                                     <?= get_status_badge($item['status'] ?? '---') ?>
                                 </td>
                                 <td class="py-4 px-2" data-label="رقم الملف"><?= (trim((string)($request['bank_file_number'] ?? '')) !== '' ? htmlspecialchars($request['bank_file_number']) : '0') ?></td>
                             </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="border-b border-gray-200">
                                <td colspan="8" class="py-4">لا توجد بيانات</td>
                            </tr>
                        <?php endif; ?>
                        
                        <tr class="border-b border-gray-400">
                            <td colSpan="8" class="h-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3 py-4 pb-6 no-print font-ge-light text-base-custom" id="actions-bar">
                <button onclick="window.print()" id="btn-print"
                    class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>'"
                    class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> إنهاء
                </button>
            </div>

            <script>
            /**
             * تحميل النموذج كاملاً PDF باستخدام html2canvas و jsPDF
             */
            async function downloadPDF() {
                const wrapper = document.getElementById('printable-area');
                const btn     = document.getElementById('btn-download-pdf');
                if (!wrapper) { alert('تعذر إيجاد محتوى النموذج.'); return; }

                const origHTML = btn ? btn.innerHTML : '';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                }

                try {
                    window.scrollTo(0, 0);
                    await document.fonts.ready;
                    const images = Array.from(wrapper.querySelectorAll('img'));
                    await Promise.all(images.map(img => {
                        if (img.complete) return Promise.resolve();
                        return new Promise(r => { img.onload = r; img.onerror = r; setTimeout(r, 3000); });
                    }));

                    await new Promise(r => setTimeout(r, 1000));

                    const opt = {
                        scale: 2, 
                        useCORS: true,
                        allowTaint: true,
                        logging: false,
                        backgroundColor: '#ffffff',
                        windowWidth: 1400,
                        onclone: (clonedDoc) => {
                            const cpWrapper = clonedDoc.getElementById('printable-area');
                            if (cpWrapper) {
                                cpWrapper.style.width = '1300px'; 
                                cpWrapper.style.margin = '0 auto';
                                cpWrapper.style.padding = '0';
                                cpWrapper.style.backgroundColor = '#ffffff';
                            }
                            
                            clonedDoc.body.style.backgroundColor = '#ffffff';

                            const hideEls = clonedDoc.querySelectorAll('#actions-bar, button, .no-pdf, .top-links');
                            hideEls.forEach(el => el.style.setProperty('display', 'none', 'important'));

                            const footer = clonedDoc.querySelector('footer');
                            if (footer) {
                                footer.style.setProperty('display', 'flex', 'important');
                                footer.classList.remove('no-print');
                            }

                            const style = clonedDoc.createElement('style');
                            style.innerHTML = `
                                * { font-family: 'GE SS Two', sans-serif !important; }
                                .no-print { display: none !important; }
                            `;
                            clonedDoc.head.appendChild(style);
                        }
                    };

                    const canvas = await html2canvas(wrapper, opt);
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const { jsPDF } = window.jspdf;
                    
                    const pdf = new jsPDF({
                        orientation: 'p',
                        unit: 'mm',
                        format: 'a4',
                        compress: true
                    });

                    const pdfWidth = 210;
                    const pdfHeight = 297;
                    const margin = 12;
                    const innerWidth = pdfWidth - (margin * 2);
                    const innerHeight = (canvas.height * innerWidth) / canvas.width;

                    let finalWidth = innerWidth;
                    let finalHeight = innerHeight;

                    if (finalHeight > (pdfHeight - (margin * 2))) {
                        finalHeight = pdfHeight - (margin * 2);
                        finalWidth = (canvas.width * finalHeight) / canvas.height;
                    }

                    const x = (pdfWidth - finalWidth) / 2;
                    const y = margin;

                    pdf.addImage(imgData, 'JPEG', x, y, finalWidth, finalHeight, undefined, 'FAST');
                    pdf.save('marriage_permit_<?= htmlspecialchars($request["issue_number"] ?? "result") ?>.pdf');

                } catch (err) {
                    console.error('PDF Error:', err);
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = origHTML;
                    }
                }
            }

            // Automatic print trigger if parameter 'print' is set
            if (window.location.search.includes('print=true')) {
                window.onload = function() {
                    setTimeout(() => {
                        window.print();
                    }, 500);
                }
            }
            </script>
            <script src="<?php echo $assetsUrl; ?>js/JsBarcode.all.min.js"></script>
            <script>
            try {
                JsBarcode("#barcode", "<?= $request['export_number'] ?? '0000000000' ?>", {
                    format: "CODE128",
                    width: 1.5,
                    height: 30,
                    displayValue: true,
                    margin: 0
                });
            } catch(e) { console.error("Barcode error:", e); }
            </script>

             <!-- Legend Table -->
            <div class="border-t border-gray-300">
                 <div class="grid grid-cols-1 sm:grid-cols-12 bg-[#f5f5f5] text-black text-sm-custom font-ge-light border-b border-gray-300">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-2 px-4 text-right font-ge-medium">حالة التصريح</div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-2 px-4 text-center border-r sm:border-r border-[#ccc] bg-[#eee] sm:bg-transparent">التفاصيل</div>
                 </div>
                 
                 <!-- Row 1: Used -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         تم استخدامها
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها إحدى الحالات التالية :<br>
                         تم استخدام هذا التصريح<br>
                         لم يتم الإستخدام وقد تم إنهاء إجراءات الدخول إلى المملكة من السفارة ويلزم مراجعة وزارة الخارجية لتصحيح وضعها أو إلغائها
                     </div>
                 </div>

                 <!-- Row 2: Approved -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         تمت الموافقة
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها أن التصاريح صالحة ويمكن الإستفادة منها وإذا رغبت في إلغائها يلزم مراجعة وزارة الداخلية لإلغائها
                     </div>
                 </div>

                 <!-- Row 3: Expired -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         انتهت صلاحيتها
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها انتهاء مدة الصلاحية(سنتين)، وبعد مضي 100 يوم من انتهاء صلاحيتها ستتغير العبارة إلى (تم استعادة رسومها). وفي حالة عدم تغير العبارة يمكنك تسجيل طلب عبر (آمر) على الرقم (199099) أو الدخول على موقعهم على الإنترنت.
                     </div>
                 </div>

                 <!-- Row 4: Cancelled -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         تم إلغاؤها
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها أنه تم إلغاؤها وينبغي الانتظار ومتابعتها حتى تتغير حالتها إلى (تم استعادة رسومها). وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 5: Cancellation Sent -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         تم إرسال طلب الإلغاء لوزارة الخارجية ولم يصل الرد
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها أنه تم إرسال طلب الإلغاء لوزارة الخارجية وما يزال تحت الإجراء، وينبغي الانتظار ومتابعتها على موقع وزارة الخارجية حتى تتغير حالتها إلى (تم استعادة رسومها)، وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 6: Refunded -->
                 <div class="grid grid-cols-1 sm:grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-1 sm:col-span-4 md:col-span-3 py-3 px-4 font-ge-medium text-gray-900 flex items-start break-words border-b sm:border-b-0 border-gray-100">
                         تم استعادة رسومها
                     </div>
                     <div class="col-span-1 sm:col-span-8 md:col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r-0 sm:border-r border-[#ccc]">
                         يقصد بها أنه تم استرجاع المبلغ إلى الحساب المسدد منه، وإذا رغبت في الحصول على (رقم هوية المسدد، واسم البنك، وتاريخ رجوع المبلغ) قم بالاتصال بمركز (آمر) على الرقم (199099) مع تجهيز رقم الهوية ورقم التأشيرة. أما في حالة سداد مبلغ التأشيرة نقدا فيتم مراجعة البنك مباشرة لاستلامه.
                     </div>
                 </div>
            </div>

          </div>
        </div>
    </main>

<?php include __DIR__ . '/../core/inquiry_footer.php'; ?>
<?php include __DIR__ . '/../core/inquiry_end.php'; ?>
<?php
