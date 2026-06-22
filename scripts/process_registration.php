<?php
session_start();
require_once 'mysql/db_connect.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register.php');
    exit;
}

// Get form data
$salutation = $_POST['salutation'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$middle_initial = $_POST['middle_initial'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$gender = $_POST['gender'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$street_address = $_POST['street_address'] ?? '';
$city = $_POST['city'] ?? '';
$region = $_POST['region'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';
$login_name = $_POST['login_name'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Save input for retention
$_SESSION['old_input'] = $_POST;

$errors = [];

// Validate required fields
if (empty($salutation)) $errors[] = 'Salutation is required';
if (empty($first_name)) $errors[] = 'First name is required';
if (empty($last_name)) $errors[] = 'Last name is required';
if (empty($gender)) $errors[] = 'Gender is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($street_address)) $errors[] = 'Street address is required';
if (empty($city)) $errors[] = 'City is required';
if (empty($region)) $errors[] = 'Province is required';
if (empty($postal_code)) $errors[] = 'Postal code is required';
if (empty($login_name)) $errors[] = 'Login name is required';
if (empty($password)) $errors[] = 'Password is required';

// Password match
if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
}

// If errors, redirect back
if (!empty($errors)) {
    $_SESSION['registration_errors'] = $errors;
    header('Location: ../pages/register.php');
    exit;
}

// Connect to database
$pdo = getDBConnection();
if (!$pdo) {
    $_SESSION['registration_errors'] = ['Database connection failed'];
    header('Location: ../pages/register.php');
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT customer_id FROM my_Customers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['registration_errors'] = ['This email address is already registered. Please use a different email or login.'];
        header('Location: ../pages/register.php');
        exit;
    }
    
    // Check if login name exists and generate unique if needed
    $original_login = $login_name;
    $counter = 1;
    $unique_login = $login_name;
    
    while (true) {
        $stmt = $pdo->prepare("SELECT customer_id FROM my_Customers WHERE login_name = ?");
        $stmt->execute([$unique_login]);
        if (!$stmt->fetch()) {
            break; // Login name is unique
        }
        // Generate new login name with number suffix
        $unique_login = $original_login . $counter;
        $counter++;
    }
    
    // If login name was changed, notify user
    if ($unique_login !== $original_login) {
        $_SESSION['info_message'] = "Your requested login name '$original_login' was taken. We've assigned you '$unique_login' instead.";
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new customer
    $stmt = $pdo->prepare("
        INSERT INTO my_Customers (
            salutation, first_name, middle_initial, last_name, gender,
            email, phone, street_address, city, region, postal_code,
            registration_date, login_name, login_password
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            NOW(), ?, ?
        )
    ");
    
    $stmt->execute([
        $salutation, $first_name, $middle_initial, $last_name, $gender,
        $email, $phone, $street_address, $city, $region, $postal_code,
        $unique_login, $hashed_password
    ]);
    
    // Get the new customer ID
    $customer_id = $pdo->lastInsertId();
    
    // Store in session that user is logged in
    $_SESSION['user_id'] = $customer_id;
    $_SESSION['user_name'] = $first_name . ' ' . $last_name;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['login_name'] = $unique_login;
    
    // Clear old input
    unset($_SESSION['old_input']);
    
    // Redirect to home page with welcome message
    $_SESSION['welcome_message'] = "Registration successful! Welcome, $first_name!";
    header('Location: ../my_business.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Registration error: " . $e->getMessage());
    $_SESSION['registration_errors'] = ['An error occurred during registration. Please try again.'];
    header('Location: ../pages/register.php');
    exit;
}
?>
