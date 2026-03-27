<?php
$file = 'C:/xampp/htdocs/aldamgalsthlam/home/dataN.sql';
$content = file_get_contents($file);

// Find the INSERT INTO `related_data` section
$pattern = '/(INSERT INTO `related_data` .*? VALUES\r?\n)(.*?)(;\r?\n)/s';
if (preg_match($pattern, $content, $matches)) {
    $header = $matches[1];
    $rows = $matches[2];
    $footer = $matches[3];

    // Split rows, add , NULL to each
    $lines = explode("\n", $rows);
    foreach ($lines as &$line) {
        $line = rtrim($line);
        if (str_ends_with($line, ',')) {
            $line = substr($line, 0, -2) . ', NULL),';
        } elseif (str_ends_with($line, '))')) { // Handling potential double parens or end case
             // skip or handle
        } elseif (!empty($line)) {
             $line = substr($line, 0, -1) . ', NULL)';
        }
    }
    $newRows = implode("\n", $lines);
    $newContent = str_replace($matches[0], $header . $newRows . $footer, $content);
    file_put_contents($file, $newContent);
    echo "Successfully updated related_data rows in dataN.sql.\n";
} else {
    echo "Could not find related_data insert section.\n";
}
