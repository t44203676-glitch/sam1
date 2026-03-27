<?php
// views/admin_manager_employees.php
// التأكد من أن المدير فقط يمكنه الوصول لهذه الصفحة
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'مدير' && $_SESSION['user_type'] !== 'Manager')) {
    die('غير مصرح لك بالوصول.');
}
$my_employees = array_filter($all_users ?? [], fn($u) => $u['manager_id'] == $_SESSION['user_id']);
?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom pb-3">
        <h5 class="mb-0 text-primary"><i class="fas fa-users me-2"></i>موظفيني</h5>
        <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#empModal">
            <i class="fas fa-plus"></i> إضافة موظف
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3">البريد الإلكتروني (Email)</th>
                        <th class="py-3">كلمة المرور</th>
                        <th class="py-3">تاريخ الإنشاء</th>
                        <th class="py-3">الحالة</th>
                        <th class="py-3">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($my_employees)): ?>
                        <tr><td colspan="4" class="text-muted p-5">لا يوجد لديك موظفون حالياً.</td></tr>
                    <?php else: ?>
                        <?php foreach($my_employees as $emp): ?>
                            <tr>
                                <td class="fw-bold"><?php echo htmlspecialchars($emp['email']); ?></td>
                                <td class="text-secondary small font-monospace"><?php echo htmlspecialchars($emp['password'] ?? '---'); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($emp['created_at'])); ?></td>
                                <td><?php echo $emp['is_banned'] ? '<span class="badge bg-danger">محظور</span>' : '<span class="badge bg-success">نشط</span>'; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-warning rounded-circle me-1 btn-edit-emp"
                                        title="تعديل"
                                        data-bs-toggle="modal" data-bs-target="#empModal"
                                        data-id="<?php echo $emp['user_id']; ?>"
                                        data-username="<?php echo htmlspecialchars($emp['email']); ?>"
                                        data-banned="<?php echo $emp['is_banned']; ?>">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('تأكيد حذف الموظف نهائياً؟');">
                                        <input type="hidden" name="action" value="delete_user">
                                        <input type="hidden" name="user_id" value="<?php echo $emp['user_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="empModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="empModalLabel">إضافة موظف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="empForm" action="index.php" method="POST" class="p-3">
                <div class="modal-body">
                    <input type="hidden" name="action" id="empFormAction" value="add_user">
                    <input type="hidden" name="user_id" id="empId" value="">
                    <input type="hidden" name="user_type" value="موظف"> <!-- Force employee type -->
                    <input type="hidden" name="manager_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- Force current manager -->
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">البريد الإلكتروني (Email)</label>
                        <input type="text" class="form-control rounded-3 py-2 bg-light border-0" id="empName" name="username" placeholder="example@domain.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" class="form-control rounded-3 py-2 bg-light border-0" id="empPass" name="password" placeholder="***">
                            <button class="btn btn-outline-secondary border-0 bg-light" type="button" id="toggleEmpPassM">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text small" id="empPassHelp">اتركه فارغاً لعدم التغيير (في حالة التعديل)</div>
                    </div>
                    <script>
                    document.getElementById('toggleEmpPassM').addEventListener('click', function (e) {
                        const passInp = document.getElementById('empPass');
                        const type = passInp.getAttribute('type') === 'password' ? 'text' : 'password';
                        passInp.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    });
                    </script>
                    <div class="mb-2 form-check bg-light p-3 rounded-3" id="empBannedBox" style="display:none;">
                        <input type="hidden" name="is_banned" value="0">
                        <input type="checkbox" class="form-check-input ms-2 mt-1 mx-2" id="empBanned" name="is_banned" value="1" style="transform: scale(1.3);">
                        <label class="form-check-label text-danger fw-bold ms-1" style="margin-right: 25px;">حظر حساب الموظف (إيقاف الدخول)</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 text-muted border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">حـفـظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-edit-emp').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('empModalLabel').innerText = 'تعديل بيانات الموظف';
            document.getElementById('empFormAction').value = 'update_user';
            document.getElementById('empId').value = btn.dataset.id;
            document.getElementById('empName').value = btn.dataset.username;
            document.getElementById('empPass').required = false;
            document.getElementById('empPassHelp').style.display = 'block';
            document.getElementById('empBannedBox').style.display = 'block';
            document.getElementById('empBanned').checked = btn.dataset.banned == '1';
        });
    });
    
    // reset form when close/add
    const empModalEl = document.getElementById('empModal');
    empModalEl.addEventListener('hidden.bs.modal', function() {
        document.getElementById('empForm').reset();
    });
    
    document.querySelector('[data-bs-target="#empModal"]:not(.btn-edit-emp)').addEventListener('click', () => {
        document.getElementById('empModalLabel').innerText = 'إضافة موظف جديد';
        document.getElementById('empFormAction').value = 'add_user';
        document.getElementById('empId').value = '';
        document.getElementById('empPass').required = true;
        document.getElementById('empPassHelp').style.display = 'none';
        document.getElementById('empBannedBox').style.display = 'none';
        document.getElementById('empBanned').checked = false;
    });
});
</script>
