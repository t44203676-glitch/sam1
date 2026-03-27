<?php
// generate_captcha_value.php
// ملف بسيط لتوليد رمز كابتشا جديد وإرجاعه وتحديث الجلسة
session_start();

if (!function_exists('generateRandomCaptcha')) {
    function generateRandomCaptcha($length = 4) {
        return rand(1000, 9999);
    }
}

$new_captcha = generateRandomCaptcha(4);
$_SESSION['captcha'] = $new_captcha;
echo $new_captcha;
?>
