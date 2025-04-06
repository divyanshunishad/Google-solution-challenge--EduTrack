<?php
// Configure session settings securely before starting the session
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);
ini_set('session.use_only_cookies', true);
ini_set('session.use_strict_mode', true);
ini_set('session.cookie_samesite', 'Strict');

session_start();

function generateCSRFToken()
{
  $token = bin2hex(random_bytes(32));
  $_SESSION['csrf_token'] = $token;
  return $token;
}

$csrfToken = generateCSRFToken();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--font awesome-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
  <!--font awesome end-->
  <!--login form js-->
  <script>
    $(document).ready(function() {
      $('#loginForm').submit(function(e) {
        e.preventDefault();

        var userid = $('#userid').val();
        var password = $('#password').val();

        $.ajax({
          type: 'POST',
          url: 'login.php',
          data: {
            userid: userid,
            password: password,
            csrf_token: '<?php echo $csrfToken; ?>'
          },
          dataType: 'json', // Expect JSON response
          success: function(response) {
            if (response.redirect_url) {
              // Redirect to the specified URL
              window.location.href = response.redirect_url;
            } else if (response.error) {
              // Display error message received from the server
              $('#error-message-text').html(response.error);
              // Show the error popup
              $('#error-popup').show();
              // Remove error message after 5 seconds
              setTimeout(function() {
                $('#error-popup').hide();
              }, 5000);
            }
          },
          error: function(xhr, status, error) {
            // Handle AJAX errors if any
            console.error(xhr.responseText);
            console.error(status, error);
          }
        });
      });

      // Close the error popup when the close button is clicked
      $('#error-popup-close').click(function() {
        $('#error-popup').hide();
      });
    });
  </script>
  <!--login form js end-->
  <title>EduTrack | Login</title>
  <style>
    body {
      background-color: #f1f1f1;

    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      max-width: 450px;
      padding: 40px;
      border-radius: 12px;
      background-color: white;
      box-shadow: 0 0 0 8px rgba(0, 0, 0, 0);
      outline: 1px solid transparent;
      outline-offset: -8px;
    }

    .logo-container {
      display: flex;
      align-items: center;
    }

    .logoname {
      padding-top: 10px;
      padding-left: 10px;
      color: #5e17eb;
      font-size: 45px;
      font-family: 'Raleway', sans-serif;
      font: bold;
      font-weight: 600px;
    }

    .logo-container img {
      margin-right: 10px;
    }

    .welcome-text {
      text-align: left;
      margin-top: 15px;
      margin-bottom: 10px;
    }

    .btn-custom {
      background-color: #212121;
      color: white;
      border: #5e17eb solid 1px;

    }

    .btn-custom:hover {
      background-color:rgb(73, 14, 193);
      color:rgb(255, 255, 255);
    }

    .w-100 {
      width: 100% !important;
    }

    .d-grid {
      display: grid !important;
    }
  </style>

</head>

<body>

  <div class="container">
    <div class="login-box">
      <div class="logo-container">
        <img src="assets/images/edutrack-logo.png" alt="EduTrack-logo" width="150">
        <!--<h3 class="logoname"><b>EduTrack</b></h3>-->
      </div>
      <div class="welcome-text">
        <p>Welcome ! 👋</p>
        <p>Please sign-in to your account to continue</p>
      </div>
      <div style="color: red; font-weight:400px;" id="message"></div>
      <form id="loginForm" method="POST">
        <div class="mb-3">
          <label for="userid" class="form-label">UserId</label>
          <input type="text" class="form-control" id="userid" name="userid" placeholder="Enter your userid">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        <div class="text-center">
          <button type="submit" class="btn btn-custom d-grid w-100">Login</button>
          <div class="text-end mt-3">
            <a href="account/forgetPassword" class="mt-2">forgetPassword</a><br>
            <a href="account/forgetUserId" class="mt-2">forgetUserId</a>
          </div>
          
        </div>
      </form>
    </div>
  </div>

  <!-- Error Popup-->
  <?php include 'assets/components/error.php'; ?>
  <!--erroe popup end-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/dist/boxicons.min.js"></script>
</body>

</html>