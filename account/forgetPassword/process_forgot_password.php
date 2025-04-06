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
$userid = trim($_POST['userid'] ?? '');
$email = trim($_POST['email'] ?? '');
$dob = trim($_POST['dob'] ?? '');

if (empty($userid) || empty($email) || empty($dob)) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format.']);
    exit;
}

// Validate Date of Birth (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
    echo json_encode(['error' => 'Invalid date of birth format. Use YYYY-MM-DD.']);
    exit;
}

// Check if the User ID exists
$checkUserStmt = $conn->prepare("SELECT userid FROM edutrackusertable WHERE userid = ?");
$checkUserStmt->bind_param("s", $userid);
$checkUserStmt->execute();
$userResult = $checkUserStmt->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['error' => 'User ID does not exist.']);
    exit;
}

// Check if the user exists with the provided email and DOB
$stmt = $conn->prepare("SELECT email FROM edutrackusertable WHERE userid = ? AND email = ? AND dob = ?");
$stmt->bind_param("sss", $userid, $email, $dob);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Generate a secure random password
    function generateRandomPassword($length = 12) {
        return bin2hex(random_bytes($length / 2)); // Generates a secure password
    }

    $newPassword = generateRandomPassword();
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the password in the database
    $updateStmt = $conn->prepare("UPDATE edutrackusertable SET password = ? WHERE userid = ?");
    $updateStmt->bind_param("ss", $hashedPassword, $userid);

    if ($updateStmt->execute()) {
        // Send email with new password
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
            $mail->Subject = 'Your Password Has Been Reset';
            $mail->Body    = "<h3>Password Reset Successful</h3>
                              <p>Hello <strong>$userid</strong>,</p>
                              <p>Your new password is: <strong>$newPassword</strong></p>
                              <p>For security, please change your password after logging in.</p>
                              <p>Best Regards, <br> EduTrack Support Team</p>";

            // Send email
            if ($mail->send()) {
                echo json_encode(['success' => 'A new password has been sent to your email.']);
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
$checkUserStmt->close();
$conn->close();
?>
