<?php
// admin/root/user_requests.php - Hierarchy-based Request Management for Root and Admin
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'Root' && $_SESSION['user_type'] !== 'Admin')) {
    die('غير مصرح لك بالوصول.');
}

$logged_user_type = $_SESSION['user_type'];
$logged_user_id = $_SESSION['user_id'];

$sel_admin_id = $_GET['admin_id'] ?? null;
$sel_manager_id = $_GET['manager_id'] ?? null;
$sel_employee_id = $_GET['employee_id'] ?? null;
$sel_root_id = $_GET['root_id'] ?? null;

$active_user_id = $sel_employee_id ?: ($sel_manager_id ?: ($sel_admin_id ?: $sel_root_id));

// Default to self if no user is selected
if (!$active_user_id) {
    $active_user_id = $logged_user_id;
}

// Security: Non-Root users cannot view Root's branch or data via URL manipulation
if ($logged_user_type !== 'Root' && $active_user_id == 1) {
    $active_user_id = $logged_user_id; // Fallback to self
}

$active_user = null;
if ($active_user_id) {
    $active_user = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $active_user_id))[0] ?? null;
}

// Logic to fetch requests for the WHOLE BRANCH
$target_requests = [];
if ($active_user) {
    $user_id = $active_user['user_id'];
    
    // Identify all IDs in the branch
    $branch_ids = [$user_id];
    if (in_array($active_user['user_type'], ['Root', 'Admin'])) {
        $children = array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $user_id && $u['user_id'] != $user_id);
        foreach($children as $child) {
            $branch_ids[] = $child['user_id'];
            if($child['user_type'] === 'Admin') {
                $m_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $child['user_id']), 'user_id');
                $branch_ids = array_merge($branch_ids, $m_ids);
                if(!empty($m_ids)) {
                    $e_ids = array_column(array_filter($all_users ?? [], fn($u) => in_array($u['manager_id'], $m_ids)), 'user_id');
                    $branch_ids = array_merge($branch_ids, $e_ids);
                }
            } elseif(in_array($child['user_type'], ['مدير', 'Manager'])) {
                $e_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $child['user_id']), 'user_id');
                $branch_ids = array_merge($branch_ids, $e_ids);
            }
        }
    } elseif (in_array($active_user['user_type'], ['مدير', 'Manager'])) {
        $e_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $user_id), 'user_id');
        $branch_ids = array_merge($branch_ids, $e_ids);
    }
    
    $branch_ids = array_unique($branch_ids);
    $target_requests = array_filter($all_requests ?? [], fn($r) => in_array($r['created_by_user_id'], $branch_ids));
}

// Service Name Mapping
$service_names = [
    'marriage_permits'       => 'تصريح زواج',
    'civil_affairs_requests' => 'الأحوال المدنية',
    'business_visits'        => 'زيارة تجارية',
    'tourism_visits'         => 'زيارة سياحية',
    'family_visits'          => 'زيارة عائلية',
    'labor_requests'         => 'تعقيب العمالة',
    'profession_changes'     => 'تعديل مهنة',
    'followup_requests'      => 'التعقيب',
    'recruitment_requests'   => 'استقدام'
];
?>
<style>
    @media (max-width: 768px) {
        .admin-tree-container { flex-direction: column; }
        .admin-tree-container > .col-md-3 { width: 100%; height: auto !important; border-bottom: 2px solid #dee2e6; }
        .admin-tree-container > .col-md-9 { width: 100%; }
    }
</style>
<div class="row g-0 admin-tree-container">
    <div class="col-md-3 border-end bg-light" style="overflow-y: auto; height: calc(100vh - 120px);">
        <div class="p-3">
            <h5 class="mb-3 border-bottom pb-2 text-primary fw-bold"><i class="fas fa-sitemap me-2"></i>اختر مستخدم</h5>
            <div class="list-group list-group-flush">
                <?php 
                if ($logged_user_type === 'Root'):
                ?>
                    <div class="mb-2">
                        <a href="?admin=1&section=requests&root_id=1" class="list-group-item list-group-item-action border-0 rounded fw-bold <?php echo ($sel_root_id == 1) ? 'active shadow-sm' : 'bg-white border-bottom'; ?>">
                            <i class="fas fa-crown text-warning me-2"></i>المسؤول الرئيسي (Root)
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mb-2">
                        <a href="?admin=1&section=requests&admin_id=<?php echo $logged_user_id; ?>" class="list-group-item list-group-item-action border-0 rounded fw-bold <?php echo ($sel_admin_id == $logged_user_id) ? 'active shadow-sm' : 'bg-white border-bottom'; ?>">
                            <i class="fas fa-user-shield text-danger me-2"></i>لوحتي (Admin)
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                $main_children = ($logged_user_type === 'Root') 
                    ? array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == 1 && $u['user_id'] != 1 && in_array($u['user_type'], ['Admin', 'Manager', 'مدير']))
                    : array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $logged_user_id && in_array($u['user_type'], ['Manager', 'مدير']));

                foreach($main_children as $child): 
                    $is_sel = ($sel_admin_id == $child['user_id'] || $sel_manager_id == $child['user_id']);
                    $icon_c = ($child['user_type'] === 'Admin') ? 'text-danger' : 'text-info';
                    $icon_n = ($child['user_type'] === 'Admin') ? 'fa-user-shield' : 'fa-user-tie';
                ?>
                    <div class="mb-1">
                        <a href="?admin=1&section=requests&<?php echo ($child['user_type'] === 'Admin' ? 'admin_id' : 'manager_id'); ?>=<?php echo $child['user_id']; ?>" 
                           class="list-group-item list-group-item-action border-0 rounded d-flex justify-content-between align-items-center <?php echo $is_sel ? 'active shadow-sm' : ''; ?>">
                            <span><i class="fas <?php echo $icon_n; ?> <?php echo $is_sel ? '' : $icon_c; ?> me-2"></i><?php echo htmlspecialchars($child['email']); ?></span>
                        </a>
                        <?php if($is_sel): ?>
                            <div class="ms-3 mt-1 border-start ps-2">
                                <?php
                                if($child['user_type'] === 'Admin'):
                                    $smngs = array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $child['user_id'] && in_array($u['user_type'], ['مدير', 'Manager']));
                                    foreach($smngs as $sm):
                                        $sm_sel = ($sel_manager_id == $sm['user_id']);
                                ?>
                                        <a href="?admin=1&section=requests&admin_id=<?php echo $child['user_id']; ?>&manager_id=<?php echo $sm['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-1 <?php echo $sm_sel ? 'bg-white border text-primary fw-bold' : ''; ?>">
                                            <i class="fas fa-user-tie text-info me-2"></i><?php echo htmlspecialchars($sm['email']); ?>
                                        </a>
                                        <?php if($sm_sel):
                                            $semps = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $sm['user_id']);
                                            foreach($semps as $se): ?>
                                                <a href="?admin=1&section=requests&admin_id=<?php echo $child['user_id']; ?>&manager_id=<?php echo $sm['user_id']; ?>&employee_id=<?php echo $se['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-0 ms-3 <?php echo ($sel_employee_id == $se['user_id']) ? 'text-success fw-bold p-1 bg-light border' : 'text-muted'; ?>">
                                                    <i class="fas fa-user me-2 opacity-50"></i><?php echo htmlspecialchars($se['email']); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                <?php endforeach; ?>
                                <?php else:
                                    $m_emps = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $child['user_id']);
                                    foreach($m_emps as $me): ?>
                                        <a href="?admin=1&section=requests&manager_id=<?php echo $child['user_id']; ?>&employee_id=<?php echo $me['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-1 <?php echo ($sel_employee_id == $me['user_id']) ? 'text-success fw-bold bg-light border' : 'text-muted'; ?>">
                                            <i class="fas fa-user me-2 opacity-50"></i><?php echo htmlspecialchars($me['email']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-9 bg-white p-4 h-100">
        <?php if($active_user): ?>
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h4 class="mb-1">طلبات: <span class="text-primary fw-bold"><?php echo htmlspecialchars($active_user['email']); ?></span></h4>
                    <span class="badge bg-secondary"><?php echo $active_user['user_type']; ?></span>
                    <span class="ms-3 text-muted small">إجمالي الطلبات في هذا المسار: <strong class="text-dark"><?php echo count($target_requests); ?></strong></span>
                </div>
                <?php if (!empty($target_requests) && $_SESSION['user_type'] === 'Root'): ?>
                <button class="btn btn-danger btn-sm shadow-sm" id="btnDeleteBranchRequests">
                    <i class="fas fa-trash-alt me-1"></i>حذف كافة الطلبات في هذا المسار
                </button>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle small">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:45px;">#</th>
                            <th>رقم الصادر</th>
                            <th>اسم مقدم الطلب</th>
                            <th>رقم الهوية</th>
                            <th>نوع الخدمة</th>
                            <th>مدخل بواسطة</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $all_reqs_arr = array_values($target_requests);
                        // ترتيب من الأحدث إلى الأقدم
                        usort($all_reqs_arr, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
                        $total_reqs   = count($all_reqs_arr);
                        // آخر 3 مؤشرات (الصفوف الأولى المعروضة = الأحدث)
                        // Create a map for faster user lookups
                        $users_by_id = [];
                        foreach ($all_users ?? [] as $u) {
                            $users_by_id[$u['user_id']] = $u;
                        }

                        foreach($all_reqs_arr as $row_idx => $req): 
                            $cr = $users_by_id[$req['created_by_user_id']] ?? null;
                            $row_bg  = isset($last3_colors[$row_idx]) ? '--bs-table-bg:' . $last3_colors[$row_idx] . '; background-color:' . $last3_colors[$row_idx] . ' !important;' : '';
                            // الترقيم من الأسفل: أقدم طلب = 1، أحدث طلب = الأعلى
                            $row_num = $total_reqs - $row_idx;
                            // التاريخ والوقت بتوقيت 12 ساعة بالعربية
                            $ts      = strtotime($req['created_at']);
                            $ampm_ar = date('A', $ts) === 'AM' ? 'ص' : 'م';
                            $date_str = date('Y-m-d', $ts) . ' ' . date('h:i', $ts) . ' ' . $ampm_ar;
                        ?>
                            <tr data-id="<?php echo $req['id']; ?>" data-source-table="<?php echo $req['source_table']; ?>" style="<?php echo $row_bg; ?>">
                                <td class="fw-bold text-muted"><?php echo $row_num; ?></td>
                                <td data-label="رقم الصادر" class="fw-bold text-primary" data-field="export_number"><?php echo htmlspecialchars(toWesternDigits($req['export_number'] ?? '---')); ?></td>
                                <td data-label="اسم مقدم الطلب" data-field="applicant_name"><?php echo htmlspecialchars($req['applicant_name'] ?? '---'); ?></td>
                                <td data-label="رقم الهوية" class="text-secondary" data-field="national_id"><?php echo htmlspecialchars(toWesternDigits($req['national_id'] ?? '---')); ?></td>
                                <td data-label="نوع الخدمة"><span class="badge bg-light text-dark border"><?php echo $service_names[$req['source_table']] ?? $req['source_table']; ?></span></td>
                                <td data-label="مدخل بواسطة"><div class="small fw-bold text-muted"><?php echo htmlspecialchars($cr['email'] ?? 'Root'); ?></div></td>
                                <td data-label="التاريخ" data-field="created_at"><?php echo $date_str; ?></td>
                                <td data-label="الحالة" data-field="status"><?php echo get_status_badge($req['status']); ?></td>
                                <td data-label="الإجراءات">
                                    <div class="btn-group btn-group-sm">
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
                                                <span class="badge bg-dark p-2"><i class="fas fa-lock me-1"></i> معلقة</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="index.php?admin=1&section=view_request&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" class="btn btn-outline-info" title="عرض"><i class="fas fa-eye"></i></a>
                                            
                                            <?php if ($_SESSION['user_type'] !== 'موظف'): ?>
                                            <button class="btn btn-outline-warning btn-edit" title="تعديل"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-success btn-save" style="display:none;" title="حفظ"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-secondary btn-cancel" style="display:none;" title="إلغاء"><i class="fas fa-times"></i></button>
                                            <?php endif; ?>

                                            <?php if (in_array($_SESSION['user_type'], ['Root', 'Admin', 'مدير'])): ?>
                                            <a href="index.php?admin=1&section=print_view&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" target="_blank" class="btn btn-outline-secondary" title="طباعة"><i class="fas fa-print"></i></a>
                                            <?php endif; ?>

                                            <?php if (in_array($_SESSION['user_type'], ['Root', 'Admin'])): ?>
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
                        <?php if(empty($target_requests)): ?>
                            <tr><td colspan="9" class="text-center py-5 text-muted">لا يوجد طلبات مسجلة في هذا المسار.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted opacity-50">
                <i class="fas fa-folder-open fs-1 mb-3"></i>
                <h5>اختر مسؤولاً من الشجرة لرؤية كامل طلبات التابعين له</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function viewReq(table, id) {
    window.location.href = `index.php?admin=1&section=view&source=${table}&id=${id}`;
}
function delReq(table, id) {
    if(confirm('سيتم حذف الطلب نهائياً، هل أنت متأكد؟')) {
        const f = new FormData();
        f.append('action', 'delete_request');
        f.append('source', table);
        f.append('id', id);
        fetch('index.php', { method: 'POST', body: f }).then(() => location.reload());
    }
}
function printReq(table, id) {
    window.open(`index.php?action=admin_print&source=${table}&id=${id}`, '_blank');
}

document.getElementById('btnDeleteBranchRequests')?.addEventListener('click', function() {
    if (confirm('سيتم حذف كافة الطلبات المعروضة في هذا المسار نهائياً. هل أنت متأكد؟')) {
        const branchIds = <?php echo json_encode(array_values($branch_ids ?? [])); ?>;
        fetch('api/delete_all_user_requests.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_ids: branchIds })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('حدث خطأ أثناء محاولة الحذف.'));
    }
});
</script>
