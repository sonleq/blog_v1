<?php
session_start(); // Start the session for storing user data

require "admin/includes/dbh.php"; // Include your database connection file

// Sanitize and validate input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Redirect back to login with error if fields are empty
if (empty($username) || empty($password)) {
    header("Location: login.html?error=1");
    exit;
}

// Prepare and execute a secure SQL query to prevent SQL injection
$sql = "SELECT * FROM users WHERE v_username = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    // Handle SQL preparation error
    error_log("Database error: " . mysqli_error($conn));
    header("Location: login.html?error=1");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch user from database
$user = mysqli_fetch_assoc($result);

// Verify user and password
if ($user && password_verify($password, $user['v_password'])) {
    // Store user information in session
    $_SESSION['username'] = $user['v_username'];
    $_SESSION['role'] = $user['v_role'];

    // Redirect based on role
    switch ($user['v_role']) {
        case 'admin':
            header("Location: http://localhost/blog/admin/index.php");
            exit;

        // Example future roles
        // case 'editor':
        //     header("Location: http://localhost/blog/editor/index.php");
        //     exit;

        default:
            echo "Dashboard for your role is not set up yet.";
            exit;
    }
} else {
    // Invalid credentials or user not found
    header("Location: login.html?error=1");
    exit;
}

?>
