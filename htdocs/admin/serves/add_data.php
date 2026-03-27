<?php

require_once 'includes/database.php';
require_once 'includes/logger.php'; // تضمين ملف تسجيل الأخطاء
require_once 'includes/functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$formType = $_GET['form'] ?? null;
$currentStep = isset($_GET['step']) ? (int) $_GET['step'] : 1;
$errors = [];
$mode = $_GET['mode'] ?? 'add';
$requestId = $_GET['request_id'] ?? null;
$form_data = [];

// Load existing data if in edit mode
if ($mode === 'edit' && $requestId && isset($serviceConfig[$formType])) {
    try {
        $tableName = $serviceConfig[$formType]['table'];
        $stmt = $pdo->prepare("SELECT * FROM `$tableName` WHERE id = ?");
        $stmt->execute([$requestId]);
        $existing_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing_data) {
            $form_data = $existing_data;
        } else {
            set_flash_message("الطلب غير موجود.", "error");
            header("Location: index.php?admin=1&section=dashboard");
            exit;
        }
    } catch (PDOException $e) {
        log_error("Error loading request for edit: " . $e->getMessage());
    }
}

// Restore form data from session if validation failed (overrides DB data)
if (isset($_SESSION['form_data'])) {
    $form_data = array_merge($form_data, $_SESSION['form_data']);
    unset($_SESSION['form_data']);
}
if (isset($_SESSION['form_errors'])) {
    $errors = $_SESSION['form_errors'];
    unset($_SESSION['form_errors']);
}


// مصفوفة لتعريف الخدمات المختلفة
$serviceConfig = [
    'marriage' => ['prefix' => '0', 'table' => 'marriage_permits', 'title' => 'تصريح زواج'],
    'family_visit' => ['prefix' => '1', 'table' => 'family_visits', 'title' => 'زيارة عائلية'],
    'tourism' => ['prefix' => '2', 'table' => 'tourism_visits', 'title' => 'زيارة سياحية'],
    'business_visit' => ['prefix' => '3', 'table' => 'business_visits', 'title' => 'زيارة تجارية'],
    'labor' => ['prefix' => '4', 'table' => 'labor_requests', 'title' => 'عمالة'],
    'followup' => ['prefix' => '5', 'table' => 'followup_requests', 'title' => 'التعقيب'],
    'profession_change' => ['prefix' => '6', 'table' => 'profession_changes', 'title' => 'تغيير مهنة'],
    'civil_affairs' => ['prefix' => '7', 'table' => 'civil_affairs_requests', 'title' => 'أحوال مدنية'],
    'recruitment' => ['prefix' => '8', 'table' => 'recruitment_requests', 'title' => 'استقدام'],
    'runaway_cancellation' => ['prefix' => '5', 'table' => 'runaway_cancellations', 'title' => 'إلغاء بلاغ هروب'],
];

// Generate a unique export number if it's a new form load
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Centralized handler for all Step 1 form submissions
    if (isset($_POST['action']) && strpos($_POST['action'], 'add_') === 0) {
        $formType = $_POST['formType'] ?? '';

        if (!isset($serviceConfig[$formType])) {
            $errors['form'] = "نوع النموذج غير صالح.";
        } else {
            $tableName = $serviceConfig[$formType]['table'];
            $serviceTitle = $serviceConfig[$formType]['title'];

            // Sanitize and collect common inputs
            $data = [
                'applicant_name' => trim($_POST['applicant_name'] ?? ''),
                'national_id' => trim($_POST['national_id'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'export_number' => trim($_POST['export_number'] ?? ''),
                'service_number' => trim($_POST['service_number'] ?? ''),
                'service_type' => trim($_POST['service_type'] ?? ''),
                'service_desc' => trim($_POST['service_desc'] ?? ''),
                'hijri_date' => trim($_POST['hijri_date'] ?? ''),
                'emirate' => trim($_POST['emirate'] ?? ''),
                'appointment_date' => !empty($_POST['appointment_date']) ? trim($_POST['appointment_date']) : null,
                'approval_date' => !empty($_POST['approval_date']) ? trim($_POST['approval_date']) : null,
                'record_number' => trim($_POST['record_number'] ?? ''),
                'issuance_number' => trim($_POST['issuance_number'] ?? ''),
                'submission_date' => !empty($_POST['submission_date']) ? trim($_POST['submission_date']) : null,
                'area' => trim($_POST['area'] ?? ''),
                'area_code' => trim($_POST['area_code'] ?? ''),
                'issuing_authority' => trim($_POST['issuing_authority'] ?? 'وزارة الداخلية-الي'), // Default value
                'bank_file_number' => trim($_POST['bank_file_number'] ?? '0'),
                'serial_number' => trim($_POST['serial_number'] ?? ''),
                'created_by_user_id' => $_SESSION['user_id'] ?? 0,
                'permit_type' => trim($_POST['permit_type'] ?? ''),
                'status' => 'قيد المراجعة'
            ];

            // Collect specific inputs for each form type
            if ($formType === 'marriage') {
                // permit_type moved to Step 2
            }

            if ($formType === 'civil_affairs') {
                $data['nationality'] = trim($_POST['nationality'] ?? null);
                $data['issue_date'] = !empty($_POST['issue_date']) ? trim($_POST['issue_date']) : null;
                $data['transaction_number'] = trim($_POST['transaction_number'] ?? null);
                $data['issuing_authority'] = trim($_POST['issuing_authority'] ?? null);
            }

            if (in_array($formType, ['family_visit', 'tourism', 'business_visit'])) {
                $data['visa_no'] = trim($_POST['visa_no'] ?? null);
                $data['issue_date'] = !empty($_POST['issue_date']) ? trim($_POST['issue_date']) : null;
                $data['valid_until'] = !empty($_POST['valid_until']) ? trim($_POST['valid_until']) : null;
                $data['duration_of_stay'] = trim($_POST['duration_of_stay'] ?? null);
                $data['passport_number'] = trim($_POST['passport_number'] ?? null);
                $data['visa_type'] = trim($_POST['visa_type'] ?? null);
                $data['entry_type'] = trim($_POST['entry_type'] ?? null);
                $data['visa_residence_no'] = trim($_POST['visa_residence_no'] ?? null);
                $data['expiry_date'] = !empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : null;
            }

            if ($formType === 'family_visit') {
                $data['nationality'] = trim($_POST['nationality'] ?? null);
                $data['arrival_place'] = trim($_POST['arrival_place'] ?? null);
            }

            if (in_array($formType, ['business_visit'])) { // Fields specific to business_visit
                $data['valid_from'] = !empty($_POST['valid_from']) ? trim($_POST['valid_from']) : null;
                $data['valid_to'] = !empty($_POST['valid_to']) ? trim($_POST['valid_to']) : null;
            }

            if ($formType === 'labor') {
                $data['establishment_name'] = trim($_POST['establishment_name'] ?? null);
                $data['owner_name'] = trim($_POST['owner_name'] ?? null);
            }
            if (in_array($formType, ['followup', 'profession_change', 'labor'])) {
                $data['sponsor_name'] = trim($_POST['sponsor_name'] ?? null);
                $data['establishment_name'] = trim($_POST['establishment_name'] ?? null);
                $data['sponsor_id'] = trim($_POST['sponsor_id'] ?? null);
            }
            if ($formType === 'followup') {
                $data['source_number'] = trim($_POST['source_number'] ?? null);
                $data['source_entity'] = trim($_POST['source_entity'] ?? 'وزارة العمل-آلي');
                $data['last_modified_date'] = trim($_POST['last_modified_date'] ?? null);
            }

            // 1. تم إلغاء شرط الاسم الرباعي ليقبل أي إدخال (حسب طلب العميل)
            if (empty($data['applicant_name'])) {
                //$errors['applicant_name'] = "يجب إدخال الاسم الرباعي كاملاً (4 كلمات على الأقل).";
            }

            // 2. تحقق من أرقام الهوية والصادر والتسلسلي (يجب أن تكون أرقاماً فقط بغض النظر عن الطول)
            if (empty(trim($data['national_id']))) {
                $errors['national_id'] = "رقم الهوية مطلوب.";
            } elseif (!preg_match('/^\d+$/', toWesternDigits(trim($data['national_id'])))) {
                $errors['national_id'] = "رقم الهوية يجب أن يحتوي على أرقام فقط.";
            }

            if (!empty($data['export_number']) && !preg_match('/^\d+$/', toWesternDigits(trim($data['export_number'])))) {
                $errors['export_number'] = "رقم الصادر يجب أن يحتوي على أرقام فقط.";
            }

            if (!empty($data['serial_number']) && !preg_match('/^\d+$/', toWesternDigits(trim($data['serial_number'])))) {
                $errors['serial_number'] = "رقم التسلسلي يجب أن يحتوي على أرقام فقط.";
            }

            if (empty($errors)) {
                if (USE_DATABASE && $pdo) {
                    try {
                        if (!empty($errors)) {
                            $_SESSION['form_errors'] = $errors;
                            $_SESSION['form_data'] = $_POST;
                            $redirect_url = "index.php?admin=1&section=add_data&form=$formType&error=validation";
                            if ($mode === 'edit')
                                $redirect_url .= "&mode=edit&request_id=$requestId";
                            header("Location: $redirect_url");
                            exit;
                        }

                        if (empty($data['serial_number'])) {
                            $data['serial_number'] = substr(str_shuffle(str_repeat('0123456789', 10)), 0, 10);
                        }

                        // Filter out keys that don't exist in the target table
                        $stmt_cols = $pdo->query("DESCRIBE `$tableName`");
                        $table_columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);
                        $data_to_save = array_intersect_key($data, array_flip($table_columns));

                        if ($mode === 'edit' && $requestId) {
                            // UPDATE Logic
                            // If employee, prevent changing protected fields even if submitted
                            if (isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['موظف', 'Employee'])) {
                                unset($data_to_save['export_number']);
                                unset($data_to_save['serial_number']);
                                unset($data_to_save['applicant_name']);
                                unset($data_to_save['national_id']);
                                unset($data_to_save['phone']);
                            }

                            $set_parts = [];
                            foreach (array_keys($data_to_save) as $col) {
                                $set_parts[] = "`$col` = :$col";
                            }
                            $set_clause = implode(', ', $set_parts);
                            $sql = "UPDATE `$tableName` SET $set_clause WHERE id = :id";
                            $stmt = $pdo->prepare($sql);
                            $data_to_save['id'] = $requestId;
                            $stmt->execute($data_to_save);
                            $last_id = $requestId;
                            set_flash_message("تم تحديث طلب '{$serviceTitle}' بنجاح!", 'success');
                        } else {
                            // INSERT Logic
                            // 3. منع تكرار الطلب لنفس الشخص في نفس الخدمة
                            $stmt_dup = $pdo->prepare("SELECT COUNT(*) FROM `$tableName` WHERE national_id = ? AND status != 'مرفوض' ");
                            $stmt_dup->execute([$data['national_id']]);
                            if ($stmt_dup->fetchColumn() > 0) {
                                $errors['national_id'] = "هذا الشخص لديه طلب مسبق (غير مرفوض) في هذه الخدمة. لا يمكن التكرار.";
                                $_SESSION['form_errors'] = $errors;
                                $_SESSION['form_data'] = $_POST;
                                header("Location: index.php?admin=1&section=add_data&form=$formType&error=validation");
                                exit;
                            }

                            // التحقق من تفرد رقم الصادر
                            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM `$tableName` WHERE export_number = ?");
                            $stmt_check->execute([$data['export_number']]);
                            if ($stmt_check->fetchColumn() > 0) {
                                $errors['export_number'] = "رقم الصادر هذا مستخدم بالفعل.";
                                $_SESSION['form_errors'] = $errors;
                                $_SESSION['form_data'] = $_POST;
                                header("Location: index.php?admin=1&section=add_data&form=$formType&error=validation");
                                exit;
                            }

                            $columns = implode(', ', array_keys($data_to_save));
                            $placeholders = ':' . implode(', :', array_keys($data_to_save));

                            $sql = "INSERT INTO `$tableName` ($columns) VALUES ($placeholders)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute($data_to_save);

                            $last_id = $pdo->lastInsertId();
                            set_flash_message("تمت إضافة طلب '{$serviceTitle}' بنجاح!", 'success');
                        }

                        // Redirect to step 2 for multi-step forms, or back to list for single-step
                        $multiStepForms = ['marriage', 'family_visit', 'tourism', 'business_visit', 'labor', 'civil_affairs', 'recruitment', 'profession_change', 'followup'];
                        if (in_array($formType, $multiStepForms)) {
                            $redirect_url = "index.php?admin=1&section=add_data&form=$formType&step=2&request_id=$last_id";
                            if ($mode === 'edit')
                                $redirect_url .= "&mode=edit";
                            header("Location: $redirect_url");
                            exit;
                        }

                        // For single-step forms, redirect to the main services page
                        header("Location: index.php?admin=1&section=add_data");
                        exit;

                    } catch (PDOException $e) {
                        error_log("PDO Error for table $tableName: " . $e->getMessage());
                        set_flash_message("خطأ في قاعدة البيانات: " . $e->getMessage(), 'error');
                        header("Location: index.php?admin=1&section=add_data&form=$formType&error=db");
                        exit;
                    }
                }
            } else {
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
                header("Location: index.php?admin=1&section=add_data&form=$formType&error=validation");
                exit;
            }
        }
    }
}

// جلب بيانات الطلب الرئيسي للخطوة 2 (مركزي)
$request_details = null;
if ($currentStep == 2 && isset($_GET['request_id']) && !empty($_GET['request_id']) && isset($serviceConfig[$formType])) {
    $req_id = $_GET['request_id'];
    $tableName = $serviceConfig[$formType]['table'];
    $mainTableAlias = 'mt'; // 별칭 사용

    if (USE_DATABASE && isset($pdo)) {
        try {
            // استعلام مرن لجلب بيانات الطلب مع اسم المنشئ
            $sql = "SELECT {$mainTableAlias}.*, u.email as creator_name 
                    FROM `{$tableName}` AS {$mainTableAlias}
                    LEFT JOIN `system_users` u ON {$mainTableAlias}.created_by_user_id = u.user_id 
                    WHERE {$mainTableAlias}.id = ?";

            $stmt_req = $pdo->prepare($sql);
            $stmt_req->execute([$req_id]);
            $request_details = $stmt_req->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            log_error("Failed to fetch main request data for form '$formType' (ID: $req_id): " . $e->getMessage(), __FILE__, __LINE__);
        }
    }
}

// Close PHP tag after all PHP logic
?>
<div class="container">

    <?php
    // تحويل الرسائل القديمة إلى إشعارات ديناميكية
// يتم التعامل مع رسائل الإشعارات الآن بواسطة common.js أو بواسطة ملفات النماذج الفردية
    ?>

    <?php if (!$formType): ?>
        <?php require_once 'service_selection.php'; ?>
        <?php
    elseif (isset($serviceConfig[$formType])): ?>
        <?php
        // Include the specific form file based on the 'form' parameter
        require_once "admin/serves/{$formType}_form.php";
        ?>
        <?php
    endif; ?>
    <!-- تضمين ملف بيانات الدول بشكل دائم في هذه الصفحة -->