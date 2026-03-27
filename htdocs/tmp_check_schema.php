<?php
require_once 'includes/database.php';
$tables = [
    'marriage_permits', 'family_visits', 'tourism_visits', 'business_visits', 
    'labor_requests', 'followup_requests', 'profession_changes', 
    'civil_affairs_requests', 'recruitment_requests'
];
foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    $stmt = $pdo->query("DESCRIBE `$table` ");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
