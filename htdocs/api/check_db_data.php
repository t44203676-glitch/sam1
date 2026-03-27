<?php
$_SERVER['SERVER_NAME'] = 'localhost';
require_once '../includes/database.php';
$stmt = $pdo->query("SHOW FULL COLUMNS FROM civil_affairs_requests");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($cols);

$stmt = $pdo->query("SELECT applicant_name, HEX(applicant_name) as hex_name FROM civil_affairs_requests LIMIT 5");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
?>
