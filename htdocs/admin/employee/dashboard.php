<?php
// views/admin_employee.php
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'موظف' && $_SESSION['user_type'] !== 'Employee')) {
    die('غير مصرح لك بالوصول.');
}
$current_section = $_GET['section'] ?? 'dashboard';

// مسمى الخدمات للجدول
$service_names_map = [
    'marriage_permits'       => 'تصريح زواج',
    'family_visits'          => 'زيارة عائلية',
    'tourism_visits'         => 'زيارة سياحية',
    'business_visits'        => 'زيارة تجارية',
    'labor_requests'         => 'تعقيب العمالة',
    'followup_requests'      => 'التعقيب',
    'profession_changes'     => 'تعديل مهنة',
    'civil_affairs_requests' => 'أحوال مدنية',
    'recruitment_requests'   => 'استقدام',
    'runaway_cancellations'  => 'إلغاء بلاغ هروب'
];

// مسمى النماذج للتحرير (Reverse mapping)
$table_to_form_map = [
    'marriage_permits'       => 'marriage',
    'family_visits'          => 'family_visit',
    'tourism_visits'         => 'tourism',
    'business_visits'        => 'business_visit',
    'labor_requests'         => 'labor',
    'followup_requests'      => 'followup',
    'profession_changes'     => 'profession_change',
    'civil_affairs_requests' => 'civil_affairs',
    'recruitment_requests'   => 'recruitment',
    'runaway_cancellations'  => 'runaway_cancellation'
];
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar sidebar collapse shadow-sm" style="background: #f8f9fa; min-height: 100vh; border-left: 1px solid #dee2e6;">
            <div class="position-sticky pt-3">
                <div class="d-flex justify-content-between align-items-center mb-3 px-3 d-md-none">
                    <h5 class="text-white mb-0 fw-bold">لوحة الموظف</h5>
                    <button type="button" class="btn-close btn-close-white sidebar-close-btn" aria-label="أغلق"></button>
                </div>
                <div class="text-center mb-4 d-none d-md-block">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-0">لوحة الموظف</h5>
                </div>
                <ul class="nav nav-pills flex-column px-2">
                    <li class="nav-item mb-2">
                        <a href="?admin=1" class="nav-link py-3 <?php echo ($current_section === 'dashboard' || $current_section === 'my_requests') ? 'active shadow' : 'text-dark'; ?>" style="border-radius: 12px;">
                            <i class="fas fa-th-large me-2"></i> القائمة الرئيسية
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="?admin=1&section=add_data" class="nav-link py-3 <?php echo ($current_section === 'add_data' || $current_section === 'view_request') ? 'active shadow' : 'text-dark'; ?>" style="border-radius: 12px;">
                            <i class="fas fa-edit me-2"></i> إدخال بيانات
                        </a>
                    </li>
                    <li class="nav-item mt-auto">
                        <hr>
                        <a href="?logout=1" class="nav-link py-3 text-danger fw-bold" style="border-radius: 12px;">
                            <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 admin-content py-4">
            <?php if ($current_section === 'dashboard' || $current_section === 'my_requests'): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-primary mb-0"><i class="fas fa-clipboard-list me-2"></i> القائمة الرئيسية - طلباتي</h2>
                    <a href="?admin=1&section=add_data" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 30px; padding: 10px 25px;">
                        <i class="fas fa-plus-circle me-1"></i> إدخال بيانات جديد
                    </a>
                </div>

                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                    <div class="card-header bg-primary text-white p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">سجل الطلبات - كافة الطلبات</h5>
                            <span class="badge bg-white text-primary rounded-pill"><?php echo count($all_requests ?? []); ?> طلب</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-center" id="requestsTable">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="py-3">اسم مقدم الطلب</th>
                                        <th>رقم الهوية</th>
                                        <th>نوع الخدمة</th>
                                        <th>تاريخ الإدخال</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $requests_to_show = $all_requests ?? [];
                                    if(empty($requests_to_show)): ?>
                                        <tr><td colspan="6" class="text-muted p-5">لا توجد طلبات مدخلة حالياً.</td></tr>
                                    <?php else: foreach($requests_to_show as $req): 
                                        $service_title = $service_names_map[$req['source_table']] ?? $req['source_table'];
                                        $form_type = $table_to_form_map[$req['source_table']] ?? '';
                                    ?>
                                        <tr>
                                            <td data-label="اسم مقدم الطلب" class="fw-bold"><?php echo htmlspecialchars($req['applicant_name'] ?? '---'); ?></td>
                                            <td data-label="رقم الهوية"><?php echo htmlspecialchars($req['national_id'] ?? '---'); ?></td>
                                            <td data-label="نوع الخدمة"><span class="badge bg-light text-dark border"><?php echo $service_title; ?></span></td>
                                            <td data-label="تاريخ الإدخال"><?php echo date('Y-m-d', strtotime($req['created_at'])); ?></td>
                                            <td data-label="الحالة">
                                                <?php echo get_status_badge($req['status']); ?>
                                            </td>
                                            <td data-label="الإجراءات">
                                                <?php if (($req['status'] ?? '') !== 'تم تعليق المعاملة'): ?>
                                                <div class="btn-group">
                                                    <a href="?admin=1&section=view_request&table=<?php echo $req['source_table']; ?>&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-outline-info" title="عرض">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                                <?php else: ?>
                                                    ---
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php elseif ($current_section === 'add_data'): ?>
                <div class="mb-4">
                    <h2 class="h3 fw-bold text-primary mb-1"><i class="fas fa-edit me-2"></i> إدخال بيانات جديد</h2>
                    <p class="text-muted">يرجى اتباع الخطوات لإكمال ملف الطلب.</p>
                </div>
                <?php require_once 'admin/serves/add_data.php'; ?>
            <?php endif; ?>

        </main>
    </div>
</div>

<?php 
require_once 'admin/shared/styles.php'; 
require_once 'admin/shared/scripts.php';
?>
