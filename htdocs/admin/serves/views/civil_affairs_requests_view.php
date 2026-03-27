<?php
/**
 * admin/serves/views/civil_affairs_requests_view.php
 * ملف مستقل لعرض وتعديل طلبات الأحوال المدنية
 */

$table = 'civil_affairs_requests';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger m-4'>رقم الطلب غير موجود.</div>";
    return;
}

// 1. جلب البيانات الأساسية
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            echo "<div class='alert alert-danger m-4'>الطلب غير موجود.</div>";
            return;
        }

        // 2. جلب البيانات المرتبطة (الأفراد)
        $stmt_partners = $pdo->prepare("SELECT * FROM related_data WHERE civil_affairs_request_id = ?");
        $stmt_partners->execute([$id]);
        $partners = $stmt_partners->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger m-4'>خطأ في قاعدة البيانات: " . $e->getMessage() . "</div>";
        return;
    }
}

/**
 * دالة محلية لعرض حقول البيانات بتصميم يتناسب مع وضع العرض والتعديل
 */
function render_service_field($label, $name, $value, $type = 'text') {
    $val = !empty($value) ? htmlspecialchars($value) : '---';
    echo "
    <div class='col-lg-3 col-md-4 col-6 mb-3'>
        <label class='form-label small fw-bold text-muted mb-1'>{$label}</label>
        <div class='view-mode-field p-2 bg-light rounded border-start border-primary border-3 shadow-sm' style='min-height: 38px; font-size: 0.9rem;'>
            {$val}
        </div>
        <input type='{$type}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='".htmlspecialchars($value)."' style='display:none;'>
    </div>";
}
?>

<main class="container-fluid px-4 my-4 animate__animated animate__fadeIn">
    <form id="updateRequestForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
        <input type="hidden" name="table" value="<?php echo $table; ?>">

        <!-- الهيدر العلوي: الحالة والصورة الشخصية -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3 bg-white p-3 rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 shadow-sm">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-dark fw-bold">الأحوال المدنية</h4>
                    <span class="text-muted small">رقم الطلب: #<?php echo $request['id']; ?></span>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4 ms-auto">
                <div class="text-end">
                    <?php
    $status_class = 'bg-secondary';
    $current_status = $request['status'] ?? '';
    switch($current_status) {
        case 'تمت الموافقة': $status_class = 'bg-success'; break;
        case 'مرفوض': $status_class = 'bg-danger'; break;
        case 'بانتظار موافقة المدير': $status_class = 'bg-primary'; break;
        case 'جاري الاعتماد': $status_class = 'bg-info text-dark'; break;
        case 'قيد المراجعة': $status_class = 'bg-warning text-dark'; break;
        case 'تم تعليق المعاملة': $status_class = 'bg-dark'; break;
    }
?>
                    
                    <div class="small text-muted mb-1">حالة الطلب الحالية</div>
                    <span class="badge view-mode-status <?php 
                        echo match($request['status']) {
                            'تمت الموافقة', 'مقبول' => 'bg-success',
                            'مرفوض' => 'bg-danger',
                            'بانتظار موافقة المدير', 'جاري الاعتمادية', 'جاري الاعتماد' => 'bg-warning text-dark',
                            default => 'bg-secondary'
                        };
                    ?> px-3 py-2 rounded-pill fs-6 border border-2 border-white shadow-sm">
                        <?php echo htmlspecialchars($request['status'] ?? '---'); ?>
                    </span>
                    <select class="form-select form-select-sm shadow-none edit-mode-status d-none fw-bold" name="status" id="main_request_status" onchange="this.style.setProperty('background-color', this.options[this.selectedIndex].style.backgroundColor, 'important'); this.style.setProperty('color', this.options[this.selectedIndex].style.color, 'important');">
                        <option value="جاري الاعتماد" style="background-color: #0dcaf0 !important; color: #000 !important;" <?php echo ($request['status'] ?? '') === 'جاري الاعتماد' ? 'selected' : ''; ?>>جاري الاعتماد</option>
                        <option value="تم تعليق المعاملة" style="background-color: #212529 !important; color: #fff !important;" <?php echo ($request['status'] ?? '') === 'تم تعليق المعاملة' ? 'selected' : ''; ?>>تم تعليق المعاملة</option>
                        <option value="تمت الموافقة" style="background-color: #198754 !important; color: #fff !important;" <?php echo ($request['status'] ?? '') === 'تمت الموافقة' ? 'selected' : ''; ?>>تمت الموافقة</option>
                        <option value="قيد المراجعة" style="background-color: #ffc107 !important; color: #000 !important;" <?php echo ($request['status'] ?? '') === 'قيد المراجعة' ? 'selected' : ''; ?>>قيد المراجعة</option>
                        <option value="بانتظار موافقة المدير" style="background-color: #0d6efd !important; color: #fff !important;" <?php echo ($request['status'] ?? '') === 'بانتظار موافقة المدير' ? 'selected' : ''; ?>>بانتظار موافقة المدير</option>
                        <option value="مرفوض" style="background-color: #dc3545 !important; color: #fff !important;" <?php echo ($request['status'] ?? '') === 'مرفوض' ? 'selected' : ''; ?>>مرفوض</option>
                    </select>
                </div>

                <!-- الصورة الشخصية -->
                <?php 
                    $photoUrl = getProfilePhotoUrl($request['profile_photo_path'] ?? '', BASE_URL . 'public/images/default-avatar.png');
                ?>
                <div class="text-center position-relative">
                    <div id="photo-preview-admin" class="rounded-circle border border-3 border-white shadow" 
                         style="width: 80px; height: 80px; background-image: url('<?php echo $photoUrl; ?>'); background-size: cover; background-position: center; cursor: pointer; transition: transform 0.3s;" 
                         onclick="window.open('<?php echo $photoUrl; ?>', '_blank')"></div>
                    <div class="mt-2">
                        <label for="profile_photo_upload" class="btn btn-xs btn-primary py-0 px-2 rounded-pill shadow-sm" style="font-size: 10px; cursor: pointer;">
                            <i class="fas fa-camera"></i> تغيير
                        </label>
                        <button type="button" id="api-save-photo-btn" class="btn btn-xs btn-success py-0 px-2 rounded-pill shadow-sm" style="font-size: 10px; display: none;" onclick="saveAdminPhoto()">
                            <i class="fas fa-check"></i> حفظ
                        </button>
                    </div>
                    <input type="file" id="profile_photo_upload" name="profile_photo_file" class="d-none" accept="image/*" onchange="previewAdminPhoto(this)">
                </div>
            </div>
        </div>

        <!-- الخطوة الأولى: البيانات الأساسية -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-user-tag me-2"></i> الخطوة الأولى: البيانات الأساسية للطلب</h5>
                
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php 
                    if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])) {
                        render_service_field('الرقم التسلسلي', 'serial_number', $request['serial_number']);
                        render_service_field('رقم الصادر', 'export_number', $request['export_number']);
                    }
                    render_service_field('رقم الجوال', 'phone', $request['phone']);
                    render_service_field('رقم الهوية / الإقامة', 'national_id', $request['national_id']);
                    render_service_field('رقم المعاملة', 'transaction_number', $request['transaction_number']);
                    render_service_field('الجنسية', 'nationality', $request['nationality']);
                    render_service_field('تاريخ الإصدار', 'issue_date', $request['issue_date']);
                    render_service_field('الجهة المصدرة', 'issuing_authority', $request['issuing_authority']);
                    ?>
                </div>
            </div>
        </div>

        <!-- الخطوة الثانية: الأفراد / البيانات الإضافية -->
        <div class="card border-0 shadow-sm mb-5 overflow-hidden">
            <div class="card-header bg-gradient bg-info text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-users-cog me-2"></i> الخطوة الثانية: بيانات الأفراد والمواعيد</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="partners-table">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <?php if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])): ?>
                                    <th class="px-4 py-3 border-0">الرقم التسلسلي</th>
                                <?php endif; ?>
                                <th class="py-3 border-0">المهنة</th>
                                <th class="py-3 border-0 text-center">حجز موعد</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach ($partners as $person): ?>
                            <tr data-id="<?php echo $person['id']; ?>">
                                <td class="px-4 py-3 fw-bold text-primary">
                                    <?php echo convertToArabicNumerals('11' . str_pad($person['id'] % 100000000, 8, '0', STR_PAD_LEFT)); ?>
                                </td>
                                <td data-field-name="job_category"><?php echo htmlspecialchars($person['job_category'] ?? '---'); ?></td>
                                <td data-field-name="appointment_date" class="text-center text-secondary"><?php echo htmlspecialchars($person['appointment_date'] ?? '---'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (empty($partners)): ?>
                    <div class="p-5 text-center text-muted"><i class="fas fa-info-circle me-2"></i> لا توجد بيانات أفراد مسجلة لهذا الطلب.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- شريط الأزرار المثبت في الأسفل -->
        <div class="fixed-bottom bg-white border-top shadow-lg py-3 animate__animated animate__slideInUp" style="z-index: 1050;">
            <div class="container-fluid px-2 px-md-5">
                <div class="d-flex justify-content-center gap-3" id="view-mode-actions">
                    <a href="index.php?admin=1&section=requests" class="btn btn-dark px-4 rounded-pill shadow-sm"><i class="fas fa-arrow-right me-2"></i> رجوع</a>
                    <?php if ($_SESSION['user_type'] === 'مدير' || $_SESSION['user_type'] === 'Root'): ?>
                        <form action="index.php" method="POST" target="_blank" class="d-inline mb-0">
                            <input type="hidden" name="action" value="admin_print">
                            <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                            <input type="hidden" name="table" value="<?php echo $table; ?>">
                            <button type="submit" class="btn btn-outline-secondary px-4 rounded-pill shadow-sm"><i class="fas fa-print me-2"></i> طباعة</button>
                        </form>
                    <?php endif; ?>
                    
                    <button type="button" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm" onclick="enableEditMode()">
                        <i class="fas fa-edit me-2"></i> تعديل البيانات
                    </button>
                    
                    <?php if (($_SESSION['user_type'] === 'مدير' || $_SESSION['user_type'] === 'Root') && ($request['status'] === 'بانتظار موافقة المدير')): ?>
                        <div class="h-divider mx-2 border-start" style="height: 40px;"></div>
                        <button type="button" class="btn btn-success px-4 rounded-pill shadow-sm" onclick="updateRequestStatus(<?php echo $request['id']; ?>, 'تمت الموافقة', '<?php echo $table; ?>')">
                            <i class="fas fa-check-circle me-2"></i> موافقة
                        </button>
                        <button type="button" class="btn btn-danger px-4 rounded-pill shadow-sm" onclick="showRejectDialog(<?php echo $request['id']; ?>, '<?php echo $table; ?>')">
                            <i class="fas fa-times-circle me-2"></i> رفض
                        </button>
                    <?php endif; ?>
                </div>

                <div class="d-none justify-content-center gap-3 animate__animated animate__fadeIn" id="edit-mode-actions">
                    <button type="button" class="btn btn-primary fw-bold px-5 rounded-pill shadow" onclick="saveChanges()">
                        <i class="fas fa-save me-2"></i> حفظ التغييرات النهائية
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" onclick="cancelEditMode()">
                        إلغاء التعديل
                    </button>
                </div>
            </div>
        </div>
        <div style="height: 120px;"></div>
    </form>

</main>

<?php include 'related_management_ui.php'; ?>

<script>
// تحريك أدوات الإدارة إلى الهيدر الخاص بالبطاقة لتوفير المساحة
document.addEventListener('DOMContentLoaded', () => {
    const mgr = document.getElementById('related-mgr-container');
    const anchor = document.getElementById('related-mgr-anchor');
    if (mgr && anchor) {
        anchor.appendChild(mgr);
        mgr.classList.remove('mt-4', 'px-3');
        mgr.style.margin = '0';
        mgr.style.padding = '0';
    }
});
</script>


<script>
/** 
 * تفعيل وضع التعديل (إخفاء النصوص وإظهار حقول الإدخال)
 */
function enableEditMode() {
    document.querySelectorAll('.view-mode-status').forEach(el => el.classList.add('d-none'));
    const statusSelects = document.querySelectorAll('.edit-mode-status');
    statusSelects.forEach(el => {
        el.classList.remove('d-none');
        if (el.tagName === 'SELECT' && el.selectedIndex >= 0) {
            const opt = el.options[el.selectedIndex];
            el.style.setProperty('background-color', opt.style.backgroundColor, 'important');
            el.style.setProperty('color', opt.style.color, 'important');
        }
    });
    document.getElementById('view-mode-actions').classList.replace('d-flex', 'd-none');
    document.getElementById('edit-mode-actions').classList.replace('d-none', 'd-flex');
    
    document.querySelectorAll('.view-mode-field').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('.edit-mode-field').forEach(el => {
        el.style.display = 'block';
        el.classList.add('animate__animated', 'animate__fadeIn');
    });
    
    // تحويل خلايا جدول الأفراد لحقول إدخال
    document.querySelectorAll('#partners-table tbody tr').forEach(row => {
        const cells = row.querySelectorAll('td[data-field-name]');
        cells.forEach(cell => {
            const val = cell.innerText.trim();
            const field = cell.dataset.fieldName;
            if (field === 'status') {
                cell.innerHTML = `
                    <select class="form-select form-select-sm" data-field="status">
                        <option value="قيد المراجعة" style="background-color: #ffc107; color: #000;" ${val === 'قيد المراجعة' ? 'selected' : ''}>قيد المراجعة</option>
                        <option value="تمت الموافقة" style="background-color: #198754; color: #fff;" ${val === 'تمت الموافقة' ? 'selected' : ''}>تمت الموافقة</option>
                        <option value="مرفوض" style="background-color: #dc3545; color: #fff;" ${val === 'مرفوض' ? 'selected' : ''}>مرفوض</option>
                    </select>`;
            } else {
                cell.innerHTML = `<input type="text" class="form-control form-control-sm shadow-none" value="${val}" data-field="${field}">`;
            }
        });
    });
}

/**
 * إلغاء وضع التعديل (إعادة تحميل الصفحة)
 */
function cancelEditMode() {
    window.location.reload();
}

/**
 * حفظ التغييرات عبر الـ API
 */
function saveChanges() {
    const form = document.getElementById('updateRequestForm');
    const formData = new FormData(form);
    
    // تجميع بيانات الأفراد كـ JSON
    const partners = [];
    document.querySelectorAll('#partners-table tbody tr').forEach(row => {
        const item = { id: row.dataset.id };
        row.querySelectorAll('input[data-field], select[data-field]').forEach(input => {
            item[input.dataset.field] = input.value;
        });
        partners.push(item);
    });
    formData.append('partners_json', JSON.stringify(partners));

    // إظهار لودر التحميل
    Swal.fire({ title: 'جاري الحفظ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    fetch('api/update_full_request.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            Swal.fire('تم الحفظ', res.message, 'success').then(() => window.location.reload());
        } else {
            Swal.fire('خطأ', res.message, 'error');
        }
    })
    .catch(err => Swal.fire('خطأ', 'فشل الاتصال بالسيرفر', 'error'));
}

/**
 * معاينة الصورة الشخصية قبل الرفع
 */
function previewAdminPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('photo-preview-admin');
            preview.style.backgroundImage = `url('${e.target.result}')`;
            preview.classList.add('border-primary');
            document.getElementById('api-save-photo-btn').style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * حفظ الصورة الشخصية بشكل منفصل
 */
function saveAdminPhoto() {
    const formData = new FormData();
    formData.append('id', <?php echo $request['id']; ?>);
    formData.append('table', '<?php echo $table; ?>');
    formData.append('profile_photo_file', document.getElementById('profile_photo_upload').files[0]);
    formData.append('action', 'update_profile_photo');

    fetch('api/update_full_request.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => {
        if (res.success) Swal.fire('نجاح', 'تم تحديث الصورة الشخصية بنجاح', 'success').then(() => {
             document.getElementById('api-save-photo-btn').style.display = 'none';
        });
        else Swal.fire('خطأ', res.message, 'error');
    });
}
</script>
