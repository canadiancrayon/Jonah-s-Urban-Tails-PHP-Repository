<?php
session_start();

$was_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['first_name'] ?? $_SESSION['user_name'] ?? '';

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Start a new session for the message
session_start();

if ($was_logged_in) {
    $_SESSION['info_message'] = 'You have been successfully logged out.';
} else {
    $_SESSION['info_message'] = 'You were not logged in.';
}

header('Location: ../my_business.php');
exit;
?>
