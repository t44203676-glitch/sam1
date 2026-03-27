<?php
/**
 * Inquiry Module - Standalone Entry Point
 * This file allows testing and viewing any inquiry result page within the module.
 */

require_once __DIR__ . '/core/bootstrap.php';

// Simple Router
$page = $_GET['result'] ?? 'marriage';
$available_pages = [
    'labor' => 'labor_inquiry_result.php',
    'business' => 'business_visit_inquiry_result.php',
    'tourism' => 'tourist_visit_inquiry_result.php',
    'family' => 'family_visa_result.php',
    'profession' => 'change_profession_inquiry_result.php',
    'civil' => 'civil_affairs_inquiry_result.php',
    'marriage' => 'marriage_inquiry_result.php',
    'recruitment' => 'recruitment_inquiry_result.php',
    'followup' => 'followup_inquiry_result.php'
];

if (!isset($available_pages[$page])) {
    die("Result page not found within the inquiry module.");
}

// Get data from session
$request = $_SESSION['inquiry_result']['data'] ?? null;

// Include the selected page
require_once INQUIRY_PAGES_PATH . '/' . $available_pages[$page];
