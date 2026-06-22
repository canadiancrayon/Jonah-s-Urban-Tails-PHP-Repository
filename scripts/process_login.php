<?php
session_start();
require_once 'mysql/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}

$login_name = $_POST['login_name'] ?? '';
$password = $_POST['password'] ?? '';

$_SESSION['old_login'] = ['login_name' => $login_name];

if (empty($login_name) || empty($password)) {
    $_SESSION['login_errors'] = ['Both login name and password are required'];
    header('Location: ../pages/login.php');
    exit;
}

$pdo = getDBConnection();
if (!$pdo) {
    $_SESSION['login_errors'] = ['Database connection failed'];
    header('Location: ../pages/login.php');
    exit;
}

try {
    // Find user by login name
    $stmt = $pdo->prepare("SELECT * FROM my_Customers WHERE login_name = ?");
    $stmt->execute([$login_name]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['login_errors'] = ['Invalid login name or password'];
        header('Location: ../pages/login.php');
        exit;
    }
    
    // Verify password
    if (!password_verify($password, $user['login_password'])) {
        $_SESSION['login_errors'] = ['Invalid login name or password'];
        header('Location: ../pages/login.php');
        exit;
    }
    
    // Login successful
    $_SESSION['user_id'] = $user['customer_id'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['login_name'] = $user['login_name'];
    
    unset($_SESSION['old_login']);
    
    $_SESSION['welcome_message'] = "Welcome back, " . $user['first_name'] . "!";
    
    // Check if there's a redirect URL (from shopping cart or checkout)
    if (isset($_SESSION['redirect_after_login'])) {
        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        header("Location: ../pages/$redirect");
        exit;
    }
    
    header('Location: ../my_business.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['login_errors'] = ['An error occurred during login'];
    header('Location: ../pages/login.php');
    exit;
}
?>
