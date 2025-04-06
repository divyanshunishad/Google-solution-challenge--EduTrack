<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include database connection
require_once "../../assets/Mxy4XNn4Hs/databaseConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(json_encode(array('error' => 'Invalid CSRF token')));
    }

    // Retrieve and sanitize input
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password']; // Do not sanitize passwords
    $designation_code = filter_input(INPUT_POST, 'designation_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Server-side validation
    if (!$userid || strlen($userid) < 8 || strlen($userid) > 10) {
        die(json_encode(array('error' => 'UserID must be between 8-10 characters.')));
    }
    if (empty($password)) {
        die(json_encode(array('error' => 'Password cannot be empty.')));
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if userid already exists
    $stmt = $conn->prepare("SELECT userid FROM edutrackusertable WHERE userid = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die(json_encode(array('error' => 'UserID already exists.')));
    }
    $stmt->close();

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO edutrackusertable (userid, password, designation_code) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $userid, $hashedPassword, $designation_code);

    if ($stmt->execute()) {
        echo json_encode(array('success' => 'User registered successfully.'));
    } else {
        echo json_encode(array('error' => 'Registration failed.'));
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label for="userid">User ID:</label>
        <input type="text" name="userid" required maxlength="10" minlength="8"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="designation_code">Designation:</label>
        <select name="designation_code">
            <option value="00">Student</option>
            <option value="ED">Faculty</option>
            <option value="ET">Admin</option>
        </select><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
