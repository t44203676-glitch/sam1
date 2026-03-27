?>
<?php
// civil_inquiry.php
// تم تعديل التصميم ليمتد الإطار على كامل عرض الصفحة مع الحفاظ على تنسيق الحقول
require_once '../../includes/functions.php';
$active_page = 'eservices';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('generateRandomCaptcha')) {
    function generateRandomCaptcha($length = 4)
    {
        return rand(1000, 9999);
    }
}
$_SESSION['captcha'] = generateRandomCaptcha(4);

include '../../includes/header.php';
?>

<?php include '../../includes/navigation.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script>
    function setVisibility(id, visibility) {
        document.getElementById(id).style.display = visibility;
    }

    function refreshCaptcha() {
        const icon = document.getElementById('refreshIcon');
        const btn = icon.parentElement;
        
        icon.classList.add('rotating');
        btn.disabled = true;
        
        // استدعاء AJAX للحصول على رمز جديد
        var xhr = new XMLHttpRequest();
        // تصحيح المسار ليكون من المجلد الحالي إلى الجذر
        xhr.open('GET', '../../generate_captcha_value.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    document.getElementById('captcha_display').innerText = xhr.responseText;
                }
                setTimeout(() => {
                    icon.classList.remove('rotating');
                    btn.disabled = false;
                }, 500);
            }
        };
        xhr.send();
    }
</script>
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        font-weight: 700;
    }
    .civil-inquiry-wrapper {
        direction: rtl;
        padding: 0;
        width: 100%;
    }

    /* Breadcrumbs */
    .breadcrumb-container {
        max-width: 1200px;
        margin: 10px auto;
        padding: 0 20px;
        font-size: 12px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .breadcrumb-container a {
        text-decoration: none;
        color: #00ab67;
        font-weight: 700;
    }

    /* Full Width Card */
    .inquiry-card {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid #ddd;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    }

    .inquiry-card-header {
        background-color: #00ab67;
        padding: 15px 30px;
        border-bottom: 3px solid #008f56;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
        text-align: right;
    }

    .inquiry-card-body {
        padding: 30px;
        max-width: 800px;
    }

    .service-info {
        text-align: right;
        font-size: 15px;
        color: #333;
        margin-bottom: 25px;
        line-height: 2;
        font-weight: 700;
    }

    .section-title-bar {
        background: #f0f8f5;
        padding: 12px 20px;
        font-weight: 800;
        font-size: 16px;
        color: #00ab67;
        text-align: right;
        margin-bottom: 25px;
        border-right: 5px solid #00ab67;
    }

    /* Alert Box */
    .alert-info-custom {
        background-color: #d9edf7;
        border: 1px solid #bce8f1;
        color: #31708f;
        padding: 12px 20px;
        text-align: right;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 4px;
    }

    /* Form Fields - All Stacked Vertically */
    .form-group-row {
        margin-bottom: 20px;
        text-align: right;
    }

    .custom-label {
        font-weight: 800;
        font-size: 15px;
        color: #222;
        margin-bottom: 8px;
        display: block;
        text-align: right;
    }
    .custom-label .req {
        color: #d9534f;
        font-weight: 800;
    }

    .custom-input {
        width: 100%;
        max-width: 200px;
        padding: 22px 15px;
        font-size: 15px;
        color: #333;
        border: 2px solid #00ab67;
        border-radius: 4px;
        text-align: right;
        outline: none;
        background-color: #fff;
        box-sizing: border-box;
        font-weight: 700;
    }
    .custom-input:focus {
        border-color: #008f56;
        box-shadow: 0 0 6px rgba(0,171,103,0.4);
    }

    /* Captcha - Right Aligned with Enhanced Design */
    .captcha-row {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        margin-bottom: 15px;
    }

    .captcha-box {
        position: relative;
        background: #e0e0e0;
        border: 2px solid #6b7280;
        width: 200px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .captcha-lines {
        position: absolute;
        inset: 0;
        opacity: 0.4;
        pointer-events: none;
        overflow: hidden;
    }
    
    .captcha-line-1 {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 2px;
        background: black;
        transform: rotate(-3deg);
    }
    
    .captcha-line-2 {
        position: absolute;
        top: 25%;
        left: 0;
        width: 100%;
        height: 1px;
        background: black;
        transform: rotate(6deg);
    }
    
    .captcha-code {
        font-size: 2.25rem;
        font-weight: 900;
        letter-spacing: 0.1em;
        color: #1f2937;
        font-family: 'Courier New', monospace;
        font-style: italic;
        position: relative;
        z-index: 10;
        user-select: none;
    }

    .refresh-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 56px; /* سد الفجوة بارتفاع الكابتشا */
        background: #00ab67;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
        font-size: 1.5rem;
        transition: all 0.3s;
    }
    .refresh-btn:hover {
        background: #008f56;
    }
    .refresh-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .refresh-btn:active {
        transform: scale(0.95);
    }
    
    .refresh-icon {
        transition: transform 0.3s;
    }
    
    .rotating {
        animation: rotate 0.5s linear;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Buttons - Stacked Vertically */
    .btn-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-submit {
        background-color: #00ab67;
        color: #fff;
        border: none;
        padding: 14px;
        font-size: 16px;
        font-weight: 800;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        max-width: 400px;
    }
    .btn-submit:hover {
        background-color: #008f56;
    }

    .btn-reset {
        background-color: #6c757d;
        color: #fff;
        border: none;
        padding: 14px;
        font-size: 16px;
        font-weight: 800;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        max-width: 400px;
    }
    .btn-reset:hover {
        background-color: #5a6268;
    }
</style>

</style>

<?php
// تحديد الخدمة المطلوبة بناءً على المعامل في الرابط
$service_key = $_GET['service'] ?? 'default';
$services_config = [
    'marriage' => [
        'title' => 'الاستفسار عن معاملات تصاريح الزواج',
        'header' => 'الاستعلام عن معاملات تصارِح زواج',
        'category' => 'ديوان وزارة الداخلية',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين إمكانية الاستعلام عن حالة معاملات تصاريح الزواج المقدمة لديوان الوزارة ومتابعة سيرها إلكترونياً.'
    ],
    'civil_inquiry' => [
        'title' => 'الاستعلام عن معاملات الاحوال المدنية',
        'header' => 'الاستعلام عن معاملات الاحوال المدنية',
        'category' => 'ديوان وزارة الداخلية',
        'desc' => 'تتيح هذه الخدمة للمستفيدين إمكانية الاستعلام عن المعاملات المتعلقة بالأحوال المدنية والمرفوعة لديوان وزارة الداخلية.'
    ],
    'labor_inquiry' => [
        'title' => 'الاستعلام عن معاملات مكتب العمل',
        'header' => 'الاستعلام عن معاملات مكتب العمل',
        'category' => 'ديوان وزارة الداخلية',
        'desc' => 'تتيح هذه الخدمة للمنشآت والأفراد الاستعلام عن المعاملات الخاصة بمكاتب العمل والمحالة للديوان.'
    ],
    'transaction' => [
        'title' => 'الاستعلام عن معاملة الأحوال المدنية',
        'header' => 'الاستعلام عن المعاملات',
        'category' => 'الأحوال المدنية',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين إمكانية الاستعلام عن حالة المعاملات المقدمة للأحوال المدنية.'
    ],
    'family_visa' => [
        'title' => 'الاستفسار عن طلب زيارة عائلية',
        'header' => 'الاستعلام عن طلبات الزيارة العائلية',
        'category' => 'الزيارات',
        'desc' => 'تتيح هذه الخدمة للمقيمين إمكانية الاستعلام عن حالة طلب تأشيرة زيارة عائلية.'
    ],
    'commercial_visa' => [
        'title' => 'الاستفسار عن طلب زيارة تجارية',
        'header' => 'الاستعلام عن طلبات الزيارة التجارية',
        'category' => 'الزيارات',
        'desc' => 'تتيح هذه الخدمة للمنشآت إمكانية الاستعلام عن حالة طلب تأشيرة زيارة تجارية.'
    ],
    'tourist_visa' => [
        'title' => 'الاستفسار عن طلب زيارة سياحية',
        'header' => 'الاستعلام عن طلبات الزيارة السياحية',
        'category' => 'الزيارات',
        'desc' => 'تتيح هذه الخدمة للزوار إمكانية الاستعلام عن حالة طلب تأشيرة زيارة سياحية.'
    ],
    'hajj' => [
        'title' => 'الاستعلام عن تصريح حج',
        'header' => 'الاستعلام عن تصاريح الحج',
        'category' => 'الحج والعمرة',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين التأكد من حالة تصريح الحج الخاص بهم.'
    ],
    'umrah' => [
        'title' => 'الاستعلام عن تأشيرة عمرة',
        'header' => 'الاستعلام عن تأشيرات العمرة',
        'category' => 'الحج والعمرة',
        'desc' => 'تتيح هذه الخدمة للمعتمرين إمكانية الاستعلام عن حالة تأشيرة العمرة.'
    ],
    'recruitment' => [
        'title' => 'الاستعلام عن طلب استقدام',
        'header' => 'الاستعلام عن طلبات الاستقدام',
        'category' => 'الاستقدام',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين إمكانية متابعة طلبات الاستقدام للعوائل.'
    ],
    'labor' => [
        'title' => 'الاستعلام عن تأشيرات الخروج والعودة',
        'header' => 'الاستعلام عن تأشيرات الخروج والعودة',
        'category' => 'الجوازات',
        'desc' => 'خدمة الاستعلام عن تأشيرات الخروج والعودة تتيح معرفة حالة التأشيرة الصادرة، سواء كانت سارية، منتهية، أو قيد التجهيز.'
    ],
    'change_profession' => [
        'title' => 'الاستعلام عن طلبات إصدار أو تجديد الجواز',
        'header' => 'الاستعلام عن طلبات إصدار أو تجديد الجواز',
        'category' => 'الجوازات',
        'desc' => 'خدمة الاستعلام عن طلبات إصدار أو تجديد الجواز تتيح متابعة حالة طلب إصدار أو تجديد جواز السفر ومعرفة إذا تم الموافقة عليه أو قيد المعالجة.'
    ],
    'id_validity' => [
        'title' => 'الاستعلام عن صلاحية الهوية',
        'header' => 'الاستعلام عن صلاحية الهوية',
        'category' => 'الأحوال المدنية',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين التأكد من صلاحية بطاقة الهوية الوطنية أو الإقامة.'
    ],
    'passports' => [
        'title' => 'الاستعلام عن صلاحية الجواز',
        'header' => 'الاستعلام عن صلاحية الجواز',
        'category' => 'الجوازات',
        'desc' => 'خدمة الاستعلام عن صلاحية الجواز تتيح التأكد من مدى سريان جواز السفر وتاريخ انتهاء صلاحيته.'
    ],
    'recruitment_family' => [
        'title' => 'الاستعلام عن طلب تأشيرة الاستقدام العائلي',
        'header' => 'الاستعلام عن طلب تأشيرة الاستقدام العائلي',
        'category' => 'الاستقدام',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين إمكانية متابعة طلبات تأشيرة الاستقدام العائلي ومعرفة حالة الطلب.'
    ],
    'traffic' => [
        'title' => 'الاستعلام عن المخالفات المرورية',
        'header' => 'الاستعلام عن المخالفات المرورية',
        'category' => 'المرور',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين الاستعلام عن المخالفات المرورية المسجلة.'
    ],
    'emirates' => [
        'title' => 'الاستعلام عن معاملات الإمارات',
        'header' => 'الاستعلام عن معاملات الإمارات',
        'category' => 'الإمارات',
        'desc' => 'تتيح هذه الخدمة الاستعلام عن المعاملات المقدمة لإمارات المناطق.'
    ],
    'followup' => [
        'title' => 'متابعة الطلبات',
        'header' => 'متابعة الطلبات',
        'category' => 'الجوازات',
        'desc' => 'تتيح هذه الخدمة للمواطنين والمقيمين إمكانية متابعة حالات الطلبات المقدمة للجوازات.'
    ],
    'default' => [
        'title' => 'الاستفسار عن المعاملات',
        'header' => 'الاستعلام عن معاملات',
        'category' => 'ديوان وزارة الداخلية',
        'desc' => 'تتيح هذه الخدمة الإلكترونية للمواطنين والمقيمين إمكانية الاستعلام عن حالة المعاملات المختلفة المقدمة لديوان وزارة الداخلية.'
    ]
];

$config = $services_config[$service_key] ?? $services_config['default'];
?>

<div class="civil-inquiry-wrapper">

    <!-- Breadcrumbs Section -->
    <div class="breadcrumb-container">
        <a href="inquiry.php">الاستعلامات الإلكترونية</a>
        <span>&gt;</span>
        <a href="#"><?php echo $config['category']; ?></a>
        <span>&gt;</span>
        <span><?php echo $config['header']; ?></span>
    </div>

    <!-- Main Card -->
    <div class="inquiry-card">
        <div class="inquiry-card-header">
            <?php echo $config['header']; ?>
        </div>

        <div class="inquiry-card-body">
            
            <?php if (isset($_SESSION['inquiry_error'])): ?>
                <div class="alert-info-custom" style="background:#f2dede; border-color:#ebccd1; color:#a94442;">
                    <?php echo htmlspecialchars($_SESSION['inquiry_error']);
    unset($_SESSION['inquiry_error']); ?>
                </div>
            <?php
endif; ?>

            <div class="service-info">
                <?php echo $config['desc']; ?>
            </div>

            <div class="section-title-bar">
                معلومات شخصية
            </div>

            <div class="alert-info-custom">
                الرجاء إدخال رقم الهوية ورقم الصادر ثم اضغط على عرض.
            </div>

            <form action="<?php echo BASE_URL; ?>smart_inquiry.php" method="POST" target="_blank" onsubmit="setTimeout(refreshCaptcha, 500);">
                <input type="hidden" name="action" value="smart_inquiry">
                <input type="hidden" name="service_key" value="<?php echo htmlspecialchars($service_key); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

                <!-- National ID -->
                <div class="form-group-row">
                    <label class="custom-label">رقم الهوية <span class="req">*</span></label>
                    <input type="text" name="national_id" class="custom-input" placeholder="أدخل رقم الهوية" required>
                </div>

                <!-- Issue Number -->
                <div class="form-group-row">
                    <label class="custom-label">رقم الصادر <span class="req">*</span></label>
                    <input type="text" name="issue_number" class="custom-input" placeholder="أدخل الرقم الصادر" required>
                </div>

                <!-- Captcha -->
                <div class="form-group-row">
                    <label class="custom-label">الرمز المرئي</label>
                    <div class="captcha-row">
                        <div class="captcha-box">
                            <div class="captcha-lines">
                                <div class="captcha-line-1"></div>
                                <div class="captcha-line-2"></div>
                            </div>
                            <span id="captcha_display" class="captcha-code"><?php echo $_SESSION['captcha']; ?></span>
                        </div>
                        <button type="button" class="refresh-btn" onclick="refreshCaptcha()" title="تحديث">
                            <i class="fas fa-sync-alt refresh-icon" id="refreshIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Captcha Input -->
                <div class="form-group-row">
                    <label class="custom-label">أدخل الرمز المرئي <span class="req">*</span></label>
                    <input type="text" name="captcha_code" class="custom-input" placeholder="أدخل الرمز" required style="max-width: 200px;">
                </div>

                <!-- Buttons Stacked -->
                <div class="btn-container">
                    <button type="submit" class="btn-submit">عرض</button>
                    <button type="reset" class="btn-reset">مسح</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
