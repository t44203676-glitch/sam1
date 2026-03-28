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

$formType = 'recruitment';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$errors = [];

// تعريف إعدادات الخدمة (للتوافق مع الطريقة الموحدة)
$serviceConfig = [
    'marriage' => ['prefix' => '0', 'table' => 'marriage_permits', 'title' => 'تصريح زواج'],
    'family_visit' => ['prefix' => '1', 'table' => 'family_visits', 'title' => 'زيارة عائلية'],
    'tourism' => ['prefix' => '2', 'table' => 'tourism_visits', 'title' => 'زيارة سياحية'],
    'business_visit' => ['prefix' => '3', 'table' => 'business_visits', 'title' => 'زيارة تجارية'],
    'labor' => ['prefix' => '4', 'table' => 'labor_requests', 'title' => 'عمالة'],
    'runaway_cancellation' => ['prefix' => '5', 'table' => 'runaway_cancellations', 'title' => 'إلغاء بلاغ هروب'],
    'profession_change' => ['prefix' => '6', 'table' => 'profession_changes', 'title' => 'تغيير مهنة'],
    'civil_affairs' => ['prefix' => '7', 'table' => 'civil_affairs_requests', 'title' => 'أحوال مدنية'],
    'recruitment' => ['prefix' => '8', 'table' => 'recruitment_requests', 'title' => 'استقدام'],
];

// استعادة بيانات النموذج والأخطاء من الجلسة في حالة حدوث خطأ في التحقق
// تهيئة المتغيرات إذا لم تكن موجودة بالفعل
if (!isset($errors)) $errors = [];
if (!isset($form_data)) $form_data = [];

// استعادة بيانات النموذج والأخطاء من الجلسة
if (empty($form_data) && isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
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
$serviceNumber = $form_data['service_number'] ?? '';
$serviceDesc = $form_data['service_desc'] ?? '';
$hijriDate = $form_data['hijri_date'] ?? '';
$emirate = $form_data['emirate'] ?? '';
$approvalDate = $form_data['approval_date'] ?? '';
$approvalTime = $form_data['approval_time'] ?? '';
$attachments = $form_data['attachments'] ?? 0;
$recordNumber = $form_data['record_number'] ?? '';
$issuanceNumber = $form_data['issuance_number'] ?? '';
$submissionDate = $form_data['submission_date'] ?? '';
$area = $form_data['area'] ?? '';
$areaCode = $form_data['area_code'] ?? '';
$remarks = $form_data['remarks'] ?? '';
$issuingAuthority = $form_data['issuing_authority'] ?? 'وزارة الداخلية-الي';

// تهيئة بيانات الشخص المستقدم
$fullName = '';
$passportNumber = '';
$jobCategory = '';
$birthDate = '';
$nationality = '';
$arrivalPlace = '';

// خيارات الإمارة (مكاتب الاستقدام)
$emirates_options = [
    '' => 'اختر مكتب الاستقدام...',
    'استقدام منطقة الرياض' => 'استقدام منطقة الرياض',
    'استقدام منطقة مكة المكرمة' => 'استقدام منطقة مكة المكرمة',
    'استقدام منطقة المدينة المنورة' => 'استقدام منطقة المدينة المنورة',
    'استقدام المنطقة الشرقية' => 'استقدام المنطقة الشرقية',
    'استقدام منطقة الجوف' => 'استقدام منطقة الجوف',
    'استقدام منطقة الباحة' => 'استقدام منطقة الباحة',
    'استقدام منطقة عسير' => 'استقدام منطقة عسير',
    'استقدام منطقة القصيم' => 'استقدام منطقة القصيم',
    'استقدام منطقة حائل' => 'استقدام منطقة حائل',
    'استقدام منطقة تبوك' => 'استقدام منطقة تبوك',
    'استقدام المنطقة الشمالية' => 'استقدام المنطقة الشمالية',
    'استقدام منطقة جازان' => 'استقدام منطقة جازان',
    'استقدام منطقة نجران' => 'استقدام منطقة نجران'
];

// توليد رقم صادر فريد
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && empty($exportNumber) && defined('USE_DATABASE') && USE_DATABASE && isset($pdo) && isset($serviceConfig[$formType])) {
    $prefix = $serviceConfig[$formType]['prefix']; // '8' for recruitment
    $tableName = $serviceConfig[$formType]['table']; // 'recruitment_requests'
    do {
        $nineDigits = substr(str_shuffle(str_repeat('0123456789', 9)), 0, 9);
        $exportNumber = $prefix . $nineDigits;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$tableName}` WHERE export_number = ?");
        $stmt->execute([$exportNumber]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
}

// يتم الآن جلب البيانات تلقائيًا بواسطة two_step_form_handler.php
// لا حاجة لاستدعاء fetch_step2_data() هنا.
// معالجة عمليات الخطوة 2 (إضافة/حذف) باستخدام المعالج المركزي
handle_step2_operations($formType, $currentStep, $pdo);

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
        <span>نموذج استقدام</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>إضافة بيانات المستقدمين</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="recruitment_form">
        <h1 class="text-center mb-4"><i class="fas fa-users-cog text-primary me-2"></i>نموذج <?php echo $serviceConfig['recruitment']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="recruitment">
            <input type="hidden" name="action" value="add_recruitment_request">

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
                        <?php render_form_group('اسم مقدم الطلب', 'applicant_name', 'text', 'أدخل الاسم الكامل كما في الهوية', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم هوية مقدم الطلب', 'national_id', 'text', 'أدخل رقم الهوية (10 أرقام)', $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
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
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل مكتب الاستقدام والمعاملة</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('رقم الجوال', 'phone', 'text', '05xxxxxxxx', $phone, $disableProtected ? ['disabled' => true] : [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('مكتب الاستقدام', 'emirate', 'datalist', 'اختر مكتب الاستقدام...', $emirate, $emirates_options); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('التاريخ هجري', 'hijri_date', 'text', 'YYYY/MM/DD', $hijriDate, [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم المعاملة', 'issuance_number', 'text', 'مثال: 429302764', $issuanceNumber, [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الموافقة', 'approval_date', 'text', 'YYYY/MM/DD', $approvalDate, [], null, false, 'hijri-picker'); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-lg btn-flex px-4 text-nowrap" onclick="showStep1a()" style="border-radius: 30px;">
                        <i class="fas fa-chevron-right"></i> الخطوة السابقة
                    </button>
                    <button type="submit" class="btn btn-success btn-lg btn-flex px-5 shadow text-nowrap" style="border-radius: 30px;">
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
    </script>
<?php
elseif ($currentStep == 2): ?>
    <div class="form-container" id="relatedPersonForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">إضافة بيانات المستقدمين</h2>
            <button id="togglePersonFormBtn" class="btn btn-primary btn-circle" title="إضافة شخص جديد"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addPersonFormContainer" class="form-collapsible">
            <form id="addPersonForm" method="POST" action="index.php?page=add_data&form=recruitment&step=2&request_id=<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="item_id" value=""> <!-- For updates -->
                <input type="hidden" name="formType" value="recruitment">
                <input type="hidden" name="action" value="add_or_update_item">
                <div class="marriage-form">
                    <?php render_form_group('الاسم الكامل', 'full_name', 'text', 'ادخل الاسم الكامل', $fullName, [], $errors['full_name'] ?? null, true); ?>
                    <?php render_form_group('رقم الجواز', 'passport_number', 'text', 'ادخل رقم الجواز', $passportNumber); ?>
                    <?php render_form_group('المهنة', 'job_category', 'text', 'ادخل المهنة', $jobCategory); ?>
                    <?php render_form_group('تاريخ الميلاد', 'birth_date', 'text', 'YYYY-MM-DD', $birthDate, ['dir' => 'ltr', 'pattern' => '\d{4}-\d{2}-\d{2}', 'title' => 'YYYY-MM-DD']); ?>
                    <div class="form-group-span-2">
                        <div class="form-group" style="position: relative;">
                            <label for="recruitmentNationalityInput" class="form-label">الجنسية</label>
                            <input type="text" id="recruitmentNationalityInput" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" value="<?php echo htmlspecialchars($nationality); ?>" required autocomplete="off">
                            <div id="recruitmentNationalityList" class="dropdown-list"></div>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label for="recruitmentArrivalPlaceInput" class="form-label">جهة القدوم</label>
                            <input type="text" id="recruitmentArrivalPlaceInput" name="arrival_place" class="form-control" placeholder="ابحث عن جهة القدوم" value="" autocomplete="off">
                            <div id="recruitmentArrivalPlaceList" class="dropdown-list"></div>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" data-add-text="إضافة شخص">إضافة شخص</button>
                        <button type="reset" class="btn btn-secondary">إعادة تعيين</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-5">
            <!-- Top bar for search and pagination -->
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-0 mb-3" style="display: none;"> <!-- Hide if not needed -->
                <div class="d-flex align-items-center">
                    <label for="results-per-page" class="me-2 mb-0">عرض:</label>
                    <select id="results-per-page" class="form-select form-select-sm" style="width: auto;">
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control form-control-sm" placeholder="بحث سريع...">
                    <button class="btn btn-outline-secondary btn-sm" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <h4 class="text-center mb-3">الأشخاص المضافون</h4>
            <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead class="table-light">
                    <tr><th>الاسم</th><th>رقم الجواز</th><th>المهنة</th><th>الجنسية</th><th>جهة القدوم</th><th class="text-center">إجراءات</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_persons)): ?>
                        <?php foreach ($related_persons as $person): ?>
                            <tr>
                                <td data-label="الاسم"><?php echo htmlspecialchars($person['full_name']); ?></td>
                                <td data-label="رقم الجواز"><?php echo htmlspecialchars($person['passport_number']); ?></td>
                                <td data-label="المهنة"><?php echo htmlspecialchars($person['job_category']); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($person['nationality']); ?></td>
                                <td data-label="جهة القدوم"><?php echo htmlspecialchars($person['country']); ?></td>
                                <td class="text-center" data-label="إجراءات">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" title="تعديل" onclick="populateFormForEdit(<?php echo htmlspecialchars(json_encode($person), ENT_QUOTES, 'UTF-8'); ?>, 'recruitment')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('recruitment', <?php echo htmlspecialchars($_GET['request_id']); ?>, <?php echo $person['id']; ?>, this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4"><a href="index.php?page=add_data" class="btn btn-info">العودة الى قائمة الخدمات</a></div>
    </div>
<?php
endif; ?>
</div>

<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRecruitmentForm);
    } else {
        initRecruitmentForm();
    }
    
    function initRecruitmentForm() {
    // --- Logic for toggling the person form ---
    const toggleBtn = document.getElementById('togglePersonFormBtn');
    const formContainer = document.getElementById('addPersonFormContainer');

    // --- Centralized Toggle Logic ---
    if (toggleBtn && formContainer) {
        const formId = 'recruitment'; // Unique ID for localStorage

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
                toggleBtn.title = 'إضافة شخص جديد';
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
            addPersonForm.addEventListener('submit', function (e) {
                e.preventDefault();
                saveRelatedItem('recruitment', this);
            });
        }
    }

    // --- Register nationality search logic ---
    if (window.registerFormInitialization) {
        window.registerFormInitialization('recruitment', function () {
            if (typeof window.initializeNationalitySearch === 'function') {
                window.initializeNationalitySearch(
                    'recruitmentNationalityInput', 
                    'recruitmentNationalityList', 
                    'recruitmentArrivalPlaceInput',
                    'recruitmentArrivalPlaceList'
                );
            }
        });
    }
    }
})();
</script>
