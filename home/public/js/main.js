/**
 * Initializes the Hijri Picker for all input fields with the 'hijri-picker' class.
 * This ensures the calendar works across all forms (marriage, tourism, etc.)
 * and can be re-initialized for dynamically added elements.
 */

function initializeHijriPickers(container = document) {
    console.log('Initializing Hijri Pickers...');
    if (typeof createHijriPicker !== 'undefined') {
        // Find pickers that have not been initialized yet.
        const datePickers = container.querySelectorAll('.hijri-picker:not(.picker-initialized)');
        console.log('Found', datePickers.length, 'hijri-picker elements');

        datePickers.forEach(picker => {
            // Ensure the field has an ID before initializing the picker.
            if (picker.id) {
                console.log('Initializing picker for:', picker.id);
                createHijriPicker(picker.id);
                picker.classList.add('picker-initialized'); // Mark as initialized
            } else {
                console.warn('Hijri picker element found without ID:', picker);
            }
        });
    } else {
        console.error('createHijriPicker function not found! Make sure hijri-picker.js is loaded.');
    }
}

// Initial call on page load
document.addEventListener('DOMContentLoaded', function () {
    // Wait a bit to ensure hijri-picker.js is loaded
    setTimeout(initializeHijriPickers, 200);
});

// تفعيل النافذة المنبثقة للطلبات
var requestModal = document.getElementById('requestModal')
if (requestModal) {
    requestModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var serviceId = button.getAttribute('data-service-id')
        var serviceName = button.getAttribute('data-service-name')

        var modalTitle = requestModal.querySelector('.modal-title')
        var serviceIdInput = requestModal.querySelector('#service_id')

        modalTitle.textContent = 'تقديم طلب: ' + serviceName
        serviceIdInput.value = serviceId
    })
}

// التنقل السلس
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// تهيئة الرسوم البيانية في لوحة التحكم
var requestsChartCanvas = document.getElementById('requestsPerServiceChart');
if (requestsChartCanvas && typeof Chart !== 'undefined') {
    try {
        const chartData = JSON.parse(requestsChartCanvas.getAttribute('data-chart-data'));

        const labels = chartData.map(item => item.service_name);
        const data = chartData.map(item => item.request_count);

        new Chart(requestsChartCanvas, {
            type: 'bar', // يمكن تغييره إلى 'pie' أو 'doughnut'
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الطلبات',
                    data: data,
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: 'rgba(44, 62, 80, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    } catch (e) {
        console.error("Error parsing chart data:", e);
    }
}

/**
 * Displays a dynamic toast notification on the screen.
 * @param {string} message The message to display.
 * @param {string} type 'success' for green, 'error' for red.
 */
function showToast(message, type = 'success') {
    const backgroundColor = type === 'success'
        ? 'linear-gradient(to right, #00b09b, #96c93d)'
        : 'linear-gradient(to right, #ff5f6d, #ffc371)';

    Toastify({
        text: message,
        duration: 4000, // 4 seconds
        close: true,
        gravity: "bottom", // `top` or `bottom`
        position: "left", // `left`, `center` or `right`
        style: { background: backgroundColor, },
    }).showToast();
}

/**
 * Legacy function for backward compatibility.
 */
function saveRelatedItem(formType, formElement) {
    return addRelatedItem(formType, formElement);
}

/**
 * Adds a related item (partner, person, visitor) via AJAX.
 * @param {string} formType - The type of the form (e.g., 'marriage', 'labor').
 * @param {HTMLFormElement} formElement - The form element being submitted.
 */
function addRelatedItem(formType, formElement) {
    const formData = new FormData(formElement);
    const data = {};
    for (const [key, value] of formData.entries()) {
        data[key] = value;
    }
    data.formType = formType; // Ensure formType is included
    data.item_id = data.item_id || null; // Ensure item_id is set for updates
    
    // Ensure CSRF token is included in the JSON body
    if (!data.csrf_token && formElement.querySelector('input[name="csrf_token"]')) {
        data.csrf_token = formElement.querySelector('input[name="csrf_token"]').value;
    }

    fetch('api/add_related_item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => {
                    // We received non-JSON, probably a PHP error.
                    throw new Error("Server response was not JSON: " + text);
                });
            }
        })
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                console.log('Returned item:', result.item); // Debug log
                if (data.item_id) { // If it was an update
                    updateRowInTable(result.item, formType);
                } else { // If it was an add
                    addRowToTable(result.item, formType);
                }
                resetRelatedItemForm(formElement);
            } else {
                showToast(result.message || 'حدث خطأ غير متوقع.', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error.message);
            // Display a more informative error from the server
            const cleanErrorMessage = error.message.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
            showToast('فشل الطلب. ' + cleanErrorMessage, 'error');
        });
}

/**
 * Deletes a related item via AJAX.
 * @param {string} formType - The type of the form.
 * @param {number} requestId - The ID of the main request.
 * @param {number} itemId - The ID of the item to delete.
 * @param {HTMLElement} buttonElement - The button that was clicked.
 */
function deleteRelatedItem(formType, requestId, itemId, buttonElement) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "لن تتمكن من التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، احذفه!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = buttonElement.closest('form');
            const csrfToken = form ? form.querySelector('input[name="csrf_token"]').value : '';
            const data = { formType, requestId, itemId, csrf_token: csrfToken };

            fetch('api/delete_related_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showToast(result.message, 'success');
                        const row = buttonElement.closest('tr');
                        if (row) row.remove();
                    } else {
                        showToast(result.message || 'فشل الحذف.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('فشل الاتصال بالخادم.', 'error');
                });
        }
    });
}

/**
 * Generates the HTML for a table row based on the form type.
 * @param {object} item - The item data.
 * @param {string} formType - The type of the form.
 * @param {HTMLElement} tableBody - The tbody element of the table.
 * @returns {string} - The innerHTML for the new or updated row.
 */
function getRowTemplate(item, formType, tableBody) {
    const container = tableBody.closest('.form-container');
    const requestInput = container ? container.querySelector('input[name="request_id"]') : document.querySelector('input[name="request_id"]');
    const requestId = requestInput ? requestInput.value : '';
    const safeItemJson = JSON.stringify(item).replace(/'/g, "&apos;");
    const actionsCell = `
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-primary me-1" title="تعديل" onclick='populateFormForEdit(${safeItemJson}, "${formType}")'>
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteRelatedItem('${formType}', '${requestId}', ${item.id}, this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;

    // Template for Marriage form
    if (formType === 'marriage') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.job_category || '---'}</td>
            <td>${item.nationality || ''}</td>
            <td>${item.country || '---'}</td>
            ${actionsCell}
        `;
    }
    // Template for Family Visit form
    else if (formType === 'family_visit') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.relationship || '---'}</td>
            <td>${item.age || '---'}</td>
            <td>${item.duration || item.duration_of_stay || '---'}</td>
            <td>${item.job_category || '---'}</td>
            <td>${item.birth_date || '---'}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.nationality || ''}</td>
            <td>${item.country || '---'}</td>
            ${actionsCell}
        `;
    }
    // Template for Business Visit form
    else if (formType === 'business_visit') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.visa_no || '---'}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.duration_of_stay || '---'}</td>
            <td>${item.valid_until || '---'}</td>
            <td>${item.nationality || ''}</td>
            <td>${item.country || '---'}</td>
            <td>${item.birth_date || '---'}</td>
            <td>${item.visa_type || '---'}</td>
            <td>${item.entry_type || '---'}</td>
            ${actionsCell}
        `;
    }
    // Template for Tourism Visit form
    else if (formType === 'tourism') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.visa_no || '---'}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.duration_of_stay || '---'}</td>
            <td>${item.valid_until || '---'}</td>
            <td>${item.nationality || ''}</td>
            <td>${item.birth_date || '---'}</td>
            <td>${item.visa_type || '---'}</td>
            <td>${item.entry_type || '---'}</td>
            ${actionsCell}
        `;
    }
    // Template for Runaway Cancellation form
    else if (formType === 'runaway_cancellation') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.old_profession || '---'}</td>
            <td>${item.job_category || '---'}</td>
            <td>${item.iqama_issue_date || '---'}</td>
            <td>${item.iqama_expiry_date || '---'}</td>
            <td>${item.nationality || '---'}</td>
            ${actionsCell}
        `;
    }
    // Template for Profession Change form
    else if (formType === 'profession_change') {
        return `
            <td>${item.full_name || ''}</td>
            <td>${item.national_id || '---'}</td>
            <td>${item.passport_number || '---'}</td>
            <td>${item.old_profession || '---'}</td>
            <td>${item.job_category || '---'}</td>
            <td>${item.iqama_issue_date || '---'}</td>
            <td>${item.iqama_expiry_date || '---'}</td>
            <td>${item.nationality || '---'}</td>
            ${actionsCell}
        `;
    }

    // Fallback for other forms (like labor, recruitment, etc.)
    return `
        <td>${item.full_name || ''}</td>
        <td>${item.passport_number || ''}</td>
        <td>${item.relationship || item.job_category || ''}</td>
        <td>${item.nationality || ''}</td>
        ${(tableBody.closest('table') && tableBody.closest('table').querySelector('th:nth-child(5)') && tableBody.closest('table').querySelector('th:nth-child(5)').innerText.includes('القدوم')) ? `<td>${item.country || item.arrival_place || ''}</td>` : ''}
        ${actionsCell}
    `;
}


/**
 * Adds a new row to the related items table.
 * @param {object} item - The item data returned from the API.
 * @param {string} formType - The type of the form.
 */
function addRowToTable(item, formType) {
    // Try to find the specific table for this form type, or use the general one
    const context = document.querySelector(`#related${formType.charAt(0).toUpperCase() + formType.slice(1)}Form`) || document;
    const tableBody = context.querySelector('.related-items-table tbody');

    if (!tableBody) {
        console.error('Table body not found for formType:', formType);
        return;
    }
    const newRow = tableBody.insertRow();
    newRow.setAttribute('data-item-id', item.id);
    newRow.innerHTML = getRowTemplate(item, formType, tableBody);
}

/**
 * Updates an existing row in the related items table.
 * @param {object} item - The updated item data returned from the API.
 * @param {string} formType - The type of the form.
 */
function updateRowInTable(item, formType) {
    const row = document.querySelector(`.related-items-table tbody tr[data-item-id="${item.id}"]`);
    if (!row) {
        console.error('Row not found for updating:', item.id);
        addRowToTable(item, formType);
        return;
    }
    row.innerHTML = getRowTemplate(item, formType, row.closest('tbody'));
}

/**
 * Populates the add/edit form with item data for editing.
 * @param {object} itemData - The data of the item to edit.
 * @param {string} formType - The type of the form.
 */
function populateFormForEdit(itemData, formType) {
    // A more robust way to find the form container for the current service
    const formContainer = document.querySelector(`#related${formType.charAt(0).toUpperCase() + formType.slice(1)}Form, #relatedPersonForm, #relatedPartnerForm, #relatedMemberForm, #relatedVisitorForm`);
    if (!formContainer) {
        console.error('Could not find a unique form container for:', formType);
        return;
    }

    const form = formContainer.querySelector('form');
    if (!form) {
        console.error('Form element not found for populating:', formType);
        return;
    }

    // --- 1. Show the form if it's hidden ---
    const toggleBtn = formContainer.querySelector('.btn-circle[id^="toggle"]');
    const collapsibleContainer = form.parentElement; // e.g., #addPersonFormContainer

    if (toggleBtn && collapsibleContainer && (collapsibleContainer.style.maxHeight === '0px' || !collapsibleContainer.style.maxHeight)) {
        toggleBtn.click(); // Programmatically click the toggle button to expand
    }

    // --- 2. Populate hidden item_id field ---
    let itemIdInput = form.querySelector('input[name="item_id"]');
    if (!itemIdInput) {
        itemIdInput = document.createElement('input');
        itemIdInput.type = 'hidden';
        itemIdInput.name = 'item_id';
        form.appendChild(itemIdInput);
    }
    itemIdInput.value = itemData.id;

    // --- 3. Populate fields dynamically ---
    // This loop iterates through all data properties and tries to find a matching input field
    for (const key in itemData) {
        // Skip keys that handle special logic or shouldn't be auto-mapped
        if (['id', 'created_at', 'updated_at'].includes(key)) continue;

        const input = form.querySelector(`[name="${key}"]`);
        if (input) {
            // Safe assignment for standard inputs
            if (input.type !== 'file' && input.type !== 'submit') {
                input.value = itemData[key] || '';
            }
        }
    }

    // Special handling for arrival_place/country mapping if needed
    // (The dynamic loop handles direct matches, but country->arrival_place needs specific logic)

    const arrivalPlaceInput = form.querySelector('[name="arrival_place"]');
    if (arrivalPlaceInput) {
        arrivalPlaceInput.value = itemData.country || itemData.arrival_place || '';
        if (arrivalPlaceInput.tagName === 'SELECT') {
            arrivalPlaceInput.disabled = false;
        }
    }

    // --- 4. Change button text ---
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.innerText = 'تحديث';
    }

    // --- 5. Scroll to the form ---
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });

    // --- 6. Trigger smart search dropdowns if applicable ---
    const natInput = form.querySelector('[name="nationality"]');
    const arrInput = form.querySelector('[name="arrival_place"]');
    if (natInput) natInput.dispatchEvent(new Event('focus'));
    if (arrInput) arrInput.dispatchEvent(new Event('focus'));
}

/**
 * Resets the related item form to its initial state (for adding new items).
 * @param {HTMLFormElement} formElement - The form element to reset.
 */
function resetRelatedItemForm(formElement) {
    formElement.reset();
    // Clear hidden item_id
    const itemIdInput = formElement.querySelector('input[name="item_id"]');
    if (itemIdInput) {
        itemIdInput.value = '';
    }
    // Reset submit button text using data attribute for specific text, with a fallback
    const submitButton = formElement.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.innerText = submitButton.dataset.addText || 'إضافة';
    }

    // Clear and ensure arrival_place is ready for input
    const arrivalPlaceElement = formElement.querySelector('[name="arrival_place"]');
    if (arrivalPlaceElement) {
        arrivalPlaceElement.value = '';
        if (arrivalPlaceElement.tagName === 'SELECT') {
            arrivalPlaceElement.value = 'مفتوح';
            arrivalPlaceElement.disabled = true;
        } else {
            arrivalPlaceElement.disabled = false;
        }
    }

    // Collapse the form after submission/reset
    const formContainer = formElement.closest('.form-collapsible');
    if (formContainer && formContainer.style.maxHeight !== '0px') {
        const toggleBtn = formContainer.parentElement ? formContainer.parentElement.querySelector('.btn-circle[id^="toggle"]') : null;
        if (toggleBtn) {
            toggleBtn.click();
        }
    }
}

// Expose functions to window for inline scripts
window.addRelatedItem = addRelatedItem;
window.deleteRelatedItem = deleteRelatedItem;
window.populateFormForEdit = populateFormForEdit;
window.resetRelatedItemForm = resetRelatedItemForm;
window.saveRelatedItem = saveRelatedItem;
window.showToast = showToast;