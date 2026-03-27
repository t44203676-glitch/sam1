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

$formType = 'family_visit';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$errors = [];

// استعادة بيانات النموذج والأخطاء من الجلسة في حالة حدوث خطأ في التحقق
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
$remarks = $form_data['remarks'] ?? '';
$issuingAuthority = $form_data['issuing_authority'] ?? 'وزارة الداخلية-الي';
$nationalitySponsor = $form_data['nationality'] ?? '';
$arrivalPlaceSponsor = $form_data['arrival_place'] ?? '';

// تهيئة بيانات الخطوة 2
$fullName = '';
$relationship = '';
$age = '';
$duration = '';

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

// توليد رقم صادر فريد
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $currentStep == 1 && defined('USE_DATABASE') && USE_DATABASE && isset($pdo)) {
    if (isset($serviceConfig[$formType]['prefix'])) {
        $exportNumber = generate_export_number($serviceConfig[$formType]['prefix'], $serviceConfig[$formType]['table']);
    }
}

// معالجة عمليات الخطوة 2 (إضافة/حذف) باستخدام المعالج المركزي
handle_step2_operations($formType, $currentStep, $pdo);

// جلب بيانات الطلب الرئيسي والزوار للخطوة 2
$request_details = null;
$related_members = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, $serviceConfig[$formType]['table']);
    $request_details = $data['request_details'];
    $related_members = $data['related_items'];
}

?>
<div class="container my-5">
    <!-- مؤشر الخطوات -->
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>بيانات صاحب الطلب</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>بيانات المطلوب زيارتهم</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="family_visitForm">
        <h1 class="text-center mb-4"><i class="fas fa-users text-primary me-2"></i>نموذج <?php echo $serviceConfig['family_visit']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="family_visit">
            <input type="hidden" name="action" value="add_family_visit">

            <!-- الخطوة 1أ: اسم الكفيل ورقم الهوية -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: بيانات الكفيل الأساسية</h5>
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
                        <?php render_form_group('اسم مقدم الطلب (الكفيل)', 'applicant_name', 'text', 'أدخل الاسم الكامل كما في الهوية', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <?php render_form_group('رقم هوية الكفيل', 'national_id', 'text', 'أدخل رقم الهوية (10 أرقام)', $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
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
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-info">الخطوة الثانية: تفاصيل الزيارة</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <?php render_form_group('رقم الجوال', 'phone', 'text', '05xxxxxxxx', $phone, $disableProtected ? ['disabled' => true] : [], null, true); ?>
                    </div>
                    <div class="col-md-6" style="position: relative;">
                        <label for="familyVisitNationalitySponsor" class="form-label fw-bold mb-1" style="font-size: 0.95rem;">جنسية الكفيل</label>
                        <input type="text" id="familyVisitNationalitySponsor" name="nationality" class="form-control shadow-sm" placeholder="ابحث عن الجنسية" value="" autocomplete="off">
                        <div id="familyVisitNationalitySponsorList" class="dropdown-list"></div>
                        <div class="invalid-feedback d-block" style="min-height: 1.25em;"></div>
                    </div>
                    <div class="col-md-6" style="position: relative;">
                        <label for="familyVisitArrivalPlaceSponsor" class="form-label fw-bold mb-1" style="font-size: 0.95rem;">عنوان الكفيل / جهة القدوم</label>
                        <input type="text" id="familyVisitArrivalPlaceSponsor" name="arrival_place" class="form-control shadow-sm" placeholder="ابحث عن الجهة" value="" autocomplete="off">
                        <div id="familyVisitArrivalPlaceSponsorList" class="dropdown-list"></div>
                        <div class="invalid-feedback d-block" style="min-height: 1.25em;"></div>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الخدمة/المعاملة', 'service_number', 'text', 'مثال: 8001001745', $serviceNumber); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('التاريخ الهجري', 'hijri_date', 'text', 'YYYY/MM/DD', $hijriDate, [], null, false, 'hijri-picker'); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('مكتب الاستقدام', 'emirate', 'datalist', 'اختر مكتب الاستقدام...', $emirate, $emirates_options); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم القيد', 'record_number', 'text', 'ادخل رقم القيد', $recordNumber, [], null, true); ?>
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
    <div class="form-container" id="relatedVisitorForm">
        <?php include 'request_details_box.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">بيانات المطلوب زيارتهم</h2>
            <button id="toggleVisitorFormBtn" class="btn btn-primary btn-circle"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addVisitorFormContainer" class="form-collapsible">
            <form id="addVisitorForm" method="POST" action="index.php?page=add_data&form=family_visit&step=2&request_id=<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="item_id" value="">
                <input type="hidden" name="formType" value="family_visit">
                <input type="hidden" name="action" value="add_or_update_item">
                <div class="marriage-form step2-mobile-row">
                    <?php
    render_form_group('الاسم الكامل', 'full_name', 'text', 'ادخل اسم الزائر', $fullName, [], $errors['full_name'] ?? null, true);
    render_form_group('رقم الجواز', 'passport_number', 'text', 'مثال: P123456', '');
    render_form_group('صلة القرابة', 'relationship', 'text', 'مثال: زوجة، ابن', $relationship);
    render_form_group('العمر (بالسنوات)', 'age', 'number', 'مثال: 30', '');
    render_form_group('المدة (باليوم)', 'duration', 'number', 'مثال: 90', '');
    render_form_group('المهنة', 'job_category', 'text', 'مثال: طالب، موظف', '');
    render_form_group('رقم التأشيرة (Visa No.)', 'visa_no', 'text', 'مثال: 1927338', '');
    render_form_group('نوع التأشيرة (Type Of Visa)', 'visa_type', 'text', 'مثال: زيارة عائلية', 'زيارة عائلية');
    render_form_group('تاريخ الإصدار (Issue Date)', 'issue_date', 'text', 'مثال: 01/01/2024', '', ['dir' => 'ltr']);
    render_form_group('تاريخ الانتهاء (Valid until)', 'valid_until', 'text', 'مثال: 01/01/2025', '', ['dir' => 'ltr']);
    render_form_group('عدد مرات الدخول (Entry Type)', 'entry_type', 'text', 'مثال: متعددة', '');
?>
                    <div class="form-group" style="position: relative;">
                        <label class="form-label">الجنسية</label>
                        <input type="text" id="familyVisitVisitorNationality" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" autocomplete="off">
                        <div id="familyVisitVisitorNationalityList" class="dropdown-list"></div>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label class="form-label">جهة القدوم</label>
                        <input type="text" id="familyVisitVisitorArrival" name="arrival_place" class="form-control" placeholder="ابحث عن جهة القدوم" autocomplete="off">
                        <div id="familyVisitVisitorArrivalList" class="dropdown-list"></div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">إضافة / تحديث</button>
                    <button type="reset" class="btn btn-secondary">إعادة تعيين</button>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-5">
            <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead class="table-light"> 
                    <tr>
                        <th>الاسم الكامل</th>
                        <th>صلة القرابة</th>
                        <th>العمر</th>
                        <th>المدة</th>
                        <th>المهنة</th>
                        <th>رقم التأشيرة</th>
                        <th>نوع التأشيرة</th>
                        <th>تاريخ الإصدار</th>
                        <th>تاريخ الانتهاء</th>
                        <th>رقم الجواز</th>
                        <th>الجنسية</th>
                        <th>جهة القدوم</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_members)): ?>
                        <?php foreach ($related_members as $member): ?>
                            <tr>
                                <td data-label="الاسم الكامل"><?php echo htmlspecialchars($member['full_name']); ?></td>
                                <td data-label="صلة القرابة"><?php echo htmlspecialchars($member['relationship'] ?? '---'); ?></td>
                                <td data-label="العمر"><?php echo htmlspecialchars($member['age'] ?? '---'); ?></td>
                                <td data-label="المدة"><?php echo htmlspecialchars($member['duration'] ?? $member['duration_of_stay'] ?? '---'); ?></td>
                                <td data-label="المهنة"><?php echo htmlspecialchars($member['job_category'] ?? '---'); ?></td>
                                <td data-label="رقم التأشيرة"><?php echo htmlspecialchars($member['visa_no'] ?? '---'); ?></td>
                                <td data-label="نوع التأشيرة"><?php echo htmlspecialchars($member['visa_type'] ?? '---'); ?></td>
                                <td data-label="تاريخ الإصدار"><?php echo htmlspecialchars($member['issue_date'] ?? '---'); ?></td>
                                <td data-label="تاريخ الانتهاء"><?php echo htmlspecialchars($member['valid_until'] ?? $member['expiry_date'] ?? '---'); ?></td>
                                <td data-label="رقم الجواز"><?php echo htmlspecialchars($member['passport_number'] ?? '---'); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($member['nationality'] ?? '---'); ?></td>
                                <td data-label="جهة القدوم"><?php echo htmlspecialchars($member['country'] ?? '---'); ?></td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="populateFormForEdit(<?php echo htmlspecialchars(json_encode($member), ENT_QUOTES, 'UTF-8'); ?>, 'family_visit')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRelatedItem('family_visit', <?php echo htmlspecialchars($_GET['request_id']); ?>, <?php echo $member['id']; ?>, this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="13" class="text-center placeholder-row">لا توجد بيانات مضافة</td></tr>
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
// --- Photo Upload Logic ---
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photo-preview');
    const saveBtn = document.getElementById('save-photo-btn');
    const uploadForm = document.getElementById('uploadPhotoForm');

    if (!photoInput || !photoPreview || !saveBtn || !uploadForm) return;

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.style.backgroundImage = `url('${e.target.result}')`;
            }
            reader.readAsDataURL(file);
            saveBtn.style.display = 'inline-block';
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
                photoPreview.style.backgroundImage = `url('${data.filePath}')`;
                saveBtn.style.display = 'none';
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
    function initFamilyVisitForm() {
        const toggleBtn = document.getElementById('toggleVisitorFormBtn');
        const formContainer = document.getElementById('addVisitorFormContainer');

        if (toggleBtn && formContainer) {
            toggleBtn.addEventListener('click', function() {
                const isHidden = formContainer.style.maxHeight === '0px' || !formContainer.style.maxHeight;
                formContainer.style.maxHeight = isHidden ? formContainer.scrollHeight + 'px' : '0px';
            });
        }

        const addForm = document.getElementById('addVisitorForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('family_visit', this);
            });
        }

        // Initialize search fields if on step 2
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('step') === '2' && window.initializeNationalitySearch) {
            window.initializeNationalitySearch(
                'familyVisitVisitorNationality', 'familyVisitVisitorNationalityList',
                'familyVisitVisitorArrival', 'familyVisitVisitorArrivalList'
            );
        }
        // Initialize search fields for step 1
        if (!urlParams.get('step') || urlParams.get('step') === '1') {
             if (window.initializeNationalitySearch) {
                window.initializeNationalitySearch(
                    'familyVisitNationalitySponsor', 'familyVisitNationalitySponsorList',
                    'familyVisitArrivalPlaceSponsor', 'familyVisitArrivalPlaceSponsorList'
                );
             }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFamilyVisitForm);
    } else {
        initFamilyVisitForm();
    }
})();
</script>

<style>
    .details-section { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: .375rem; }
    .marriage-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem 1.5rem; }
    .button-group { grid-column: 1 / -1; display: flex; gap: 1rem; margin-top: 1.5rem; }
    .dropdown-list { position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ddd; width: 100%; z-index: 1000; display: none; max-height: 200px; overflow-y: auto; }
    .form-collapsible { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    .btn-circle { width: 40px; height: 40px; border-radius: 50%; padding: 0; align-items: center; justify-content: center; display: inline-flex; }
    .photo-preview-circle { width: 60px; height: 60px; border-radius: 50%; background-size: cover; background-position: center; background-color: #e9ecef; border: 2px solid #dee2e6; }
</style>
