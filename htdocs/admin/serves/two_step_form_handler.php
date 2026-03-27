<?php
/**
 * معالج مركزي للنماذج ذات الخطوتين
 * يتم استخدامه في: marriage, family_visit, tourism, business_visit, labor, civil_affairs, recruitment, profession_change, runaway_cancellation
 */

if (!defined('TWO_STEP_HANDLER_LOADED')) {
    define('TWO_STEP_HANDLER_LOADED', true);

    /**
     * معالجة عمليات الخطوة 2 (إضافة/تعديل/حذف البيانات المرتبطة)
     * 
     * @param string $formType نوع النموذج (marriage, family_visit, إلخ)
     * @param int $currentStep الخطوة الحالية
     * @param PDO $pdo اتصال قاعدة البيانات
     */
    function handle_step2_operations($formType, $currentStep, $pdo)
    {
        if ($currentStep != 2) {
            return;
        }

        $relatedTable = 'related_data';

        // تحديد اسم مفتاح الربط بشكل ديناميكي بناءً على نوع النموذج
        $formTypeMapping = [
            'marriage' => 'marriage_permit_id',
            'family_visit' => 'family_visit_id',
            'tourism' => 'tourism_visit_id',
            'business_visit' => 'business_visit_id',
            'labor' => 'labor_request_id',
            'followup' => 'followup_request_id',
            'profession_change' => 'profession_change_id',
            'civil_affairs' => 'civil_affairs_request_id',
            'recruitment' => 'recruitment_request_id',
            'runaway_cancellation' => 'runaway_cancellation_id',
        ];

        // استخدم 'request_id' كقيمة افتراضية إذا لم يتم العثور على نوع النموذج
        $foreignKeyColumn = $formTypeMapping[$formType] ?? 'request_id';


        $requestId = $_GET['request_id'] ?? $_POST['request_id'] ?? null;
        $action = $_GET['action'] ?? $_POST['action'] ?? '';

        // Debug logging
        if (function_exists('log_error')) {
            log_error("Step 2 Handler: FormType=$formType, Step=$currentStep, RequestID=" . ($requestId ?? 'NULL') . ", ForeignKey=$foreignKeyColumn", __FILE__, __LINE__);
        }

        if (!$requestId || !USE_DATABASE || !isset($pdo)) {
            return;
        }

        try {
            // AJAX handler for Add/Update
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action === 'add_or_update_item') {
                header('Content-Type: application/json');
                $itemId = $_POST['item_id'] ?? null;
                $data = [
                    'full_name' => trim($_POST['full_name'] ?? ''),
                    'national_id' => trim($_POST['national_id'] ?? null),
                    'passport_number' => trim($_POST['passport_number'] ?? ''),
                    'duration_of_stay' => trim($_POST['duration_of_stay'] ?? null),
                    'job_category' => trim($_POST['job_category'] ?? ''),
                    'birth_date' => !empty($_POST['birth_date']) ? trim($_POST['birth_date']) : null,
                    'nationality' => trim($_POST['nationality'] ?? ''),
                    'country' => trim($_POST['arrival_place'] ?? $_POST['country'] ?? ''), // 'arrival_place' from form maps to 'country'
                    'relationship' => trim($_POST['relationship'] ?? ''),
                    'age' => trim($_POST['age'] ?? null),
                    'duration' => trim($_POST['duration'] ?? null),
                    'visa_type' => trim($_POST['visa_type'] ?? null),
                    'entry_type' => trim($_POST['entry_type'] ?? null),
                    'expiry_date' => !empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : (!empty($_POST['valid_until']) ? trim($_POST['valid_until']) : null),
                    'visa_no' => trim($_POST['visa_no'] ?? $_POST['visa_residence_no'] ?? null),
                    'issue_date' => !empty($_POST['issue_date']) ? trim($_POST['issue_date']) : null,
                    'valid_until' => !empty($_POST['valid_until']) ? trim($_POST['valid_until']) : (!empty($_POST['expiry_date']) ? trim($_POST['expiry_date']) : null),
                    'iqama_issue_date' => !empty($_POST['iqama_issue_date']) ? trim($_POST['iqama_issue_date']) : null,
                    'iqama_expiry_date' => !empty($_POST['iqama_expiry_date']) ? trim($_POST['iqama_expiry_date']) : null,
                    'old_profession' => trim($_POST['old_profession'] ?? null),
                    'bank_file_number' => trim($_POST['bank_file_number'] ?? ''),
                    'bank_send_date' => trim($_POST['bank_send_date'] ?? ''),
                    'approval_type' => trim($_POST['approval_type'] ?? ''),
                    'permit_type' => trim($_POST['permit_type'] ?? ''),
                    'appointment_date' => !empty($_POST['appointment_date']) ? trim($_POST['appointment_date']) : null,
                    'status' => !empty(trim($_POST['status'] ?? '')) ? trim($_POST['status']) : 'قيد المراجعة',
                ];

                // --- Photo Upload Handling (Step 2) ---
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = UPLOADS_ROOT_PATH;
                    if (!is_dir($uploadDir))
                        mkdir($uploadDir, 0777, true);

                    $fileExtension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
                    $fileName = 'photo_' . uniqid() . '.' . $fileExtension;
                    
                    // تخزين اسم الملف فقط في قاعدة البيانات (بدون بادئة uploads/)
                    $photoPath = $fileName;

                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array(strtolower($fileExtension), $allowed)) {
                        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadDir . $fileName)) {
                            $data['profile_photo_path'] = $photoPath;
                        }
                    }
                }

                if (empty($data['full_name'])) {
                    echo json_encode(['success' => false, 'message' => 'يرجى إدخال الاسم الكامل.']);
                    exit;
                }

                // Filter out keys that don't exist in the target table
                $stmt_cols = $pdo->query("DESCRIBE `$relatedTable`");
                $table_columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);
                $filtered_data = array_intersect_key($data, array_flip($table_columns));


                if ($itemId) { // Update
                    $filtered_data['id'] = $itemId;
                    // Filter out keys that are not submitted for update
                    $update_data = array_filter($filtered_data, function ($key) {
                        if ($key === 'id')
                            return true;
                        if ($key === 'profile_photo_path' && isset($filtered_data[$key]))
                            return true;
                        if (isset($_POST[$key]))
                            return true;

                        // Check for common mapped/aliased fields
                        $mappings = [
                            'country' => 'arrival_place',
                            'visa_no' => 'visa_residence_no',
                            'expiry_date' => 'valid_until',
                            'valid_until' => 'expiry_date'
                        ];

                        return isset($mappings[$key]) && isset($_POST[$mappings[$key]]);
                    }, ARRAY_FILTER_USE_KEY);

                    $set_clauses = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($update_data)));
                    $sql = "UPDATE `$relatedTable` SET $set_clauses WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($update_data);
                    echo json_encode(['success' => true, 'message' => 'تم تحديث البيانات بنجاح!', 'item' => $update_data]);
                }
                else { // Insert
                    $filtered_data[$foreignKeyColumn] = $requestId;
                    $columns = implode(', ', array_keys($filtered_data));
                    $placeholders = ':' . implode(', :', array_keys($filtered_data));
                    $sql = "INSERT INTO `$relatedTable` ($columns) VALUES ($placeholders)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($filtered_data);
                    $lastId = $pdo->lastInsertId();
                    $filtered_data['id'] = $lastId; // Add ID to the returned data
                    echo json_encode(['success' => true, 'message' => 'تمت إضافة البيانات بنجاح!', 'item' => $filtered_data]);
                }
                exit;
            }

            // Original logic for non-AJAX delete (can be removed if delete is only AJAX)
            if ($action === 'delete_item' && isset($_GET['item_id'])) {
                $itemId = $_GET['item_id'];
                $stmt = $pdo->prepare("DELETE FROM `$relatedTable` WHERE id = ? AND `$foreignKeyColumn` = ?");
                $stmt->execute([$itemId, $requestId]);
                // This part is for page reloads, AJAX response is handled in delete_related_item.php
                set_flash_message("تم الحذف بنجاح!", 'success');
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

        }
        catch (PDOException $e) {
            log_error("Database error during step 2 operation for $formType: " . $e->getMessage(), __FILE__, __LINE__);
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action === 'add_or_update_item') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
                exit;
            }
            else {
                set_flash_message("خطأ في قاعدة البيانات: " . $e->getMessage(), 'danger');
            }
        }
    }

    /**
     * جلب بيانات الطلب الرئيسي والبيانات المرتبطة للخطوة 2
     * 
     * @param string $formType نوع النموذج
     * @param int $currentStep الخطوة الحالية
     * @param PDO $pdo اتصال قاعدة البيانات
     * @param string $mainTable اسم الجدول الرئيسي
     * @return array مصفوفة تحتوي على request_details و related_items
     */
    function fetch_step2_data($formType, $currentStep, $pdo, $mainTable)
    {
        $result = [
            'request_details' => null,
            'related_items' => []
        ];

        if ($currentStep != 2 || empty($_GET['request_id']) || !USE_DATABASE || !isset($pdo)) {
            return $result;
        }

        $relatedTable = 'related_data';

        // تحديد اسم مفتاح الربط بشكل ديناميكي بناءً على نوع النموذج
        $formTypeMapping = [
            'marriage' => 'marriage_permit_id',
            'tourism' => 'tourism_visit_id',
            'family_visit' => 'family_visit_id',
            'business_visit' => 'business_visit_id',
            'labor' => 'labor_request_id',
            'followup' => 'followup_request_id',
            'profession_change' => 'profession_change_id',
            'civil_affairs' => 'civil_affairs_request_id',
            'recruitment' => 'recruitment_request_id',
            'runaway_cancellation' => 'runaway_cancellation_id',
        ];

        // استخدم 'request_id' كقيمة افتراضية إذا لم يتم العثور على نوع النموذج
        $foreignKeyColumn = $formTypeMapping[$formType] ?? 'request_id';

        $req_id = $_GET['request_id'];

        try {
            // جلب بيانات الطلب الرئيسي
            $stmt_req = $pdo->prepare(
                "SELECT mt.*, u.username as creator_name 
                 FROM `{$mainTable}` mt 
                 LEFT JOIN `system_users` u ON mt.created_by_user_id = u.user_id 
                 WHERE mt.id = ?"
            );
            $stmt_req->execute([$req_id]);
            $result['request_details'] = $stmt_req->fetch(PDO::FETCH_ASSOC);

            // جلب البيانات المرتبطة
            $stmt_items = $pdo->prepare("SELECT * FROM `$relatedTable` WHERE `$foreignKeyColumn` = ? ORDER BY id ASC");
            $stmt_items->execute([$req_id]);
            $result['related_items'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            log_error("Failed to fetch data for $formType ID $req_id: " . $e->getMessage(), __FILE__, __LINE__);
            $_SESSION['flash_message'] = "خطأ في جلب بيانات الطلب.";
            $_SESSION['flash_type'] = 'danger';
        }

        return $result;
    }
}
?>