<?php
require_once 'includes/database.php';

try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $results = [];

    foreach ($tables as $table) {
        $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_COLUMN);
        $has_personal = in_array('personal_photo', $columns);
        $has_profile = in_array('profile_photo_path', $columns);

        if ($has_personal || $has_profile) {
            $results[] = [
                'table' => $table,
                'has_personal' => $has_personal,
                'has_profile' => $has_profile
            ];
        }
    }

    echo json_encode($results, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
