<?php
require_once 'EmailService.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));

    // Validation
    if (empty($email)) {
        echo "Email is required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Send newsletter subscription using EmailService
    $emailService = new EmailService();
    $result = $emailService->sendNewsletterConfirmation($email);
    echo $result['message'];
    
} else {
    echo "Invalid request!";
}
?>