<?php
// views/admin_root_manage_employees.php
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'Root' && $_SESSION['user_type'] !== 'Admin')) {
    die('غير مصرح لك بالوصول.');
}
$selected_manager_id = $_GET['manager_id'] ?? null;
$selected_employee_id = $_GET['employee_id'] ?? null;

$managers = array_filter($all_users ?? [], fn($u) => $u['user_type'] === 'مدير' || $u['user_type'] === 'Manager');
$employees = array_filter($all_users ?? [], fn($u) => $u['user_type'] === 'موظف' || $u['user_type'] === 'Employee');

// Fetch manager's/employee's requests if filter is active
$target_requests = [];
if ($selected_manager_id) {
    if ($selected_employee_id) {
        // Filter by specific employee
        $target_requests = array_filter($all_requests ?? [], fn($r) => $r['created_by_user_id'] == $selected_employee_id);
    } else {
        // Show everything under the manager
        $emp_ids = array_map(fn($e) => $e['user_id'], array_filter($employees, fn($e) => $e['manager_id'] == $selected_manager_id));
        $emp_ids[] = (int)$selected_manager_id;
        $target_requests = array_filter($all_requests ?? [], fn($r) => in_array($r['created_by_user_id'], $emp_ids));
    }
}
?>
<style>
    @media (max-width: 768px) {
        .row > .col-md-3, .row > .col-md-9, .row > .col-md-6 { width: 100%; }
        .card { margin-bottom: 20px; }
    }
</style>
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between">
        <h3>إدارة الموظفين والمسارات</h3>
        <div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#employeeModal"><i class="fas fa-plus"></i> إضافة موظف</button>
        </div>
    </div>
</div>

<div class="row">
    <!-- قائمة المدراء -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light">قائمة المدراء</div>
            <div class="list-group list-group-flush">
                <?php foreach($managers as $manager): ?>
                    <a href="?admin=1&section=manage_employees&manager_id=<?php echo $manager['user_id']; ?>" 
                       class="list-group-item list-group-item-action <?php echo $selected_manager_id == $manager['user_id'] ? 'active' : ''; ?>">
                       <i class="fas fa-user-tie me-2"></i><?php echo htmlspecialchars($manager['email']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- التفاصيل للمدير المحدد -->
    <div class="col-md-9">
        <?php if($selected_manager_id): 
            $selected_manager = array_values(array_filter($managers, fn($m) => $m['user_id'] == $selected_manager_id))[0] ?? null;
            $manager_emps = array_filter($employees, fn($e) => $e['manager_id'] == $selected_manager_id);
        ?>
            <div class="row">
                <!-- الموظفون -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-primary text-white d-flex justify-content-between">
                            <span>موظفو <?php echo htmlspecialchars($selected_manager['email'] ?? 'غير معروف'); ?> (<?php echo count($manager_emps); ?>)</span>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach($manager_emps as $emp): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="?admin=1&section=manage_employees&manager_id=<?php echo $selected_manager_id; ?>&employee_id=<?php echo $emp['user_id']; ?>" 
                                               class="text-decoration-none fw-bold <?php echo $selected_employee_id == $emp['user_id'] ? 'text-primary' : 'text-dark'; ?>">
                                                <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($emp['email']); ?>
                                            </a>
                                            <?php if($selected_employee_id == $emp['user_id']): ?>
                                                <a href="?admin=1&section=manage_employees&manager_id=<?php echo $selected_manager_id; ?>" class="btn btn-sm btn-link p-0 text-danger"><i class="fas fa-times-circle"></i></a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted small">Pass: <strong class="text-dark font-monospace"><?php echo htmlspecialchars($emp['password'] ?? '---'); ?></strong></span>
                                            <div>
                                                <?php echo $emp['is_banned'] ? '<span class="badge bg-danger p-1" style="font-size:10px">محظور</span>' : '<span class="badge bg-success p-1" style="font-size:10px">نشط</span>'; ?>
                                                <span class="text-muted" style="font-size:10px; margin-left:5px;"><?php echo date('Y-m-d', strtotime($emp['created_at'])); ?></span>
                                                <button class="btn btn-sm btn-light p-0 px-2 btn-edit-emp ms-1"
                                                    title="تعديل"
                                                    data-bs-toggle="modal" data-bs-target="#employeeModal"
                                                    data-id="<?php echo $emp['user_id']; ?>"
                                                    data-username="<?php echo htmlspecialchars($emp['email']); ?>"
                                                    data-banned="<?php echo $emp['is_banned']; ?>"
                                                    data-manager="<?php echo $emp['manager_id']; ?>"><i class="fas fa-edit text-warning"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; if(empty($manager_emps)) echo '<li class="list-group-item text-muted">لا يوجد موظفون</li>'; ?>
                        </ul>
                    </div>
                </div>

                <!-- الطلبات -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-info">
                        <div class="card-header bg-info text-white d-flex justify-content-between">
                            <span><?php echo $selected_employee_id ? 'طلبات الموظف' : 'طلبات القسم'; ?> (<?php echo count($target_requests); ?>)</span>
                            <?php if($selected_employee_id): ?>
                                <a href="?admin=1&section=manage_employees&manager_id=<?php echo $selected_manager_id; ?>" class="btn btn-sm btn-outline-light"><i class="fas fa-undo"></i> عرض الكل</a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-0" style="max-height:400px; overflow-y:auto;">
                            <table class="table table-sm table-striped m-0 text-center align-middle">
                                <thead style="position: sticky; top:0; background:#fff">
                                    <tr>
                                        <th>الرمز</th>
                                        <th>النوع</th>
                                        <th>الحالة</th>
                                        <th>أدخله</th>
                                        <th>إجراء</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($target_requests as $req): 
                                        $creator = array_values(array_filter($all_users, fn($u) => $u['user_id'] == $req['created_by_user_id']))[0] ?? null;
                                        $creator_name = $creator ? ($creator['user_type'] === 'مدير' ? 'المدير' : $creator['email']) : 'مجهول';
                                    ?>
                                        <tr>
                                            <td data-label="الرمز"><?php echo $req['id']; ?></td>
                                            <td data-label="النوع" style="font-size:0.75rem"><?php echo explode('_', $req['source_table'])[0]; ?></td>
                                            <td data-label="الحالة"><span class="badge bg-secondary" style="font-size:0.7rem"><?php echo htmlspecialchars($req['status']); ?></span></td>
                                            <td data-label="أدخله" style="font-size:0.75rem"><?php echo htmlspecialchars($creator_name); ?></td>
                                            <td data-label="إجراء">
                                                <a href="?admin=1&section=view_request&id=<?php echo $req['id']; ?>&table=<?php echo $req['source_table']; ?>" class="btn btn-xs btn-primary py-0 px-2" style="font-size:0.7rem">عرض</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; if(empty($target_requests)) echo '<tr><td colspan="5" class="text-muted text-center py-3">لا يوجد طلبات</td></tr>'; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center">الرجاء اختيار مدير من القائمة لعرض الموظفين والطلبات التابعة له.</div>
            
            <!-- جدول جميع الموظفين -->
            <div class="card mt-4">
                <div class="card-header">جميع الموظفين</div>
                <div class="card-body">
                    <table class="table table-bordered text-center table-hover">
                        <thead class="table-light">
                            <tr><th>الموظف</th><th>المدير التابع له</th><th>تاريخ الإنشاء</th><th>الحالة</th><th>إجراءات</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($employees as $emp): 
                                $mgr = array_values(array_filter($managers, fn($m) => $m['user_id'] == $emp['manager_id']))[0]['email'] ?? 'بدون مدير';
                            ?>
                                <tr>
                                    <td data-label="الموظف"><?php echo htmlspecialchars($emp['email']); ?></td>
                                    <td data-label="المدير التابع له"><?php echo htmlspecialchars($mgr); ?></td>
                                    <td data-label="تاريخ الإنشاء"><?php echo date('Y-m-d', strtotime($emp['created_at'])); ?></td>
                                    <td data-label="الحالة"><?php echo $emp['is_banned'] ? '<span class="badge bg-danger">محظور</span>' : '<span class="badge bg-success">نشط</span>'; ?></td>
                                    <td data-label="إجراءات">
                                        <button class="btn btn-sm btn-warning btn-edit-emp"
                                            data-bs-toggle="modal" data-bs-target="#employeeModal"
                                            data-id="<?php echo $emp['user_id']; ?>"
                                            data-username="<?php echo htmlspecialchars($emp['email']); ?>"
                                            data-banned="<?php echo $emp['is_banned']; ?>"
                                            data-manager="<?php echo $emp['manager_id']; ?>"><i class="fas fa-edit"></i></button>
                                        <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $emp['user_id']; ?>">
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="empForm" action="index.php" method="POST">
                <div class="modal-header"><h5 class="modal-title" id="empModalLabel">إضافة موظف</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="empFormAction" value="add_user">
                    <input type="hidden" name="user_id" id="empId" value="">
                    <input type="hidden" name="user_type" value="موظف">
                    <div class="mb-3"><label>البريد الإلكتروني (Email)</label><input type="text" class="form-control" id="empName" name="username" placeholder="example@domain.com" required></div>
                    <div class="mb-3"><label>المدير المباشر</label>
                        <select name="manager_id" id="empManager" class="form-select" required>
                            <option value="">اختر مديراً...</option>
                            <?php foreach($managers as $m): ?><option value="<?php echo $m['user_id']; ?>"><?php echo htmlspecialchars($m['email']); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>كلمة المرور</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="empPass" name="password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleEmpPass">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <script>
                    document.getElementById('toggleEmpPass').addEventListener('click', function (e) {
                        const passInput = document.getElementById('empPass');
                        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passInput.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('fa-eye-slash');
                    });
                    </script>
                    <div class="mb-3 form-check" id="empBannedBox" style="display:none;">
                        <input type="hidden" name="is_banned" value="0">
                        <input type="checkbox" class="form-check-input" id="empBanned" name="is_banned" value="1"><label class="form-check-label text-danger">حظر الحساب</label>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">حفظ</button></div>
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
            document.getElementById('empManager').value = btn.dataset.manager;
            document.getElementById('empPass').required = false;
            document.getElementById('empBannedBox').style.display = 'block';
            document.getElementById('empBanned').checked = btn.dataset.banned == '1';
        });
    });
    document.querySelector('[data-bs-target="#employeeModal"]').addEventListener('click', () => {
        document.getElementById('empModalLabel').innerText = 'إضافة موظف جديد';
        document.getElementById('empFormAction').value = 'add_user';
        document.getElementById('empForm').reset();
        document.getElementById('empPass').required = true;
        document.getElementById('empBannedBox').style.display = 'none';
    });
});
</script>
