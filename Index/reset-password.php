<?php
require 'db_connection.php';
session_start();

// 1. Check if token exists in URL
if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid reset link";
    header("Location: forgot-password.html");
    exit;
}

// 2. Verify token (from your DB)
$token = $_GET['token'];
$reset = dbQuery(
    "SELECT user_id FROM password_resets 
     WHERE token = ? AND expires_at > NOW() AND used = FALSE",
    [$token]
);

if (!$reset) {
    $_SESSION['error'] = "Invalid/expired link";
    header("Location: forgot-password.html");
    exit;
}

// 3. Handle password reset form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Update password
    dbQuery(
        "UPDATE users SET password = ? WHERE user_id = ?",
        [$new_password, $reset[0]['user_id']]
    );
    
    // Mark token as used
    dbQuery("UPDATE password_resets SET used = TRUE WHERE token = ?", [$token]);
    
    $_SESSION['message'] = "Password updated. Login now.";
    header("Location: log-in.php");
    exit;
}

// 4. Show reset form (create reset-password-form.html)
include 'reset-password-form.html';
?>