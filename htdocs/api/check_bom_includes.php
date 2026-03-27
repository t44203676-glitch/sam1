<?php
$files = [
    '../includes/database.php',
    '../includes/logger.php',
    '../includes/functions.php'
];
foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "$file: NOT FOUND\n";
        continue;
    }
    $content = file_get_contents($file);
    $bom = bin2hex(substr($content, 0, 3));
    echo "$file BOM: " . $bom . " (" . bin2hex(substr($content, 0, 10)) . ")\n";
}
?>
