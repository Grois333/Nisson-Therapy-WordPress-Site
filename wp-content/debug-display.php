<?php
/**
 * Temporary debug file to display PHP errors
 * DELETE THIS FILE AFTER DEBUGGING - IT'S A SECURITY RISK
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Try to load WordPress
if (file_exists(__DIR__ . '/../wp-load.php')) {
    require_once(__DIR__ . '/../wp-load.php');
    echo '<h1>WordPress Loaded Successfully</h1>';
    echo '<p>WordPress core files are present.</p>';
} else {
    echo '<h1 style="color: red;">ERROR: wp-load.php not found!</h1>';
    echo '<p>The WordPress core files are missing. You need to restore them.</p>';
    echo '<p>Path checked: ' . __DIR__ . '/../wp-load.php</p>';
}

// Show PHP info
echo '<hr>';
echo '<h2>PHP Information</h2>';
echo '<p>PHP Version: ' . phpversion() . '</p>';
echo '<p>Error Reporting: ' . error_reporting() . '</p>';
echo '<p>Display Errors: ' . ini_get('display_errors') . '</p>';

// Check for error log
$error_log = __DIR__ . '/error_log';
if (file_exists($error_log)) {
    echo '<hr>';
    echo '<h2>Error Log (last 50 lines)</h2>';
    echo '<pre>';
    echo htmlspecialchars(implode("\n", array_slice(file($error_log), -50)));
    echo '</pre>';
}

// Check for debug log
$debug_log = __DIR__ . '/debug.log';
if (file_exists($debug_log)) {
    echo '<hr>';
    echo '<h2>Debug Log (last 50 lines)</h2>';
    echo '<pre>';
    echo htmlspecialchars(implode("\n", array_slice(file($debug_log), -50)));
    echo '</pre>';
}

