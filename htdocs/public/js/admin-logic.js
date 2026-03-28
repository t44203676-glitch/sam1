/**
 * admin-logic.js
 * Centralized logic for the Admin Dashboard.
 * Moved from inline script to allow browser caching and minification.
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Universal Table Actions (Edit, Save, Cancel, Delete, Lock, Unlock)
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit');
        const saveBtn = e.target.closest('.btn-save');
        const cancelBtn = e.target.closest('.btn-cancel');
        const deleteBtn = e.target.closest('.btn-delete, .btn-delete-request');
        const lockBtn = e.target.closest('.btn-lock-request');
        const unlockBtn = e.target.closest('.btn-unlock-request');

        if (editBtn) { e.preventDefault(); handleUniversalEdit(editBtn); }
        if (saveBtn) { e.preventDefault(); handleUniversalSave(saveBtn); }
        if (cancelBtn) { e.preventDefault(); handleUniversalCancel(cancelBtn); }
        if (deleteBtn) { e.preventDefault(); handleUniversalDelete(deleteBtn); }
        if (lockBtn) { e.preventDefault(); handleUniversalLock(lockBtn); }
        if (unlockBtn) { e.preventDefault(); handleUniversalUnlock(unlockBtn); }
    });

    function handleUniversalUnlock(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const id = button.dataset.id || row.dataset.id;
        const table = button.dataset.table || row.dataset.sourceTable || row.dataset.table;

        Swal.fire({
            title: 'تأكيد فك التعليق',
            text: 'هل أنت متأكد من فك تعليق هذه المعاملة؟ سيتمكن الموظفون والمدراء من تعديلها مجدداً.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، فك التعليق!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch('api/update_request.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: id,
                        source_table: table,
                        is_locked: 0,
                        status: 'قيد المراجعة'
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('نجاح', 'تم فك تعليق المعاملة بنجاح.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('خطأ', data.message || 'فشل فك تعليق المعاملة.', 'error');
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-lock-open"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('خطأ', 'حدث خطأ غير متوقع.', 'error');
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-lock-open"></i>';
                    });
            }
        });
    }

    function handleUniversalLock(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const id = button.dataset.id || row.dataset.id;
        const table = button.dataset.table || row.dataset.sourceTable || row.dataset.table;

        Swal.fire({
            title: 'تأكيد التعليق',
            text: 'هل أنت متأكد من تعليق هذه المعاملة؟ سيتم قفلها نهائياً ولا يمكن تعديلها لاحقاً.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، قم بتعليقها!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch('api/update_request.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: id,
                        source_table: table,
                        is_locked: 1,
                        status: 'تم تعليق المعاملة'
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('نجاح', data.message || 'تم تعليق المعاملة بنجاح.', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('خطأ', data.message || 'فشل تعليق المعاملة.', 'error');
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-user-lock"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('خطأ', 'حدث خطأ في الشبكة.', 'error');
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-user-lock"></i>';
                    });
            }
        });
    }

    function handleUniversalEdit(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const saveBtn = row.querySelector('.btn-save');
        const cancelBtn = row.querySelector('.btn-cancel');

        button.style.display = 'none';
        if (saveBtn) saveBtn.style.display = 'inline-block';
        if (cancelBtn) cancelBtn.style.display = 'inline-block';
        row.classList.add('table-warning');

        const userType = window.currentUserType || 'Guest';

        const editableCells = row.querySelectorAll('[data-field]');
        editableCells.forEach(cell => {
            const field = cell.dataset.field;
            if (field !== 'status') return;

            const originalHTML = cell.innerHTML;
            const originalValue = cell.textContent.trim();

            cell.dataset.originalHtml = originalHTML;
            cell.dataset.originalValue = originalValue;

            let isReadOnly = false;
            const sensitiveFields = ['national_id', 'export_number', 'serial_number', 'transaction_number', 'applicant_name'];
            if (userType === 'موظف' && sensitiveFields.includes(field)) {
                isReadOnly = true;
            }

            if (isReadOnly) return;

            if (field === 'status') {
                const statusConfig = {
                    'جديد': { color: '#6c757d', textColor: '#fff' },
                    'بانتظار موافقة المدير': { color: '#0d6efd', textColor: '#fff' },
                    'تمت الموافقة': { color: '#198754', textColor: '#fff' },
                    'تمت المراجعة': { color: '#198754', textColor: '#fff' },
                    'قيد المراجعة': { color: '#ffc107', textColor: '#000' },
                    'جاري الاعتماد': { color: '#0dcaf0', textColor: '#000' },
                    'تم تعليق المعاملة': { color: '#212529', textColor: '#fff' },
                    'مرفوض': { color: '#dc3545', textColor: '#fff' }
                };
                let optionsHTML = '';
                for (const [status, config] of Object.entries(statusConfig)) {
                    const isSelected = (status === originalValue || (originalValue === '' && status === 'جديد')) ? 'selected' : '';
                    const style = `background-color: ${config.color} !important; color: ${config.textColor} !important; font-weight: bold;`;
                    optionsHTML += `<option value="${status}" ${isSelected} style="${style}">${status}</option>`;
                }
                cell.innerHTML = `<select class="form-select form-select-sm border-warning border-2 shadow-sm fw-bold edit-status-select" onchange="this.style.setProperty('background-color', this.options[this.selectedIndex].style.backgroundColor, 'important'); this.style.setProperty('color', this.options[this.selectedIndex].style.color, 'important');">${optionsHTML}</select>`;

                const select = cell.querySelector('select');
                if (select && select.selectedIndex >= 0) {
                    const opt = select.options[select.selectedIndex];
                    select.style.setProperty('background-color', opt.style.backgroundColor, 'important');
                    select.style.setProperty('color', opt.style.color, 'important');
                }
            } else if (field === 'created_at' || field === 'request_date') {
                let dateVal = originalValue;
                if (dateVal.includes('/')) dateVal = dateVal.split('/').join('-');
                cell.innerHTML = `<input type="date" class="form-control form-control-sm border-warning border-2 bg-light shadow-sm" value="${dateVal}">`;
            } else if (field === 'permit_type') {
                const permitOptions = [
                    '',
                    'زواج سعودي من اجنبية مواليد السعودية',
                    'زواج سعودية من اجنبي مواليد السعودية',
                    'زواج سعودي من اجنبية داخل الاراضي السعودية مواليد خارج السعودية',
                    'زواج سعودية من اجنبي داخل الاراضي السعودية مواليد خارج السعودية',
                    'زواج سعودي من اجنبية خارج الاراضي السعودية مواليد خارج السعودية',
                    'زواج سعودية من اجنبي خارج الاراضي السعودية مواليد خارج السعودية'
                ];
                let ptOptionsHTML = '';
                for (const pt of permitOptions) {
                    const isSelected = (pt === originalValue) ? 'selected' : '';
                    ptOptionsHTML += `<option value="${pt}" ${isSelected}>${pt || 'اختر نوع التصريح...'}</option>`;
                }
                cell.innerHTML = `<select class="form-select form-select-sm border-warning border-2 bg-light shadow-sm" style="width: 100%;">${ptOptionsHTML}</select>`;
            } else {
                cell.innerHTML = `<input type="text" class="form-control form-control-sm border-warning border-2 bg-light shadow-sm" style="width: 100%;" value="${originalValue}">`;
            }
        });
    }

    function handleUniversalCancel(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const editBtn = row.querySelector('.btn-edit');
        const saveBtn = row.querySelector('.btn-save');

        button.style.display = 'none';
        if (saveBtn) saveBtn.style.display = 'none';
        if (editBtn) editBtn.style.display = 'inline-block';
        row.classList.remove('table-warning');

        const editableCells = row.querySelectorAll('[data-field]');
        editableCells.forEach(cell => {
            if (cell.dataset.originalHtml) {
                cell.innerHTML = cell.dataset.originalHtml;
            }
        });
    }

    function handleUniversalSave(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const id = row.dataset.id || row.dataset.itemId;
        const table = row.dataset.sourceTable || row.dataset.table;
        const editBtn = row.querySelector('.btn-edit');
        const cancelBtn = row.querySelector('.btn-cancel');

        const dataToSave = { id: id, source_table: table };
        const inputs = row.querySelectorAll('[data-field] input, [data-field] select');
        inputs.forEach(input => {
            const cell = input.closest('[data-field]');
            dataToSave[cell.dataset.field] = input.value;
        });

        const apiUrl = row.dataset.itemId ? 'api/update_related_item.php' : 'api/update_request.php';

        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataToSave),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('نجاح', data.message || 'تم التحديث بنجاح.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('خطأ', data.message || 'فشل التحديث.', 'error');
                }
            })
            .catch(error => { console.error('Error:', error); showNotification('حدث خطأ في الشبكة.', 'danger'); })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i>';
            });
    }

    function handleUniversalDelete(button) {
        const row = button.closest('tr') || button.closest('.details-section');
        const id = button.dataset.id || row.dataset.id || row.dataset.itemId;
        const table = button.dataset.table || row.dataset.sourceTable || row.dataset.table;

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تتمكن من استرجاع هذا السجل!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('api/delete_request.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, source_table: table }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            row.remove();
                        } else {
                            showNotification(data.message, 'danger');
                        }
                    })
                    .catch(error => { console.error('Error:', error); showNotification('حدث خطأ في الشبكة.', 'danger'); });
            }
        });
    }

    function showNotification(message, type = 'success') {
        const toastType = type === 'danger' ? 'error' : type;
        if (typeof showToast === 'function') {
            showToast(message, toastType);
        } else {
            Swal.fire({ icon: toastType, title: 'تنبيه', text: message, confirmButtonText: 'حسناً' });
        }
    }

    // 2. Dynamic Search
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const serviceFilter = document.getElementById('serviceFilter');
    const tableBody = document.getElementById('requestsTableBody');
    let searchDebounceTimer;

    if (searchInput && statusFilter && serviceFilter && tableBody) {
        function performSearch() {
            const searchTerm = searchInput.value;
            const statusValue = statusFilter.value;
            const serviceValue = serviceFilter.value;

            tableBody.innerHTML = '<tr><td colspan="7" class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

            fetch(`api/search_requests.php?search=${encodeURIComponent(searchTerm)}&status=${encodeURIComponent(statusValue)}&service=${encodeURIComponent(serviceValue)}`)
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html || '<tr><td colspan="7" class="text-center alert alert-info">لا توجد طلبات تطابق معايير البحث.</td></tr>';
                })
                .catch(error => {
                    console.error('Search Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center alert alert-danger">حدث خطأ أثناء البحث.</td></tr>';
                });
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(performSearch, 300);
        });

        statusFilter.addEventListener('change', performSearch);
        serviceFilter.addEventListener('change', performSearch);

        if (new URLSearchParams(window.location.search).get('section') === 'requests') {
            performSearch();
        }
    }

    // 3. Sidebar Toggle
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('sidebarMenu');

    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }

    // 4. Charts Initialization
    function initCharts() {
        const requestsByStatus = window.requestsByStatus || [];
        const weeklyStats = window.weeklyStats || { labels: [], data: [] };

        const statusCtx = document.getElementById('statusDistributionChart');
        if (statusCtx && requestsByStatus.length > 0) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: requestsByStatus.map(item => item.status),
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: requestsByStatus.map(item => item.count),
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)', 'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)', 'rgba(6, 182, 212, 0.8)',
                            'rgba(16, 185, 129, 0.8)', 'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: 'rgba(255,255,255,0.9)',
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { family: 'Tahoma', size: 12 }, padding: 12, usePointStyle: true, pointStyle: 'circle' } },
                        tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.95)', padding: 12, cornerRadius: 8 }
                    }
                }
            });
        }

        const weeklyCtx = document.getElementById('weeklyRequestsChart');
        if (weeklyCtx && weeklyStats.labels && weeklyStats.labels.length > 0) {
            new Chart(weeklyCtx, {
                type: 'line',
                data: {
                    labels: weeklyStats.labels,
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: weeklyStats.data,
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return 'rgba(99, 102, 241, 0.1)';
                            const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
                            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.02)');
                            return gradient;
                        },
                        borderColor: '#6366f1',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.1)' }, ticks: { color: '#94a3b8', font: { size: 12 }, stepSize: 1 } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } }
                    },
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.95)', padding: 12, cornerRadius: 8, displayColors: false } }
                }
            });
        }
    }
    initCharts();

    // 5. Validation Logic
    const toWesternDigits = (str) => {
        if (!str) return str;
        return str.toString().replace(/[٠-٩]/g, d => String.fromCharCode(d.charCodeAt(0) - 1632)).replace(/[۰-۹]/g, d => String.fromCharCode(d.charCodeAt(0) - 1776));
    };

    const validators = {
        national_id: (val) => /^[0-9]{10}$/.test(toWesternDigits(val.trim())),
        phone: (val) => /^05[0-9]{8}$/.test(toWesternDigits(val.trim())),
        passport_number: (val) => /^[a-zA-Z0-9]{6,15}$/.test(toWesternDigits(val.trim())),
        record_number: (val) => /^[0-9]{10}$/.test(toWesternDigits(val.trim())),
        issuance_number: (val) => /^[0-9]{10}$/.test(toWesternDigits(val.trim())),
        transaction_number: (val) => /^[0-9]{10}$/.test(toWesternDigits(val.trim())),
        service_number: (val) => /^[0-9]{10}$/.test(toWesternDigits(val.trim())),
        bank_file_number: (val) => !isNaN(toWesternDigits(val.trim())),
        applicant_name: (val) => val.trim().split(/\s+/).filter(word => word.length > 0).length >= 4
    };

    const errorMessages = {
        national_id: 'يجب إدخال 10 أرقام بالضبط.',
        phone: 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام.',
        passport_number: 'رقم الجواز يجب أن يكون بين 6 إلى 15 حرفاً أو رقماً.',
        record_number: 'يجب إدخال 10 أرقام بالضبط.',
        issuance_number: 'يجب إدخال 10 أرقام بالضبط.',
        transaction_number: 'يجب إدخال 10 أرقام بالضبط.',
        service_number: 'يجب إدخال 10 أرقام بالضبط.',
        bank_file_number: 'يرجى إدخال رقم ملف صحيح.',
        applicant_name: 'يرجى إدخال الاسم الرباعي على الأقل (4 أسماء).'
    };

    const validateField = (input, validator, errorMsg) => {
        const feedback = document.getElementById(input.id + '-feedback');
        if (!feedback) return;

        const isValid = validator(input.value);
        if (input.value === '') {
            input.classList.remove('is-invalid', 'is-valid');
            feedback.textContent = '';
        } else if (!isValid) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            const strict10Fields = ['national_id', 'issuance_number', 'transaction_number', 'service_number', 'record_number'];
            feedback.textContent = strict10Fields.includes(input.id) ? `يجب إدخال 10 أرقام بالضبط (أدخلت ${input.value.length})` : errorMsg;
            feedback.style.color = '#dc3545';
        } else {
            if (feedback.textContent.includes('موجود')) return;
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            feedback.textContent = 'تبدو جيدة!';
            feedback.style.color = '#198754';
        }
    };

    window.isSectionValid = function (sectionId) {
        const section = document.getElementById(sectionId);
        if (!section) return true;

        let isValid = true;
        let firstInvalid = null;
        const inputs = section.querySelectorAll('input:not([type="hidden"]), select');
        
        inputs.forEach(input => {
            const feedback = document.getElementById(input.id + '-feedback');
            if (feedback && feedback.textContent.includes('موجود')) { isValid = false; if (!firstInvalid) firstInvalid = input; return; }
            if (input.dataset.checkingDuplicate === "true") { 
                isValid = false; if (!firstInvalid) firstInvalid = input; 
                if (feedback) { feedback.textContent = 'جاري التحقق...'; feedback.style.color = '#0d6efd'; }
                return;
            }

            const validator = validators[input.id];
            const isEmpty = !input.value.trim();

            if (isEmpty && input.hasAttribute('required')) {
                input.classList.add('is-invalid');
                if (feedback) { feedback.textContent = 'هذا الحقل إلزامي.'; feedback.style.color = '#dc3545'; }
                isValid = false; if (!firstInvalid) firstInvalid = input;
            } else if (!isEmpty && validator) {
                if (!validator(input.value)) { validateField(input, validator, errorMessages[input.id]); isValid = false; if (!firstInvalid) firstInvalid = input; }
            }
        });

        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Show a global error notice
            if (typeof showNotification === 'function') {
                showNotification('هناك أخطاء في الحقول، يرجى تصحيحها للمتابعة.', 'danger');
            } else if (typeof Swal !== 'undefined') {
                Swal.fire('خطأ في البيانات', 'يرجى مراجعة الحقول باللون الأحمر قبل المتابعة.', 'error');
            }
        }
        return isValid;
    };

    document.querySelectorAll('form').forEach(form => {
        if(['addPartnerForm', 'addItemForm', 'multiStepForm'].includes(form.id)) {
            form.setAttribute('novalidate', 'true');
            form.addEventListener('submit', (e) => { if (!window.isSectionValid(form.id)) e.preventDefault(); });
        }
    });

    // Tooltips
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(el));
});
