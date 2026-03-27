<?php
// views/print_data_form.php

// Define the services available for printing
$services = [
    'family_visits' => 'الزيارات العائلية',
    'business_visits' => 'زيارات الأعمال',
    'labor_office' => 'مكتب العمل',
    // Add other services here as needed
];

?>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">طباعة بيانات الخدمة على PDF</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">إعدادات الطباعة</h5>
                </div>
                <div class="card-body">
                    <form action="print_service_data.php" method="post" enctype="multipart/form-data" target="_blank">
                        <div class="mb-3">
                            <label for="service_type" class="form-label">اختر الخدمة:</label>
                            <select class="form-select" id="service_type" name="service_type" required>
                                <option value="" disabled selected>-- اختر نوع الخدمة --</option>
                                <?php foreach ($services as $key => $name): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="request_id" class="form-label">رقم الطلب (ID):</label>
                            <input type="number" class="form-control" id="request_id" name="request_id" placeholder="أدخل رقم الطلب المراد طباعة بياناته" required>
                            <div class="form-text">أدخل الرقم التعريفي (ID) للطلب من جدول الخدمة المحدد.</div>
                        </div>

                        <div class="mb-3">
                            <label for="pdf_template" class="form-label">قالب PDF:</label>
                            <input class="form-control" type="file" id="pdf_template" name="pdf_template" accept=".pdf" required>
                            <div class="form-text">ارفع ملف PDF الذي سيتم استخدامه كقالب للكتابة عليه.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="coordinates" class="form-label">إحداثيات الحقول (اختياري):</label>
                            <textarea class="form-control" id="coordinates" name="coordinates" rows="5" placeholder="أدخل الإحداثيات لكل حقل بصيغة JSON. إذا ترك فارغاً، سيتم استخدام الإحداثيات الافتراضية."></textarea>
                            <div class="form-text">
                                مثال على الصيغة:
                                <pre dir="ltr" class="text-start p-2 bg-light border rounded"><code>{
    "applicant_name": {"x": 50, "y": 60, "w": 150, "h": 10},
    "passport_number": {"x": 50, "y": 75, "w": 100, "h": 10}
}</code></pre>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-print me-2"></i>معاينة وطباعة
                        </button>                    </form>
                </div>
            </div>
        </main>
    </div>
</div>