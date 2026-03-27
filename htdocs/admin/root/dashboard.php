<?php
// views/admin_root.php
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'Root' && $_SESSION['user_type'] !== 'Admin')) {
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
                    <h5 class="text-white mb-0">لوحة تحكم (<?php echo $_SESSION['user_type']; ?>)</h5>
                    <button type="button" class="btn-close btn-close-white sidebar-close-btn" aria-label="أغلق"></button>
                </div>
                <h4 class="text-center mb-4 d-none d-md-block">لوحة تحكم (<?php echo $_SESSION['user_type']; ?>)</h4>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="?admin=1" class="nav-link <?php echo ($current_section === 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=admins" class="nav-link <?php echo ($current_section === 'admins') ? 'active' : ''; ?>">
                            <i class="fas fa-chart-line me-2"></i>إدارة إحصائيات المستخدمين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=manage_employees" class="nav-link <?php echo ($current_section === 'manage_employees') ? 'active' : ''; ?>">
                            <i class="fas fa-copy me-2"></i>إدارة طلبات المستخدمين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=manage_managers" class="nav-link <?php echo ($current_section === 'manage_managers') ? 'active' : ''; ?>">
                            <i class="fas fa-user-tie me-2"></i>إدارة المدراء
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=add_data" class="nav-link <?php echo ($current_section === 'add_data') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle me-2"></i>إدخال الطلبات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?admin=1&section=requests" class="nav-link <?php echo ($current_section === 'requests') ? 'active' : ''; ?>">
                            <i class="fas fa-list me-2"></i>إدارة الطلبات
                        </a>
                    </li>
                    <?php if ($_SESSION['user_type'] === 'Root'): ?>
                    <li class="nav-item">
                        <a href="?admin=1&section=query_logs" class="nav-link <?php echo ($current_section === 'query_logs') ? 'active' : ''; ?>">
                            <i class="fas fa-history me-2"></i>سجل الاستعلامات
                        </a>
                    </li>
                    <?php endif; ?>
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
                    <h2 class="h2-responsive ms-2 mb-0">لوحة القيادة العليا (Root)</h2>
                </div>
            </div>
            
            <?php 
            if ($current_section === 'dashboard'): 
                $managers_count = 0;
                $employees_count = 0;
                $admins_count = 0;
                foreach($all_users ?? [] as $u) {
                    if($u['user_type'] === 'مدير' || $u['user_type'] === 'Manager') $managers_count++;
                    if($u['user_type'] === 'موظف' || $u['user_type'] === 'Employee') $employees_count++;
                    if($u['user_type'] === 'Admin') $admins_count++;
                }
            ?>
                <!-- Stats -->
                <div class="row mb-4">
                    <?php if($_SESSION['user_type'] === 'Root'): ?>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-primary"><i class="fas fa-user-shield"></i></div>
                            <div class="stat-number"><?php echo $admins_count; ?></div>
                            <div class="stat-label">عدد الـ Admin</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-info"><i class="fas fa-user-tie"></i></div>
                            <div class="stat-number"><?php echo $managers_count; ?></div>
                            <div class="stat-label">عدد المدراء</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-info"><i class="fas fa-users"></i></div>
                            <div class="stat-number"><?php echo $employees_count; ?></div>
                            <div class="stat-label">عدد الموظفين</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-primary"><i class="fas fa-envelope-open-text"></i></div>
                            <div class="stat-number"><?php echo $stats['total_requests'] ?? 0; ?></div>
                            <div class="stat-label">إجمالي الطلبات</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-warning"><i class="fas fa-hourglass-half"></i></div>
                            <div class="stat-number"><?php echo $stats['pending_approval'] ?? 0; ?></div>
                            <div class="stat-label">قيد المراجعة</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-number"><?php 
                                $appr = 0;
                                foreach($requests_by_status ?? [] as $r) { if(strpos($r['status'], 'موافق')!==false || strpos($r['status'], 'مقبول')!==false) $appr+=$r['count']; }
                                echo $appr; 
                            ?></div>
                            <div class="stat-label">الطلبات المقبولة</div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mb-3 px-1">
                        <div class="stat-card">
                            <div class="stat-icon text-danger"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-number"><?php echo $stats['rejected_requests'] ?? 0; ?></div>
                            <div class="stat-label">الطلبات المرفوضة</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title mb-0">الطلبات خلال آخر 7 أيام</h5></div>
                            <div class="card-body"><canvas id="weeklyRequestsChart"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header"><h5 class="card-title mb-0">توزيع حالات الطلبات</h5></div>
                            <div class="card-body"><canvas id="statusDistributionChart"></canvas></div>
                        </div>
                    </div>
                </div>
            <?php elseif ($current_section === 'admins'): ?>
                <?php require 'admin/root/admins.php'; ?>
            <?php elseif ($current_section === 'manage_managers'): ?>
                <?php require 'admin/root/managers.php'; ?>
            <?php elseif ($current_section === 'manage_employees'): ?>
                <?php require 'admin/root/user_requests.php'; ?>
            <?php elseif ($current_section === 'requests'): ?>
                <!-- Global Requests -->
                <?php
                $services = [
                    'marriage_permits' => 'تصاريح الزواج',
                    'family_visits' => 'الزيارات العائلية',
                    'business_visits' => 'زيارات الأعمال',
                    'tourist_visits' => 'الزيارات السياحية',
                    'recruitment' => 'الاستقدام',
                    'labor_office' => 'مكتب العمل',
                    'civil_affairs' => 'الأحوال المدنية',
                    'profession_change' => 'تغيير المهنة',
                    'followup' => 'التعقيب',
                ];
                ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="card-title mb-0">إدارة الجميــع الطلبات</h5>
                        <div class="d-flex align-items-center flex-grow-1 flex-md-grow-0 gap-2 flex-wrap flex-md-nowrap">
                            <input type="text" id="searchInput" class="form-control" placeholder="بحث..." style="min-width: 120px; flex: 1;">
                            <select id="serviceFilter" class="form-select" style="min-width: 120px; flex: 1;">
                                <option value="">كل الخدمات</option>
                                <?php foreach ($services as $key => $name): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="statusFilter" class="form-select" style="min-width: 120px; flex: 1;">
                                <option value="">جميع الحالات</option>
                                <?php foreach ($all_statuses ?? [] as $status): ?>
                                    <option value="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center align-middle border stack-on-mobile">
                                <thead style="background:var(--primary-accent);color:#fff">
                                    <tr>
                                        <th style="width:45px;">#</th>
                                        <th>رقم الصادر</th>
                                        <th>اسم مقدم الطلب</th>
                                        <th>رقم الهوية</th>
                                        <th>نوع الطلب</th>
                                        <th>بواسطة</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="requestsTableBody">
                                    <?php 
                                    // ترتيب من الأحدث إلى الأقدم
                                    $dash_reqs = array_values($all_requests ?? []);
                                    usort($dash_reqs, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
                                    $dash_total = count($dash_reqs);
                                    $dash_colors = [
                                        0 => '#fffacc',
                                        1 => '#d4f8d4',
                                        2 => '#d0f0ff',
                                    ];
                                    if(empty($dash_reqs)): ?>
                                        <tr><td colspan="9" class="text-center p-4">لا توجد طلبات</td></tr>
                                    <?php else: ?>
                                        <?php foreach($dash_reqs as $ridx => $req): 
                                            $creator_info = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $req['created_by_user_id']))[0] ?? null;
                                            $creator_name = $creator_info ? $creator_info['email'] : 'غير معروف';
                                            $service_name = explode('_', $req['source_table'])[0];
                                            $ar_services = [
                                                'marriage' => 'تصريح زواج', 'family' => 'زيارة عائلية', 'business' => 'زيارة تجارية',
                                                'tourism' => 'زيارة سياحية', 'recruitment' => 'استقدام', 'labor' => 'مكتب العمل',
                                                'civil' => 'أحوال مدنية', 'profession' => 'تغيير مهنة', 'followup' => 'تعقيب'
                                            ];
                                            $display_service = $ar_services[$service_name] ?? $service_name;
                                            $row_bg   = isset($dash_colors[$ridx]) ? '--bs-table-bg:' . $dash_colors[$ridx] . '; background-color:' . $dash_colors[$ridx] . ' !important;' : '';
                                            $row_num  = $dash_total - $ridx;
                                            $ts       = strtotime($req['created_at']);
                                            $ampm_ar  = date('A', $ts) === 'AM' ? 'ص' : 'م';
                                            $date_str = date('Y-m-d', $ts) . ' ' . date('h:i', $ts) . ' ' . $ampm_ar;
                                        ?>
                                            <tr data-id="<?php echo $req['id']; ?>" data-source-table="<?php echo $req['source_table']; ?>" style="<?php echo $row_bg; ?>">
                                                <td class="fw-bold text-muted"><?php echo $row_num; ?></td>
                                                <td data-label="رقم الصادر" class="small" data-field="export_number"><?php echo htmlspecialchars(toWesternDigits($req['export_number'] ?? '---')); ?></td>
                                                <td data-label="اسم مقدم الطلب" class="fw-bold" data-field="applicant_name"><?php echo htmlspecialchars($req['applicant_name'] ?? '---'); ?></td>
                                                <td data-label="رقم الهوية" class="small" data-field="national_id"><?php echo htmlspecialchars(toWesternDigits($req['national_id'] ?? '---')); ?></td>
                                                <td data-label="نوع الطلب" class="small"><?php echo $display_service; ?></td>
                                                <td data-label="بواسطة" class="small text-muted"><?php echo htmlspecialchars($creator_name); ?></td>
                                                <td data-label="التاريخ" class="small" data-field="created_at"><?php echo $date_str; ?></td>
                                                <td data-label="الحالة" data-field="status">
                                                    <?php echo get_status_badge($req['status']); ?>
                                                </td>
                                                <td data-label="الإجراءات">
                                                    <div class="btn-group btn-group-sm shadow-sm">
                                                        <?php if ($req['is_locked'] == 1): ?>
                                                            <?php 
                                                                $locker_id = $req['locked_by_user_id'] ?? null;
                                                                $locker_info = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $locker_id))[0] ?? ['user_type' => 'Root'];
                                                                $locker_role = $locker_info['user_type'];
                                                                
                                                                $can_unlock = false;
                                                                if ($_SESSION['user_type'] === 'Root') $can_unlock = true;
                                                                elseif ($_SESSION['user_type'] === 'Admin' && $locker_role !== 'Root') $can_unlock = true;
                                                            ?>
                                                            <?php if ($can_unlock): ?>
                                                                <button class="btn btn-success btn-unlock-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="فك تعليق المعاملة">
                                                                    <i class="fas fa-lock-open"></i> فك التعليق
                                                                </button>
                                                            <?php else: ?>
                                                                <span class="badge bg-dark p-2"><i class="fas fa-lock me-1"></i> معلقة</span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <a href="index.php?admin=1&section=view_request&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" class="btn btn-outline-info" title="عرض"><i class="fas fa-eye"></i></a>
                                                            <button class="btn btn-outline-warning btn-edit" title="تعديل"><i class="fas fa-pencil-alt"></i></button>
                                                            <button class="btn btn-success btn-save" style="display:none;" title="حفظ"><i class="fas fa-check"></i></button>
                                                            <button class="btn btn-secondary btn-cancel" style="display:none;" title="إلغاء"><i class="fas fa-times"></i></button>
                                                            <a href="?admin=1&section=print_view&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" target="_blank" class="btn btn-outline-secondary" title="طباعة"><i class="fas fa-print"></i></a>
                                                            
                                                            <?php if ($_SESSION['user_type'] === 'Root' || $_SESSION['user_type'] === 'Admin'): ?>
                                                                <button class="btn btn-outline-dark btn-lock-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="تعليق المعاملة (قفل)"><i class="fas fa-user-lock"></i></button>
                                                            <?php endif; ?>

                                                            <?php if ($_SESSION['user_type'] === 'Root'): ?>
                                                                <button class="btn btn-outline-danger btn-delete-request" data-id="<?php echo $req['id']; ?>" data-table="<?php echo $req['source_table']; ?>" title="حذف"><i class="fas fa-trash"></i></button>
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
            <?php elseif ($current_section === 'inquiry'): ?>
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <?php require_once 'views/admin_inquiry_form.php'; ?>
                    </div>
                </div>
            <?php elseif ($current_section === 'add_data'): ?>
                <?php require_once 'admin/serves/add_data.php'; ?>
            <?php elseif ($current_section === 'org_chart'): ?>
                <div class="card">
                    <div class="card-header"><h5 class="card-title">الهيكل الإداري</h5></div>
                    <div class="card-body">
                        <!-- Simple Tree View -->
                        <ul class="tree">
                            <li>
                                <strong>Root</strong>
                                <ul>
                                    <?php 
                                    $managers = array_filter($all_users ?? [], fn($u) => $u['user_type'] === 'مدير' || $u['user_type'] === 'Manager');
                                    foreach($managers as $manager): 
                                    ?>
                                    <li>
                                        <em>المدير: <?php echo htmlspecialchars($manager['email']); ?></em>
                                        <ul>
                                            <?php 
                                            $employees = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $manager['user_id']);
                                            foreach($employees as $emp): 
                                            ?>
                                            <li>الموظف: <?php echo htmlspecialchars($emp['email']); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <style>
                .tree, .tree ul { list-style-type: none; margin: 0; padding: 0; }
                .tree ul { margin-left: 20px; border-left: 1px dashed #ccc; padding-left: 20px; }
                .tree li { margin: 10px 0; position: relative; }
                .tree li::before { content: ''; position: absolute; top: 12px; left: -20px; width: 15px; border-top: 1px dashed #ccc; }
                </style>
            <?php elseif ($current_section === 'query_logs' && $_SESSION['user_type'] === 'Root'): ?>
                <?php require_once 'views/query_logs.php'; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php 
require_once 'admin/shared/styles.php'; 
require_once 'admin/shared/scripts.php';
?>
