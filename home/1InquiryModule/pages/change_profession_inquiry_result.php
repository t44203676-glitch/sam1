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
$establishmentName = $request['establishment_name'] ?? $request['applicant_name'] ?? '---';
$sponsorIdId     = $request['sponsor_id'] ?? '---';
$serviceNumber   = $request['service_number'] ?? $request['export_number'] ?? '---';
$rawIssueDate    = $request['updated_at'] ?? $request['created_at'] ?? date('Y-m-d');
$hijriDate       = convertToHijri($rawIssueDate);
$issuingAuth     = $request['issuing_authority'] ?? 'وزارة العمل-الي';
$status          = $request['status'] ?? 'بانتظار الموافقة';

// Related data for Profession Change
$related_data = $request['related_data'] ?? $request['related_partners'] ?? [];
$assetsUrl = getInquiryAsset('');

$page_title = "وزارة الداخلية - المملكة العربية السعودية - الاستفسار عن صلاحية الإقامة";
$breadcrumbs = [
    ['label' => 'الاستعلامات الإلكترونية', 'url' => '#'],
    ['label' => 'الجوازات', 'url' => '#'],
    ['label' => 'الاستفسار عن صلاحية الإقامة للعمالة الوافدة']
];

include __DIR__ . '/../core/inquiry_header.php';
?>

    <!-- Main Content: PermitDetails Component -->
    <main class="w-full bg-white flex justify-center">
        <div class="w-full max-w-[<?= $containerWidth ?? '1170px' ?>] mx-auto mt-2 mb-4 px-4">
          
          <!-- Page Title Bar -->
          <div class="border border-gray-200 bg-[#fbfbfb] px-4 py-2 mb-2 text-[#444444] font-ge-light text-base-custom shadow-[0_1px_2px_rgba(0,0,0,0.02)] no-print">
               الإستفسار عن صلاحية الإقامة
          </div>

          <div class="bg-white border border-gray-300 shadow-sm rounded-t-lg overflow-hidden" id="printable-area">
            
            <!-- Card Header -->
            <div class="bg-[#f5f5f5] px-4 py-3 border-b border-gray-200">
                <h3 class="text-black font-ge-light text-lg-custom">
                    تفاصيل العمالة الوافدة || <?= htmlspecialchars($establishmentName) ?>
                </h3>
            </div>

            <!-- Top Info Grid -->
            <div class="p-6 grid grid-cols-1 print:grid-cols-2 md:grid-cols-2 gap-y-4 gap-x-12 text-base-custom text-gray-700 font-ge-light border-b border-gray-200">
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">رقم هوية الكفيل :</span>
                    <span class="font-ge-light"><?= htmlspecialchars(toWesternDigits($sponsorIdId)) ?></span>
                </div>
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">رقم المصدر :</span>
                    <span class="font-ge-light"><?= htmlspecialchars(toWesternDigits($serviceNumber)) ?></span>
                </div>
                <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">تاريخ آخر تعديل :</span>
                    <span class="font-ge-light"><?= htmlspecialchars(toWesternDigits($hijriDate)) ?></span>
                </div>
                 <div class="flex justify-between print:justify-start md:justify-start gap-2">
                    <span class="text-black font-ge-light">الجهة المصدرة :</span>
                    <span class="font-ge-light"><?= htmlspecialchars($issuingAuth) ?></span>
                </div>
            </div>


            <!-- Data Table -->
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm-custom">
                    <thead>
                        <tr class="bg-[#f9f9f9] text-gray-600 font-ge-medium border-b border-t border-gray-200">
                             <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الإسم</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">رقم الإقامة</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">الجنسية</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">المهنة السابقة</th>
                            <th class="py-3 px-2 border-l border-gray-100 font-ge-medium">المهنة الجديدة</th>
                            <th class="py-3 px-2 font-ge-medium">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($related_data)): ?>
                             <?php foreach ($related_data as $item): ?>
                              <tr class="border-b border-gray-200 hover:bg-gray-50 text-gray-700 font-ge-light">
                                 <td class="py-4 px-2"><?= htmlspecialchars($item['full_name'] ?? '---') ?></td>
                                 <td class="py-4 px-2"><?= htmlspecialchars(toWesternDigits($item['national_id'] ?? '---')) ?></td>
                                 <td class="py-4 px-2"><?= htmlspecialchars($item['nationality'] ?? '---') ?></td>
                                 <td class="py-4 px-2"><?= htmlspecialchars($item['old_profession'] ?? '---') ?></td>
                                 <td class="py-4 px-2"><?= htmlspecialchars($item['job_category'] ?? '---') ?></td>
                                 <td class="py-4 px-2">
                                     <?= get_status_badge($item['status'] ?? $status) ?>
                                 </td>
                             </tr>
                             <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="border-b border-gray-200">
                                 <td colspan="6" class="py-4">لا توجد بيانات</td>
                             </tr>
                         <?php endif; ?>
                         
                         <tr class="border-b border-gray-400">
                             <td colSpan="6" class="h-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center gap-3 py-4 pb-6 no-print font-ge-light text-base-custom" id="actions-bar">
                <button onclick="window.print()" id="btn-print"
                    class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>'" class="bg-[#00AB67] hover:bg-[#008f56] text-white px-8 py-2 rounded shadow-sm transition-colors min-w-[120px] flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> إنهاء
                </button>
            </div>

             <!-- Legend Table -->
            <div class="border-t border-gray-300">
                 <div class="grid grid-cols-12 bg-[#f5f5f5] text-black text-sm-custom font-ge-light py-2 px-4 border-b border-gray-300">
                     <div class="col-span-3 text-right pr-4">حالة الإقامة</div>
                     <div class="col-span-9 text-center border-r border-[#ccc]">التفاصيل</div>
                 </div>
                 
                 <!-- Row 1: Valid -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
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
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
                         تمت الموافقة
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أن التأشيرة صالحة ويمكن الاستقدام عليها وإذا رغبت في إلغائها يلزم مراجعة مكتب الاستقدام أو مكتب العمل التابع لوزارة العمل لإلغائها
                     </div>
                 </div>

                 <!-- Row 3: Expired -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
                         انتهت صلاحيتها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها انتهاء مدة التأشيرة (سنتين)، وبعد مضي 100 يوم من انتهاء صلاحيتها ستتغير العبارة إلى (تم استعادة رسومها). وفي حالة عدم تغير العبارة يمكنك تسجيل طلب عبر (آمر) على الرقم (199099) أو الدخول على موقعهم على الإنترنت.
                     </div>
                 </div>

                 <!-- Row 4: Cancelled -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-[#f4f8fa] font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
                         تم إلغاؤها
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم إلغاؤها من مكتب الاستقدام أو مكتب العمل، وينبغي الانتظار ومتابعتها حتى تتغير حالتها إلى (تم استعادة رسومها). وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
                     </div>
                 </div>

                 <!-- Row 5: Cancellation Sent -->
                 <div class="grid grid-cols-12 text-sm-custom border-b border-gray-200 bg-white font-ge-light">
                     <div class="col-span-3 py-3 pr-4 font-ge-medium text-gray-900 flex items-start">
                         تم إرسال طلب الإلغاء لوزارة الخارجية ولم يصل الرد
                     </div>
                     <div class="col-span-9 py-3 px-4 text-gray-600 leading-relaxed border-r border-[#ccc]">
                         يقصد بها أنه تم إرسال طلب الإلغاء لوزارة الخارجية وما يزال تحت الإجراء، وينبغي الانتظار ومتابعتها على موقع وزارة الخارجية حتى تتغير حالتها إلى (تم استعادة رسومها)، وإذا لم تتغير حالتها بعد مضي (15) يوما يلزم مراجعة وزارة الخارجية.
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
        </div>
    </main>

    <script>
    /**
     * تحميل النموذج كاملاً PDF باستخدام html2canvas و jsPDF
     */
    async function downloadPDF() {
        const wrapper = document.getElementById('printable-area');
        const btn     = document.querySelector('#actions-bar button');
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
                            style.innerHTML = `* { font-family: 'GE SS Two', sans-serif !important; } .no-print { display: none !important; }`;
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
            pdf.save('change_profession_result_<?= htmlspecialchars($request["issue_number"] ?? "report") ?>.pdf');

        } catch (err) {
            console.error('PDF Error:', err);
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHTML;
            }
        }
    }
    </script>
<?php include __DIR__ . '/../core/inquiry_footer.php'; ?>
<?php include __DIR__ . '/../core/inquiry_end.php'; ?>