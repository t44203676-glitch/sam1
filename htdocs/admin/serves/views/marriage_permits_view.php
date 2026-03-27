<?php
/**
 * admin/serves/views/marriage_permits_view.php
 * ملف مستقل لعرض وتعديل تصاريح الزواج
 */

$table = 'marriage_permits';
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

        // 2. جلب البيانات المرتبطة (الطرف الآخر / المودقين)
        $stmt_partners = $pdo->prepare("SELECT * FROM related_data WHERE marriage_permit_id = ?");
        $stmt_partners->execute([$id]);
        $partners = $stmt_partners->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (PDOException $e) {
        echo "<div class='alert alert-danger m-4'>خطأ في قاعدة البيانات: " . $e->getMessage() . "</div>";
        return;
    }
}

/**
 * دالة محلية لعرض حقول البيانات بتصميم يتناسب مع وضع العرض والتعديل
 */
function render_service_field($label, $name, $value, $type = 'text', $options = [])
{
    $val = !empty($value) ? htmlspecialchars($value) : '---';
    $input = "";
    if ($type === 'datalist' || $type === 'select') {
        $listId = $name . "_list";
        if ($type === 'select') {
            $input = "<select class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' style='display:none;'>";
            $input .= "<option value=''>اختر نوع التصريح...</option>";
            foreach ($options as $opt_val => $opt_label) {
                $selected = ($value == $opt_val) ? 'selected' : '';
                $input .= "<option value='" . htmlspecialchars($opt_val) . "' {$selected}>" . htmlspecialchars($opt_label) . "</option>";
            }
            $input .= "</select>";
        }
        else {
            $input = "<input list='{$listId}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='" . htmlspecialchars($value) . "' style='display:none;'>";
            $input .= "<datalist id='{$listId}'>";
            foreach ($options as $opt_val => $opt_label) {
                $input .= "<option value='" . htmlspecialchars($opt_val) . "'>" . htmlspecialchars($opt_label) . "</option>";
            }
            $input .= "</datalist>";
        }
    }
    else {
        $input = "<input type='{$type}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='" . htmlspecialchars($value) . "' style='display:none;'>";
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

$permit_types_options = [
    'زواج سعودي من اجنبية مواليد السعودية' => 'زواج سعودي من اجنبية مواليد السعودية',
    'زواج سعودية من اجنبي مواليد السعودية' => 'زواج سعودية من اجنبي مواليد السعودية',
    'زواج سعودي من اجنبية داخل الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودي من اجنبية داخل الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودية من اجنبي داخل الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودية من اجنبي داخل الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودي من اجنبية خارج الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودي من اجنبية خارج الاراضي السعودية مواليد خارج السعودية',
    'زواج سعودية من اجنبي خارج الاراضي السعودية مواليد خارج السعودية' => 'زواج سعودية من اجنبي خارج الاراضي السعودية مواليد خارج السعودية'
];
?>

<main class="container-fluid px-4 my-4 animate__animated animate__fadeIn">
    <form id="updateRequestForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
        <input type="hidden" name="table" value="<?php echo $table; ?>">

        <!-- الهيدر العلوي -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-3 bg-white p-3 rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 shadow-sm">
                    <i class="fas fa-heart fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-dark fw-bold">تصاريح الزواج</h4>
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

        <!-- الخطوة الأولى: البيانات الأساسية -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-file-invoice me-2"></i> الخطوة الأولى: البيانات الأساسية</h5>
                </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php
if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])) {
    render_service_field('الرقم التسلسلي', 'serial_number', $request['serial_number'] ?? '---');
}
render_service_field('نوع التصريح', 'permit_type', $request['permit_type'] ?? '---', 'select', $permit_types_options);
if (!in_array($_SESSION['user_type'], ['موظف', 'Employee'])) {
    render_service_field('رقم الصادر', 'export_number', $request['export_number'] ?? '---');
}
render_service_field('اسم مقدم الطلب', 'applicant_name', $request['applicant_name'] ?? '---');
render_service_field('رقم الهوية / الإقامة', 'national_id', $request['national_id'] ?? '---');
render_service_field('رقم الهاتف', 'phone', $request['phone'] ?? '---');
render_service_field('الإمارة', 'emirate', $request['emirate'] ?? '---');
render_service_field('تاريخ الموافقة', 'approval_date', $request['approval_date'] ?? '---', 'date');
render_service_field('رقم السجل', 'record_number', $request['record_number'] ?? '---');
render_service_field('رقم الملف المرسل للبنك', 'bank_file_number', $request['bank_file_number'] ?? '0');

// حقول إضافية قد تكون مهمة
render_service_field('رقم المعاملة', 'issuance_number', $request['issuance_number'] ?? '---');
render_service_field('الجهة المصدرة', 'issuing_authority', $request['issuing_authority'] ?? '---');
?>
                </div>
            </div>
        </div>

        <!-- الخطوة الثانية: بيانات الزوجة / الأطراف الأخرى -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-info text-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fs-6"><i class="fas fa-users me-2"></i> الخطوة الثانية: بيانات الطرف الآخر</h5>
                <span class="badge bg-white text-info rounded-pill px-3">بيانات الشركاء</span>
            
                <div id="related-mgr-anchor"></div> <!-- مرساة لأدوات الإدارة --></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-responsive-stack" id="partners-table">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="px-4 py-3">الرقم</th>
                                <th class="py-3">الاسم الكامل</th>
                                <th class="py-3">رقم الجواز/ رقم الاقامة</th>
                                <th class="py-3">فئة المهنة</th>
                                <th class="py-3">الجنسية</th>
                                <th class="py-3">جهة القدوم</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($partners as $partner): ?>
                             <tr data-id="<?php echo $partner['id']; ?>">
                                <td class="px-4 py-3 fw-bold text-primary" data-label="الرقم">#<?php echo htmlspecialchars($partner['id']); ?></td>
                                <td data-field-name="full_name" data-label="الاسم الكامل"><?php echo htmlspecialchars($partner['full_name'] ?? '---'); ?></td>
                                <td data-field-name="passport_number" data-label="رقم الجواز/ رقم الاقامة"><?php echo htmlspecialchars($partner['passport_number'] ?? '---'); ?></td>
                                <td data-field-name="job_category" data-label="فئة المهنة"><?php echo htmlspecialchars($partner['job_category'] ?? '---'); ?></td>
                                <td data-field-name="nationality" data-label="الجنسية"><?php echo htmlspecialchars($partner['nationality'] ?? '---'); ?></td>
                                <td data-field-name="country" data-label="جهة القدوم"><?php echo htmlspecialchars($partner['country'] ?? '---'); ?></td>
                                </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- شريط الأزرار -->
        <div class="fixed-bottom bg-white border-top shadow-lg py-3" style="z-index: 1050;">
            <div class="container-fluid px-2 px-md-5">
                <div class="d-flex justify-content-center gap-3" id="view-mode-actions">
                    <a href="index.php?admin=1&section=requests" class="btn btn-dark px-4 rounded-pill shadow-sm"><i class="fas fa-arrow-right me-2"></i> رجوع</a>
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
