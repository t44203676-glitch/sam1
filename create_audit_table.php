<?php
$host = 'localhost';
$db   = 'u721293045_sam';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "
    CREATE TABLE IF NOT EXISTS `request_edits_log` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `request_id` int(11) NOT NULL,
      `user_id` int(11) NOT NULL,
      `source_table` varchar(50) NOT NULL,
      `field_name` varchar(100) NOT NULL,
      `old_value` text DEFAULT NULL,
      `new_value` text DEFAULT NULL,
      `created_at` timestamp DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `request_id` (`request_id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `fk_request_edits_user` FOREIGN KEY (`user_id`) REFERENCES `system_users` (`user_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($sql);
    echo "Table request_edits_log created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
