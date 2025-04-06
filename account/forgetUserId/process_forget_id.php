<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once "../../assets/Mxy4XNn4Hs/databaseConnection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php'; // Include PHPMailer

// CSRF token validation
$submittedToken = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || $submittedToken !== $_SESSION['csrf_token']) {
    echo json_encode(['error' => 'Invalid CSRF token.']);
    exit;
}

// Input Validation
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_SPECIAL_CHARS);
$dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$email || !$contact || !$dob) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// Validate Email Format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format.']);
    exit;
}

// Validate Date of Birth Format (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
    echo json_encode(['error' => 'Invalid date of birth format. Use YYYY-MM-DD.']);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT userid FROM edutrackusertable WHERE email = ? AND contact = ? AND dob = ?");
$stmt->bind_param("sss", $email, $contact, $dob);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $userid = htmlspecialchars($row['userid']);

    // Generate a secure new password
    function generateRandomPassword($length = 12) {
        return bin2hex(random_bytes($length / 2)); // Generates a secure password
    }

    $newPassword = generateRandomPassword();
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the password in the database
    $updateStmt = $conn->prepare("UPDATE edutrackusertable SET password = ? WHERE userid = ?");
    $updateStmt->bind_param("ss", $hashedPassword, $userid);

    if ($updateStmt->execute()) {
        // Send email with User ID and new password
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vinaynishad507@gmail.com'; // Replace with your Gmail
            $mail->Password   = 'xsog oqhz hsno nrod'; // Use App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('vinaynishad507@gmail.com', 'EduTrack Support');
            $mail->addAddress($email, $userid);

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = 'Your EduTrack User ID and New Password';
            $mail->Body    = "<h3>Account Recovery</h3>
                              <p>Hello,</p>
                              <p>Your User ID: <strong>$userid</strong></p>
                              <p>Your new password: <strong>$newPassword</strong></p>
                              <p>For security, please change your password after logging in.</p>
                              <p>Best Regards, <br> EduTrack Support Team</p>";

            // Send email
            if ($mail->send()) {
                echo json_encode(['success' => 'Your User ID and new password have been sent to your email.']);
            } else {
                echo json_encode(['error' => 'Error sending email. Please try again.']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(['error' => 'Error updating password. Please try again.']);
    }

    $updateStmt->close();
} else {
    echo json_encode(['error' => 'No account found with these details.']);
}

$stmt->close();
$conn->close();
?>
