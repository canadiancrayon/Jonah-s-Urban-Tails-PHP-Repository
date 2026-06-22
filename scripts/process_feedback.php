<?php
// Start session for potential messages
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/feedback.php');
    exit;
}

// Get form data
$salutation = $_POST['salutation'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$subject = $_POST['subject'] ?? '';
$comments = $_POST['comments'] ?? '';
$followup = isset($_POST['followup']) ? 'Yes' : 'No';

// Save input for retention
$_SESSION['feedback_data'] = $_POST;

$errors = [];

// Validate required fields
if (empty($salutation)) $errors[] = 'Salutation is required';
if (empty($firstName)) $errors[] = 'First name is required';
if (empty($lastName)) $errors[] = 'Last name is required';
if (empty($email)) $errors[] = 'Email is required';
if (empty($subject)) $errors[] = 'Subject is required';
if (empty(trim($comments))) $errors[] = 'Comments are required';

// Email validation
if (!empty($email) && !preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i', $email)) {
    $errors[] = 'Invalid email format';
}

// Phone validation (if provided)
if (!empty($phone) && !preg_match('/^[0-9\-\(\)\s\+]{10,20}$/', $phone)) {
    $errors[] = 'Invalid phone format';
}

// If errors, redirect back
if (!empty($errors)) {
    $_SESSION['feedback_errors'] = $errors;
    header('Location: ../pages/feedback.php');
    exit;
}

// 1. Save feedback to file (in the data directory)
$feedback_file = __DIR__ . '/../data/feedback.txt';

// Create directory if it doesn't exist
$feedback_dir = dirname($feedback_file);
if (!file_exists($feedback_dir)) {
    mkdir($feedback_dir, 0755, true);
}

$feedback_content = "=== Feedback Submission ===\n";
$feedback_content .= "Date: " . date('Y-m-d H:i:s') . "\n";
$feedback_content .= "Salutation: $salutation\n";
$feedback_content .= "Name: $firstName $lastName\n";
$feedback_content .= "Email: $email\n";
$feedback_content .= "Phone: $phone\n";
$feedback_content .= "Subject: $subject\n";
$feedback_content .= "Comments:\n$comments\n";
$feedback_content .= "Follow-up requested: $followup\n";
$feedback_content .= "=========================\n\n";

// Append to the feedback file
file_put_contents($feedback_file, $feedback_content, FILE_APPEND);

// 2. Send confirmation email to user
$to_user = $email;
$user_subject = "Thank you for your feedback - Jonah's Urban Tails";
$user_message = "Dear $salutation $lastName,\n\n";
$user_message .= "Thank you for contacting Jonah's Urban Tails. We have received your feedback and will review it shortly.\n\n";
$user_message .= "Here's a copy of your submission:\n";
$user_message .= "----------------------------------------\n";
$user_message .= "Name: $salutation $firstName $lastName\n";
$user_message .= "Email: $email\n";
$user_message .= "Phone: $phone\n";
$user_message .= "Subject: $subject\n";
$user_message .= "Comments:\n$comments\n";
$user_message .= "Follow-up requested: $followup\n";
$user_message .= "----------------------------------------\n\n";
$user_message .= "We appreciate your interest in our services!\n\n";
$user_message .= "Best regards,\n";
$user_message .= "Jonah's Urban Tails Team";

$user_headers = "From: u07@mail.cs-smu.ca\r\n";
$user_headers .= "Reply-To: u07@mail.cs-smu.ca\r\n";
$user_headers .= "X-Mailer: PHP/" . phpversion();

mail($to_user, $user_subject, $user_message, $user_headers);

// 3. Send email to business (marker)
$to_business = "u50@mail.cs-smu.ca";
$business_subject = "New Feedback Submission - $subject";
$business_message = "A new feedback form has been submitted:\n\n";
$business_message .= "----------------------------------------\n";
$business_message .= "Date: " . date('Y-m-d H:i:s') . "\n";
$business_message .= "Name: $salutation $firstName $lastName\n";
$business_message .= "Email: $email\n";
$business_message .= "Phone: $phone\n";
$business_message .= "Subject: $subject\n";
$business_message .= "Comments:\n$comments\n";
$business_message .= "Follow-up requested: $followup\n";
$business_message .= "----------------------------------------\n";

$business_headers = "From: $email\r\n";
$business_headers .= "Reply-To: $email\r\n";

mail($to_business, $business_subject, $business_message, $business_headers);

// 4. Redirect to confirmation page
$_SESSION['feedback_name'] = "$salutation $lastName";
$_SESSION['feedback_email'] = $email;
header('Location: ../pages/feedback_confirmation.php');
exit;
?>
