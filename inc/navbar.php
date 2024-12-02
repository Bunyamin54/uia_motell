<?php
session_start();
require_once '../config/config.php';

// Fetch the site name from the settings table
$stmt = $pdo->prepare("SELECT value FROM settings WHERE name = 'site_name'");
$stmt->execute();
$siteName = $stmt->fetchColumn() ?? 'UIA Motel'; // Default to "UIA Motel" if no setting is found
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteName) ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/styles.css">


    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
</head>

<body>


    <?php session_start(); ?>
    <nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php" style="color:#C80F2F;">
                <?= htmlspecialchars($siteName) ?>
            </a>

            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link me-2" href="#home" style="color: #D9334D;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="#rooms" style="color: #D9334D;">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="#contact" style="color: #D9334D;">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user']) && !headers_sent()): ?>
                        <!-- Display for logged-in users -->
                        <span class="me-3">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</span>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <a href="http://localhost/uia_motell/admin/dashboard.php" class="btn btn-primary shadow-none me-2">Admin Panel</a>

                        <?php elseif ($_SESSION['user']['role'] === 'guest'): ?>
                            <a href="../inc/guest_dashboard.php" class="btn btn-primary shadow-none me-2">Dashboard</a>
                        <?php endif; ?>
                        <a href="../inc/logout.php" class="btn btn-danger shadow-none">Logout</a>
                    <?php else: ?>
                        <!-- Display for non-logged-in users -->
                        <button type="button" class="btn btn-outline-success shadow-none me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button type="button" class="btn btn-outline-warning shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>





    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="login-form" action="../admin/login.php" method="POST">

                    <div class="modal-header">
                        <i class="bi bi-person-lines-fill fs-3 me-2"></i>
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="email_mobile" required class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" required class="form-control shadow-none">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <button type="submit" class="btn btn-success shadow-none">Login</button>
                            <a href="#" class="text-decoration-none btn btn-warning shadow-none">Forgot password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="register-form" action="../admin/register.php" method="POST">

                    <div class="modal-header">
                        <i class="bi bi-person-lines-fill fs-3 me-2"></i>
                        <h5 class="modal-title" id="registerModalLabel">Register</h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input name="phone" type="number" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input name="password" type="password" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="confirm_password" type="password" class="form-control shadow-none" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning">Register</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        // Initialize Flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#birthDatePicker", {
                dateFormat: "Y-m-d", // Specify the format
                maxDate: "today" // Prevent future dates
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include Flatpickr for Date Picker -->

    <!-- Booking Choice Modal -->
    <div class="modal fade" id="bookingChoiceModal" tabindex="-1" aria-labelledby="bookingChoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="bookingChoiceModalLabel">Continue Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Would you like to register for a personalized experience or continue as a guest?</p>
                    <div class="d-flex justify-content-evenly">
                        <a href="#" class="btn btn-outline-primary" id="registerButton">Register</a>
                        <a href="#" class="btn btn-outline-success" id="guestButton">Continue as Guest</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>