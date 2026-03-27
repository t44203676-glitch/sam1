<?php
// views/admin_manage_users.php

// التأكد من أن المدير فقط يمكنه الوصول لهذه الصفحة
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'مدير') {
    die('غير مصرح لك بالوصول.');
}

// رسائل النجاح
$success_messages = [
    'add' => 'تم إضافة المستخدم بنجاح.',
    'update' => 'تم تحديث بيانات المستخدم بنجاح.',
    'delete' => 'تم حذف المستخدم بنجاح.'
];
$success_key = $_GET['success'] ?? null;
?>

<main class="container my-4">
    <h3 class="text-center mb-4">إدارة الموظفين والمستخدمين</h3>

    <?php if (isset($success_messages[$success_key])): ?>
        <div class="alert alert-success"><?php echo $success_messages[$success_key]; ?></div>
    <?php endif; ?>

    <!-- زر إضافة مستخدم جديد -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
            <i class="fas fa-plus me-1"></i> إضافة مستخدم جديد
        </button>
        <a href="index.php?admin=1" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-1"></i> الرجوع إلى لوحة التحكم
        </a>
    </div>

    <!-- جدول عرض المستخدمين -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>اسم المستخدم</th>
                    <th>نوع المستخدم</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($all_users)): ?>
                    <?php foreach ($all_users as $user): ?>
                        <tr>
                            <td data-label="اسم المستخدم"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td data-label="نوع المستخدم"><?php echo htmlspecialchars($user['user_type']); ?></td>
                            <td data-label="تاريخ الإنشاء"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            <td data-label="الإجراءات">
                                <button class="btn btn-sm btn-warning btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userModal"
                                        data-id="<?php echo $user['user_id']; ?>"
                                        data-username="<?php echo htmlspecialchars($user['email']); ?>"
                                        data-type="<?php echo htmlspecialchars($user['user_type']); ?>">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): // لا يمكن للمدير حذف نفسه ?>
                                    <form action="index.php" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                        <input type="hidden" name="action" value="delete_user">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">لا يوجد مستخدمون لعرضهم.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal لإضافة وتعديل المستخدمين -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="userForm" action="index.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">إضافة مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add_user">
                    <input type="hidden" name="user_id" id="userId" value="">

                    <div class="mb-3">
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small id="passwordHelp" class="form-text text-muted">اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور الحالية.</small>
                    </div>

                    <div class="mb-3">
                        <label for="user_type" class="form-label">نوع المستخدم</label>
                        <select class="form-select" id="user_type" name="user_type" required>
                            <option value="موظف">موظف</option>
                            <option value="مدير">مدير</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userModal = document.getElementById('userModal');
    const modalTitle = document.getElementById('userModalLabel');
    const form = document.getElementById('userForm');
    const formAction = document.getElementById('formAction');
    const userIdInput = document.getElementById('userId');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const userTypeSelect = document.getElementById('user_type');
    const passwordHelp = document.getElementById('passwordHelp');

    userModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const isEdit = button.classList.contains('btn-edit');

        if (isEdit) {
            // وضع التعديل
            modalTitle.textContent = 'تعديل بيانات المستخدم';
            formAction.value = 'update_user';
            
            // ملء الحقول بالبيانات الحالية
            userIdInput.value = button.dataset.id;
            usernameInput.value = button.dataset.username;
            userTypeSelect.value = button.dataset.type;

            // جعل كلمة المرور غير مطلوبة في وضع التعديل
            passwordInput.required = false;
            passwordHelp.style.display = 'block';

        } else {
            // وضع الإضافة
            modalTitle.textContent = 'إضافة مستخدم جديد';
            formAction.value = 'add_user';
            
            // تفريغ الحقول
            form.reset();
            userIdInput.value = '';

            // جعل كلمة المرور مطلوبة في وضع الإضافة
            passwordInput.required = true;
            passwordHelp.style.display = 'none';
        }
    });
});
</script>

<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .danger-zone {
        margin-top: 40px;
        padding: 20px;
        border: 2px solid #dc3545;
        border-radius: 10px;
        background: #fff5f5;
    }
    [data-bs-theme="dark"] .danger-zone {
        background: #2c1215;
        border-color: #dc3545;
    }
    .danger-zone h5 {
        color: #dc3545;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .danger-zone p {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }
    [data-bs-theme="dark"] .danger-zone p {
        color: #aaa;
    }
</style>

<!-- منطقة الخطر - حذف جميع البيانات -->
<div class="container my-4">
    <div class="danger-zone">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>منطقة الخطر</h5>
        <p>حذف جميع بيانات الخدمات نهائياً من قاعدة البيانات (تصاريح الزواج، الزيارات، العمالة، الأحوال المدنية، وغيرها). هذا الإجراء لا يمكن التراجع عنه.</p>
        <button type="button" class="btn btn-danger" id="purgeAllDataBtn">
            <i class="fas fa-trash-alt me-2"></i>حذف جميع بيانات الخدمات
        </button>
    </div>
</div>

<script>
document.getElementById('purgeAllDataBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'تحذير!',
        text: 'هل أنت متأكد من حذف جميع بيانات الخدمات؟ هذا الإجراء نهائي ولا يمكن التراجع عنه.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احذف الكل',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'تأكيد نهائي',
                text: 'اكتب "حذف" للتأكيد',
                icon: 'error',
                input: 'text',
                inputPlaceholder: 'اكتب حذف هنا',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'تأكيد الحذف النهائي',
                cancelButtonText: 'إلغاء',
                inputValidator: (value) => {
                    if (value !== 'حذف') {
                        return 'يجب كتابة "حذف" للتأكيد';
                    }
                }
            }).then((result2) => {
                if (result2.isConfirmed) {
                    fetch('api/purge_all_data.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ confirm: true })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'تم الحذف!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('خطأ', data.message, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('خطأ', 'حدث خطأ في الاتصال بالخادم.', 'error');
                    });
                }
            });
        }
    });
});
</script>
