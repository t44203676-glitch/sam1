<?php
header('Content-Type: application/json');
require_once '../includes/database.php';
require_once '../includes/logger.php';

// Get the posted data.
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->service_type) || !isset($data->request_id) || !isset($data->partners) || !is_array($data->partners)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete or invalid data provided.']);
    exit;
}

$service_type = $data->service_type;
$request_id = filter_var($data->request_id, FILTER_VALIDATE_INT);
$partners = $data->partners;

if ($request_id === false) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request ID.']);
    exit;
}

// Map service type to table column
$service_id_column_map = [
    'marriage_permits' => 'marriage_permit_id',
    'family_visits' => 'family_visit_id',
    'recruitment_requests' => 'recruitment_request_id',
    'business_visits' => 'business_visit_id',
    'civil_affairs_requests' => 'civil_affairs_request_id',
    'labor_requests' => 'labor_request_id',
    'profession_changes' => 'profession_change_id',
    'runaway_cancellations' => 'runaway_cancellation_id',
    'tourism_visits' => 'tourism_visit_id',
    'followup_requests' => 'followup_request_id'
];

if (!array_key_exists($service_type, $service_id_column_map)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid service type.']);
    exit;
}

$service_id_column = $service_id_column_map[$service_type];

$pdo->beginTransaction();

try {
    // Prepare the statement for inserting into related_data
    $sql = "INSERT INTO related_data (
            {$service_id_column}, 
            full_name, 
            passport_number, 
            relationship, 
            birth_date, 
            age, 
            nationality, 
            country, 
            sponsor_type
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
    $stmt = $pdo->prepare($sql);

    foreach ($partners as $partner) {
        // Basic validation for required fields
        if (empty($partner->full_name) || empty($partner->passport_number)) {
            // Skip this partner or throw an error
            continue; 
        }

        // Sanitize and set optional values
        $birth_date_hijri = !empty($partner->birth_date) ? htmlspecialchars(strip_tags($partner->birth_date)) : null;
        $birth_date = null;
        if ($birth_date_hijri) {
            try {
                $gregorian_date = new DateTime($birth_date_hijri);
                $birth_date = $gregorian_date->format('Y-m-d');
            } catch (Exception $e) {
                // Handle invalid date format if necessary, for now, keep it null
            }
        }
        $age = !empty($partner->age) ? filter_var($partner->age, FILTER_VALIDATE_INT) : null;
        $relationship = !empty($partner->relationship) ? htmlspecialchars(strip_tags($partner->relationship)) : null;
        $nationality = !empty($partner->nationality) ? htmlspecialchars(strip_tags($partner->nationality)) : null;
        $country = !empty($partner->country) ? htmlspecialchars(strip_tags($partner->country)) : null;
        $sponsor_type = !empty($partner->sponsor_type) ? htmlspecialchars(strip_tags($partner->sponsor_type)) : null;
        $full_name = htmlspecialchars(strip_tags($partner->full_name));
        $passport_number = htmlspecialchars(strip_tags($partner->passport_number));

        if (!$stmt->execute([
            $request_id,
            $full_name,
            $passport_number,
            $relationship,
            $birth_date,
            $age,
            $nationality,
            $country,
            $sponsor_type
        ])) {
            throw new Exception("Execute failed.");
        }
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Partners added successfully.']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    log_error($e->getMessage(), 'add_partner_api');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to add partners.']);
}
?>
