<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/logger.php';

$countryName = $_GET['country'] ?? '';

if (empty($countryName) || !isset($pdo)) {
    echo json_encode([]);
    exit;
}

try {
    // استعلام يربط بين جدول الدول والمدن لجلب المدن مباشرة
    $stmt = $pdo->prepare(
        "SELECT c.name FROM cities c 
         JOIN countries co ON c.country_id = co.id 
         WHERE co.name = ? 
         ORDER BY c.name ASC"
    );
    $stmt->execute([$countryName]);
    $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($cities);

} catch (PDOException $e) {
    log_error("API Error fetching cities for country '$countryName': " . $e->getMessage(), __FILE__, __LINE__);
    // في حالة حدوث خطأ، أرجع مصفوفة فارغة
    echo json_encode([]);
}
?>