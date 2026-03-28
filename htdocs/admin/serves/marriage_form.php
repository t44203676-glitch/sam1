<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login&error=unauthorized');
    exit;
}

// التأكد من وجود اتصال قاعدة البيانات
global $pdo;

// تضمين ملف الدوال المركزي
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/logger.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/two_step_form_handler.php';

// تعريف إعدادات الخدمات
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

// ==================================================================
// 1. منطق معالجة النموذج
// ==================================================================

$message = '';
$formType = 'marriage';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;

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

// تهيئة بيانات النموذج الرئيسي
$applicantName = $form_data['applicant_name'] ?? '';
$nationalId = $form_data['national_id'] ?? '';
$phone = $form_data['phone'] ?? '';
$exportNumber = $form_data['export_number'] ?? '';

// توليد رقم صادر فريد فقط عند تحميل النموذج لأول مرة
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && empty($exportNumber) && defined('USE_DATABASE') && USE_DATABASE && isset($pdo) && isset($serviceConfig[$formType])) {
    $prefix = $serviceConfig[$formType]['prefix']; // '0' for marriage
    $tableName = $serviceConfig[$formType]['table']; // 'marriage_permits'
    do {
        $nineDigits = substr(str_shuffle(str_repeat('0123456789', 9)), 0, 9);
        $exportNumber = $prefix . $nineDigits;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$tableName}` WHERE export_number = ?");
        $stmt->execute([$exportNumber]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
}

$serviceNumber = '';
$serviceDesc = '';
$hijriDate = '';
$permitType = '';
$emirate = '';
$issuingAuthority = 'وزارة الداخلية-الي';

// تهيئة الرقم التسلسلي
$serialNumber = $form_data['serial_number'] ?? '';
if (empty($serialNumber) && $currentStep == 1) {
    $serialNumber = substr(str_shuffle(str_repeat('0123456789', 10)), 0, 10);
}

// تهيئة التواريخ وأرقام السجلات
$approvalDate = $form_data['approval_date'] ?? '';
$recordNumber = $form_data['record_number'] ?? '';

// تهيئة بيانات الشريك
$fullName = '';
$passportNumber = '';
$jobCategory = '';
$birthDate = '';
$nationality = '';
$arrivalPlace = '';

// خيارات نوع التصريح والإمارة
$permit_types_options = [
    '' => 'اختر نوع التصريح...',
    'زواج سعودي من اجنبية مواليد السعودية' => 'زواج سعودي من اجنبية مواليد السعودية',
    'زواج سعودية من اجنبي مواليد السعودية' => 'زواج سعودية من اجنبي مواليد السعودية',
    'زواج سعودي من اجنبية داخل الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودي من اجنبية داخل الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودية من اجنبي داخل الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودية من اجنبي داخل الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودي من اجنبية خارج الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودي من اجنبية خارج الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودية من اجنبي خارج الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودية من اجنبي خارج الاراضي السعودية مواليد خارج السعودية'
];
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

// معالجة عمليات الخطوة 2 (إضافة/حذف) باستخدام المعالج المركزي
handle_step2_operations($formType, $currentStep, $pdo);

// جلب بيانات الطلب الرئيسي والشركاء للخطوة 2
$request_details = null;
$related_partners = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, $serviceConfig[$formType]['table']);
    // Re-fetch request_details along with related_items to ensure data is current after AJAX operations.
    $request_details = $data['request_details'];
    $related_partners = $data['related_items'];
}

?>
<div class="container my-5">
    <!-- مؤشر الخطوات -->
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>نموذج تصريح زواج</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>إضافة بيانات الشريك</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="marriage_form">
        <h1 class="text-center mb-4"><i class="fas fa-file-signature text-primary me-2"></i>نموذج <?php echo $serviceConfig['marriage']['title']; ?></h1>
        
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger shadow-sm border-start border-danger border-4 mb-4">
            <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> هناك أخطاء في البيانات المدخلة:</h6>
            <ul class="mb-0 fs-14">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php
        endforeach; ?>
            </ul>
        </div>
        <?php
    endif; ?>
        
        <form method="POST" action="" id="multiStepForm">
            <input type="hidden" name="formType" value="marriage">
            <input type="hidden" name="action" value="add_marriage_permit">
            
            <!-- الخطوة 1أ: اسم مقدم الطلب ورقم الهوية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: البيانات الأساسية</h5>
                <div class="row g-3">
                    <?php if ($disableProtected): ?>
                        <div class="col-md-12">
                            <?php render_form_group('الرقم التسلسلي (غير قابل للتعديل)', 'serial_number_display', 'text', '', $form_data['serial_number'] ?? $serialNumber, ['disabled' => true]); ?>
                            <input type="hidden" name="serial_number" value="<?php echo htmlspecialchars($form_data['serial_number'] ?? $serialNumber); ?>">
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="serial_number" value="<?php echo htmlspecialchars($form_data['serial_number'] ?? $serialNumber); ?>">
                    <?php endif; ?>

                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('اسم مقدم الطلب', 'applicant_name', 'text', 'ادخل الاسم الكامل كما في الهوية', $form_data['applicant_name'] ?? $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم الهوية / الإقامة', 'national_id', 'text', 'ادخل رقم الهوية أو الإقامة', $form_data['national_id'] ?? $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
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
                    <?php if ($disableProtected): ?>
                        <div class="col-md-12">
                            <?php render_form_group('رقم الصادر (غير قابل للتعديل)', 'export_number_display', 'text', '', $exportNumber, ['disabled' => true]); ?>
                            <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($exportNumber); ?>">
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($exportNumber); ?>">
                    <?php endif; ?>

                    <div class="col-md-6">
                        <?php render_form_group('رقم الهاتف', 'phone', 'text', '05xxxxxxxx', $form_data['phone'] ?? $phone, $disableProtected ? ['disabled' => true] : [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('نوع تصريح الزواج', 'permit_type', 'select', 'اختر نوع التصريح...', $form_data['permit_type'] ?? $permitType, $permit_types_options); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('الإمارة', 'emirate', 'datalist', 'اختر الإمارة...', $form_data['emirate'] ?? $emirate, $emirates_options); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الموافقة', 'approval_date', 'text', 'YYYY/MM/DD', $form_data['approval_date'] ?? $approvalDate, [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم السجل', 'record_number', 'text', 'ادخل رقم السجل', $form_data['record_number'] ?? $recordNumber, [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الملف المرسل للبنك', 'bank_file_number', 'number', '0', $form_data['bank_file_number'] ?? '0'); ?>
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
    <div class="form-container" id="relatedPartnerForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">إضافة بيانات الشريك</h2>
            <button id="togglePartnerFormBtn" class="btn btn-primary btn-circle" title="إضافة شريك جديد"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addPartnerFormContainer" class="form-collapsible">
            <form id="addPartnerForm" method="POST" action="" class="">
                <input type="hidden" name="item_id" value=""> <!-- For updates -->
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="formType" value="marriage">
                <input type="hidden" name="action" value="add_or_update_item">
                <div class="marriage-form step2-mobile-row">
                    <?php render_form_group('الاسم الكامل', 'full_name', 'text', 'ادخل الاسم الكامل', $fullName, [], $errors['full_name'] ?? null, true); ?>
                    <?php render_form_group('رقم الجواز/ رقم الاقامة', 'passport_number', 'text', 'ادخل رقم الجواز أو الإقامة', $passportNumber); ?>
                    <?php render_form_group('فئة المهنة', 'job_category', 'text', 'ادخل فئة المهنة', $jobCategory); ?>
                    

                    <div class="form-group" style="position: relative;">
                        <label for="marriageNationalityInput" class="form-label">الجنسية</label>
                        <input type="text" id="marriageNationalityInput" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" value="<?php echo htmlspecialchars($nationality); ?>" required autocomplete="off">
                        <div id="marriageNationalityList" class="dropdown-list"></div>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="marriageArrivalPlaceInput" class="form-label">جهة القدوم</label>
                        <input type="text" id="marriageArrivalPlaceInput" name="arrival_place" class="form-control" placeholder="ابحث عن جهة القدوم" value="<?php echo htmlspecialchars($arrivalPlace); ?>" required autocomplete="off">
                        <div id="marriageArrivalPlaceList" class="dropdown-list"></div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary" data-add-text="إضافة شريك">إضافة شريك</button>
                    <button type="reset" class="btn btn-secondary">إعادة تعيين</button>
                </div>
            </form>
        </div>
                    <!-- Top bar for search and pagination -->
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-0 mb-3" style="display: none !important;">
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
                            <button class="btn btn-outline-secondary btn-sm" type="button" title="بحث"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <h4 class="text-center mb-3 mt-5">الشركاء المضافون</h4>
                    <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%;">الاسم الكامل</th>
                                <th style="width: 20%;">رقم الجواز/الإقامة</th>
                                <th style="width: 15%;">فئة المهنة</th>
                                <th style="width: 15%;">الجنسية</th>
                                <th style="width: 15%;">مكان القدوم</th>
                                <th style="width: 10%;" class="text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($related_partners)): ?>
                                <?php foreach ($related_partners as $partner): ?>
                                    <tr data-item-id="<?php echo $partner['id']; ?>" data-table="related_data">
                                        <td data-label="الاسم الكامل"><?php echo htmlspecialchars($partner['full_name'] ?? '---'); ?></td>
                                        <td data-label="رقم الجواز/الإقامة"><?php echo htmlspecialchars($partner['passport_number'] ?? '---'); ?></td>
                                        <td data-label="فئة المهنة"><?php echo htmlspecialchars($partner['job_category'] ?? '---'); ?></td>
                                        <td data-label="الجنسية"><?php echo htmlspecialchars($partner['nationality'] ?? '---'); ?></td>
                                        <td data-label="مكان القدوم"><?php echo htmlspecialchars($partner['country'] ?? '---'); ?></td>
                                        <td class="text-center">
                                            <?php $safeItemJson = htmlspecialchars(json_encode($partner), ENT_QUOTES, 'UTF-8'); ?>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" title="تعديل" onclick='populateFormForEdit(<?php echo $safeItemJson; ?>, "marriage")'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('marriage', '<?php echo htmlspecialchars($_GET['request_id'] ?? ($_POST['request_id'] ?? '')); ?>', <?php echo $partner['id']; ?>, this)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <div class="mt-4"><a href="index.php?page=add_data" class="btn btn-info">العودة الى قائمة الخدمات</a></div>
    </div>

<?php
endif; ?>

<script>
// This script runs after common.js is loaded
(function() {
    // Wait for DOM to be fully ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMarriageForm);
    } else {
        initMarriageForm();
    }
    
    function initMarriageForm() {
    // --- Logic for toggling the partner form ---
    const toggleBtn = document.getElementById('togglePartnerFormBtn');
    const formContainer = document.getElementById('addPartnerFormContainer');

        // Handle form submission via AJAX
        const addPartnerForm = document.getElementById('addPartnerForm');
        if (addPartnerForm) {
            addPartnerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('marriage', this); // This now handles UI updates via AJAX
            });
        }

    // --- Centralized Toggle Logic for all forms ---
    // This logic is now being standardized across all forms.
    if (toggleBtn && formContainer) {
        const formId = 'marriage'; // Unique ID for this form's localStorage

        formContainer.addEventListener('transitionend', () => {
            if (formContainer.style.maxHeight !== '0px') {
                formContainer.style.overflow = 'visible'; // Allow dropdowns to overflow
                formContainer.style.maxHeight = 'none'; // Allow container to grow with dynamic content (new table rows)
            }
        });

        function toggleForm(open) {
            const icon = toggleBtn.querySelector('i');

            if (open) {
                formContainer.style.overflow = 'hidden'; // Ensure hidden during expand transition
                const innerForm = formContainer.querySelector('form');
                formContainer.style.maxHeight = (innerForm ? innerForm.scrollHeight + 60 : 500) + 'px'; // Add padding for transition target
                
                icon.classList.replace('fa-plus', 'fa-minus');
                toggleBtn.title = 'إخفاء النموذج';
                localStorage.setItem(formId + 'FormState', 'open');
            } else {
                // Determine current height to animate from
                if (formContainer.style.maxHeight === 'none') {
                     // Set explicit height to enable transition
                     formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                     // Force reflow
                     void formContainer.offsetHeight;
                }

                formContainer.style.overflow = 'hidden'; // Ensure hidden before collapsing
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
    }

        // --- Register nationality search logic after DOM is loaded ---
        // Only initialize if we're on step 2 (where nationality fields exist)
        const urlParams = new URLSearchParams(window.location.search);
        const currentStep = parseInt(urlParams.get('step')) || 1;
        
        if (currentStep === 2) {
            if (window.registerFormInitialization) {
                window.registerFormInitialization('marriage', function() {
                    // This function will be executed by common.js AFTER countries data is fetched.
                    if (typeof window.initializeNationalitySearch === 'function') {
                        const initialized = window.initializeNationalitySearch(
                            'marriageNationalityInput',
                            'marriageNationalityList',
                            'marriageArrivalPlaceInput',
                            'marriageArrivalPlaceList'
                        );
                        console.log('Marriage form nationality search ready');
                    } else {
                        console.error("initializeNationalitySearch is not defined.");
                    }
                });
            } else {
                console.error("registerFormInitialization is not defined. Check if common.js is loaded correctly.");
            }
        } else {
            console.log('Marriage form step 1 - nationality search not needed');
        }
    }
})();
</script>