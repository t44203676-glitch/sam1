<?php
/**
 * admin/serves/views/employee/civil_affairs_requests_employee_view.php
 * نسخة مخصصة للموظفين - الأحوال المدنية
 */

$table = 'civil_affairs_requests';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger m-4'>رقم الطلب غير موجود.</div>";
    return;
}

if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            echo "<div class='alert alert-danger m-4'>الطلب غير موجود.</div>";
            return;
        }

        $stmt_partners = $pdo->prepare("SELECT * FROM related_data WHERE civil_affairs_request_id = ?");
        $stmt_partners->execute([$id]);
        $partners = $stmt_partners->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger m-4'>خطأ في قاعدة البيانات: " . $e->getMessage() . "</div>";
        return;
    }
}

function render_employee_restricted_field($label, $name, $value, $type = 'text', $is_protected = false) {
    $val = !empty($value) ? htmlspecialchars($value) : '---';
    $input = "";
    if (!$is_protected) {
        $input = "<input type='{$type}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='".htmlspecialchars($value)."' style='display:none;'>";
    }
    echo "
    <div class='col-lg-3 col-md-4 col-6 mb-3'>
        <label class='form-label small fw-bold text-muted mb-1'>{$label}</label>
        <div class='view-mode-field p-2 bg-light rounded border-start border-primary border-3 shadow-sm' style='min-height: 38px; font-size: 0.9rem;'>
            {$val}
        </div>
        {$input}
    </div>";
}
?>

<main class="container-fluid px-4 my-4 animate__animated animate__fadeIn">
    <form id="updateRequestForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
        <input type="hidden" name="table" value="<?php echo $table; ?>">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3 bg-white p-3 rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 shadow-sm">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-dark fw-bold">الأحوال المدنية (رؤية الموظف)</h4>
                    <span class="text-muted small">رقم الطلب: #<?php echo $request['id']; ?></span>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4 ms-auto">
                <div class="text-end">
                    <div class="small text-muted mb-1">حالة الطلب الحالية</div>
                    <span class="badge <?php 
                        echo ($request['status'] === 'تمت الموافقة' || $request['status'] === 'مقبول') ? 'bg-success' : 'bg-primary'; 
                    ?> px-3 py-2 rounded-pill fs-6 border border-2 border-white shadow-sm">
                        <?php echo htmlspecialchars($request['status'] ?? '---'); ?>
                    </span>
                </div>

                <?php 
                    $photoUrl = getProfilePhotoUrl($request['profile_photo_path'] ?? '', BASE_URL . 'public/images/default-avatar.png');
                ?>
                <div class="text-center position-relative">
                    <div id="photo-preview-admin" class="rounded-circle border border-3 border-white shadow" 
                         style="width: 80px; height: 80px; background-image: url('<?php echo $photoUrl; ?>'); background-size: cover; background-position: center; cursor: pointer;" 
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

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-user-tag me-2"></i> الخطوة الأولى: البيانات الأساسية</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php 
                    // حقول محمية
                    render_employee_restricted_field('الرقم التسلسلي', 'serial_number', $request['serial_number'] ?? '---', 'text', true);
                    render_employee_restricted_field('رقم الصادر', 'export_number', $request['export_number'] ?? '---', 'text', true);
                    render_employee_restricted_field('رقم الهوية / الإقامة', 'national_id', $request['national_id'], 'text', true);
                    render_employee_restricted_field('رقم الجوال', 'phone', $request['phone'], 'text', true);
                    
                    // حقول قابلة للتعديل
                    render_employee_restricted_field('رقم المعاملة', 'transaction_number', $request['transaction_number']);
                    render_employee_restricted_field('الجنسية', 'nationality', $request['nationality']);
                    render_employee_restricted_field('تاريخ الإصدار', 'issue_date', $request['issue_date']);
                    render_employee_restricted_field('الجهة المصدرة', 'issuing_authority', $request['issuing_authority']);
                    ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-info text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-users-cog me-2"></i> الخطوة الثانية: بيانات الأفراد والمواعيد</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="partners-table">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="px-4 py-3 border-0">الرقم التسلسلي</th>
                                <th class="py-3 border-0">المهنة</th>
                                <th class="py-3 border-0 text-center">حجز موعد</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($partners as $person): ?>
                            <tr data-id="<?php echo $person['id']; ?>">
                                <td class="px-4 py-3 fw-bold text-primary">
                                    #<?php echo htmlspecialchars($person['id']); ?>
                                </td>
                                <td data-field-name="job_category"><?php echo htmlspecialchars($person['job_category'] ?? '---'); ?></td>
                                <td data-field-name="appointment_date" class="text-center"><?php echo htmlspecialchars($person['appointment_date'] ?? '---'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="fixed-bottom bg-white border-top shadow-lg py-3" style="z-index: 1050;">
            <div class="container-fluid px-2 px-md-5">
                <div class="d-flex justify-content-center gap-3" id="view-mode-actions">
                    <a href="index.php?admin=1" class="btn btn-dark px-4 rounded-pill shadow-sm">رجوع</a>
                    <button type="button" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm" onclick="enableEditMode()">تعديل</button>
                </div>
                <div class="d-none justify-content-center gap-3" id="edit-mode-actions">
                    <button type="button" class="btn btn-primary fw-bold px-5 rounded-pill shadow" onclick="saveChanges()">حفظ التعديلات</button>
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" onclick="cancelEditMode()">إلغاء</button>
                </div>
            </div>
        </div>
        <div style="height: 100px;"></div>
    </form>
</main>

<script>
function enableEditMode() {
    document.getElementById('view-mode-actions').classList.replace('d-flex', 'd-none');
    document.getElementById('edit-mode-actions').classList.replace('d-none', 'd-flex');
    document.querySelectorAll('.view-mode-field').forEach(el => {
        const nextInput = el.nextElementSibling;
        if (nextInput && nextInput.classList.contains('edit-mode-field')) {
            el.classList.add('d-none');
            nextInput.style.display = 'block';
        }
    });
    document.querySelectorAll('#partners-table tbody tr').forEach(row => {
        row.querySelectorAll('td[data-field-name]').forEach(cell => {
            const field = cell.dataset.fieldName;
            const value = cell.innerText.trim();
            cell.innerHTML = `<input type="text" class="form-control form-control-sm" value="${value}" data-field="${field}">`;
        });
    });
}
function cancelEditMode() { window.location.reload(); }
function saveChanges() {
    const formData = new FormData(document.getElementById('updateRequestForm'));
    const partners = [];
    document.querySelectorAll('#partners-table tbody tr').forEach(row => {
        const item = { id: row.dataset.id };
        row.querySelectorAll('input[data-field]').forEach(input => item[input.dataset.field] = input.value);
        partners.push(item);
    });
    formData.append('partners_json', JSON.stringify(partners));
    Swal.fire({ title: 'جاري الحفظ...', didOpen: () => Swal.showLoading() });
    fetch('api/update_full_request.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => res.success ? Swal.fire('نجاح', 'تم تحديث البيانات، بانتظار موافقة المدير.', 'success').then(() => window.location.reload()) : Swal.fire('خطأ', res.message, 'error'));
}
function previewAdminPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photo-preview-admin').style.backgroundImage = `url('${e.target.result}')`;
            document.getElementById('api-save-photo-btn').style.display = 'inline-block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function saveAdminPhoto() {
    const formData = new FormData();
    formData.append('id', <?php echo $request['id']; ?>);
    formData.append('table', '<?php echo $table; ?>');
    formData.append('profile_photo_file', document.getElementById('profile_photo_upload').files[0]);
    fetch('api/update_full_request.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => res.success ? Swal.fire('نجاح', 'تم تحديث الصورة', 'success') : Swal.fire('خطأ', res.message, 'error'));
}
</script>
