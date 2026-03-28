<?php
// runaway_cancellation_form.php - نموذج إلغاء بلاغ هروب

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login&error=unauthorized');
    exit;
}

require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/logger.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/two_step_form_handler.php';

$formType = 'runaway_cancellation';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];

// استعادة بيانات النموذج والأخطاء من الجلسة
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
$sponsorName = $form_data['sponsor_name'] ?? '';
$sponsorId = $form_data['sponsor_id'] ?? '';
$emirate = $form_data['emirate'] ?? '';
$approvalDate = $form_data['approval_date'] ?? '';
$issuanceNumber = $form_data['issuance_number'] ?? '';
$remarks = $form_data['remarks'] ?? '';

$serviceConfig = [
    'runaway_cancellation' => ['prefix' => '5', 'table' => 'runaway_cancellations', 'title' => 'إلغاء بلاغ هروب'],
];

// توليد رقم صادر فريد
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && empty($exportNumber) && defined('USE_DATABASE') && USE_DATABASE && isset($pdo)) {
    $prefix = $serviceConfig[$formType]['prefix'];
    $tableName = $serviceConfig[$formType]['table'];
    do {
        $nineDigits = substr(str_shuffle(str_repeat('0123456789', 9)), 0, 9);
        $exportNumber = $prefix . $nineDigits;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$tableName}` WHERE export_number = ?");
        $stmt->execute([$exportNumber]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
}

// معالجة عمليات الخطوة 2
handle_step2_operations($formType, $currentStep, $pdo);

// جلب بيانات الخطوة 2
$request_details = null;
$related_items = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, $serviceConfig[$formType]['table']);
    $request_details = $data['request_details'];
    $related_items = $data['related_items'];
}

?>

<div class="container my-5">
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>بيانات الطلب الأساسية</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>بيانات العمالة</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="runaway_form">
        <h1 class="text-center mb-4"><i class="fas fa-user-slash text-primary me-2"></i>نموذج <?php echo $serviceConfig['runaway_cancellation']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="runaway_cancellation">
            <input type="hidden" name="action" value="add_runaway_request">

            <!-- الخطوة 1أ: البيانات الأساسية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: بيانات مقدم الطلب</h5>
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
                        <?php render_form_group('اسم مقدم الطلب', 'applicant_name', 'text', 'ادخل الاسم الكامل', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم الهوية / السجل', 'national_id', 'text', 'رقم الهوية المكون من 10 أرقام', $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الجوال', 'phone', 'text', '05xxxxxxxx', $phone, $disableProtected ? ['disabled' => true] : [], null, true); ?>
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

            <!-- الخطوة 1ب: تفاصيل الكفالة -->
            <div id="step1b" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-info border-4" style="display: none;">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل الكفيل والمعاملة</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('اسم صاحب العمل', 'sponsor_name', 'text', 'اسم الكفيل', $sponsorName); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم هوية الكفيل', 'sponsor_id', 'text', 'رقم هوية الكفيل', $sponsorId, [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('جهة الإصدار', 'emirate', 'text', 'ادخل جهة الإصدار', $emirate); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الموافقة', 'approval_date', 'text', 'YYYY/MM/DD', $approvalDate, [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم المعاملة', 'issuance_number', 'text', 'ادخل رقم المعاملة', $issuanceNumber, [], null, true); ?>
                    </div>
                    <div class="col-md-12">
                        <?php render_form_group('ملاحظات', 'remarks', 'textarea', 'أدخل أي ملاحظات إضافية', $remarks); ?>
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
    </script>

<?php elseif ($currentStep == 2): ?>
    <div class="form-container" id="relatedItemForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">بيانات العمالة الملغى بلاغهم</h2>
            <button id="toggleItemFormBtn" class="btn btn-primary btn-circle" title="إضافة فرد جديد"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addItemFormContainer" class="form-collapsible">
            <form id="addItemForm" method="POST" action="">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="formType" value="runaway_cancellation">
                <input type="hidden" name="action" value="add_or_update_item">
                <input type="hidden" name="item_id" value="">
                
                <div class="marriage-form">
                    <?php render_form_group('الاسم الكامل', 'full_name', 'text', 'ادخل الاسم الكامل', '', [], null, true); ?>
                    <?php render_form_group('رقم الهوية / الإقامة', 'national_id', 'text', 'ادخل رقم الهوية', ''); ?>
                    <?php render_form_group('صلة القرابة', 'relationship', 'text', 'ادخل صلة القرابة', ''); ?>
                    
                    <div class="form-group-span-2">
                        <div class="form-group" style="position: relative;">
                            <label class="form-label">الجنسية</label>
                            <input type="text" id="runawayNationalityInput" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" autocomplete="off">
                            <div id="runawayNationalityList" class="dropdown-list"></div>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label class="form-label">بلد القدوم</label>
                            <input type="text" id="runawayArrivalInput" name="arrival_place" class="form-control" placeholder="بلد القدوم" autocomplete="off">
                            <div id="runawayArrivalList" class="dropdown-list"></div>
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">إضافة / تحديث</button>
                    <button type="reset" class="btn btn-secondary">تفريغ</button>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead>
                    <tr>
                        <th>الاسم الكامل</th>
                        <th>رقم الهوية / الإقامة</th>
                        <th>صلة القرابة</th>
                        <th>الجنسية</th>
                        <th>بلد القدوم</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_items)): ?>
                        <?php foreach ($related_items as $item): ?>
                            <tr data-item-id="<?php echo $item['id']; ?>">
                                <td data-label="الاسم الكامل"><?php echo htmlspecialchars($item['full_name']); ?></td>
                                <td data-label="الهوية"><?php echo htmlspecialchars($item['national_id']); ?></td>
                                <td data-label="القرابة"><?php echo htmlspecialchars($item['relationship'] ?? '---'); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($item['nationality']); ?></td>
                                <td data-label="البلد"><?php echo htmlspecialchars($item['country'] ?? '---'); ?></td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick='populateFormForEdit(<?php echo json_encode($item); ?>, "runaway_cancellation")'><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick='deleteRelatedItem("runaway_cancellation", <?php echo $_GET["request_id"]; ?>, <?php echo $item["id"]; ?>, this)'><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <a href="index.php?page=add_data" class="btn btn-info">إنهاء</a>
        </div>
    </div>
<?php endif; ?>
</div>

<script>
(function() {
    function initRunawayForm() {
        const toggleBtn = document.getElementById('toggleItemFormBtn');
        const formContainer = document.getElementById('addItemFormContainer');
        const addItemForm = document.getElementById('addItemForm');

        if (addItemForm) {
            addItemForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('runaway_cancellation', this);
            });
        }

        if (toggleBtn && formContainer) {
            toggleBtn.addEventListener('click', function() {
                const isOpen = formContainer.style.maxHeight !== '0px' && formContainer.style.maxHeight !== '';
                if (isOpen) {
                    formContainer.style.maxHeight = '0px';
                    toggleBtn.querySelector('i').classList.replace('fa-minus', 'fa-plus');
                } else {
                    formContainer.style.maxHeight = formContainer.scrollHeight + 'px';
                    toggleBtn.querySelector('i').classList.replace('fa-plus', 'fa-minus');
                }
            });
        }

        if (typeof window.registerFormInitialization === 'function') {
            window.registerFormInitialization('runaway_cancellation', function() {
                if (typeof window.initializeNationalitySearch === 'function') {
                    window.initializeNationalitySearch(
                        'runawayNationalityInput',
                        'runawayNationalityList',
                        'runawayArrivalInput',
                        'runawayArrivalList'
                    );
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRunawayForm);
    } else {
        initRunawayForm();
    }
})();
</script>
