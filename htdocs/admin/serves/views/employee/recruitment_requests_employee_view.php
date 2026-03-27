<?php
/**
 * admin/serves/views/employee/recruitment_requests_employee_view.php
 * نسخة مخصصة للموظفين - الاستقدام
 */

$table = 'recruitment_requests';
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

        $stmt_partners = $pdo->prepare("SELECT * FROM related_data WHERE recruitment_request_id = ?");
        $stmt_partners->execute([$id]);
        $partners = $stmt_partners->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger m-4'>خطأ: " . $e->getMessage() . "</div>";
        return;
    }
}

function render_employee_restricted_field($label, $name, $value, $type = 'text', $options = [], $is_protected = false) {
    $val = !empty($value) ? htmlspecialchars($value) : '---';
    $edit_field = "";
    if (!$is_protected) {
        if (!empty($options)) {
            $select_html = "<select class='form-select form-select-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' style='display:none;'>";
            $select_html .= "<option value=''>-- اختر --</option>";
            foreach ($options as $opt_val => $opt_label) {
                $selected = ($value == $opt_val) ? 'selected' : '';
                $select_html .= "<option value='" . htmlspecialchars($opt_val) . "' {$selected}>" . htmlspecialchars($opt_label) . "</option>";
            }
            $select_html .= "</select>";
            $edit_field = $select_html;
        } else {
            $edit_field = "<input type='{$type}' class='form-control form-control-sm edit-mode-field shadow-none' id='{$name}' name='{$name}' value='".htmlspecialchars($value)."' style='display:none;'>";
        }
    }
    
    echo "
    <div class='col-lg-3 col-md-4 col-6 mb-3'>
        <label class='form-label small fw-bold text-muted mb-1'>{$label}</label>
        <div class='view-mode-field p-2 bg-light rounded border-start border-primary border-3 shadow-sm' style='min-height: 38px; font-size: 0.9rem;'>
            {$val}
        </div>
        {$edit_field}
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
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-dark fw-bold">الاستقدام (رؤية الموظف)</h4>
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
                    <div class="small text-muted mb-1">حالة الطلب</div>
                    <span class="badge <?php echo $status_class; ?> px-3 py-2 rounded-pill fs-6 shadow-sm">
                        <?php echo htmlspecialchars($request['status'] ?? '---'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-user-tie me-2"></i> بيانات الطلب والكفيل</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php 
                    // حقول محمية
                    render_employee_restricted_field('الرقم التسلسلي', 'serial_number', $request['serial_number'] ?? '', 'text', [], true);
                    render_employee_restricted_field('اسم مقدم الطلب', 'applicant_name', $request['applicant_name'] ?? '', 'text', [], true);
                    render_employee_restricted_field('رقم هوية الكفيل', 'national_id', $request['national_id'] ?? '', 'text', [], true);
                    render_employee_restricted_field('رقم الجوال', 'phone', $request['phone'] ?? '', 'text', [], true);
                    render_employee_restricted_field('رقم المصدر', 'service_number', !empty($request['service_number']) ? $request['service_number'] : ($request['export_number'] ?? ''), 'text', [], true);
                    
                    // حقول قابلة للتعديل
                    render_employee_restricted_field('مكتب الاستقدام', 'emirate', $request['emirate'] ?? '', 'text', [
                        'استقدام منطقة الرياض' => 'استقدام منطقة الرياض',
                        'استقدام منطقة مكة المكرمة' => 'استقدام منطقة مكة المكرمة',
                        'استقدام المنطقة الشرقية' => 'استقدام المنطقة الشرقية',
                        'استقدام منطقة المدينة المنورة' => 'استقدام منطقة المدينة المنورة',
                        'استقدام منطقة القصيم' => 'استقدام منطقة القصيم',
                        'استقدام منطقة عسير' => 'استقدام منطقة عسير',
                        'استقدام منطقة تبوك' => 'استقدام منطقة تبوك',
                        'استقدام منطقة حائل' => 'استقدام منطقة حائل',
                        'استقدام منطقة جازان' => 'استقدام منطقة جازان',
                        'استقدام منطقة نجران' => 'استقدام منطقة نجران',
                        'استقدام منطقة الجوف' => 'استقدام منطقة الجوف',
                        'استقدام منطقة الباحة' => 'استقدام منطقة الباحة',
                    ]);
                    render_employee_restricted_field('تاريخ الإصدار (هجري)', 'hijri_date', $request['hijri_date'] ?? '');
                    render_employee_restricted_field('الجهة المصدرة', 'issuing_authority', $request['issuing_authority'] ?? '');
                    render_employee_restricted_field('رقم المعاملة', 'issuance_number', $request['issuance_number'] ?? '');
                    render_employee_restricted_field('تاريخ الموافقة', 'approval_date', $request['approval_date'] ?? '');
                    ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-info text-white py-3 border-0">
                <h5 class="mb-0 fs-6"><i class="fas fa-users me-2"></i> بيانات الوافدين</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="partners-table">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="px-4 py-3" style="width: 50px;">#</th>
                                <th class="py-3">فئة المهنة</th>
                                <th class="py-3">الجنسية</th>
                                <th class="py-3">النوع</th>
                                <th class="py-3">مكان القدوم</th>
                                <th class="py-3">رقم الملف المرسل للبنك</th>
                                <th class="py-3">تاريخ الإرسال للبنك</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            foreach ($partners as $partner): ?>
                            <tr data-id="<?php echo $partner['id']; ?>">
                                <td class="px-4 py-3 text-muted"><?php echo str_pad($count, 2, '0', STR_PAD_LEFT); ?></td>
                                <td data-field-name="job_category"><?php echo htmlspecialchars($partner['job_category'] ?? '---'); ?></td>
                                <td data-field-name="nationality"><?php echo htmlspecialchars($partner['nationality'] ?? '---'); ?></td>
                                <td data-field-name="visa_type"><?php echo htmlspecialchars($partner['visa_type'] ?? 'استقدام عائلي'); ?></td>
                                <td data-field-name="country"><?php echo htmlspecialchars($partner['country'] ?? '---'); ?></td>
                                <td data-field-name="bank_file_number"><?php echo htmlspecialchars($partner['bank_file_number'] ?? '0'); ?></td>
                                <td data-field-name="bank_send_date"><?php echo htmlspecialchars($partner['bank_send_date'] ?? '---'); ?></td>
                             </tr>
                             <?php $count++; endforeach; ?>
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
</script>
