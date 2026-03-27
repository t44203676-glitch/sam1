<?php
session_start();
require_once 'includes/functions.php'; // Include functions file for generateRandomCaptcha

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generate a new 4-digit CAPTCHA
$new_captcha = generateRandomCaptcha(4);
$_SESSION['captcha'] = $new_captcha;

echo htmlspecialchars($new_captcha);
?>
