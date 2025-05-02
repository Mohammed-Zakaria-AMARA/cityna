<?php
/**
 * User Registration Script - FINAL VERSION
 * Location: CITYNA/index/sign-up.php
 */

// Absolute path to db_connection.php
require_once __DIR__ . '/db_connection.php';
session_start();

// Debugging
error_log("Sign-up request started");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data (keep your existing sanitization code)
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = 'citizen'; // Default role

    // Validate input (keep your existing validation)
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Database insertion
        $sql = "INSERT INTO users (full_name, email, phone_number, password, role) 
                VALUES (?, ?, ?, ?, ?)";
        $user_id = dbInsert($sql, [$fullname, $email, $phone, $hashed_password, $role]);
        
        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            
            // Permanent success redirect
            header("Location: congrats.html");
            exit;
        }
    }

    // Error handling (keep your existing error session storage)
    header("Location: sign-up.html");
    exit;
} else {
    header("Location: sign-up.html");
    exit;
}
?>