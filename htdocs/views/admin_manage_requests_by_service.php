<?php
// views/admin_manage_requests_by_service.php

// قائمة الخدمات مع مفاتيح تطابق أسماء الجداول في قاعدة البيانات
$services = [
    'marriage_permits' => 'تصاريح الزواج',
    'family_visits' => 'الزيارات العائلية',
    'business_visits' => 'زيارات الأعمال',
    'tourist_visits' => 'الزيارات السياحية',
    'recruitment' => 'الاستقدام',
    'labor_office' => 'مكتب العمل',
    'civil_affairs' => 'الأحوال المدنية',
    'nationality' => 'الجنسية',
    'profession_change' => 'تغيير المهنة',
    'absconding_report_cancellation' => 'إلغاء بلاغ هروب',
];

$selected_service = $_GET['service'] ?? null;
$all_statuses = $GLOBALS['all_statuses'] ?? []; // جلب الحالات من النطاق العام
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-cogs me-2"></i>إدارة الطلبات
        </h5>
        <?php if ($selected_service): ?>
            <a href="?admin=1&section=requests" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-right me-1"></i> العودة إلى قائمة الخدمات
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (!$selected_service): ?>
            <h6 class="card-subtitle mb-3 text-muted">الرجاء اختيار خدمة لعرض الطلبات الخاصة بها:</h6>
            <div class="list-group">
                <?php foreach ($services as $key => $name): ?>
                    <a href="?admin=1&section=requests&service=<?php echo $key; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-folder me-2"></i><?php echo $name; ?>
                        </span>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">طلبات خدمة: <?php echo htmlspecialchars($services[$selected_service]); ?></h5>
            </div>
            
            <!-- Search and filter controls -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث في الطلبات (حسب الاسم, رقم الهوية, رقم الصادر)...">
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-select">
                        <option value="">جميع الحالات</option>
                        <?php foreach ($all_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Requests table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:45px;">#</th>
                            <th>رقم الصادر</th>
                            <th>اسم مقدم الطلب</th>
                            <th>رقم الهوية</th>
                            <th>نوع التصريح/الطلب</th>
                            <th>بواسطة</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">
                        <!-- Populated by JS -->
                        <tr><td colspan="9" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedService = '<?php echo $selected_service; ?>';
    if (!selectedService) return;

    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('requestsTableBody');
    let searchDebounceTimer;

    function fetchRequests() {
        const searchTerm = searchInput.value;
        const statusValue = statusFilter.value;

        tableBody.innerHTML = '<tr><td colspan="9" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        // تم تعديل الرابط ليشمل service
        fetch(`api/search_requests.php?service=${encodeURIComponent(selectedService)}&search=${encodeURIComponent(searchTerm)}&status=${encodeURIComponent(statusValue)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                tableBody.innerHTML = html || '<tr><td colspan="8" class="text-center alert alert-info">لا توجد طلبات لهذه الخدمة حاليًا.</td></tr>';
            })
            .catch(error => {
                console.error('Search Error:', error);
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center alert alert-danger">حدث خطأ أثناء تحميل الطلبات. الرجاء المحاولة مرة أخرى.</td></tr>';
            });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(fetchRequests, 350); // زيادة طفيفة في التأخير
    });

    statusFilter.addEventListener('change', fetchRequests);

    // تحميل الطلبات عند فتح الصفحة
    fetchRequests();

    // ملاحظة: كود الحذف والتعديل موجود بالفعل في `admin.php` وسيتم تطبيقه على الجدول الذي يتم تحميله هنا.
});
</script>
