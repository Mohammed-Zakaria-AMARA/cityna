<?php
session_start();

// Clear old error
unset($_SESSION['login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Both fields are required";
        header("Location: log-in.php");
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=cityna_db', 'root', '');
        $stmt = $pdo->prepare("SELECT user_id, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: citizen-form.php"); // Adjust destination by role later
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid email or password";
            header("Location: log-in.php");
            exit;
        }
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        $_SESSION['login_error'] = "Database error";
        header("Location: log-in.php");
        exit;
    }
} else {
    header("Location: log-in.php");
    exit;
}
