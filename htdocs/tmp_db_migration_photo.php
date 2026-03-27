<?php
require_once 'includes/database.php';

try {
    $pdo->beginTransaction();

    // 1. Handle civil_affairs_requests (has both)
    echo "Syncing civil_affairs_requests...\n";
    $pdo->exec("UPDATE civil_affairs_requests 
                SET profile_photo_path = personal_photo 
                WHERE (profile_photo_path IS NULL OR profile_photo_path = '' OR profile_photo_path = '---') 
                AND personal_photo IS NOT NULL AND personal_photo != '' AND personal_photo != '---'");
    
    $pdo->exec("ALTER TABLE civil_affairs_requests DROP COLUMN personal_photo");
    echo "Dropped personal_photo from civil_affairs_requests.\n";

    // 2. Handle related_data (has only personal_photo)
    echo "Renaming personal_photo in related_data...\n";
    $pdo->exec("ALTER TABLE related_data CHANGE COLUMN personal_photo profile_photo_path VARCHAR(255)");
    echo "Renamed personal_photo to profile_photo_path in related_data.\n";

    $pdo->commit();
    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
