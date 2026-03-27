<?php
// views/admin_manager.php
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'مدير' && $_SESSION['user_type'] !== 'Manager')) {
    die('غير مصرح لك بالوصول.');
}
$current_section = $_GET['section'] ?? 'dashboard';
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block admin-sidebar sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="d-flex justify-content-between align-items-center mb-3 px-3 d-md-none">
                    <h5 class="text-white mb-0">لوحة تحكم المدير</h5>
                    <button type="button" class="btn-close btn-close-white sidebar-close-btn" aria-label="أغلق"></button>
                </div>
                <h4 class="text-center mb-4 d-none d-md-block">لوحة تحكم المدير</h4>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="?admin=1" class="nav-link <?php echo ($current_section === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i>الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=add_data" class="nav-link <?php echo ($current_section === 'add_data') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle me-2"></i>إدخال الطلبات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=manage_employees" class="nav-link <?php echo ($current_section === 'manage_employees') ? 'active' : ''; ?>">
                            <i class="fas fa-users me-2"></i>موظفيني
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=requests" class="nav-link <?php echo ($current_section === 'requests') ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt me-2"></i>إدارة الطلبات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=pending_review" class="nav-link <?php echo ($current_section === 'pending_review') ? 'active' : ''; ?>">
                            <i class="fas fa-tasks me-2"></i>المراجعة
                            <?php 
                            $pending_statuses = ['قيد المراجعة', 'مقبول', 'تمت المراجعة', 'تمت الموافقة'];
                            $pending = array_filter($all_requests ?? [], fn($r) => in_array(trim($r['status']), $pending_statuses));
                            if(count($pending) > 0) echo '<span class="badge bg-danger rounded-pill float-end">'.count($pending).'</span>';
                            ?>
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="?logout=1" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-3 admin-content">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div class="d-flex align-items-center" id="main-header">
                    <h2 class="h2-responsive ms-2 mb-0">لوحة المدير</h2>
                </div>
            </div>

            <?php if ($current_section === 'dashboard'): ?>
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card border-success">
                            <div class="stat-icon text-success"><i class="fas fa-file-alt"></i></div>
                            <div class="stat-number"><?php echo count($all_requests ?? []); ?></div>
                            <div class="stat-label">إجمالي الطلبات للقسم</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card border-warning">
                            <div class="stat-icon text-warning"><i class="fas fa-clock"></i></div>
                            <div class="stat-number"><?php echo count(array_filter($all_requests ?? [], fn($r) => trim($r['status']) == 'قيد المراجعة')); ?></div>
                            <div class="stat-label">طلبات بانتظار المراجعة</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card border-info">
                            <div class="stat-icon text-info"><i class="fas fa-user-friends"></i></div>
                            <div class="stat-number"><?php echo count(array_filter($all_users ?? [], fn($u) => $u['user_id'] != $_SESSION['user_id'])); ?></div>
                            <div class="stat-label">عدد موظفيك</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="stat-card border-primary">
                            <div class="stat-icon text-primary"><i class="fas fa-print"></i></div>
                            <div class="stat-number">
                                <?php 
                                $total_prints = 0;
                                foreach($all_requests ?? [] as $req) $total_prints += ($req['printed_count'] ?? 0);
                                echo $total_prints;
                                ?>
                            </div>
                            <div class="stat-label">المعاملات المطبوعة</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title mb-0">آخر الطلبات (نظرة عامة)</h5></div>
                            <div class="card-body">
                                <canvas id="weeklyRequestsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($current_section === 'manage_employees'): ?>
                <?php require 'admin/manager/employees.php'; ?>
            <?php elseif ($current_section === 'add_data'): ?>
                <?php require_once 'admin/serves/add_data.php'; ?>
            <?php elseif ($current_section === 'requests' || $current_section === 'my_requests'): ?>
                <!-- Employees Requests List by Employee Selection -->
                <?php
                $my_team = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $_SESSION['user_id'] || $u['user_id'] == $_SESSION['user_id']);
                $selected_emp_id = $_GET['emp_id'] ?? null;
                
                // If an employee is selected, filter. Otherwise show overall list
                $list_requests = $all_requests ?? [];
                if ($selected_emp_id) {
                    $list_requests = array_filter($list_requests, fn($r) => $r['created_by_user_id'] == $selected_emp_id);
                }
                ?>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-white"><h6 class="mb-0 text-primary"><i class="fas fa-users me-2"></i>موظفيني</h6></div>
                            <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                                <a href="?admin=1&section=requests" 
                                   class="list-group-item list-group-item-action fw-bold <?php echo !$selected_emp_id ? 'active bg-primary text-white border-primary' : ''; ?>">
                                   <i class="fas fa-layer-group me-2"></i>عرض الكل
                                </a>
                                <?php foreach($my_team as $emp): 
                                    $is_me = ($emp['user_id'] == $_SESSION['user_id']);
                                    $emp_name = $is_me ? 'أنا (إدخالاتي المشرفة)' : $emp['email'];
                                    $icon = $is_me ? 'fa-user-tie' : 'fa-user';
                                ?>
                                    <a href="?admin=1&section=requests&emp_id=<?php echo $emp['user_id']; ?>" 
                                       class="list-group-item list-group-item-action <?php echo $selected_emp_id == $emp['user_id'] ? 'active bg-primary text-white border-primary' : ''; ?>">
                                       <i class="fas <?php echo $icon; ?> me-2"></i><?php echo htmlspecialchars($emp_name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mb-4">
                        <div class="card border-0 shadow-sm">
                             <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">إدارة الطلبات</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-center align-middle border">
                                        <thead style="background:var(--primary-accent);color:#fff">
                                            <tr>
                                                <th>رقم الصادر</th>
                                                <th>اسم مقدم الطلب</th>
                                                <th>رقم الهوية</th>
                                                <th>نوع الطلب</th>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($list_requests)): ?>
                                                <tr><td colspan="7" class="text-muted p-4">لا يوجد طلبات لهذا الموظف.</td></tr>
                                            <?php else: ?>
                                                <?php foreach($list_requests as $req): 
                                                    $service_name = explode('_', $req['source_table'])[0];
                                                    // Mapping system table names to Arabic display
                                                    $ar_services = [
                                                        'marriage' => 'تصريح زواج', 'family' => 'زيارة عائلية', 'business' => 'زيارة تجارية',
                                                        'tourism' => 'زيارة سياحية', 'recruitment' => 'استقدام', 'labor' => 'مكتب العمل',
                                                        'civil' => 'أحوال مدنية', 'profession' => 'تغيير مهنة', 'followup' => 'تعقيب'
                                                    ];
                                                    $display_service = $ar_services[$service_name] ?? $service_name;
                                                ?>
                                                    <tr data-id="<?php echo $req['id']; ?>" data-source-table="<?php echo $req['source_table']; ?>">
                                                        <td data-label="رقم الصادر" class="small" data-field="export_number"><?php echo htmlspecialchars($req['export_number'] ?? '---'); ?></td>
                                                        <td data-label="اسم مقدم الطلب" class="fw-bold" data-field="applicant_name"><?php echo htmlspecialchars($req['applicant_name'] ?? '---'); ?></td>
                                                        <td data-label="رقم الهوية" class="small" data-field="national_id"><?php echo htmlspecialchars($req['national_id'] ?? '---'); ?></td>
                                                        <td data-label="نوع الطلب" class="small"><?php echo $display_service; ?></td>
                                                        <td data-label="التاريخ" class="small" data-field="created_at"><?php echo date('Y/m/d', strtotime($req['created_at'])); ?></td>
                                                        <td data-label="الحالة" data-field="status">
                                                            <?php echo get_status_badge($req['status']); ?>
                                                        </td>
                                                        <td data-label="الإجراءات">
                                                            <div class="btn-group shadow-sm">
                                                                <?php if ($req['is_locked'] == 1): ?>
                                                                    <?php 
                                                                        $locker_id = $req['locked_by_user_id'] ?? null;
                                                                        $locker = $users_by_id[$locker_id] ?? ['user_type' => 'Root'];
                                                                        $locker_role = $locker['user_type'];
                                                                        
                                                                        $can_unlock = false;
                                                                        if ($_SESSION['user_type'] === 'Root') $can_unlock = true;
                                                                        elseif ($_SESSION['user_type'] === 'Admin' && $locker_role !== 'Root') $can_unlock = true;
                                                                    ?>
                                                                    <?php if ($can_unlock): ?>
                                                                        <button class="btn btn-sm btn-success btn-unlock-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="فك تعليق المعاملة (فتح القفل)">
                                                                            <i class="fas fa-lock-open"></i> فك التعليق
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-dark p-2 text-wrap" style="max-width: 150px;"><i class="fas fa-lock me-1"></i> معلقة</span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <a href="index.php?admin=1&section=view_request&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" class="btn btn-sm btn-info text-white" title="عرض"><i class="fas fa-eye"></i></a>
                                                                    
                                                                    <?php if ($_SESSION['user_type'] !== 'موظف'): ?>
                                                                    <button class="btn btn-sm btn-warning btn-edit" title="تعديل"><i class="fas fa-pencil-alt"></i></button>
                                                                    <button class="btn btn-sm btn-success btn-save" style="display:none;" title="حفظ"><i class="fas fa-check"></i></button>
                                                                    <button class="btn btn-sm btn-secondary btn-cancel" style="display:none;" title="إلغاء"><i class="fas fa-times"></i></button>
                                                                    <?php endif; ?>

                                                                    <a href="?admin=1&section=print_view&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" target="_blank" class="btn btn-sm btn-secondary" title="طباعة"><i class="fas fa-print"></i></a>

                                                                    <?php if (in_array($_SESSION['user_type'], ['Root', 'Admin'])): ?>
                                                                        <button class="btn btn-sm btn-outline-dark btn-lock-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="تعليق المعاملة (قفل)"><i class="fas fa-user-lock"></i></button>
                                                                    <?php endif; ?>

                                                                    <?php if ($_SESSION['user_type'] === 'Root'): ?>
                                                                    <button class="btn btn-sm btn-danger btn-delete-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="حذف"><i class="fas fa-trash"></i></button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($current_section === 'pending_review'): ?>
                <!-- Pending Review List -->
                <?php
                $list_requests = array_filter($all_requests ?? [], fn($r) => in_array(trim($r['status']), ['قيد المراجعة', 'مقبول', 'تمت المراجعة', 'تمت الموافقة']));
                ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">طلبات قيد المراجعة</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center align-middle border">
                                <thead style="background:var(--primary-accent);color:#fff">
                                    <tr>
                                        <th>الرمز</th>
                                        <th>نوع الطلب</th>
                                        <th>مقدم الطلب</th>
                                        <th>أدخله</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($list_requests)): ?>
                                        <tr><td colspan="7" class="text-muted p-4">لا يوجد طلبات.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($list_requests as $req): 
                                            // Find who created it
                                            $creator_info = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $req['created_by_user_id']))[0] ?? null;
                                            $creator_name = 'مدير (أنت)';
                                            if ($creator_info && $creator_info['user_id'] != $_SESSION['user_id']) {
                                                $creator_name = 'الموظف: ' . $creator_info['email'];
                                            }
                                        ?>
                                            <tr>
                                                <td class="fw-bold"><?php echo $req['id']; ?></td>
                                                <td><?php echo explode('_', $req['source_table'])[0]; ?></td>
                                                <td><?php echo htmlspecialchars($req['applicant_name'] ?? 'غير متوفر'); ?></td>
                                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($creator_name); ?></span></td>
                                                <td><?php echo date('Y-m-d', strtotime($req['created_at'])); ?></td>
                                                <td>
                                                    <!-- Inline review controls -->
                                                    <?php if ($req['is_locked'] == 1): ?>
                                                        <span class="badge bg-dark p-2"><i class="fas fa-lock me-1"></i> معلقة</span>
                                                    <?php else: ?>
                                                        <select class="form-select form-select-sm status-changer d-inline-block w-auto shadow-sm fw-bold" 
                                                                data-id="<?php echo $req['id']; ?>" 
                                                                data-table="<?php echo $req['source_table']; ?>"
                                                                onchange="this.className='form-select form-select-sm status-changer d-inline-block w-auto shadow-sm fw-bold ' + (this.options[this.selectedIndex].dataset.bgClass || '');"
                                                                style="min-width: 140px;">
                                                            <?php 
                                                            $opts = [
                                                                'قيد المراجعة' => 'bg-warning text-dark',
                                                                'تمت المراجعة' => 'bg-success text-white',
                                                                'مقبول' => 'bg-success text-white',
                                                                'تمت الموافقة' => 'bg-success text-white',
                                                                'مرفوض' => 'bg-danger text-white',
                                                                'يحتاج تعديل' => 'bg-info text-dark',
                                                                'بانتظار موافقة المدير' => 'bg-primary text-white'
                                                            ];
                                                            foreach($opts as $val => $bg):
                                                                $sel = (trim($req['status']) == $val || ($val == 'مقبول' && trim($req['status']) == 'تمت الموافقة')) ? 'selected' : '';
                                                                $bg_class = $bg;
                                                            ?>
                                                                <option value="<?php echo $val; ?>" <?php echo $sel; ?> data-bg-class="<?php echo $bg_class; ?>" class="<?php echo $bg_class; ?>"><?php echo $val; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    <?php endif; ?>
                                                    <script>
                                                        // Set initial color for the select
                                                        (function() {
                                                            const sel = document.querySelector('select.status-changer[data-id="<?php echo $req['id']; ?>"]');
                                                            if(sel) sel.className += ' ' + (sel.options[sel.selectedIndex].dataset.bgClass || '');
                                                        })();
                                                    </script>
                                                </td>
                                                <td>
                                                    <?php if ($req['is_locked'] == 1): ?>
                                                        <span class="text-muted small">لا يوجد إجراءات</span>
                                                    <?php else: ?>
                                                        <a href="index.php?admin=1&section=view_request&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.status-changer').forEach(select => {
        select.addEventListener('change', function() {
            const table = this.dataset.table;
            const id = this.dataset.id;
            const newStatus = this.value;
            
            fetch('api/update_status.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: id, table: table, status: newStatus})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    // show toast and remove row if we are in pending review
                    alert('تم تغيير الحالة بنجاح: ' + newStatus);
                    this.closest('tr').style.opacity = '0.5';
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert('خطأ: ' + data.message);
                }
            });
        });
    });
});
</script>

<?php 
require_once 'admin/shared/styles.php'; 
require_once 'admin/shared/scripts.php';
?>
