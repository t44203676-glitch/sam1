<?php
require_once 'includes/database.php';
$tables = [
    'marriage_permits', 'family_visits', 'tourism_visits', 'business_visits', 
    'labor_requests', 'followup_requests', 'profession_changes', 
    'civil_affairs_requests', 'recruitment_requests'
];
foreach ($tables as $table) {
    echo "Processing $table... ";
    try {
        // Add is_locked column if it doesn't exist
        $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE 'is_locked'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `$table` ADD COLUMN `is_locked` TINYINT(1) DEFAULT 0 AFTER `status` ");
            echo "Added is_locked. ";
        } else {
            echo "is_locked already exists. ";
        }
        echo "Done.\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
echo "Full Schema Update Complete.\n";
