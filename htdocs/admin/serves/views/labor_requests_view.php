<?php
/**
 * admin/serves/views/labor_requests_view.php
 */

$table = 'labor_requests';
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

        $stmt_partners = $pdo->prepare("SELECT * FROM related_data WHERE labor_request_id = ?");
        $stmt_partners->execute([$id]);
        $partners = $stmt_partners->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger m-4'>خطأ: " . $e->getMessage() . "</div>";
        return;
    }
}

function render_service_field($label, $name, $value, $type = 'text')
{
    $val = !empty($value) ? htmlspecialchars($value) : '---';
    echo "
    <div class='col-lg-3 col-md-4 col-6 mb-3'>
        <label class='form-label small fw-bold text-muted mb-1'>{$label}</label>
        <div class='view-mode-field p-2 bg-light rounded border-start border-primary border-3 shadow-sm' style='min-height: 38px; font-size: 0.9rem;'>
            {$val}
        </div>
        <input type='{$type}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='" . htmlspecialchars($value) . "' style='display:none;'>
    </div>";
}

function toArabicDigits($number)
{
    if (empty($number) && $number !== 0 && $number !== '0')
        return $number;
    $arabic_digits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $latin_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    return str_replace($latin_digits, $arabic_digits, $number);
}
?>

<main class="container-fluid px-4 my-4 animate__animated animate__fadeIn">
    <form id="updateRequestForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
        <input type="hidden" name="table" value="<?php echo $table; ?>">

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3 bg-white p-3 rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 shadow-sm">
                    <i class="fas fa-building fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-dark fw-bold">مكتب العمل</h4>
                    <span class="text-muted small">رقم الطلب: #<?php echo $request['id']; ?></span>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4 ms-auto">
                <div class="text-end">
                    <?php
$status_class = 'bg-secondary';
$current_status = $request['status'] ?? '';
switch ($current_status) {
    case 'تمت الموافقة':
        $status_class = 'bg-success';
        break;
    case 'مرفوض':
        $status_class = 'bg-danger';
        break;
    case 'بانتظار موافقة المدير':
        $status_class = 'bg-primary';
        break;
    case 'جاري الاعتماد':
        $status_class = 'bg-info text-dark';
        break;
    case 'قيد المراجعة':
        $status_class = 'bg-warning text-dark';
        break;
    case 'تم تعليق المعاملة':
        $status_class = 'bg-dark';
        break;
}
?>
                    
                                        <div class="small text-muted mb-1">حالة الطلب</div>
                    <span class="badge <?php echo $status_class; ?> px-3 py-2 rounded-pill fs-6 shadow-sm view-mode-status">
                        <?php echo htmlspecialchars($request['status'] ?? '---'); ?>
                    </span>
                    <select class="form-select form-select-sm shadow-none edit-mode-status d-none fw-bold" name="status" id="main_request_status" onchange="this.style.setProperty('background-color', this.options[this.selectedIndex].style.backgroundColor, 'important'); this.style.setProperty('color', this.options[this.selectedIndex].style.color, 'important');">
                        <option value="جاري الاعتماد" style="background-color: #0dcaf0 !important; color: #000 !important;" <?php echo($request['status'] ?? '') === 'جاري الاعتماد' ? 'selected' : ''; ?>>جاري الاعتماد</option>
                        <option value="تم تعليق المعاملة" style="background-color: #212529 !important; color: #fff !important;" <?php echo($request['status'] ?? '') === 'تم تعليق المعاملة' ? 'selected' : ''; ?>>تم تعليق المعاملة</option>
                        <option value="تمت الموافقة" style="background-color: #198754 !important; color: #fff !important;" <?php echo($request['status'] ?? '') === 'تمت الموافقة' ? 'selected' : ''; ?>>تمت الموافقة</option>
                        <option value="قيد المراجعة" style="background-color: #ffc107 !important; color: #000 !important;" <?php echo($request['status'] ?? '') === 'قيد المراجعة' ? 'selected' : ''; ?>>قيد المراجعة</option>
                        <option value="بانتظار موافقة المدير" style="background-color: #0d6efd !important; color: #fff !important;" <?php echo($request['status'] ?? '') === 'بانتظار موافقة المدير' ? 'selected' : ''; ?>>بانتظار موافقة المدير</option>
                        <option value="مرفوض" style="background-color: #dc3545 !important; color: #fff !important;" <?php echo($request['status'] ?? '') === 'مرفوض' ? 'selected' : ''; ?>>مرفوض</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-industry me-2"></i> بيانات المنشأة ومقدم الطلب</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php
render_service_field('اسم المنشأة', 'establishment_name', $request['establishment_name'] ?? '');
render_service_field('رقم الهوية / السجل', 'national_id', $request['national_id'] ?? '');
render_service_field('اسم المالك', 'owner_name', $request['owner_name'] ?? '');
render_service_field('رقم الملف', 'record_number', $request['record_number'] ?? '');
if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])) {
    render_service_field('رقم الصادر', 'export_number', $request['export_number'] ?? '');
}
render_service_field('مكتب العمل', 'emirate', $request['emirate'] ?? '');
render_service_field('التاريخ هجري', 'hijri_date', $request['hijri_date'] ?? '', 'text');
render_service_field('المرجع', 'issuance_number', $request['issuance_number'] ?? '');
?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-info text-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fs-6"><i class="fas fa-user-friends me-2"></i> بيانات العمالة المرتبطة</h5>
                
                <div id="related-mgr-anchor"></div> <!-- مرساة لأدوات الإدارة --></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="partners-table">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="px-4 py-3"># الرقم</th>
                                <th class="py-3">المهنة</th>
                                <th class="py-3">الجنسية</th>
                                <th class="py-3">بلد القدوم</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php
$count = 1;
foreach ($partners as $partner):
    $serialStr = str_pad($count, 3, '0', STR_PAD_LEFT);
    $serialAr = toArabicDigits($serialStr);
?>
                            <tr data-id="<?php echo $partner['id']; ?>">
                                <td class="px-4 py-3 fw-bold text-primary"><?php echo $serialAr; ?></td>
                                <td data-field-name="job_category"><?php echo htmlspecialchars($partner['job_category'] ?? '---'); ?></td>
                                <td data-field-name="nationality"><?php echo htmlspecialchars($partner['nationality'] ?? '---'); ?></td>
                                <td data-field-name="country"><?php echo htmlspecialchars($partner['country'] ?? '---'); ?></td>
                                </tr>
                            <?php
    $count++;
endforeach;
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="fixed-bottom bg-white border-top shadow-lg py-3" style="z-index: 1050;">
            <div class="container-fluid px-2 px-md-5">
                <div class="d-flex justify-content-center gap-3" id="view-mode-actions">
                    <a href="index.php?admin=1&section=requests" class="btn btn-dark px-4 rounded-pill shadow-sm">رجوع</a>
                    <button type="button" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm" onclick="enableEditMode()">تعديل</button>

                    <?php if (($_SESSION['user_type'] === 'مدير' || $_SESSION['user_type'] === 'Root') && ($request['status'] === 'بانتظار موافقة المدير')): ?>
                        <div class="vr mx-2"></div>
                        <button type="button" class="btn btn-success px-4 rounded-pill shadow-sm" onclick="updateRequestStatus(<?php echo $request['id']; ?>, 'تمت الموافقة', '<?php echo $table; ?>')">موافقة</button>
                        <button type="button" class="btn btn-danger px-4 rounded-pill shadow-sm" onclick="showRejectDialog(<?php echo $request['id']; ?>, '<?php echo $table; ?>')">رفض</button>
                    <?php
endif; ?>
                </div>
                <div class="d-none justify-content-center gap-3" id="edit-mode-actions">
                    <button type="button" class="btn btn-primary fw-bold px-5 rounded-pill shadow" onclick="saveChanges()">حفظ</button>
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" onclick="cancelEditMode()">إلغاء</button>
                </div>
            </div>
        </div>
        <div style="height: 100px;"></div>
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
    document.querySelectorAll('.edit-mode-field').forEach(el => el.style.display = 'block');
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
        row.querySelectorAll('input[data-field], select[data-field]').forEach(input => item[input.dataset.field] = input.value);
        partners.push(item);
    });
    formData.append('partners_json', JSON.stringify(partners));
    Swal.fire({ title: 'جاري الحفظ...', didOpen: () => Swal.showLoading() });
    fetch('api/update_full_request.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => res.success ? Swal.fire('نجاح', res.message, 'success').then(() => window.location.reload()) : Swal.fire('خطأ', res.message, 'error'));
}
</script>
