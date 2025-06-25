<?php
session_start();
require "admin/includes/dbh.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users WHERE v_username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['v_password'])) {
    $_SESSION['username'] = $user['v_username'];
    $_SESSION['role'] = $user['v_role'];

    if ($user['v_role'] === 'admin') {
        header("Location: http://localhost/blog/admin/index.php");
        exit; // important
    } else {
        echo "Dashboard for your role is not set up yet.";
    }
} else {
    header("Location: login.html?error=1");
    exit;
}

?>
