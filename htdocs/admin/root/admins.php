<?php
// admin/root/admins.php - Tree-based Analytics for Root and Admin
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
$active_user = null;
if ($active_user_id) {
    $active_user = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $active_user_id))[0] ?? null;
}

// Logic to fetch user specific stats (Aggregate for the whole branch)
$user_stats = ['requests' => 0, 'prints' => 0, 'logins' => 0, 'last_ip' => '---', 'device' => '---'];
if ($active_user) {
    $user_id = $active_user['user_id'];
    
    // Identify all IDs in the branch
    $branch_ids = [$user_id];
    if (in_array($active_user['user_type'], ['Root', 'Admin'])) {
        // Add direct children (Managers or Admins)
        $children = array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $user_id && $u['user_id'] != $user_id);
        foreach($children as $child) {
            $branch_ids[] = $child['user_id'];
            // If child is Admin, add their managers and employees
            if($child['user_type'] === 'Admin') {
                $m_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $child['user_id']), 'user_id');
                $branch_ids = array_merge($branch_ids, $m_ids);
                if(!empty($m_ids)) {
                    $e_ids = array_column(array_filter($all_users ?? [], fn($u) => in_array($u['manager_id'], $m_ids)), 'user_id');
                    $branch_ids = array_merge($branch_ids, $e_ids);
                }
            }
            // If child is Manager, add their employees
            elseif(in_array($child['user_type'], ['مدير', 'Manager'])) {
                $e_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $child['user_id']), 'user_id');
                $branch_ids = array_merge($branch_ids, $e_ids);
            }
        }
    } elseif (in_array($active_user['user_type'], ['مدير', 'Manager'])) {
        // Add employees of this manager
        $e_ids = array_column(array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $user_id), 'user_id');
        $branch_ids = array_merge($branch_ids, $e_ids);
    }
    
    $branch_ids = array_unique($branch_ids);

    $user_stats['logins'] = $active_user['login_count'] ?? 0;
    $user_stats['last_ip'] = $active_user['last_ip'] ?? '---';
    $user_stats['last_login'] = $active_user['last_login'] ?? '---';

    $log_stmt = $pdo->prepare("SELECT user_agent FROM user_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $log_stmt->execute([$user_id]);
    $user_stats['device'] = $log_stmt->fetchColumn() ?: '---';

    $branch_requests = array_filter($all_requests ?? [], fn($r) => in_array($r['created_by_user_id'], $branch_ids));
    $user_stats['requests'] = count($branch_requests);
    $user_stats['prints'] = array_sum(array_column($branch_requests, 'printed_count'));
}

?>
<style>
    @media (max-width: 768px) {
        .admin-tree-container { flex-direction: column; }
        .admin-tree-container > .col-md-4 { width: 100%; height: auto !important; border-bottom: 2px solid #dee2e6; }
        .admin-tree-container > .col-md-8 { width: 100%; }
    }
</style>
<div class="row g-0 admin-tree-container">
    <div class="col-md-4 border-end bg-light" style="overflow-y: auto; height: calc(100vh - 120px);">
        <div class="p-3">
            <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-sitemap me-2 text-secondary"></i>هيكل الإدارة</h5>
            <div class="list-group list-group-flush">
                <?php 
                // --- 1. TOP NODE ---
                if ($logged_user_type === 'Root'):
                ?>
                    <div class="mb-2">
                        <a href="?admin=1&section=admins&root_id=1" class="list-group-item list-group-item-action border-0 rounded fw-bold <?php echo ($sel_root_id == 1) ? 'active shadow-sm' : 'bg-white border-bottom'; ?>">
                            <i class="fas fa-crown text-warning me-2"></i>المسؤول الرئيسي (Root)
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mb-2">
                        <a href="?admin=1&section=admins&admin_id=<?php echo $logged_user_id; ?>" class="list-group-item list-group-item-action border-0 rounded fw-bold <?php echo ($sel_admin_id == $logged_user_id) ? 'active shadow-sm' : 'bg-white border-bottom'; ?>">
                            <i class="fas fa-user-shield text-danger me-2"></i>لوحتي (Admin)
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                // --- 2. CHILDREN ---
                $main_children = ($logged_user_type === 'Root') 
                    ? array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == 1 && $u['user_id'] != 1 && in_array($u['user_type'], ['Admin', 'Manager', 'مدير']))
                    : array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $logged_user_id && in_array($u['user_type'], ['Manager', 'مدير']));

                foreach($main_children as $child): 
                    $is_sel = ($sel_admin_id == $child['user_id'] || $sel_manager_id == $child['user_id']);
                    $icon_c = ($child['user_type'] === 'Admin') ? 'text-danger' : 'text-info';
                    $icon_n = ($child['user_type'] === 'Admin') ? 'fa-user-shield' : 'fa-user-tie';
                    $sub_cnt = count(array_filter($all_users ?? [], fn($u) => ($child['user_type'] === 'Admin' ? $u['created_by_user_id'] : $u['manager_id']) == $child['user_id']));
                ?>
                    <div class="mb-1">
                        <a href="?admin=1&section=admins&<?php echo ($child['user_type'] === 'Admin' ? 'admin_id' : 'manager_id'); ?>=<?php echo $child['user_id']; ?>" 
                           class="list-group-item list-group-item-action border-0 rounded d-flex justify-content-between align-items-center <?php echo $is_sel ? 'active shadow-sm' : ''; ?>">
                            <span><i class="fas <?php echo $icon_n; ?> <?php echo $is_sel ? '' : $icon_c; ?> me-2"></i><?php echo htmlspecialchars($child['email']); ?> <small class="opacity-75">(<?php echo $sub_cnt; ?>)</small></span>
                            <i class="fas fa-chevron-left small opacity-50"></i>
                        </a>
                        <?php if($is_sel): ?>
                            <div class="ms-3 mt-1 border-start ps-2">
                                <?php
                                if($child['user_type'] === 'Admin'):
                                    $smngs = array_filter($all_users ?? [], fn($u) => $u['created_by_user_id'] == $child['user_id'] && in_array($u['user_type'], ['مدير', 'Manager']));
                                    foreach($smngs as $sm):
                                        $sm_sel = ($sel_manager_id == $sm['user_id']);
                                ?>
                                        <a href="?admin=1&section=admins&admin_id=<?php echo $child['user_id']; ?>&manager_id=<?php echo $sm['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-1 <?php echo $sm_sel ? 'bg-white border text-primary fw-bold' : ''; ?>">
                                            <i class="fas fa-user-tie text-info me-2"></i><?php echo htmlspecialchars($sm['email']); ?>
                                        </a>
                                        <?php if($sm_sel):
                                            $semps = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $sm['user_id']);
                                            foreach($semps as $se): ?>
                                                <a href="?admin=1&section=admins&admin_id=<?php echo $child['user_id']; ?>&manager_id=<?php echo $sm['user_id']; ?>&employee_id=<?php echo $se['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-0 ms-3 <?php echo ($sel_employee_id == $se['user_id']) ? 'text-success fw-bold p-1 bg-light border' : 'text-muted'; ?>">
                                                    <i class="fas fa-user me-2 opacity-50"></i><?php echo htmlspecialchars($se['email']); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                <?php endforeach; ?>
                                <?php else:
                                    $m_emps = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $child['user_id']);
                                    foreach($m_emps as $me): ?>
                                        <a href="?admin=1&section=admins&manager_id=<?php echo $child['user_id']; ?>&employee_id=<?php echo $me['user_id']; ?>" class="list-group-item list-group-item-action border-0 rounded small py-1 <?php echo ($sel_employee_id == $me['user_id']) ? 'text-success fw-bold bg-light border' : 'text-muted'; ?>">
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

    <!-- Main View -->
    <div class="col-md-8 bg-white p-4">
        <?php if($active_user): ?>
            <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-3">
                <div>
                    <h3 class="mb-1 text-primary"><?php echo htmlspecialchars($active_user['email']); ?></h3>
                    <span class="badge bg-secondary"><?php echo $active_user['user_type']; ?></span>
                    <span class="text-muted small ms-3">تاريخ الإنضمام: <?php echo date('Y-m-d', strtotime($active_user['created_at'])); ?></span>
                </div>
            </div>

            <div class="row mb-4 text-center">
                <div class="col-6 col-lg-3 mb-3"><div class="p-3 border rounded shadow-sm bg-light"><i class="fas fa-file-invoice text-primary mb-2 fs-4"></i><div class="fw-bold fs-5"><?php echo $user_stats['requests']; ?></div><div class="small text-muted">الطلبات</div></div></div>
                <div class="col-6 col-lg-3 mb-3"><div class="p-3 border rounded shadow-sm bg-light"><i class="fas fa-print text-success mb-2 fs-4"></i><div class="fw-bold fs-5"><?php echo $user_stats['prints']; ?></div><div class="small text-muted">الطباعة</div></div></div>
                <div class="col-6 col-lg-3 mb-3"><div class="p-3 border rounded shadow-sm bg-light"><i class="fas fa-sign-in-alt text-warning mb-2 fs-4"></i><div class="fw-bold fs-5"><?php echo $user_stats['logins']; ?></div><div class="small text-muted">الدخول</div></div></div>
                <div class="col-6 col-lg-3 mb-3"><div class="p-3 border rounded shadow-sm bg-light"><i class="fas fa-network-wired text-info mb-2 fs-4"></i><div class="text-truncate small fw-bold"><?php echo $user_stats['last_ip']; ?></div><div class="small text-muted">آخر IP</div></div></div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm"><div class="card-header bg-light"><i class="fas fa-info-circle me-1"></i> معلومات</div><div class="card-body">
                        <div class="mb-2 small text-muted">آخر ظهور: <strong><?php echo $user_stats['last_login']; ?></strong></div>
                        <div class="small bg-light p-2 rounded text-break" style="height: 60px; overflow-y:auto;"><?php echo htmlspecialchars($user_stats['device']); ?></div>
                    </div></div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm"><div class="card-header bg-light"><i class="fas fa-user-edit me-1"></i> التسلسل</div><div class="card-body">
                        <div class="small text-muted">المنشئ بواسطة: <strong><?php 
                            if($active_user['created_by_user_id'] == 1) echo "Root";
                            else {
                                $cr = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == $active_user['created_by_user_id']))[0] ?? null;
                                echo $cr ? htmlspecialchars($cr['email']) : 'غير معروف';
                            }
                        ?></strong></div>
                    </div></div>
                </div>
            </div>

            <div class="card mt-2 border-0 shadow-sm">
                <div class="card-header bg-dark text-white p-2 small"><i class="fas fa-history me-1"></i> آخر 10 عمليات دخول</div>
                <div class="table-responsive">
                    <table class="table table-sm hover mb-0 text-center small">
                        <thead><tr><th>التاريخ</th><th>الـ IP</th></tr></thead>
                        <tbody>
                            <?php 
                            $lstmt = $pdo->prepare("SELECT ip_address, created_at FROM user_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
                            $lstmt->execute([$active_user['user_id']]);
                            $ls = $lstmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach($ls as $l): ?>
                                <tr><td data-label="التاريخ"><?php echo $l['created_at']; ?></td><td data-label="الـ IP" class="text-primary fw-bold"><?php echo $l['ip_address']; ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted opacity-50"><i class="fas fa-mouse-pointer fs-1 mb-3"></i><h5>اختر مستخدماً لرؤية الإحصائيات</h5></div>
        <?php endif; ?>
    </div>
</div>
