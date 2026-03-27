<?php
/**
 * admin/serves/views/related_management_ui.php
 * مكون مشترك لإدارة الأشخاص (إضافة وحذف) داخل جداول الخدمات
 */
?>
<style>
/* تنسيق أزرار التحكم العلوية وأزرار أوضاع العمل */
.related-controls, .related-actions {
    display: flex;
    gap: 6px;
    padding: 6px;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    align-items: center;
}

.related-actions {
    display: none; /* تظهر فقط في أوضاع الإضافة والحذف */
}

/* الأزرار / الرموز المربعة الصغيرة */
.btn-related-tool, .btn-action-icon {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: transform 0.2s ease, filter 0.2s ease;
    border: none;
    color: white;
    cursor: pointer;
}

.btn-related-tool i, .btn-action-icon i { 
    font-size: 0.9rem; 
}

/* تاثير الضغط والتحويم */
.btn-related-tool:hover, .btn-action-icon:hover {
    filter: brightness(0.9);
    transform: scale(1.05);
}

.btn-related-tool:active, .btn-action-icon:active {
    transform: scale(0.95);
}

/* ألوان تعبر عن الوظائف (رموز) */
.btn-add-person, .btn-add-row { background-color: #28a745; } /* أخضر = إضافة */
.btn-delete-mode, .btn-confirm-delete { background-color: #dc3545; } /* أحمر = حذف */
.btn-save-related { background-color: #007bff; } /* أزرق = حفظ */
.btn-cancel-related { background-color: #6c757d; } /* رمادي = إلغاء */

/* تنسيق الصفوف الجديدة */
.new-person-row {
    background-color: #f0fff4 !important; /* لون أخضر خفيف جداً */
}

.new-person-row input {
    border: 1px solid #c3e6cb;
    padding: 4px 6px;
    border-radius: 4px;
    width: 100%;
    font-size: 0.85rem;
}

/* استجابة حجم الرموز للشاشات والحفاظ على ديناميكية الحجم */
@media (max-width: 576px) {
    .btn-related-tool, .btn-action-icon {
        width: 26px;
        height: 26px;
        border-radius: 5px;
    }
    .btn-related-tool i, .btn-action-icon i {
        font-size: 0.8rem;
    }
    .related-controls, .related-actions {
        padding: 4px;
        gap: 4px;
    }
}

/* عمود الاختيار (Chechbox) */
.col-selection {
    width: 40px;
    text-align: center;
    display: none; /* يظهر فقط في وضع الحذف */
}

.animated-show {
    animation: fadeInDown 0.3s ease;
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- واجهة أزرار التحكم -->
<div class="related-management-container px-3 mt-4" id="related-mgr-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <label class="fw-bold text-muted small mb-0"><i class="fas fa-cog me-1"></i> أدوات الإدارة السريعة</label>
        
        <div class="related-controls" id="default-controls">
            <!-- الترتيب كما في الصورة: حذف (- أو سلة) على اليسار وإضافة (+) على اليمين داخل الواجهة أو العكس حسب الاتجاه -->
            <button type="button" class="btn-related-tool btn-delete-mode" onclick="relatedMgr.enterDeleteMode()" title="حذف أشخاص">
                <i class="fas fa-trash-alt"></i>
            </button>
            <button type="button" class="btn-related-tool btn-add-person" onclick="relatedMgr.enterAddMode()" title="إضافة شخص جديد">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <div class="related-actions" id="add-actions">
            <!-- رموز لعمليات الإضافة: إلغاء، إضافة صف آخر، حفظ (مرتبة حسب الألوان) -->
            <button type="button" class="btn-action-icon btn-cancel-related" onclick="relatedMgr.reset()" title="إلغاء">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="btn-action-icon btn-add-row" onclick="relatedMgr.addNewRow()" title="إضافة صف آخر">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn-action-icon btn-save-related" onclick="relatedMgr.saveNewRows()" title="حفظ الإضافات">
                <i class="fas fa-check"></i>
            </button>
        </div>

        <div class="related-actions" id="delete-actions">
            <!-- رموز لعمليات الحذف: إلغاء، تأكيد الحذف -->
            <button type="button" class="btn-action-icon btn-cancel-related" onclick="relatedMgr.reset()" title="إلغاء">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="btn-action-icon btn-confirm-delete" onclick="relatedMgr.confirmDelete()" title="تأكيد حذف المحددين">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>

<script>
const relatedMgr = {
    mode: 'view',
    table: document.getElementById('partners-table'),
    mainTable: "<?php echo $table ?? ''; ?>",
    mainId: "<?php echo $id ?? ''; ?>",
    fields: [],

    init() {
        if (!this.table) return;
        // استخراج أسماء الحقول من أول صف متاح
        const firstRow = this.table.querySelector('tbody tr[data-id]');
        if (firstRow) {
            firstRow.querySelectorAll('td[data-field-name]').forEach(td => {
                this.fields.push(td.dataset.fieldName);
            });
        }
        
        // اكتشاف وجود عمود الرقم/ID تلقائياً لتوافق جميع الخدمات
        const headers = Array.from(this.table.querySelectorAll('thead th')).map(th => th.innerText.trim());
        this.hasIdColumn = headers.some(h => h.includes('الرقم') || h.includes('ID') || h.includes('#') || h.includes('التسلسلي'));
        
        // إضافة عمود الاختيار في الهيدر (مخفي)
        const theadRow = this.table.querySelector('thead tr');
        const thSelection = document.createElement('th');
        thSelection.className = 'col-selection';
        thSelection.innerHTML = '<input type="checkbox" onclick="relatedMgr.toggleAll(this)">';
        theadRow.prepend(thSelection);
    },

    enterAddMode() {
        this.mode = 'add';
        document.getElementById('default-controls').style.display = 'none';
        document.getElementById('add-actions').style.display = 'flex';
        this.addNewRow();
    },

    addNewRow() {
        const tbody = this.table.querySelector('tbody');
        const row = document.createElement('tr');
        row.className = 'new-person-row animated-show';
        
        // عمود الاختيار (فارغ في وضع الإضافة، ونتركه مخفياً حسب الـ CSS)
        const tdSel = document.createElement('td');
        tdSel.className = 'col-selection';
        row.appendChild(tdSel);

        // إضافة عمود الرقم فقط إذا كان موجوداً في الهيدر (للتوافق مع الخدمات الأخرى)
        if (this.hasIdColumn) {
            const tdId = document.createElement('td');
            tdId.className = 'px-4 py-3 fw-bold text-success';
            tdId.innerText = 'قيد المراجعة';
            row.appendChild(tdId);
        }

        // الحقول الديناميكية
        this.fields.forEach(field => {
            const td = document.createElement('td');
            td.innerHTML = `<input type="text" class="form-control form-control-sm" data-new-field="${field}" placeholder="...">`;
            row.appendChild(td);
        });

        tbody.appendChild(row);
    },

    enterDeleteMode() {
        const rows = this.table.querySelectorAll('tbody tr[data-id]');
        if (rows.length === 0) {
            Swal.fire('تنبيه', 'لا يوجد أشخاص لحذفهم.', 'info');
            return;
        }

        this.mode = 'delete';
        document.getElementById('default-controls').style.display = 'none';
        document.getElementById('delete-actions').style.display = 'flex';

        // إظهار أعمدة الاختيار
        document.querySelectorAll('.col-selection').forEach(el => el.style.display = 'table-cell');

        // إضافة checkboxes للتروس الحالية
        rows.forEach(row => {
            if (!row.querySelector('.col-selection')) {
                const td = document.createElement('td');
                td.className = 'col-selection';
                td.style.display = 'table-cell';
                td.innerHTML = `<input type="checkbox" class="row-checkbox" value="${row.dataset.id}">`;
                row.prepend(td);
            }
        });
    },

    toggleAll(master) {
        this.table.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
    },

    reset() {
        if (this.mode === 'add') {
            this.table.querySelectorAll('.new-person-row').forEach(row => row.remove());
        }
        
        this.mode = 'view';
        document.getElementById('default-controls').style.display = 'flex';
        document.getElementById('add-actions').style.display = 'none';
        document.getElementById('delete-actions').style.display = 'none';
        document.querySelectorAll('.col-selection').forEach(el => el.style.display = 'none');
        this.table.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
    },

    saveNewRows() {
        const rows = this.table.querySelectorAll('.new-person-row');
        const persons = [];
        let valid = true;

        rows.forEach(row => {
            const person = {};
            let hasData = false;
            row.querySelectorAll('input[data-new-field]').forEach(input => {
                const field = input.dataset.newField;
                const value = input.value.trim();
                person[field] = value;
                if (value !== '') hasData = true;
            });
            if (hasData) {
                // تحقق بسيط من الحقول الإلزامية (الاسم مثلاً إذا كان موجوداً)
                if (person.full_name === '') {
                    valid = false;
                    row.querySelector('input[data-new-field="full_name"]').classList.add('is-invalid');
                }
                persons.push(person);
            }
        });

        if (!valid) {
            Swal.fire('خطأ', 'يرجى ملء الاسم الكامل لجميع الصفوف المضافة.', 'warning');
            return;
        }

        if (persons.length === 0) {
            this.reset();
            return;
        }

        // إعادة تفعيل الزر إذا فشل التحقق
        const btn = document.querySelector('.btn-save-related');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('main_table', this.mainTable);
        formData.append('main_id', this.mainId);
        formData.append('persons', JSON.stringify(persons));

        fetch('api/manage_related_data.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                Swal.fire('تم بنجاح', res.message, 'success').then(() => window.location.reload());
            } else {
                Swal.fire('فشل', res.message, 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i>';
            }
        })
        .catch(err => {
            Swal.fire('خطأ', 'حدث خطأ غير متوقع بالاتصال بالخادم.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i>';
        });
    },

    confirmDelete() {
        const checked = this.table.querySelectorAll('.row-checkbox:checked');
        if (checked.length === 0) {
            Swal.fire('تنبيه', 'يرجى اختيار شخص واحد على الأقل للحذف.', 'warning');
            return;
        }

        const ids = Array.from(checked).map(cb => cb.value);

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف ${ids.length} أشخاص من الطلب نهائياً!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'نعم، احذفهم',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('ids', JSON.stringify(ids));

                fetch('api/manage_related_data.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('نجاح', res.message, 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('فشل', res.message, 'error');
                    }
                });
            }
        });
    }
};

// تشغيل المكون بعد تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => relatedMgr.init());
</script>
