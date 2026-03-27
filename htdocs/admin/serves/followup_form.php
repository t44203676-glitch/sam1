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
    'followup' => ['prefix' => '5', 'table' => 'followup_requests', 'title' => 'التعقيب'],
];

$message = '';
$formType = 'followup';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];

// استعادة بيانات النموذج والأخطاء من الجلسة
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}
if (isset($_SESSION['form_errors'])) {
    $errors = $_SESSION['form_errors'];
    unset($_SESSION['form_errors']);
}

$isEdit = (isset($mode) && $mode === 'edit');
$isEmployee = (isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['موظف', 'Employee']));
$disableProtected = ($isEdit && $isEmployee);

// تهيئة بيانات النموذج الرئيسي
$applicantName = $form_data['applicant_name'] ?? '';
$nationalId = $form_data['national_id'] ?? '';
$exportNumber = $form_data['export_number'] ?? '';
$sponsorId = $form_data['sponsor_id'] ?? '';
$sourceNumber = $form_data['source_number'] ?? '';
$sourceEntity = $form_data['source_entity'] ?? 'وزارة العمل-آلي';
$lastModifiedDate = $form_data['last_modified_date'] ?? '';
$nationality = $form_data['nationality'] ?? '';
$arrivalPlace = $form_data['arrival_place'] ?? '';

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

// جلب بيانات الطلب الرئيسي والبيانات المرتبطة للخطوة 2
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
      <style>
          .step-clickable { cursor: pointer; text-decoration: none; transition: all 0.2s; }
          .step-clickable:hover { opacity: 0.8; transform: translateY(-2px); }
      </style>
      
      <?php if ($currentStep == 2 && isset($_GET['request_id'])): ?>
          <a href="index.php?page=add_data&form=followup&step=1&request_id=<?php echo $_GET['request_id']; ?>" class="step step-clickable">
            <span class="step-number">1</span>
            <span>بيانات طلب التعقيب</span>
          </a>
      <?php
else: ?>
          <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
            <span class="step-number">1</span>
            <span>بيانات طلب التعقيب</span>
          </div>
      <?php
endif; ?>

      <?php if ($currentStep == 1 && isset($_GET['request_id'])): ?>
          <a href="index.php?page=add_data&form=followup&step=2&request_id=<?php echo $_GET['request_id']; ?>" class="step step-clickable">
            <span class="step-number">2</span>
            <span>تفاصيل العمالة الوافدة</span>
          </a>
      <?php
else: ?>
          <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
            <span class="step-number">2</span>
            <span>تفاصيل العمالة الوافدة</span>
          </div>
      <?php
endif; ?>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="followup_form">
        <h1 class="text-center mb-4"><i class="fas fa-file-invoice text-primary me-2"></i>نموذج <?php echo $serviceConfig['followup']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="followup">
            <input type="hidden" name="action" value="add_followup_request">
            <!-- الخطوة 1أ: اسم مقدم الطلب ورقم الهوية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: البيانات الأساسية</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('اسم مقدم الطلب / المنشأة', 'applicant_name', 'text', 'ادخل الاسم الكامل كما في الهوية أو السجل', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('الرقم الوطني لمقدم الطلب', 'national_id', 'text', 'أدخل رقم الهوية أو السجل (10 أرقام)', $nationalId, $disableProtected ? ['disabled' => true] : [], null, true); ?>
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
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل التعقيب والكفالة</h5>
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
                        <?php render_form_group('رقم هوية الكفيل', 'sponsor_id', 'text', 'ادخل رقم هوية الكفيل', $sponsorId, [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم المصدر', 'source_number', 'text', 'ادخل رقم المصدر', $sourceNumber, [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('جهة المصدر', 'source_entity', 'text', 'ادخل الجهة المصدرة', $sourceEntity); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('التاريخ هجري', 'hijri_date', 'text', 'YYYY/MM/DD', $form_data['hijri_date'] ?? '', [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الإصدار', 'last_modified_date', 'text', 'YYYY/MM/DD', $lastModifiedDate, [], null, false, 'hijri-picker'); ?>
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
        </form>
    </div>

<?php
elseif ($currentStep == 2): ?>
    <div class="form-container" id="relatedItemForm">
        <?php if ($request_details): ?>
        <div class="details-section mb-4">
            <div class="row p-3">
                <div class="col-md-7">
                    <h5 class="details-title">معلومات الطلب</h5>
                    <p class="mb-2"><strong>المنشأة:</strong> <?php echo htmlspecialchars($request_details['applicant_name']); ?></p>
                    <p class="mb-2"><strong>رقم هوية الكفيل:</strong> <?php echo htmlspecialchars($request_details['sponsor_id'] ?? '---'); ?></p>
                    <p class="mb-2"><strong>رقم المصدر:</strong> <?php echo htmlspecialchars($request_details['source_number'] ?? '---'); ?></p>
                    <p class="mb-2"><strong>الرقم التسلسلي:</strong> <span id="main-serial-number"><?php echo htmlspecialchars($request_details['serial_number'] ?? '---'); ?></span></p>
                </div>
                <div class="col-md-5">
                    <h5 class="details-title">توقيت ومعلومات السجل</h5>
                    <?php if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])): ?>
                    <p class="mb-2"><strong>رقم الصادر:</strong> <?php echo htmlspecialchars($request_details['export_number']); ?></p>
                    <?php endif; ?>
                    <p class="mb-2"><strong>تاريخ الإصدار:</strong> <?php echo htmlspecialchars($request_details['last_modified_date'] ?? '---'); ?></p>
                </div>
            </div>
        </div>
        <?php
    endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">تفاصيل العمالة الوافدة</h2>
            <button id="toggleItemFormBtn" class="btn btn-primary btn-circle" title="إضافة فرد جديد"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addItemFormContainer" class="form-collapsible">
            <form id="addItemForm" method="POST" action="">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="formType" value="followup">
                <input type="hidden" name="action" value="add_or_update_item">
                <input type="hidden" name="item_id" value="">
                
                <div class="marriage-form">
                    <?php render_form_group('فئة المهنة', 'job_category', 'text', 'فئة المهنة'); ?>
                    
                    <div class="form-group-span-2">
                        <div class="form-group" style="position: relative;">
                            <label class="form-label">الجنسية</label>
                            <input type="text" id="followupNationalityInput" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" autocomplete="off">
                            <div id="followupNationalityList" class="dropdown-list"></div>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label class="form-label">مكان القدوم</label>
                            <input type="text" id="followupArrivalInput" name="arrival_place" class="form-control" placeholder="مكان القدوم" autocomplete="off">
                            <div id="followupArrivalList" class="dropdown-list"></div>
                        </div>
                    </div>
                    
                    <?php render_form_group('النوع', 'visa_type', 'text', 'النوع', 'تعقيب وجوازات'); ?>
                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <input type="text" name="status" class="form-control" value="---" readonly style="background-color: #f8f9fa;">
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
                        <th>الرقم التسلسلي</th>
                        <th>فئة المهنة</th>
                        <th>الجنسية</th>
                        <th>النوع</th>
                        <th>مكان القدوم</th>
                        <th>الحالة</th>
                        <th>رقم الملف المرسل للبنك</th>
                        <th>تاريخ الارسال للبنك</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_items)): ?>
                        <?php foreach ($related_items as $item): ?>
                            <tr data-item-id="<?php echo $item['id']; ?>">
                                <td data-label="الرقم التسلسلي"><?php echo htmlspecialchars($request_details['serial_number'] ?? '---'); ?></td>
                                <td data-label="المهنة"><?php echo htmlspecialchars($item['job_category']); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($item['nationality']); ?></td>
                                <td data-label="النوع"><?php echo htmlspecialchars($item['visa_type'] ?? 'تعقيب وجوازات'); ?></td>
                                <td data-label="مكان القدوم"><?php echo htmlspecialchars($item['country'] ?? $item['arrival_place'] ?? '---'); ?></td>
                                <td data-label="الحالة"><?php echo htmlspecialchars($item['status'] ?? '---'); ?></td>
                                <td data-label="رقم الملف المرسل للبنك"><?php echo htmlspecialchars(toWesternDigits('0')); ?></td>
                                <td data-label="تاريخ الارسال للبنك">---</td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick='populateFormForEdit(<?php echo json_encode($item); ?>, "followup")'><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick='deleteRelatedItem("followup", <?php echo $_GET["request_id"]; ?>, <?php echo $item["id"]; ?>, this)'><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <a href="index.php?page=add_data" class="btn btn-info">العودة للخدمات</a>
        </div>
    </div>
<?php
endif; ?>
</div>

<script>
(function() {
    function initFollowupForm() {
        const toggleBtn = document.getElementById('toggleItemFormBtn');
        const formContainer = document.getElementById('addItemFormContainer');
        const addItemForm = document.getElementById('addItemForm');

        if (addItemForm) {
            addItemForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('followup', this);
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

        // تهيئة البحث عن الجنسية
        if (typeof window.registerFormInitialization === 'function') {
            window.registerFormInitialization('followup', function() {
                if (typeof window.initializeNationalitySearch === 'function') {
                    // Step 2 Search
                    window.initializeNationalitySearch(
                        'followupNationalityInput',
                        'followupNationalityList',
                        'followupArrivalInput',
                        'followupArrivalList'
                    );
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFollowupForm);
    } else {
        initFollowupForm();
    }
})();
</script>
