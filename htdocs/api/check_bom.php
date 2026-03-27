<?php
$file = 'search_requests.php';
$lines = file($file);
$line34 = $lines[33]; // Line 34 is index 33
echo "Line 34: " . trim($line34) . "\n";
echo "Line 34 (hex): " . bin2hex($line34) . "\n";

// Extract the part between 'label' => ' and '],
if (preg_match("/'label' => '(.+?)'/", $line34, $matches)) {
    $label = $matches[1];
    echo "Label: " . $label . "\n";
    echo "Label (hex): " . bin2hex($label) . "\n";
}
?>
