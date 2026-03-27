<?php
header('Content-Type: application/json');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/database.php';
// require_once __DIR__ . '/../includes/logger.php';

// Check for user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input.']);
    exit;
}

$formType = $input['formType'] ?? null;
$requestId = $input['request_id'] ?? null;
$itemId = $input['item_id'] ?? null; // Used for updates

if (!$formType || !$requestId) {
    echo json_encode(['success' => false, 'message' => 'Form type or request ID is missing.']);
    exit;
}

// --- Map formType to its corresponding foreign key in the related_data table ---
$formTypeMapping = [
    'marriage' => 'marriage_permit_id',
    'family_visit' => 'family_visit_id',
    'tourism' => 'tourism_visit_id',
    'business_visit' => 'business_visit_id',
    'labor' => 'labor_request_id',
    'runaway_cancellation' => 'runaway_cancellation_id',
    'profession_change' => 'profession_change_id',
    'civil_affairs' => 'civil_affairs_request_id',
    'recruitment' => 'recruitment_request_id',
    'followup' => 'followup_request_id',
];

$foreignKeyColumn = $formTypeMapping[$formType] ?? null;

if (!$foreignKeyColumn) {
    // log_error("Unknown formType '$formType' in add_related_item.php.");
    echo json_encode(['success' => false, 'message' => "Unknown form type: $formType"]);
    exit;
}

$relatedTable = 'related_data';

// --- Get actual columns from the related_data table ---
$stmt_cols = $pdo->query("DESCRIBE `$relatedTable`");
$allowed_columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);

// --- Explicitly define all possible fields from the forms ---
$data = [
    'full_name'        => trim($input['full_name'] ?? ''),
    'national_id'      => trim($input['national_id'] ?? null),
    'passport_number'  => trim($input['passport_number'] ?? null),
    'duration_of_stay' => trim($input['duration'] ?? $input['duration_of_stay'] ?? null),
    'duration'         => trim($input['duration'] ?? null),
    'relationship'     => trim($input['relationship'] ?? null),
    'birth_date'       => !empty($input['birth_date']) ? trim($input['birth_date']) : null,
    'nationality'      => trim($input['nationality'] ?? null),
    'country'          => trim($input['arrival_place'] ?? null), // Map form field 'arrival_place' to DB column 'country'
    'age'              => trim($input['age'] ?? null),
    'permit_type'      => trim($input['permit_type'] ?? null),
    'visa_type'        => trim($input['visa_type'] ?? null),
    'entry_type'       => trim($input['entry_type'] ?? null),
    'visa_no'          => trim($input['visa_no'] ?? $input['visa_residence_no'] ?? null), // Accept both field names
    'issue_date'       => !empty($input['issue_date']) ? trim($input['issue_date']) : null,
    'valid_until'      => trim($input['valid_until'] ?? $input['expiry_date'] ?? null), // Accept both field names
    'job_category'     => trim($input['job_category'] ?? $input['new_profession'] ?? null),
    'old_profession'   => trim($input['old_profession'] ?? null),
    'iqama_issue_date' => !empty($input['iqama_issue_date']) ? trim($input['iqama_issue_date']) : null,
    'iqama_expiry_date'=> !empty($input['iqama_expiry_date']) ? trim($input['iqama_expiry_date']) : null,
    'arrival_place'    => trim($input['arrival_place'] ?? null),
    'approval_type'    => trim($input['approval_type'] ?? null),
    'status'           => trim($input['status'] ?? null),
    'appointment_date' => !empty($input['appointment_date']) ? trim($input['appointment_date']) : null,
    'appointment_time' => !empty($input['appointment_time']) ? trim($input['appointment_time']) : null,
];

// Filter out null and empty string values to create a clean array for the DB
$data_for_db = array_filter($data, function($value) {
    return $value !== null && $value !== '';
});


try {
    $pdo->beginTransaction();

    $message = '';
    $affected_id = null;

    if ($itemId && !empty($data_for_db)) { // --- UPDATE Logic ---
        // Filter data to only include whitelisted columns
        $data_to_update = array_intersect_key($data_for_db, array_flip($allowed_columns));

        if (empty($data_to_update)) {
            throw new Exception('لا توجد بيانات جديدة لتحديثها.');
        }

        $set_parts = [];
        foreach ($data_to_update as $key => $value) {
            $set_parts[] = "`$key` = :$key";
        }
        $set_clause = implode(', ', $set_parts);
        $sql = "UPDATE `$relatedTable` SET $set_clause WHERE `id` = :item_id";
        $stmt = $pdo->prepare($sql);
        $data_to_update['item_id'] = $itemId;
        $stmt->execute($data_to_update);

        $affected_id = $itemId;
        $message = 'تم التحديث بنجاح!';

    } else if (!$itemId) { // --- INSERT Logic ---
        // Add the foreign key for the main request
        $data_for_db[$foreignKeyColumn] = $requestId;
        
        // Filter data to only include whitelisted columns plus the foreign key
        $allowed_insert_columns = array_merge($allowed_columns, [$foreignKeyColumn]);
        $data_to_insert = array_intersect_key($data_for_db, array_flip($allowed_insert_columns));

        // Prevent inserting a record that only contains the foreign key
        if (count($data_to_insert) <= 1) {
             echo json_encode(['success' => false, 'message' => 'لا يمكن إضافة سجل فارغ. يرجى ملء بعض الحقول.']);
             exit;
        }

        $columns = implode(', ', array_map(fn($col) => "`$col`", array_keys($data_to_insert)));
        $placeholders = ':' . implode(', :', array_keys($data_to_insert));
        $sql = "INSERT INTO `$relatedTable` ($columns) VALUES ($placeholders)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data_to_insert);
        $affected_id = $pdo->lastInsertId();
        $message = 'تمت الإضافة بنجاح!';
    } else {
        // This case handles an update request with no actual data to update.
        $affected_id = $itemId;
        $message = 'لا توجد بيانات جديدة لتحديثها.';
    }

    // --- Fetch the complete, updated item from the DB and return it ---
    if (!$affected_id) {
        throw new Exception("Failed to get a valid ID for the affected record.");
    }

    $select_stmt = $pdo->prepare("SELECT * FROM `$relatedTable` WHERE id = ?");
    $select_stmt->execute([$affected_id]);
    $complete_item = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$complete_item) {
        throw new Exception("Could not retrieve the record after saving.");
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => $message, 'item' => $complete_item]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // log_error("Error in add_related_item.php: " . $e->getMessage() . " | SQL: " . ($sql ?? 'N/A'));
    echo json_encode(['success' => false, 'message' => 'An application error occurred: ' . $e->getMessage()]);
}
?>