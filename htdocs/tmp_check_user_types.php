<?php
require_once 'includes/database.php';
$st = $pdo->query('SELECT DISTINCT user_type FROM system_users');
print_r($st->fetchAll(PDO::FETCH_COLUMN));
