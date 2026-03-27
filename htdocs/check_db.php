<?php
require_once __DIR__ . '/includes/database.php';
$stmt_cols = $pdo->query("DESCRIBE `related_data`");
$table_columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);
print_r($table_columns);
?>
