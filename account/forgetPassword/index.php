<?php
// Secure session settings
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_secure', true);
ini_set('session.use_only_cookies', true);
ini_set('session.use_strict_mode', true);
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../../assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>EduTrack | Forgot Password</title>

  <script>
    $(document).ready(function() {
      $('#forgotPasswordForm').submit(function(e) {
        e.preventDefault();

        var userid = $('#userid').val();
        var email = $('#email').val();
        var dob = $('#dob').val();

        $.ajax({
          type: 'POST',
          url: 'process_forgot_password.php',
          data: {
            userid: userid,
            email: email,
            dob: dob,
            csrf_token: '<?php echo $csrfToken; ?>'
          },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              $('#success-message-text').html(response.success);
              $('#success-popup').show();
              setTimeout(function() { $('#success-popup').hide(); }, 5000);
            } else if (response.error) {
              $('#error-message-text').html(response.error);
              $('#error-popup').show();
              setTimeout(function() { $('#error-popup').hide(); }, 5000);
            }
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });

      // Close popups when close button is clicked
      $('#error-popup-close').click(function() {
        $('#error-popup').hide();
      });

      $('#success-popup-close').click(function() {
        $('#success-popup').hide();
      });
    });
  </script>

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
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .btn-custom {
      background-color: #212121;
      color: white;
      border: #5e17eb solid 1px;
    }
    .btn-custom:hover {
      background-color: rgb(73, 14, 193);
    }
    .btn-secondary {
      background-color: #5e17eb;
      color: white;
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
      <div class="logo-container text-start">
        <img src="../../assets/images/edutrack-logo.png" alt="EduTrack-logo" width="150">
      </div>
      <div class="text-start mt-2">
        <h5>Forgot Password? 🔑</h5>
        <p>Enter your details to reset your password.</p>
      </div>

      <form id="forgotPasswordForm" method="POST">
        <div class="mb-3">
          <label for="userid" class="form-label">User ID</label>
          <input type="text" class="form-control" id="userid" name="userid" placeholder="Enter your User ID" >
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" >
        </div>
        <div class="mb-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob" >
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        
        <div class="text-center">
          <button type="submit" class="btn btn-custom d-grid w-100">Reset Password</button>
          <a href="../../index.php" class="btn btn-secondary d-grid w-100 mt-2">Back to Login</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Error Popup-->
  <?php include '../../assets/components/success.php'; ?>
  <!--erroe popup end-->

  <!-- Error Popup-->
  <?php include '../../assets/components/error.php'; ?>
  <!--erroe popup end-->

  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
