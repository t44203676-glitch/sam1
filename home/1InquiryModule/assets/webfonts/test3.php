<?php
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
]);
$url = "https://fonts.cdnfonts.com/css/ge-ss-two";
$data = @file_get_contents($url, false, $context);
if ($data) {
    file_put_contents('cdnfonts.css', $data);
    echo "Success!";
} else {
    echo "Failed";
}
