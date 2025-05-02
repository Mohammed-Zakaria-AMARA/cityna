<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // Debug: Check if email is received
    error_log("Submitted email: " . $email);


    $user = dbQuery("SELECT user_id FROM users WHERE email = ?", [$email]);
    
    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600);
        
        // Insert token into DB
        dbQuery(
            "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)",
            [$user[0]['user_id'], $token, $expires]
        );
        
        // LOCAL TESTING: Show link directly
        echo "DEV MODE: <a href='reset-password.php?token=$token'>Reset Password</a>";
        exit;
    } else {
        $_SESSION['error'] = "Email not found";
    }


    // $user = dbQuery("SELECT user_id FROM users WHERE email = ?", [$email]);
    
    // if ($user) {
    //     $token = bin2hex(random_bytes(32));
    //     $expires = date('Y-m-d H:i:s', time() + 3600);
        
    //     // Insert token into DB
    //     dbQuery(
    //         "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)",
    //         [$user[0]['user_id'], $token, $expires]
    //     );
        
    //     // LOCAL TESTING: Show link directly
    //     echo "DEV MODE: <a href='reset-password.php?token=$token'>Reset Password</a>";
    //     exit;
    // } else {
    //     $_SESSION['error'] = "Email not found";
    // }
    
    header("Location: forgot-password.html");
    exit;
}

// If not POST request, redirect back
header("Location: forgot-password.html");
exit;

?>