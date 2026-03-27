<?php
require_once 'includes/database.php';

if (USE_DATABASE && $pdo) {
    try {
        // 1. Create countries table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `countries` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL UNIQUE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "<p>Table 'countries' checked/created successfully.</p>";

        // 2. Create cities table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `cities` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `country_id` INT NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                FOREIGN KEY (`country_id`) REFERENCES `countries`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo "<p>Table 'cities' checked/created successfully.</p>";

        // 3. Data to insert
        $countriesData = [
            ["name" => "المملكة العربية السعودية", "cities" => ["الرياض", "جدة", "مكة", "الدمام"]],
            ["name" => "مصر", "cities" => ["القاهرة", "الإسكندرية", "الجيزة"]],
            ["name" => "اليمن", "cities" => ["صنعاء", "عدن", "تعز"]],
            ["name" => "سوريا", "cities" => ["دمشق", "حلب", "حمص"]],
            ["name" => "الأردن", "cities" => ["عمان", "الزرقاء", "إربد"]],
            ["name" => "الهند", "cities" => ["دلهي", "مومباي", "بنغالور"]],
            ["name" => "باكستان", "cities" => ["كراتشي", "لاهور", "إسلام أباد"]],
            ["name" => "الفلبين", "cities" => ["مانيلا", "سيبو", "دافاو"]],
            ["name" => "الإمارات العربية المتحدة", "cities" => ["دبي", "أبوظبي", "الشارقة"]]
        ];

        // 4. Insert data
        $countryStmt = $pdo->prepare("INSERT IGNORE INTO countries (name) VALUES (?)");
        $cityStmt = $pdo->prepare("INSERT IGNORE INTO cities (country_id, name) VALUES (?, ?)");

        foreach ($countriesData as $country) {
            $countryStmt->execute([$country['name']]);
            $countryId = $pdo->lastInsertId();
            // If country already existed, get its ID
            if ($countryId == 0) {
                $findIdStmt = $pdo->prepare("SELECT id FROM countries WHERE name = ?");
                $findIdStmt->execute([$country['name']]);
                $countryId = $findIdStmt->fetchColumn();
            }

            foreach ($country['cities'] as $city) {
                $cityStmt->execute([$countryId, $city]);
            }
        }
        echo "<p>Countries and cities data inserted/updated successfully.</p>";

    } catch (PDOException $e) {
        echo "<p style='color:red;'>Database Error: " . $e->getMessage() . "</p>";
    }
}
?>