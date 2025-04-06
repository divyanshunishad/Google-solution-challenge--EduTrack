<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

$mail = new PHPMailer(true);

try {
    // Enable debugging (for testing, remove in production)
    $mail->SMTPDebug = 2; // Options: 0 = off, 1 = client messages, 2 = client & server messages

    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'vinaynishad507@gmail.com'; // Replace with your Gmail
    $mail->Password   = 'xsog oqhz hsno nrod';   // Use App Password (never use actual Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('vinaynishad507@gmail.com', 'Your Name');
    $mail->addAddress('email.solutionsphere@gmail.com', 'Recipient Name'); // Recipient email & name

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHP';
    $mail->Body    = '<h1>Welcome to My Website</h1><p>This is an HTML email sent using PHPMailer.</p>';
    $mail->AltBody = 'This is a plain-text email for non-HTML email clients.';

    // Send email
    if ($mail->send()) {
        echo 'Email has been sent successfully!';
    } else {
        echo 'Failed to send email.';
    }

} catch (Exception $e) {
    // Detailed error reporting
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
    error_log("PHPMailer Error: " . $mail->ErrorInfo, 3, "error_log.txt"); // Logs error to a file
}
?>
