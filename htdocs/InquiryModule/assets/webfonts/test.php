<?php
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n" .
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
    ]
]);
$url = "https://arbfonts.com/wp-content/fonts/g/GE-SS-Two-Medium.otf";
$data = @file_get_contents($url, false, $context);
if ($data) {
    file_put_contents('test.otf', $data);
    echo "Success: " . strlen($data);
} else {
    echo "Failed";
}
