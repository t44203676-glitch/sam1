<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login&error=unauthorized');
    exit;
}

// تضمين الملفات الأساسية
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/logger.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/two_step_form_handler.php'; // تضمين المعالج المركزي

// ==================================================================
// 1. منطق معالجة النموذج
// ==================================================================

$formType = 'civil_affairs';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
// تهيئة المتغيرات إذا لم تكن موجودة بالفعل (يتم توفيرها عادة بواسطة add_data.php)
if (!isset($errors)) $errors = [];
if (!isset($form_data)) $form_data = [];

// استعادة بيانات النموذج والأخطاء من الجلسة في حالة حدوث خطأ في التحقق (فقط إذا لم يتم جلبها بالفعل)
if (empty($form_data) && isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    // لا نحذفها هنا، add_data.php سيهتم بذلك أو نتركها للنموذج
}
if (empty($errors) && isset($_SESSION['form_errors'])) {
    $errors = $_SESSION['form_errors'];
}

$isEdit = (isset($mode) && $mode === 'edit');
$isEmployee = (isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['موظف', 'Employee']));
$disableProtected = ($isEdit && $isEmployee);

// تهيئة بيانات النموذج الرئيسي (مع استعادة البيانات من الجلسة إن وجدت)
$applicantName = $form_data['applicant_name'] ?? '';
$nationalId = $form_data['national_id'] ?? '';
$phone = $form_data['phone'] ?? '';
$exportNumber = $form_data['export_number'] ?? '';
$transactionNumber = $form_data['transaction_number'] ?? '';
$nationality = $form_data['nationality'] ?? '';
$issueDate = $form_data['issue_date'] ?? '';
$issuingAuthority = $form_data['issuing_authority'] ?? 'وزارة الداخلية - الأحوال المدنية';

// تهيئة بيانات الفرد المضاف
$jobCategory = '';
$status = '';
$appointmentDate = 'إلى تاريخ غير محدد';

// خيارات الإمارة
$emirates_options = [
    '' => 'اختر الإمارة...',
    'إمارة منطقة الرياض' => 'إمارة منطقة الرياض',
    'إمارة منطقة مكة المكرمة' => 'إمارة منطقة مكة المكرمة',
    'إمارة منطقة المدينة المنورة' => 'إمارة منطقة المدينة المنورة',
    'إمارة المنطقة الشرقية' => 'إمارة المنطقة الشرقية',
    'إمارة منطقة الجوف' => 'إمارة منطقة الجوف',
    'إمارة منطقة الباحة' => 'إمارة منطقة الباحة',
    'إمارة منطقة عسير' => 'إمارة منطقة عسير',
    'إمارة منطقة القصيم' => 'إمارة منطقة القصيم',
    'إمارة منطقة حائل' => 'إمارة منطقة حائل',
    'إمارة منطقة تبوك' => 'إمارة منطقة تبوك',
    'إمارة منطقة الحدود الشمالية' => 'إمارة منطقة الحدود الشمالية',
    'إمارة منطقة جازان' => 'إمارة منطقة جازان',
    'إمارة منطقة نجران' => 'إمارة منطقة نجران'
];

// تعريف إعدادات الخدمات
$serviceConfig = [
    'marriage'             => ['prefix' => '0', 'table' => 'marriage_permits', 'title' => 'تصريح زواج'],
    'family_visit'         => ['prefix' => '1', 'table' => 'family_visits', 'title' => 'زيارة عائلية'],
    'tourism'              => ['prefix' => '2', 'table' => 'tourism_visits', 'title' => 'زيارة سياحية'],
    'business_visit'       => ['prefix' => '3', 'table' => 'business_visits', 'title' => 'زيارة تجارية'],
    'labor'                => ['prefix' => '4', 'table' => 'labor_requests', 'title' => 'عمالة'],
    'runaway_cancellation' => ['prefix' => '5', 'table' => 'runaway_cancellations', 'title' => 'إلغاء بلاغ هروب'],
    'profession_change'    => ['prefix' => '6', 'table' => 'profession_changes', 'title' => 'تغيير مهنة'],
    'civil_affairs'        => ['prefix' => '1900', 'table' => 'civil_affairs_requests', 'title' => 'أحوال مدنية'],
    'recruitment'          => ['prefix' => '8', 'table' => 'recruitment_requests', 'title' => 'استقدام'],
];

// ... (existing variables)

// توليد رقم صادر فريد
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && defined('USE_DATABASE') && USE_DATABASE && isset($pdo)) {
    if (isset($serviceConfig[$formType]['prefix'])) {
        $exportNumber = generate_export_number($serviceConfig[$formType]['prefix'], $serviceConfig[$formType]['table']);
    }
}

// يتم الآن جلب البيانات تلقائيًا بواسطة two_step_form_handler.php
// لا حاجة لاستدعاء fetch_step2_data() هنا.
// جلب بيانات الطلب الرئيسي والأفراد للخطوة 2
$request_details = null;
$related_persons = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, $serviceConfig[$formType]['table']);
    $request_details = $data['request_details'];
    $related_persons = $data['related_items'];
}

?>
<div class="container my-5">
    <!-- مؤشر الخطوات -->
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>نموذج أحوال مدنية</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>إضافة بيانات الأفراد</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="civil_affairsForm">
        <h1 class="text-center mb-4"><i class="fas fa-id-card text-primary me-2"></i>نموذج <?php echo $serviceConfig['civil_affairs']['title']; ?></h1>
        
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger shadow-sm border-start border-danger border-4 mb-4">
            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> هناك أخطاء في البيانات المدخلة:</h6>
            <ul class="mb-0 fs-14">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="multiStepForm">
            <input type="hidden" name="formType" value="civil_affairs">
            <input type="hidden" name="action" value="add_civil_affairs">

            <!-- الخطوة 1أ: اسم مقدم الطلب ورقم الهوية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: البيانات الأساسية</h5>
                <div class="row g-3">
                    <?php if ($disableProtected): ?>
                        <div class="col-md-12">
                            <?php render_form_group('رقم الصادر (غير قابل للتعديل)', 'export_number_display', 'text', '', $exportNumber, ['disabled' => true]); ?>
                            <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($exportNumber); ?>">
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($exportNumber); ?>">
                    <?php endif; ?>

                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('الاسم الكامل', 'applicant_name', 'text', 'أدخل الاسم الكامل كما في الهوية', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم الهوية', 'national_id', 'text', 'أدخل رقم الهوية (10 أرقام)', $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الجوال', 'phone', 'text', '05xxxxxxxx', $phone, $disableProtected ? ['disabled' => true] : [], $errors['phone'] ?? null, true); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php?admin=1" class="btn btn-outline-secondary btn-lg btn-flex shadow-sm px-4 text-nowrap" style="border-radius: 30px;">
                        <i class="fas fa-th-large"></i> القائمة الرئيسية
                    </a>
                    <button type="button" id="btnNext1" class="btn btn-primary btn-lg btn-flex shadow-sm px-5 text-nowrap" onclick="showStep1b()" style="border-radius: 30px;">
                        التالي <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>

            <!-- الخطوة 1ب: بقية البيانات -->
            <div id="step1b" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-info border-4" style="display: none;">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل المعاملة</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('رقم المعاملة', 'transaction_number', 'text', 'أدخل رقم المعاملة', $transactionNumber, [], $errors['transaction_number'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3" style="position: relative;">
                            <label for="civilAffairsNationalityInput" class="form-label fw-bold mb-1" style="font-size: 0.95rem;">الجنسية <span class="text-danger">*</span></label>
                            <input type="text" id="civilAffairsNationalityInput" name="nationality" class="form-control shadow-sm <?php echo isset($errors['nationality']) ? 'error-input' : ''; ?>" placeholder="ابحث عن الجنسية" value="<?php echo htmlspecialchars($nationality); ?>" required autocomplete="off">
                            <div id="civilAffairsNationalityList" class="dropdown-list"></div>
                            <div class="invalid-feedback d-block" style="min-height: 1.25em; font-size: 0.85rem;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الإصدار', 'issue_date', 'text', 'YYYY/MM/DD', $issueDate, [], $errors['issue_date'] ?? null, true, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('الجهة المصدرة', 'issuing_authority', 'text', '', $issuingAuthority, ['readonly' => true], $errors['issuing_authority'] ?? null); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-lg btn-flex px-4" onclick="showStep1a()" style="border-radius: 30px;">
                        <i class="fas fa-chevron-right"></i> الخطوة السابقة
                    </button>
                    <button type="submit" class="btn btn-success btn-lg btn-flex px-5 shadow" style="border-radius: 30px;">
                        حفظ ومتابعة <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    function showStep1b() {
        if (!window.isSectionValid('step1a')) return;
        
        const btn = document.getElementById('btnNext1');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري التحقق...';

        setTimeout(() => {
            document.getElementById('step1a').style.display = 'none';
            document.getElementById('step1b').style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = 'التالي <i class="fas fa-chevron-left ms-2"></i>';
            window.scrollTo(0,0);
        }, 400);
    }
    function showStep1a() {
        document.getElementById('step1b').style.display = 'none';
        document.getElementById('step1a').style.display = 'block';
        window.scrollTo(0,0);
    }
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof window.initializeNationalitySearch === 'function') {
            window.initializeNationalitySearch(
                'civilAffairsNationalityInput',
                'civilAffairsNationalityList',
                null,
                null
            );
        }
    });
    </script>
<?php elseif ($currentStep == 2): ?>
    <div class="form-container" id="relatedPersonForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">بيانات الأفراد (حجز موعد)</h4>
            <button type="button" class="btn btn-circle btn-primary" id="togglePersonFormBtn" title="إضافة فرد جديد">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <div id="addPersonFormContainer" class="form-collapsible">
            <form id="addPersonForm" method="POST">
                <input type="hidden" name="request_id" value="<?php echo (int)$_GET['request_id']; ?>">
                <div class="marriage-form">
                    <?php 
                    render_form_group('المهنة', 'job_category', 'text', 'أدخل المهنة', $jobCategory, [], null, true); 
                    render_form_group('الحالة', 'status', 'text', 'أدخل الحالة', $status, [], null, true); 
                    render_form_group('حجز موعد', 'appointment_date', 'text', 'YYYY/MM/DD أو أدخل نص (مثل: إلى تاريخ غير محدد)', $appointmentDate, ['data-default-value' => 'إلى تاريخ غير محدد'], null, false);
                    ?>
                    <div class="button-group w-100 mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary ms-2" data-add-text="إضافة فرد">إضافة فرد</button>
                        <button type="reset" class="btn btn-secondary">إلغاء</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive mt-5">
            <h4 class="text-center mb-3">الأفراد المضافون</h4>
            <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead class="table-light">
                    <tr>
                        <th>الرقم التسلسلي</th>
                        <th>المهنة</th>
                        <th>الحالة</th>
                        <th>حجز موعد</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_persons)): ?>
                        <?php foreach ($related_persons as $person): ?>
                            <tr>
                                <td data-label="الرقم التسلسلي"><?php echo convertToArabicNumerals('11' . str_pad($person['id'] % 100000000, 8, '0', STR_PAD_LEFT)); ?></td>
                                <td data-label="المهنة"><?php echo htmlspecialchars($person['job_category'] ?? '---'); ?></td>
                                <td data-label="الحالة"><?php echo htmlspecialchars($person['status'] ?? '---'); ?></td>
                                <td data-label="حجز موعد"><?php echo convertToArabicNumerals($person['appointment_date'] ?? '---'); ?></td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" title="تعديل" onclick="populateFormForEdit(<?php echo htmlspecialchars(json_encode($person), ENT_QUOTES, 'UTF-8'); ?>, 'civil_affairs')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('civil_affairs', <?php echo htmlspecialchars($_GET['request_id']); ?>, <?php echo $person['id']; ?>, this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><a href="index.php?page=add_data" class="btn btn-info">العودة الى قائمة الخدمات</a></div>
    </div>
<?php endif; ?>
</div>

<script>
// --- Photo Upload Logic ---
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photo-preview');
    const saveBtn = document.getElementById('save-photo-btn');
    const uploadForm = document.getElementById('uploadPhotoForm');
    const defaultAvatar = 'public/images/default-avatar.png';

    if (!photoInput || !photoPreview || !saveBtn || !uploadForm) return;

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.style.backgroundImage = `url('${e.target.result}')`;
            }
            reader.readAsDataURL(file);
            saveBtn.style.display = 'inline-block'; // إظهار زر الحفظ
        }
    });

    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جارٍ الحفظ...';

        fetch('api/upload_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                photoPreview.style.backgroundImage = `url('${data.filePath}')`; // تحديث المعاينة بالصورة الجديدة
                saveBtn.style.display = 'none'; // إخفاء الزر بعد النجاح
            } else {
                showToast('فشل رفع الصورة: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الشبكة.', 'error');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'حفظ الصورة';
        });
    });
});

(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCivilAffairsForm);
    } else {
        initCivilAffairsForm();
    }
    
    function initCivilAffairsForm() {
    // --- Logic for toggling the person form ---
    const toggleBtn = document.getElementById('togglePersonFormBtn');
    const formContainer = document.getElementById('addPersonFormContainer');

    // --- Centralized Toggle Logic ---
    if (toggleBtn && formContainer) {
        const formId = 'civil_affairs'; // Unique ID for localStorage

        formContainer.addEventListener('transitionend', () => {
            if (formContainer.style.maxHeight !== '0px') {
                formContainer.style.overflow = 'visible';
            }
        });

        function toggleForm(open) {
            const icon = toggleBtn.querySelector('i');
            formContainer.style.overflow = 'hidden';
            if (open) {
                formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                icon.classList.replace('fa-plus', 'fa-minus');
                toggleBtn.title = 'إخفاء النموذج';
                localStorage.setItem(formId + 'FormState', 'open');
            } else {
                formContainer.style.maxHeight = '0px';
                icon.classList.replace('fa-minus', 'fa-plus');
                toggleBtn.title = 'إضافة فرد جديد';
                localStorage.setItem(formId + 'FormState', 'closed');
            }
        }

        // On page load, check state. Default to OPEN.
        if (localStorage.getItem(formId + 'FormState') === 'closed') {
            toggleForm(false);
        } else {
            toggleForm(true);
        }

        toggleBtn.addEventListener('click', function() {
            const isCollapsed = formContainer.style.maxHeight === '0px' || formContainer.style.maxHeight === '';
            toggleForm(isCollapsed);
        });

        // Handle form submission via AJAX
        const addPersonForm = document.getElementById('addPersonForm');
        if (addPersonForm) {
            addPersonForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('civil_affairs', this);
            });
        }
    }
    }

})();
</script>
<style>
    .photo-preview-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        background-color: #e9ecef;
        border: 2px solid #dee2e6;
    }
</style>
