<?php
session_start();
header('Content-Type: application/json');
// api/update_full_request.php
// تحديث بيانات الطلب بالكامل (مع دعم رفع الصور وتحديث الشركاء لكافة الخدمات)

// منع عرض الأخطاء مباشرة لتجنب تخريب الـ JSON
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once '../includes/logger.php';

// استخدام try-catch شامل لالتقاط أي خطأ قاتل
try {
    require_once '../includes/database.php';

    $response = ['success' => false, 'message' => 'طلب غير صالح.'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. استلام البيانات
        $requestId = $_POST['id'] ?? null;
        $tableName = $_POST['table'] ?? 'marriage_permits';

        // قائمة الجداول المسموح بها
        $allowed_tables = [
            'marriage_permits',
            'family_visits',
            'tourism_visits',
            'business_visits',
            'recruitment_requests',
            'labor_requests',
            'civil_affairs_requests',
            'profession_changes',
            'followup_requests'
        ];

        if (!in_array($tableName, $allowed_tables)) {
            echo json_encode(['success' => false, 'message' => 'نوع الجدول غير صالح.']);
            exit;
        }

        if ($requestId && $pdo) {
            try {
                $pdo->beginTransaction();
                $errors = [];

                // 2. معالجة رفع الصورة (إذا وجدت)
                $newPhotoPath = null;
                if (isset($_FILES['profile_photo_file']) && $_FILES['profile_photo_file']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = UPLOADS_ROOT_PATH;

                    $fileExt = strtolower(pathinfo($_FILES['profile_photo_file']['name'], PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($fileExt, $allowedExts)) {
                        $fileName = uniqid('photo_') . '.' . $fileExt;
                        $targetPath = $uploadDir . $fileName;
                        
                        // تخزين اسم الملف فقط في قاعدة البيانات (بدون بادئة uploads/)
                        $dbPath = $fileName;

                        if (move_uploaded_file($_FILES['profile_photo_file']['tmp_name'], $targetPath)) {
                            $newPhotoPath = $dbPath;

                            // حذف الصورة القديمة
                            $oldPhotoPath = $_POST['profile_photo_path'] ?? '';
                            if (!empty($oldPhotoPath)) {
                                $cleanOldFilename = basename($oldPhotoPath);
                                $fullOldPhysicalPath = UPLOADS_ROOT_PATH . $cleanOldFilename;
                                
                                if (file_exists($fullOldPhysicalPath)) {
                                    unlink($fullOldPhysicalPath);
                                }
                            }
                        } else {
                            $errors[] = "فشل رفع الصورة الشخصية.";
                        }
                    } else {
                        $errors[] = "نوع الملف غير مدعوم. يرجى رفع صورة (JPG, PNG).";
                    }
                }

                // 3. تحديث بيانات الطلب الرئيسية
                // جلب أعمدة الجدول للتحقق من صحة الحقول المرسلة
                $stmtCols = $pdo->prepare("DESCRIBE `$tableName`");
                $stmtCols->execute();
                $tableColumns = $stmtCols->fetchAll(PDO::FETCH_COLUMN);

                $fields = [];
                $params = [];

                // استبعاد الحقول الخاصة بالنظام والتي لا يجب تحديثها مباشرة من الـ POST
                $excludedFields = ['id', 'table', 'partners_json', 'profile_photo_file', 'profile_photo_path'];

                require_once '../includes/functions.php';
                $is_employee = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['موظف', 'Employee']);
                $protected_fields = ['serial_number', 'export_number', 'applicant_name', 'national_id'];

                foreach ($_POST as $key => $value) {
                    // تجاهل الحقول المستبعدة أو التي لا توجد في الجدول
                    if (in_array($key, $excludedFields) || !in_array($key, $tableColumns))
                        continue;

                    // منع الموظف من تعديل الحقول السيادية
                    if ($is_employee && in_array($key, $protected_fields))
                        continue;

                    $fields[] = "`$key` = ?";
                    $val = ($value === '') ? null : $value;

                    // Convert numbers to Western digits before saving
                    if ($val !== null && ($key === 'national_id' || $key === 'export_number' || $key === 'service_number' || $key === 'issuance_number' || $key === 'record_number' || $key === 'sponsor_id' || $key === 'source_number' || $key === 'bank_file_number')) {
                        $val = toWesternDigits($val);
                    }

                    $params[] = $val;
                }

                // إضافة مسار الصورة الجديد إذا تم رفعه (فقط إذا كان العمود موجوداً)
                if ($newPhotoPath) {
                    if (in_array('profile_photo_path', $tableColumns)) {
                        $fields[] = "`profile_photo_path` = ?";
                        $params[] = $newPhotoPath;
                    }
                }

                // Reset status only if the user is a worker/employee (not Root or Manager)
                $is_admin = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['Root', 'مدير', 'Manager']);
                if (!$is_admin) {
                    if (in_array('status', $tableColumns)) {
                        $fields[] = "`status` = 'قيد المراجعة'";
                    }
                    if (in_array('rejection_reason', $tableColumns)) {
                        $fields[] = "`rejection_reason` = NULL";
                    }
                }

                if (!empty($fields)) {
                    $sql = "UPDATE `$tableName` SET " . implode(', ', $fields) . " WHERE id = ?";
                    $params[] = $requestId;
                    $stmt = $pdo->prepare($sql);
                    if (!$stmt->execute($params)) {
                        $errors[] = "فشل تحديث بيانات الطلب.";
                    }
                }

                // --- NEW: Sync status with related_data if main status was updated ---
                if (isset($_POST['status']) && !empty($fields)) {
                    $related_mapping = [
                        'marriage_permits' => 'marriage_permit_id',
                        'family_visits' => 'family_visit_id',
                        'tourism_visits' => 'tourism_visit_id',
                        'business_visits' => 'business_visit_id',
                        'labor_requests' => 'labor_request_id',
                        'followup_requests' => 'followup_request_id',
                        'profession_changes' => 'profession_change_id',
                        'civil_affairs_requests' => 'civil_affairs_request_id',
                        'recruitment_requests' => 'recruitment_request_id',
                    ];

                    if (isset($related_mapping[$tableName])) {
                        $fk = $related_mapping[$tableName];
                        $stmt_related_sync = $pdo->prepare("UPDATE related_data SET status = ? WHERE `{$fk}` = ?");
                        $stmt_related_sync->execute([$_POST['status'], $requestId]);
                    }
                }
                // ---------------------------------------------------------------------

                // 4. تحديث بيانات الشركاء (لجميع الخدمات)
                if (!empty($_POST['partners_json'])) {
                    $partnersData = json_decode($_POST['partners_json'], true);
                    if (is_array($partnersData)) {
                        // جلب أعمدة جدول related_data للتحقق من وجود الحقول
                        $stmtPartnerCols = $pdo->prepare("DESCRIBE `related_data`");
                        $stmtPartnerCols->execute();
                        $partnerColumns = $stmtPartnerCols->fetchAll(PDO::FETCH_COLUMN);

                        $anyPartnerStatusUpdate = null;

                        foreach ($partnersData as $partner) {
                            $partnerId = $partner['id'] ?? null;
                            if (!$partnerId)
                                continue;

                            $partnerFields = [];
                            $partnerParams = [':id' => $partnerId];

                            // خريطة الحقول المتاحة والقيم المرسلة
                            $possiblePartnerFields = [
                                'full_name',
                                'passport_number',
                                'relationship',
                                'job_category',
                                'birth_date',
                                'age',
                                'nationality',
                                'country',
                                'bank_file_number',
                                'bank_send_date',
                                'appointment_date',
                                'status',
                                'visa_no',
                                'visa_type',
                                'issue_date',
                                'valid_until',
                                'expiry_date',
                                'duration_of_stay',
                                'entry_type',
                                'permit_type'
                            ];

                            foreach ($possiblePartnerFields as $col) {
                                if (in_array($col, $partnerColumns)) {
                                    if (isset($partner[$col])) {
                                        $partnerFields[] = "`$col` = :$col";
                                        $val = $partner[$col];
                                        if (($col === 'birth_date' || $col === 'age' || $col === 'appointment_date') && empty($val)) {
                                            $partnerParams[":$col"] = null;
                                        } else {
                                            if ($col === 'national_id' || $col === 'passport_number' || $col === 'visa_no') {
                                                $val = toWesternDigits($val);
                                            }
                                            $partnerParams[":$col"] = $val;
                                        }

                                        // Track if status is updated to sync with main request
                                        if ($col === 'status') {
                                            $anyPartnerStatusUpdate = $val;
                                        }
                                    }
                                }
                            }

                            if (!empty($partnerFields)) {
                                $sqlPartner = "UPDATE related_data SET " . implode(', ', $partnerFields) . " WHERE id = :id";
                                $partnerStmt = $pdo->prepare($sqlPartner);
                                if (!$partnerStmt->execute($partnerParams)) {
                                    $errors[] = "فشل تحديث بيانات الفرد ID: $partnerId";
                                }
                            }
                        }

                        // --- NEW: Sync status with main request if any partner status was updated ---
                        if ($anyPartnerStatusUpdate !== null && empty($errors)) {
                            $stmt_main_sync = $pdo->prepare("UPDATE `{$tableName}` SET status = ? WHERE id = ?");
                            $stmt_main_sync->execute([$anyPartnerStatusUpdate, $requestId]);
                        }
                        // ---------------------------------------------------------------------------
                    }
                }

                if (empty($errors)) {
                    $pdo->commit();
                    $response['success'] = true;
                    $response['message'] = 'تم تحديث الطلب بنجاح!';
                } else {
                    $pdo->rollBack();
                    $response['message'] = 'حدثت أخطاء: ' . implode(', ', $errors);
                }

            } catch (PDOException $e) {
                if ($pdo->inTransaction())
                    $pdo->rollBack();
                $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
            }
        }
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    // التقاط أي خطأ قاتل وإرجاعه كـ JSON
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ غير متوقع في الخادم: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>