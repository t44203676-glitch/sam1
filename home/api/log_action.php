<?php
// home/api/log_action.php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        // Try $_POST if json_decode failed
        $data = $_POST;
    }

    $national_id = $data['national_id'] ?? '---';
    $export_number = $data['export_number'] ?? '---';
    $service_type = $data['service_type'] ?? '---';
    $action = $data['action'] ?? 'query';

    if (function_exists('log_query_action')) {
        log_query_action($national_id, $export_number, $service_type, $action);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Logging function not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
