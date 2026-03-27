<?php
header('Content-Type: application/json');
require_once '../includes/logger.php';

require_once '../includes/database.php';

$response = ['exists' => false];

if (isset($_GET['number']) && $pdo) {
    $exportNumber = $_GET['number'];
    $formType = $_GET['formType'] ?? 'marriage'; // Default to marriage if not provided

    $serviceTableMap = [
        'marriage'             => 'marriage_permits', // تم التوحيد
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
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$tableName` WHERE export_number = ?");
            $stmt->execute([$exportNumber]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $response['exists'] = true;
            }
        } catch (PDOException $e) {
            log_error("Failed to check export number '$exportNumber' for form type '$formType': " . $e->getMessage(), __FILE__, __LINE__);
            // Log error, but don't expose details to the client
            error_log('Check Export Number API Error: ' . $e->getMessage());
        }
    }
}

echo json_encode($response);