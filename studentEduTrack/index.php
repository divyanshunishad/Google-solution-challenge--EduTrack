<?php
include "../assets/authentication/authentication.php";
if (isset($_GET['userid']) && isset($_GET['csrf_token'])) {
    $userid = $_GET['userid'];
    $csrf_token = $_GET['csrf_token'];
} else {
    header("Location: ../index.php"); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>EduTrack | Dashboard Student</title>
    <meta content="" name="description" content="">
    <meta content="" name="keywords" content="">

    <!-- Favicons -->
    <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon">

    

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        span {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo"><img src="../assets/images/edutrack-logo.png" alt="EduTrack-logo" class="img-fluid"></a>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#">Home</a></li>
                    <!--<li><a class="nav-link scrollto" href="" target="_blank"></a></li>
                    <li><a class="nav-link scrollto" href="" target="_blank"></a></li>
                    <li><a class="nav-link scrollto " href="" target="_blank"></a></li>
                    <li><a class="nav-link scrollto" href="" target="_blank"></a></li>-->
                    <a class="getstarted scrollto" href="?logout=1&csrf_token=<?php echo $_SESSION['csrf_token']; ?>">Logout</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <main id="main">
        <section class="inner-page">
            <div class="container">
                <section id="featured-services" class="featured-services">
                    <div class="container">

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="icon-box">
                                    <h2 style="color:salmon;">Welcome, <?php echo $userid; ?>🎉</h2>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container footer-bottom clearfix">
            <div class="copyright">
                &copy; Copyright <strong><span>SolutionSphere</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="?logout=1">SolutionSphere</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>


