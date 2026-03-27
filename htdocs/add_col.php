<?php
require_once __DIR__ . '/includes/database.php';
try {
    $sql = "ALTER TABLE `related_data` ADD COLUMN `permit_type` VARCHAR(255) NULL AFTER `status`";
    $pdo->exec($sql);
    echo "Column added successfully";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
