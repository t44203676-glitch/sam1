<?php
$_SERVER['SERVER_NAME'] = 'localhost';
require_once '../includes/database.php';
if (!$pdo) die("Failed to connect to DB");
$stmt = $pdo->query("SHOW VARIABLES LIKE 'char%'");
$vars = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
print_r($vars);
$stmt = $pdo->query("SHOW VARIABLES LIKE 'coll%'");
$vars = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
print_r($vars);
?>
