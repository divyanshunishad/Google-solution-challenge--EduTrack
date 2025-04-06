<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// loggedinststus  checker 
// Session configuration
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);
ini_set('session.use_only_cookies', true);
ini_set('session.use_strict_mode', true);
ini_set('session.cookie_samesite', 'Strict');

session_start();


// Function to authenticate user and verify CSRF token for GET requests
function authenticateUserGET()
{
    // Ensure the user is authenticated
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        return false; // User is not authenticated
    }

    return true; // Authentication successful
}

// Function to authenticate user and verify CSRF token for POST requests
function authenticateUserPOST()
{
    // Ensure the user is authenticated
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        return false; // User is not authenticated
    }

    return true; // Authentication successful
}

// Function to handle logout
function handleLogout()
{
    session_unset();
    $_SESSION = array();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    $response['error'] = 'Authentication failed';
    $response['redirect_url'] = 'http://localhost/edutrackf/index.php'; // Adjust redirect URL as needed
    echo json_encode($response);
    exit();
}

// Call the appropriate authenticateUser function based on request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!authenticateUserGET()) {
        // If authentication fails, redirect the user to the login page or display an error message
        
        handleLogout();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!authenticateUserPOST()) {
        // If authentication fails, redirect the user to the login page or display an error message

        handleLogout();
    }
}

// Handle logout if requested
if (isset($_GET['logout'])) {
    handleLogout();
}
//loggedinststus  checker end

?>
