<?php
require_once 'includes/database.php';

$tables = [
    'marriage_permits', 'family_visits', 'tourism_visits', 
    'business_visits', 'recruitment_requests', 'labor_requests',
    'civil_affairs_requests', 'profession_changes', 'runaway_cancellations',
    'followup_requests'
];

foreach ($tables as $table) {
    echo "Checking table: $table\n";
    try {
        $stmt = $pdo->query("DESCRIBE `$table` ");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (in_array('rejection_reason', $columns)) {
            echo "  [OK] rejection_reason exists.\n";
        } else {
            echo "  [MISSING] rejection_reason is missing!\n";
        }
    } catch (PDOException $e) {
        echo "  [ERROR] " . $e->getMessage() . "\n";
    }
}
?>
