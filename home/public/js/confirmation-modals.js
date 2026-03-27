/**
 * نظام النوافذ المنبثقة للتأكيد (Confirmation Modals)
 * يوفر نوافذ تأكيد حديثة ومركزية لجميع الإجراءات الحساسة
 */

// تهيئة Bootstrap Modals
let confirmModal, rejectModal, deleteModal;

document.addEventListener('DOMContentLoaded', function () {
    // تهيئة النوافذ المنبثقة
    const confirmModalEl = document.getElementById('confirmActionModal');
    const rejectModalEl = document.getElementById('rejectReasonModal');
    const deleteModalEl = document.getElementById('deleteConfirmModal');

    if (confirmModalEl) confirmModal = new bootstrap.Modal(confirmModalEl);
    if (rejectModalEl) rejectModal = new bootstrap.Modal(rejectModalEl);
    if (deleteModalEl) deleteModal = new bootstrap.Modal(deleteModalEl);
});

/**
 * عرض نافذة تأكيد عامة
 * @param {string} title - عنوان النافذة
 * @param {string} message - رسالة التأكيد
 * @param {function} onConfirm - دالة يتم تنفيذها عند التأكيد
 * @param {string} confirmBtnText - نص زر التأكيد (افتراضي: "تأكيد")
 * @param {string} confirmBtnClass - فئة CSS لزر التأكيد (افتراضي: "btn-primary")
 */
function showConfirmDialog(title, message, onConfirm, confirmBtnText = 'تأكيد', confirmBtnClass = 'btn-primary') {
    const modalEl = document.getElementById('confirmActionModal');
    if (!modalEl) {
        console.error('Confirm modal not found');
        return;
    }

    // تحديث محتوى النافذة
    document.getElementById('confirmModalTitle').innerHTML = title;
    document.getElementById('confirmModalBody').innerHTML = message;

    const confirmBtn = document.getElementById('confirmActionBtn');
    confirmBtn.textContent = confirmBtnText;
    confirmBtn.className = `btn ${confirmBtnClass}`;

    // إزالة المستمعات السابقة
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    // إضافة مستمع جديد
    newConfirmBtn.addEventListener('click', function () {
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
        confirmModal.hide();
    });

    confirmModal.show();
}

/**
 * عرض نافذة إدخال سبب الرفض
 * @param {number} requestId - معرف الطلب
 * @param {string} tableName - اسم الجدول
 * @param {function} onReject - دالة يتم تنفيذها عند التأكيد
 */
function showRejectDialog(requestId, tableName, onReject) {
    const modalEl = document.getElementById('rejectReasonModal');
    if (!modalEl) {
        console.error('Reject modal not found');
        return;
    }

    const reasonTextarea = document.getElementById('rejectionReasonText');
    reasonTextarea.value = ''; // تفريغ الحقل

    const confirmBtn = document.getElementById('confirmRejectBtn');

    // إزالة المستمعات السابقة
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    // إضافة مستمع جديد
    newConfirmBtn.addEventListener('click', function () {
        const reason = reasonTextarea.value.trim();
        if (!reason) {
            if (typeof showToast === 'function') {
                showToast('يجب إدخال سبب للرفض.', 'warning');
            } else {
                alert('يجب إدخال سبب للرفض.');
            }
            return;
        }

        if (typeof onReject === 'function') {
            onReject(reason);
        }
        rejectModal.hide();
    });

    rejectModal.show();
}

/**
 * عرض نافذة تأكيد الحذف
 * @param {string} itemName - اسم العنصر المراد حذفه
 * @param {function} onDelete - دالة يتم تنفيذها عند التأكيد
 */
function showDeleteDialog(itemName, onDelete) {
    const modalEl = document.getElementById('deleteConfirmModal');
    if (!modalEl) {
        console.error('Delete modal not found');
        return;
    }

    const messageEl = document.getElementById('deleteModalBody');
    messageEl.innerHTML = `هل أنت متأكد من حذف <strong>${itemName}</strong>؟<br><small class="text-muted">لا يمكن التراجع عن هذا الإجراء.</small>`;

    const confirmBtn = document.getElementById('confirmDeleteBtn');

    // إزالة المستمعات السابقة
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    // إضافة مستمع جديد
    newConfirmBtn.addEventListener('click', function () {
        if (typeof onDelete === 'function') {
            onDelete();
        }
        deleteModal.hide();
    });

    deleteModal.show();
}

/**
 * تحديث حالة الطلب
 * @param {number} requestId - معرف الطلب
 * @param {string} newStatus - الحالة الجديدة
 * @param {string} tableName - اسم الجدول
 * @param {object} extraData - بيانات إضافية (مثل سبب الرفض)
 */
function updateRequestStatus(requestId, newStatus, tableName, extraData = {}) {
    const payload = {
        id: requestId,
        status: newStatus,
        table: tableName,
        ...extraData
    };

    fetch('api/update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') {
                    showToast(data.message, 'success');
                } else {
                    alert(data.message);
                }
                // إعادة تحميل الصفحة بعد ثانيتين
                setTimeout(() => window.location.reload(), 2000);
            } else {
                if (typeof showToast === 'function') {
                    showToast('فشل تحديث الحالة: ' + data.message, 'error');
                } else {
                    alert('فشل تحديث الحالة: ' + data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast === 'function') {
                showToast('حدث خطأ في الشبكة.', 'error');
            } else {
                alert('حدث خطأ في الشبكة.');
            }
        });
}
