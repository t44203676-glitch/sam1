<?php
// labor_form.php - نموذج إضافة العمالة المحدث

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login&error=unauthorized');
    exit;
}

require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/two_step_form_handler.php';

$formType = 'labor';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// إعدادات الخدمة
$serviceConfig = [
    'labor' => ['prefix' => '4', 'table' => 'labor_requests', 'title' => 'خدمة العمالة'],
];

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
$fields = [
    'establishment_name' => $form_data['establishment_name'] ?? '',
    'national_id' => $form_data['national_id'] ?? '',
    'owner_name' => $form_data['owner_name'] ?? '',
    'record_number' => $form_data['record_number'] ?? '',
    'export_number' => $form_data['export_number'] ?? '',
    'hijri_date' => $form_data['hijri_date'] ?? '',
    'emirate' => $form_data['emirate'] ?? '',
    'issuance_number' => $form_data['issuance_number'] ?? '',
    'applicant_name' => $form_data['applicant_name'] ?? '',
];

// توليد رقم صادر تلقائي
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && empty($fields['export_number'])) {
    $prefix = '4';
    $nineDigits = substr(str_shuffle(str_repeat('0123456789', 9)), 0, 9);
    $fields['export_number'] = $prefix . $nineDigits;
}

// معالجة عمليات الخطوة 2
handle_step2_operations($formType, $currentStep, $pdo);

// جلب البيانات للخطوة 2
$request_details = null;
$related_items = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, 'labor_requests');
    $request_details = $data['request_details'];
    $related_items = $data['related_items'];
}

// خيارات مكتب العمل
$labor_offices = [
    '' => 'اختر مكتب العمل...',
    'مكتب عمل منطقة الرياض' => 'مكتب عمل منطقة الرياض',
    'مكتب عمل منطقة مكة المكرمة' => 'مكتب عمل منطقة مكة المكرمة',
    'مكتب عمل المنطقة الشرقية' => 'مكتب عمل المنطقة الشرقية',
    'مكتب عمل منطقة المدينة المنورة' => 'مكتب عمل منطقة المدينة المنورة',
    'مكتب عمل منطقة القصيم' => 'مكتب عمل منطقة القصيم',
    'مكتب عمل منطقة عسير' => 'مكتب عمل منطقة عسير',
    'مكتب عمل منطقة تبوك' => 'مكتب عمل منطقة تبوك',
    'مكتب عمل منطقة حائل' => 'مكتب عمل منطقة حائل',
    'مكتب عمل الحدود الشمالية' => 'مكتب عمل الحدود الشمالية',
    'مكتب عمل منطقة جازان' => 'مكتب عمل منطقة جازان',
    'مكتب عمل منطقة نجران' => 'مكتب عمل منطقة نجران',
    'مكتب عمل منطقة الجوف' => 'مكتب عمل منطقة الجوف',
    'مكتب عمل منطقة الباحة' => 'مكتب عمل منطقة الباحة'
];

?>

<div class="container my-5">
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>بيانات الطلب الرئيسية</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>تفاصيل التأشيرات</span>
      </div>
    </div>

    <?php if ($currentStep == 1): ?>
    <div class="form-container" id="labor_form">
        <h1 class="text-center mb-4"><i class="fas fa-hammer text-primary me-2"></i>نموذج <?php echo $serviceConfig['labor']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="labor">
            <input type="hidden" name="action" value="add_labor_request">

            <!-- الخطوة 1أ: اسم المنشأة ورقم الهوية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: بيانات المنشأة الأساسية</h5>
                <div class="row g-3">
                    <?php if ($disableProtected): ?>
                        <div class="col-md-12">
                            <?php render_form_group('رقم الصادر (غير قابل للتعديل)', 'export_number_display', 'text', '', $fields['export_number'], ['disabled' => true]); ?>
                            <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($fields['export_number']); ?>">
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="export_number" value="<?php echo htmlspecialchars($fields['export_number']); ?>">
                    <?php endif; ?>

                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('اسم المنشأة/الطلب', 'establishment_name', 'text', 'اسم المؤسسة كما في السجل', $fields['establishment_name'], $disableProtected ? ['disabled' => true] : [], $errors['establishment_name'] ?? null, false); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم هوية المالك/السجل', 'national_id', 'text', 'أدخل رقم الهوية أو السجل', $fields['national_id'], $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
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
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل الملف ومكتب العمل</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('اسم المالك', 'owner_name', 'text', 'اسم مالك المنشأة', $fields['owner_name']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الملف', 'record_number', 'text', 'رقم الملف في مكتب العمل', $fields['record_number'], [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('التاريخ هجري', 'hijri_date', 'text', 'YYYY/MM/DD', $fields['hijri_date'], [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('مكتب العمل', 'emirate', 'datalist', 'اختر مكتب العمل...', $fields['emirate'], $labor_offices); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('المرجع', 'issuance_number', 'text', 'الرقم المرجعي', $fields['issuance_number']); ?>
                    </div>
                </div>
                <input type="hidden" name="applicant_name" id="applicant_name_hidden" value="<?php echo htmlspecialchars($fields['establishment_name']); ?>">

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
        
        const name = document.getElementsByName('establishment_name')[0].value;
        const hiddenApplicant = document.getElementById('applicant_name_hidden');
        if (hiddenApplicant) hiddenApplicant.value = name;

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
    <div class="form-container" id="relatedLaborForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">إضافة التأشيرات</h2>
            <button id="toggleItemFormBtn" class="btn btn-primary btn-circle" title="إضافة تأشيرة"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addItemFormContainer" class="form-collapsible">
            <form id="addItemForm" method="POST" action="">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="formType" value="labor">
                <input type="hidden" name="action" value="add_or_update_item">
                <input type="hidden" name="item_id" value="">

                <div class="marriage-form">
                    <?php
    render_form_group('المهنة', 'job_category', 'text', 'مثال: عامل، مهندس', '', [], null, true);
?>
                    
                    <div class="form-group-span-2">
                        <div class="form-group" style="position: relative;">
                            <label for="laborNationalityInput" class="form-label">الجنسية</label>
                            <input type="text" id="laborNationalityInput" name="nationality" class="form-control" placeholder="بحث..." required autocomplete="off">
                            <div id="laborNationalityList" class="dropdown-list"></div>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label for="laborArrivalPlaceInput" class="form-label">جهة القدوم</label>
                            <input type="text" id="laborArrivalPlaceInput" name="arrival_place" class="form-control" placeholder="بحث..." required autocomplete="off">
                            <div id="laborArrivalPlaceList" class="dropdown-list"></div>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" data-add-text="إضافة تأشيرة">إضافة تأشيرة</button>
                        <button type="reset" class="btn btn-secondary" onclick="resetRelatedItemForm(this.form)">إلغاء</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-5">
            <h4 class="text-center mb-3">التأشيرات المضافة</h4>
                <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead class="table-light">
                    <tr>
                        <th>المهنة</th>
                        <th>الجنسية</th>
                        <th>جهة القدوم</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_items)): ?>
                        <?php foreach ($related_items as $item): ?>
                            <tr data-item-id="<?php echo $item['id']; ?>">
                                <td data-label="المهنة"><?php echo htmlspecialchars($item['job_category']); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($item['nationality']); ?></td>
                                <td data-label="جهة القدوم"><?php echo htmlspecialchars($item['country'] ?? '---'); ?></td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" title="تعديل" onclick='populateFormForEdit(<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>, "labor")'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('labor', '<?php echo htmlspecialchars($_GET['request_id']); ?>', <?php echo $item['id']; ?>, this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php
        endforeach; ?>
                    <?php
    else: ?>
                        <tr><td colspan="4" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                    <?php
    endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
             <a href="index.php?page=add_data" class="btn btn-info">إنهاء</a>
             <a href="preview_labor.php" target="_blank" class="btn btn-secondary ms-2">معاينة التذكرة</a>
        </div>
    </div>
    <?php
endif; ?>
</div>

<script>
// سكربت التفاعل الخاص بالنموذج (مشابه لـ marriage_form)
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleItemFormBtn');
    const formContainer = document.getElementById('addItemFormContainer');
    
    // 1. تفعيل البحث الذكي عن الجنسيات
    if (window.registerFormInitialization) {
        window.registerFormInitialization('labor', function() {
             if (typeof initializeNationalitySearch === 'function') {
                initializeNationalitySearch('laborNationalityInput', 'laborNationalityList', 'laborArrivalPlaceInput', 'laborArrivalPlaceList');
            }
        });
    }

    // 2. منطق إخفاء/إظهار نموذج الإضافة
    if (toggleBtn && formContainer) {
        // ... (نفس منطق التبديل الموجود في marriage_form)
        toggleBtn.addEventListener('click', function() {
            const isHidden = formContainer.style.maxHeight === '0px' || !formContainer.style.maxHeight;
            formContainer.style.overflow = 'hidden';
            if (isHidden) {
                formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                toggleBtn.querySelector('i').classList.replace('fa-plus', 'fa-minus');
            } else {
                formContainer.style.maxHeight = '0px';
                toggleBtn.querySelector('i').classList.replace('fa-minus', 'fa-plus');
            }
        });
        // Default closed
        formContainer.style.maxHeight = '0px';
    }

    // 3. معالجة الإرسال عبر AJAX
    const addItemForm = document.getElementById('addItemForm');
    if (addItemForm) {
        addItemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveRelatedItem('labor', this);
        });
    }
});
</script>