<?php
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n" .
                    "Referer: https://db.onlinewebfonts.com/\r\n" .
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
    ]
]);
$url = "https://db.onlinewebfonts.com/t/20e28f77ab5df91143891461dcfbafeb.woff2";
$data = @file_get_contents($url, false, $context);
if ($data) {
    file_put_contents('ge-ss-two-light.woff2', $data);
    echo "Success: " . strlen($data);
} else {
    echo "Failed";
}
