<?php
// api/get_nationalities.php

header('Content-Type: application/json');

require_once '../includes/database.php';

$response = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT name FROM nationalities ORDER BY name ASC");
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log error, but don't expose details
    }
}

echo json_encode($response);