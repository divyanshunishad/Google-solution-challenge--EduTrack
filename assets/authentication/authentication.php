<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);
ini_set('session.use_only_cookies', true);
ini_set('session.use_strict_mode', true);
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Function to regenerate CSRF token if needed
function regenerateCSRFTokenIfNeeded()
{
    if (!isset($_SESSION['csrf_token'])) {
        regenerateCSRFToken();
    }
}

// Function to regenerate CSRF token
function regenerateCSRFToken()
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to authenticate user and verify CSRF token for GET requests
function authenticateUserGET()
{
    // Ensure the user is authenticated
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        return false; // User is not authenticated
    }

    // Verify CSRF token for GET requests
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        return false; // CSRF token mismatch
    }

    // Regenerate CSRF token
    regenerateCSRFTokenIfNeeded();

    return true; // Authentication successful
}

// Function to authenticate user and verify CSRF token for POST requests
function authenticateUserPOST()
{
    // Ensure the user is authenticated
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        return false; // User is not authenticated
    }

    // Verify CSRF token for POST requests
    if (!isset($_POST['csrf_token']) ||  $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        return false; // CSRF token mismatch
    }

    // Regenerate CSRF token
    regenerateCSRFTokenIfNeeded();
    return true; // Authentication successful
}

// Function to handle logout
function handleLogout()
{
    session_unset();
    $_SESSION = array();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    header("Location: http://localhost/edutrackf/index.php");
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

// CSRF token for the frontend
$submittedToken = $_SESSION['csrf_token'];
?>
