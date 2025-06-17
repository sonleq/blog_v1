<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching so back button doesn’t show protected pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Your existing login check here
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/blog/login.html");  // adjust path accordingly
    exit;
}
?>
