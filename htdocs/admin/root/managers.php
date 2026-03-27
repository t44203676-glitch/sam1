<?php
// views/admin_manage_managers.php
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'Root' && $_SESSION['user_type'] !== 'Admin')) {
    die('غير مصرح لك بالوصول.');
}
$success_messages = [
    'add' => 'تم إضافة المدير بنجاح.',
    'update' => 'تم تحديث بيانات المدير بنجاح.',
    'delete' => 'تم حذف المدير بنجاح.'
];
$success_key = $_GET['success'] ?? null;
?>
<main class="container my-4">
    <h3 class="text-center mb-4">إدارة المدراء</h3>
    <?php if (isset($success_messages[$success_key])): ?>
        <div class="alert alert-success"><?php echo $success_messages[$success_key]; ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#managerModal">
            <i class="fas fa-plus me-1"></i> إضافة مدير جديد
        </button>
        <a href="index.php?admin=1" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-1"></i> الرجوع للوحة
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>المستخدم</th>
                    <th>كلمة المرور</th>
                    <th>النوع</th>
                    <th>فريق العمل</th>
                    <th>إجمالي الطباعة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>بواسطة</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $managers = array_filter($all_users ?? [], fn($u) => in_array($u['user_type'], ['مدير', 'Manager', 'Admin', 'Root']));
                if (!empty($managers)): 
                    foreach ($managers as $user): 
                        $emp_count = count(array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $user['user_id']));
                        $creator_info = array_values(array_filter($all_users ?? [], fn($u) => $u['user_id'] == ($user['created_by_user_id'] ?? 0)))[0] ?? null;
                        $creator_name = $creator_info ? $creator_info['email'] : 'النظام/Root';
                ?>
                        <tr>
                            <td data-label="المستخدم">
                                <a href="?admin=1&section=manage_employees&manager_id=<?php echo $user['user_id']; ?>" class="text-decoration-none fw-bold">
                                    <i class="fas <?php echo $user['user_type'] === 'Admin' ? 'fa-user-shield text-danger' : 'fa-id-badge'; ?> me-1"></i><?php echo htmlspecialchars($user['email']); ?>
                                </a>
                            </td>
                            <td data-label="كلمة المرور" class="small font-monospace text-muted"><?php echo htmlspecialchars($user['password'] ?? '---'); ?></td>
                            <td data-label="النوع"><span class="badge bg-light text-dark border"><?php echo $user['user_type']; ?></span></td>
                            <td data-label="فريق العمل"><?php echo $emp_count; ?> موظف</td>
                            <td data-label="إجمالي الطباعة">
                                <?php 
                                // Calculate total prints for this manager's section
                                $team_ids = array_map(fn($u) => $u['user_id'], array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $user['user_id'] || $u['user_id'] == $user['user_id']));
                                $manager_prints = array_sum(array_map(fn($r) => $r['printed_count'] ?? 0, array_filter($all_requests ?? [], fn($r) => in_array($r['created_by_user_id'], $team_ids))));
                                echo '<span class="badge bg-primary">'.$manager_prints.'</span>';
                                ?>
                            </td>
                            <td data-label="تاريخ الإنشاء"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            <td data-label="بواسطة" class="small text-muted"><?php echo htmlspecialchars($creator_name); ?></td>
                            <td data-label="الحالة"><?php echo $user['is_banned'] ? '<span class="badge bg-danger">محظور</span>' : '<span class="badge bg-success">نشط</span>'; ?></td>
                            <td data-label="الإجراءات">
                                <a href="?admin=1&section=manage_employees&manager_id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-list-ul"></i> عرض القسم
                                </a>
                                <button class="btn btn-sm btn-warning btn-edit"
                                        data-bs-toggle="modal" data-bs-target="#managerModal"
                                        data-id="<?php echo $user['user_id']; ?>"
                                        data-username="<?php echo htmlspecialchars($user['email']); ?>"
                                        data-type="<?php echo $user['user_type']; ?>"
                                        data-banned="<?php echo $user['is_banned']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                                        <input type="hidden" name="action" value="delete_user">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">لا يوجد مدراء لعرضهم.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<div class="modal fade" id="managerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="managerForm" action="index.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="managerModalLabel">إضافة مدير</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="formActionM" value="add_user">
                    <input type="hidden" name="user_id" id="userIdM" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">نوع الحساب</label>
                        <select class="form-select" name="user_type" id="userTypeM">
                            <option value="مدير">مدير (Manager)</option>
                            <?php if ($_SESSION['user_type'] === 'Root'): ?>
                                <option value="Admin">آدمين (Admin - تحكم في مدراءه)</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>البريد الإلكتروني (Email)</label>
                        <input type="text" class="form-control" id="usernameM" name="username" placeholder="example@domain.com" required>
                    </div>
                    <div class="mb-3">
                        <label>كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="passwordM" name="password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordM">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <script>
                    document.getElementById('togglePasswordM').addEventListener('click', function (e) {
                        const passwordInput = document.getElementById('passwordM');
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    });
                    </script>
                    <div class="mb-3 form-check" id="bannedCheckContainer" style="display:none;">
                        <input type="hidden" name="is_banned" value="0">
                        <input type="checkbox" class="form-check-input" id="isBannedM" name="is_banned" value="1">
                        <label class="form-check-label text-danger">حظر الحساب</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('managerModal');
    if (!modal) return;
    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const isEdit = btn.classList.contains('btn-edit');
        if (isEdit) {
            document.getElementById('managerModalLabel').textContent = 'تعديل مدير';
            document.getElementById('formActionM').value = 'update_user';
            document.getElementById('userIdM').value = btn.dataset.id;
            document.getElementById('usernameM').value = btn.dataset.username;
            document.getElementById('userTypeM').value = btn.dataset.type || 'مدير';
            document.getElementById('passwordM').required = false;
            
            const bannedCheck = document.getElementById('bannedCheckContainer');
            bannedCheck.style.display = 'block';
            document.getElementById('isBannedM').checked = (btn.dataset.banned == '1');
        } else {
            document.getElementById('managerModalLabel').textContent = 'إضافة مدير';
            document.getElementById('formActionM').value = 'add_user';
            document.getElementById('managerForm').reset();
            document.getElementById('passwordM').required = true;
            document.getElementById('bannedCheckContainer').style.display = 'none';
        }
    });
});
</script>
