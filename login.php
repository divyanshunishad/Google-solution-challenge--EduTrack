<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Validate and sanitize user input
$userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

// CSRF token validation
$submittedToken = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || $submittedToken !== $_SESSION['csrf_token']) {
    echo json_encode(array('error' => 'Invalid CSRF token'));
    exit;
}

// Check if the form is submitted and inputs are valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userid && $password) {
    require_once "assets/Mxy4XNn4Hs/databaseConnection.php";

    // Server-side validation and sanitization for userid
    $userid = mysqli_real_escape_string($conn, $userid);
    
    // Prepare and execute the SQL query to retrieve user data including designation_code
    $stmt = $conn->prepare("SELECT userid, password, designation_code FROM edutrackusertable WHERE userid = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $row['userid'];

            // Fetch the designation_code 
            $designation_code = $row['designation_code'];

            // Prepare the redirect URL based on designation_code
            if ($designation_code === '00') {
                $redirectURL = "studentEduTrack/index.php?userid=$userid&csrf_token=$submittedToken";
            } elseif ($designation_code === 'ED') {
                $redirectURL = "facultyEduTrack/index.php?userid=$userid&csrf_token=$submittedToken";
            } elseif ($designation_code === 'ET') {
                $redirectURL = "adminEduTrack/index.php?userid=$userid&csrf_token=$submittedToken";
            } else {
                $redirectURL = 'index.php'; // Default redirection if designation is unrecognized
            }

            // Send JSON response containing the redirect URL
            echo json_encode(array('redirect_url' => $redirectURL));
            exit;
        } else {
            echo json_encode(array('error' => 'Invalid password'));
            exit;
        }
    } else {
        echo json_encode(array('error' => 'Invalid userid'));
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array('error' => 'UserID and password fields are required'));
    exit;
}
?>
