<?php
header('Content-Type: application/json; charset=utf-8');
// api/get_countries_cities.php

try {
    
    // Include necessary files
    require_once __DIR__ . '/../includes/database.php';
    require_once __DIR__ . '/../includes/logger.php';
    
    // Check if the database connection is established
    if (!isset($pdo) || !defined('USE_DATABASE') || !USE_DATABASE) {
        throw new Exception("Database connection not available.");
    }

    // Fetch countries with their cities
    $stmt = $pdo->query("
        SELECT 
            c.name,
            COALESCE(GROUP_CONCAT(ci.name ORDER BY ci.name SEPARATOR ','), '') as cities
        FROM countries c
        LEFT JOIN cities ci ON c.id = ci.country_id
        GROUP BY c.id, c.name
        ORDER BY c.name ASC
    ");

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert the cities string to an array
    foreach ($results as &$result) {
        if (!empty($result['cities'])) {
            $result['cities'] = explode(',', $result['cities']);
        } else {
            $result['cities'] = [];
        }
    }

    // Output the data as a JSON encoded string
    echo json_encode($results, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

} catch (Exception $e) {
    // Log any errors and return a valid empty JSON array
    log_error("Failed to fetch countries data: " . $e->getMessage(), __FILE__, __LINE__);
    http_response_code(500); // Internal Server Error
    echo json_encode([]); // Return empty array on error
}