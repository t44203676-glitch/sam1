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

$formType = 'business_visit';
$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$errors = [];

// استعادة بيانات النموذج والأخطاء من الجلسة في حالة حدوث خطأ في التحقق
$form_data = [];
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
$validFrom = $form_data['valid_from'] ?? '';
$validTo = $form_data['valid_to'] ?? '';
$approvalDate = $form_data['approval_date'] ?? '';
$approvalTime = $form_data['approval_time'] ?? '';
$attachments = $form_data['attachments'] ?? 0;
$recordNumber = $form_data['record_number'] ?? '';
$issuanceNumber = $form_data['issuance_number'] ?? '';
$remarks = $form_data['remarks'] ?? '';
$issuingAuthority = $form_data['issuing_authority'] ?? 'وزارة الداخلية-الي';

// تهيئة بيانات الزائر
$fullNameArr = '';
$visaNoArr = '';
$passportNumberArr = '';
$durationOfStayArr = '';
$expiryDateArr = '';
$birthDateArr = '';
$visaTypeArr = '';
$entryTypeArr = '';
$nationalityArr = '';
$arrivalPlaceArr = '';

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

// ... (existing variables)

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
$related_visitors = [];
if ($currentStep == 2 && isset($_GET['request_id'])) {
    $data = fetch_step2_data($formType, $currentStep, $pdo, $serviceConfig[$formType]['table']);
    $request_details = $data['request_details'];
    $related_visitors = $data['related_items'];
}

?>
<div class="container my-5">
    <!-- مؤشر الخطوات -->
    <div class="step-indicator">
      <div class="step <?php echo $currentStep == 1 ? 'active' : ''; ?>">
        <span class="step-number">1</span>
        <span>نموذج الزيارة التجارية</span>
      </div>
      <div class="step <?php echo $currentStep == 2 ? 'active' : ''; ?>">
        <span class="step-number">2</span>
        <span>إضافة بيانات الزوار</span>
      </div>
    </div>

<?php if ($currentStep == 1): ?>
    <div class="form-container" id="business_visitForm">
        <h1 class="text-center mb-4"><i class="fas fa-briefcase text-primary me-2"></i>نموذج <?php echo $serviceConfig['business_visit']['title']; ?></h1>
        
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
            <input type="hidden" name="formType" value="business_visit">
            <input type="hidden" name="action" value="add_business_visit">

            <!-- الخطوة الأولى: البيانات الأساسية والتأشيرة -->
            <div id="step1a" class="form-section shadow-sm p-4 bg-white rounded-3 mb-4 border-start border-primary border-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2 text-primary">الخطوة الأولى: بيانات مقدم الطلب والتأشيرة</h5>
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
                        <?php render_form_group('اسم مقدم الطلب', 'applicant_name', 'text', 'أدخل الاسم الكامل كما في الهوية', $applicantName, $disableProtected ? ['disabled' => true] : [], $errors['applicant_name'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم الهوية / الإقامة', 'national_id', 'text', 'أدخل رقم الهوية (10 أرقام)', $nationalId, $disableProtected ? ['disabled' => true] : [], $errors['national_id'] ?? null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم التأشيرة — Visa No.', 'visa_no', 'text', '', '', [], null, true); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخها — Date of Issue', 'issue_date', 'text', 'YYYY-MM-DD', '', ['dir' => 'ltr', 'pattern' => '\d{4}-\d{2}-\d{2}', 'title' => 'YYYY-MM-DD']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('صالحة لغاية — Valid until', 'valid_until', 'text', 'YYYY-MM-DD', '', ['dir' => 'ltr', 'pattern' => '\d{4}-\d{2}-\d{2}', 'title' => 'YYYY-MM-DD']); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('مدة الإقامة — Duration of Stay', 'duration_of_stay', 'text', '', ''); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('نوع التأشيرة — Type of Visa', 'visa_type', 'text', '', ''); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('عدد مرات الدخول — Entry Type', 'entry_type', 'text', '', ''); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('رقم التأشيرة / الإقامة — Residence / Visa Number', 'visa_residence_no', 'text', '', ''); ?>
                    </div>
                    <div class="col-md-6">
                        <?php render_form_group('تاريخ الانتهاء — Expiry Date', 'expiry_date', 'text', 'YYYY-MM-DD', '', ['dir' => 'ltr', 'pattern' => '\d{4}-\d{2}-\d{2}', 'title' => 'YYYY-MM-DD']); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="index.php?admin=1" class="btn btn-outline-secondary btn-lg btn-flex shadow-sm px-4 text-nowrap" style="border-radius: 30px;">
                        <i class="fas fa-th-large"></i> القائمة الرئيسية
                    </a>
                    <button type="submit" class="btn btn-success btn-lg btn-flex px-5 shadow text-nowrap" style="border-radius: 30px;" onclick="return window.isSectionValid('step1a')">
                        حفظ ومتابعة <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    function showStep1b() {
        const name = document.getElementsByName('applicant_name')[0].value;
        const nid = document.getElementsByName('national_id')[0].value;
        if(!name || !nid) {
            alert('يرجى إدخال اسم مقدم الطلب ورقم الهوية للمتابعة');
            return;
        }
        document.getElementById('step1a').style.display = 'none';
        document.getElementById('step1b').style.display = 'block';
        window.scrollTo(0,0);
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
            <h2 class="h4 mb-0">إضافة بيانات الزوار</h2>
            <button id="toggleVisitorFormBtn" class="btn btn-primary btn-circle" title="إضافة زائر جديد"><i class="fas fa-plus"></i></button>
        </div>

        <div id="addVisitorFormContainer" class="form-collapsible">
            <form id="addVisitorForm" method="POST" action="index.php?page=add_data&form=business_visit&step=2&request_id=<?php echo htmlspecialchars($_GET['request_id']); ?>" enctype="multipart/form-data">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($_GET['request_id']); ?>">
                <input type="hidden" name="item_id" value=""> <!-- For updates -->
                <input type="hidden" name="formType" value="business_visit">
                <input type="hidden" name="action" value="add_or_update_item">
                <div class="marriage-form">
                    <?php render_form_group('الاسم الكامل — Full Name', 'full_name', 'text', 'ادخل الاسم الكامل', $fullNameArr, [], $errors['full_name'] ?? null, true); ?>
                    <?php render_form_group('تاريخ الميلاد — Birth Date', 'birth_date', 'text', 'YYYY-MM-DD', $birthDateArr, ['dir' => 'ltr', 'pattern' => '\d{4}-\d{2}-\d{2}', 'title' => 'YYYY-MM-DD']); ?>
                    <?php render_form_group('رقم جواز السفر — Passport No.', 'passport_number', 'text', 'مثال: A22159784', $passportNumberArr); ?>
                    <div class="form-group-span-2">
                        <div class="form-group" style="position: relative;">
                            <label for="businessVisitNationalityInput" class="form-label">الجنسية — Nationality</label>
                            <input type="text" id="businessVisitNationalityInput" name="nationality" class="form-control" placeholder="ابحث عن الجنسية" value="<?php echo htmlspecialchars($nationalityArr); ?>" required autocomplete="off">
                            <div id="businessVisitNationalityList" class="dropdown-list"></div>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label for="businessVisitArrivalPlaceInput" class="form-label">جهة القدوم — Place of Issue</label>
                            <input type="text" id="businessVisitArrivalPlaceInput" name="arrival_place" class="form-control" placeholder="ابحث عن المدينة" value="<?php echo htmlspecialchars($arrivalPlaceArr); ?>" autocomplete="off">
                            <div id="businessVisitArrivalPlaceList" class="dropdown-list"></div>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" data-add-text="إضافة زائر">إضافة زائر</button>
                        <button type="reset" class="btn btn-secondary">إعادة تعيين</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-5">
            <h4 class="text-center mb-3">الزوار المضافون</h4>
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
            <table class="table table-bordered table-striped related-items-table table-responsive-stack">
                <thead class="table-light">
                    <tr>
                        <th>الاسم الكامل — Full Name</th>
                        <th>تاريخ الميلاد</th>
                        <th>رقم الجواز</th>
                        <th>الجنسية</th>
                        <th>جهة القدوم</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($related_visitors)): ?>
                        <?php foreach ($related_visitors as $visitor): ?>
                            <tr>
                                <td data-label="الاسم"><?php echo htmlspecialchars($visitor['full_name']); ?></td>
                                <td data-label="تاريخ الميلاد"><?php echo htmlspecialchars($visitor['birth_date'] ?? '---'); ?></td>
                                <td data-label="رقم الجواز"><?php echo htmlspecialchars($visitor['passport_number'] ?? '---'); ?></td>
                                <td data-label="الجنسية"><?php echo htmlspecialchars($visitor['nationality'] ?? '---'); ?></td>
                                <td data-label="جهة القدوم"><?php echo htmlspecialchars($visitor['country'] ?? $visitor['arrival_place'] ?? '---'); ?></td>
                                <td data-label="إجراءات" class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" title="تعديل" onclick="populateFormForEdit(<?php echo htmlspecialchars(json_encode($visitor), ENT_QUOTES, 'UTF-8'); ?>, 'business_visit')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('business_visit', <?php echo htmlspecialchars($_GET['request_id']); ?>, <?php echo $visitor['id']; ?>, this)">
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
        document.addEventListener('DOMContentLoaded', initBusinessVisitForm);
    } else {
        initBusinessVisitForm();
    }
    
    function initBusinessVisitForm() {
    // --- Photo Upload Logic ---
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photo-preview');
    const saveBtn = document.getElementById('save-photo-btn');
    const uploadForm = document.getElementById('uploadPhotoForm');

    if (photoInput && photoPreview && saveBtn && uploadForm) {
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
    }

    // --- منطق زر إظهار/إخفاء نموذج الإضافة ---
    const toggleBtn = document.getElementById('toggleVisitorFormBtn');
    const formContainer = document.getElementById('addVisitorFormContainer');

    // --- Centralized Toggle Logic ---
    if (toggleBtn && formContainer) {
        const formId = 'business_visit'; // Unique ID for localStorage

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
                toggleBtn.title = 'إضافة زائر جديد';
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
            const isHidden = formContainer.style.maxHeight === '0px' || !formContainer.style.maxHeight;
            toggleForm(isHidden);
        });

        // Handle form submission via AJAX
        const addVisitorForm = document.getElementById('addVisitorForm');
        if (addVisitorForm) {
            addVisitorForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveRelatedItem('business_visit', this);
            });
        }
    }

        // --- Register nationality search logic ---
        // Only initialize if we're on step 2
        const urlParams = new URLSearchParams(window.location.search);
        const currentStep = parseInt(urlParams.get('step')) || 1;
        
        if (currentStep === 2) {
            if (window.registerFormInitialization) {
                window.registerFormInitialization('business_visit', function() {
                    if (typeof window.initializeNationalitySearch === 'function') {
                        const initialized = window.initializeNationalitySearch(
                            'businessVisitNationalityInput',
                            'businessVisitNationalityList',
                            'businessVisitArrivalPlaceInput',
                            'businessVisitArrivalPlaceList'
                        );
                        if (initialized) {
                            console.log('Business visit form nationality search ready');
                        }
                    } else {
                        console.error("initializeNationalitySearch is not defined.");
                    }
                });
            }
        } else {
            console.log('Business visit form step 1 - nationality search not needed');
        }
    }

})();
</script>
<style>
    /* أنماط مخصصة مشابهة للنماذج الأخرى */
    .details-section { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: .375rem; } .details-title { font-size: 1.1rem; font-weight: bold; color: #003366; padding-bottom: 0.5rem; border-bottom: 1px solid #dee2e6; margin-bottom: 1rem; }
    .marriage-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem 1.5rem; }
    .button-group { grid-column: 1 / -1; display: flex; gap: 1rem; justify-content: flex-start; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #dee2e6; }
    .dropdown-list { position: absolute; top: 100%; left: 0; background-color: white; border: 1px solid #ddd; border-radius: 10px; max-height: 200px; overflow-y: auto; width: 100%; z-index: 1050; display: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .dropdown-item:hover { background-color: #f0f0f0; }
    .btn-circle { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; padding: 0; font-size: 1.2rem; }
    .form-collapsible { max-height: 0; overflow: hidden; transition: max-height 0.5s ease-in-out; }
    .form-group-span-2 { grid-column: span 2; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem 1.5rem; }
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