<?php
// /tmp/db_merge.php

$hostingSqlFile = 'c:/xampp/htdocs/if0_41328620_ash.sql';
$localSqlFile = 'c:/xampp/htdocs/local_data_only.sql';
$outputSqlFile = 'c:/xampp/htdocs/if0_41328620_ash_updated.sql';

if (!file_exists($hostingSqlFile) || !file_exists($localSqlFile)) {
    die("Files not found.\n");
}

$hostingLines = file($hostingSqlFile);
$localContent = file_get_contents($localSqlFile);

$systemUsersBlock = [];
$collecting = false;

foreach ($hostingLines as $line) {
    if (strpos($line, 'CREATE TABLE `system_users`') !== false || strpos($line, '-- بنية الجدول `system_users`') !== false) {
        $collecting = true;
    }
    
    if ($collecting) {
        $systemUsersBlock[] = $line;
    }
    
    // Stop collecting when we see the next table structure or end of dumped tables section
    if ($collecting && (strpos($line, '-- Indexes for table `system_users`') !== false)) {
        $collecting = false;
    }
}

// Prepare the final SQL
$finalSql = "-- Unified SQL Dump (Auto-merged)\n";
$finalSql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\nSTART TRANSACTION;\nSET time_zone = \"+00:00\";\n\n";

// Add Local Data (which has everything EXCEPT system_users)
$finalSql .= $localContent;

// Add System Users block (Structure + Inserts)
$finalSql .= "\n-- =========================================\n";
$finalSql .= "-- PRESERVED SYSTEM USERS FROM HOSTING\n";
$finalSql .= "-- =========================================\n";
$finalSql .= implode("", $systemUsersBlock);

// Add Constraints and Auto-increments for system_users (extracted from tail of original file)
$finalSql .= "\nALTER TABLE `system_users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;\n";
$finalSql .= "ALTER TABLE `system_users` ADD CONSTRAINT `system_users_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `system_users` (`user_id`) ON DELETE SET NULL;\n";

$finalSql .= "COMMIT;\n";

// Final Clean up: Ensure profile_photo_path contains ONLY the filename (basename)
// This makes the database "clean" for our new helper function
$finalSql = preg_replace_callback("/'uploads\/(?:personal_photos|profile_photos|uploads)?\/?([^']+)'/", function($matches) {
    return "'" . $matches[1] . "'";
}, $finalSql);

file_put_contents($outputSqlFile, $finalSql);
echo "Database merged successfully into $outputSqlFile\n";
