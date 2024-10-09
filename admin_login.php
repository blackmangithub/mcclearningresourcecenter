<?php
ini_set('session.cookie_httponly', 1);
session_start();
include('config/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" href="./assets/img/mcc-logo.png">
     <title>MCC Learning Resource Center</title>

     <!-- Alertify JS link -->
     <link rel="stylesheet" href="assets/css/alertify.min.css" />
     <link rel="stylesheet" href="assets/css/alertify.bootstraptheme.min.css" />
     <link rel="stylesheet" href="assets/css/bootstrap-icons.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

     <!-- Iconscout cdn link -->
     <link rel="stylesheet" href="assets/css/line.css">
     <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
     
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="assets/css/bootstrap5.min.css" />

     <!-- Bootstrap Icon -->
     <link rel="stylesheet" href="assets/font/bootstrap-icons.css">

     <!-- Custom CSS Styling -->
     <link rel="stylesheet" href="assets/css/login.css">
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
    <section class="d-flex mt-4 flex-column justify-content-center align-items-center">
        <div class="container-xl">
            <div class="col mx-auto rounded shadow bg-white">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <img src="assets/img/mcc-logo.png" alt="logo" class="img-fluid d-none d-md-block p-5" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 px-5">
                        <div class="mt-4 mb-4">
                            <center>
                                <h1 class="m-0"><strong>MCC</strong></h1>
                                <p class="fs-4 fw-semibold text-info">Learning Resource Center</p>
                                <p class="m-0 fw-semibold">Admin Login</p>
                            </center>
                        </div>

                        <?php if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']): ?>
                            <?php
                            $lockout_time_remaining = $_SESSION['lockout_time'] - time();
                            $minutes_remaining = ceil($lockout_time_remaining / 60);
                            ?>
                            <div class="alert alert-danger">
                                Too many failed attempts. Please try again in <?php echo $minutes_remaining; ?> minute(s).
                            </div>
                        <?php endif; ?>

                        <form action="admin_login_code.php" method="POST" class="needs-validation" novalidate>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="admin_type" name="admin_type" required <?php echo (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) ? 'disabled' : ''; ?>>
                                        <option value="" selected disabled>Select Admin Type</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Staff">Staff</option>
                                    </select>
                                    <label for="admin_type">Admin Type</label>
                                    <div class="invalid-feedback">
                                        Please select an admin type.
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Email" autocomplete="off" required <?php echo (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) ? 'disabled' : ''; ?>>
                                    <label for="email">Email</label>
                                    <div id="validationServerEmailFeedback" class="invalid-feedback">
                                        Please enter your email
                                    </div>
                                </div>
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required <?php echo (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) ? 'disabled' : ''; ?>>
                                    <label for="password">Password</label>
                                    <span class="password-show-toggle js-password-show-toggle">
                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                    </span>
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                        Please enter your password.
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <div class="g-recaptcha" data-sitekey="6LfNJ1wqAAAAAKE4vmQh1Gc4LJC6e7Js1Eg9Ns76"></div>
                                    <div class="invalid-feedback">Please complete the reCAPTCHA.</div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 md-3 mb-3">
                                <button type="submit" name="admin_login_btn" class="btn btn-primary text-light font-weight-bolder btn-lg" <?php echo (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) ? 'disabled' : ''; ?>>Login</button>
                            </div>
                            <div class="text-end mb-3">
                                <p>
                                    <a href="../login" class="text-primary text-decoration-none fw-semibold">User Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include('includes/script.php'); include('message.php'); ?>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
            <?php unset($_SESSION['login_success']); // Clear session variable ?>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                showConfirmButton: true
            }).then(() => {
                window.location.href = './admin/.'; // Redirect after showing SweetAlert
            });
        <?php endif; ?>
    });
</script>

</html>
