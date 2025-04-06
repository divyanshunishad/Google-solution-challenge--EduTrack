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
  <title>EduTrack | Forgot User ID</title>

  <link rel="icon" href="../../assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
      <div class="text-start">
        <img src="../../assets/images/edutrack-logo.png" alt="EduTrack Logo" width="150">
      </div>
      <div class="text-start mt-2">
        <h5>Forgot User ID? 📌</h5>
        <p>Enter your details to retrieve your User ID.</p>
      </div>

      <form id="forgetIdForm">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
        </div>
        <div class="mb-3">
          <label for="contact" class="form-label">Contact Number</label>
          <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your Contact Number" required>
        </div>
        <div class="mb-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

        <div class="text-center">
          <button type="submit" class="btn btn-custom d-grid w-100">Retrieve User ID</button>
          <a href="../../index.php" class="btn btn-secondary d-grid w-100 mt-2">Back to Login</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Success Popup -->
  <?php include '../../assets/components/success.php'; ?>
  <!-- Success Popup End -->

  <!-- Error Popup -->
  <?php include '../../assets/components/error.php'; ?>
  <!-- Error Popup End -->

  <script>
    $(document).ready(function () {
      $('#forgetIdForm').submit(function (e) {
        e.preventDefault();

        var email = $('#email').val().trim();
        var contact = $('#contact').val().trim();
        var dob = $('#dob').val().trim();

        // Input validation
        if (email === "" || contact === "" || dob === "") {
          showError("All fields are required.");
          return;
        }

        // Disable button to prevent duplicate submissions
        $('button[type="submit"]').prop('disabled', true).text("Processing...");

        $.ajax({
          type: 'POST',
          url: 'process_forget_id.php',
          data: {
            email: email,
            contact: contact,
            dob: dob,
            csrf_token: '<?php echo $csrfToken; ?>'
          },
          dataType: 'json',
          success: function (response) {
            $('button[type="submit"]').prop('disabled', false).text("Retrieve User ID");

            if (response.success) {
              $('#success-message-text').html(response.success);
              $('#success-popup').show();
              setTimeout(function() { $('#success-popup').hide(); }, 5000);
              $('#forgetIdForm')[0].reset();
            } else if (response.error) {
              $('#error-message-text').html(response.error);
              $('#error-popup').show();
              setTimeout(function() { $('#error-popup').hide(); }, 5000);
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            $('#error-message-text').html("An error occurred. Please try again.");
            $('#error-popup').show();
            setTimeout(function() { $('#error-popup').hide(); }, 5000);
            $('button[type="submit"]').prop('disabled', false).text("Retrieve User ID");
          }
        });
      });

      // Close popups when close button is clicked
      $('#error-popup-close').click(function () {
        $('#error-popup').hide();
      });

      $('#success-popup-close').click(function () {
        $('#success-popup').hide();
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
