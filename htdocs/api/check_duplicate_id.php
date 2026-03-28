<?php
// api/check_duplicate_id.php
header('Content-Type: application/json');

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/logger.php';

$response = ['exists' => false, 'error' => null];

if (isset($_GET['national_id']) && $pdo) {
    $nationalId = toWesternDigits(trim($_GET['national_id']));
    $formType = $_GET['formType'] ?? '';
    // Optional: exclude current ID if editing
    $excludeId = isset($_GET['exclude_id']) ? (int)$_GET['exclude_id'] : null;

    $serviceTableMap = [
        'marriage'             => 'marriage_permits',
        'family_visit'         => 'family_visits',
        'tourism'              => 'tourism_visits',
        'business_visit'       => 'business_visits',
        'labor'                => 'labor_requests',
        'runaway_cancellation' => 'runaway_cancellations',
        'profession_change'    => 'profession_changes',
        'civil_affairs'        => 'civil_affairs_requests',
        'recruitment'          => 'recruitment_requests',
        'followup'             => 'followup_requests',
    ];

    if (isset($serviceTableMap[$formType])) {
        $tableName = $serviceTableMap[$formType];
        try {
            $sql = "SELECT COUNT(*) FROM `$tableName` WHERE national_id = ?";
            $params = [$nationalId];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $response['exists'] = true;
                $response['message'] = 'رقم السجل موجود مسبقاً في النظام لهذه الخدمة';
            }
        } catch (PDOException $e) {
            log_error("Failed to check duplicate national_id '$nationalId' for form type '$formType': " . $e->getMessage(), __FILE__, __LINE__);
            $response['error'] = 'Database error';
        }
    } else {
        $response['error'] = 'Invalid form type';
    }
} else {
    $response['error'] = 'Missing parameters';
}

echo json_encode($response);
