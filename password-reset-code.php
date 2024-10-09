<?php
ini_set('session.cookie_httponly', 1);
session_start();
include('./admin/config/dbcon.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';
    require 'phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';

function send_password_reset($get_name, $get_email, $token) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Outlook/Microsoft 365 SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mcclearningresourcecenter@gmail.com'; // Your Outlook/Microsoft 365 email address
            $mail->Password   = 'qxbi jqnf hgfn lkih'; // Your email account password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
            $mail->Port       = 587; // Port for TLS

            //Recipients
            $mail->setFrom('mcclearningresourcecenter@gmail.com', 'MCC Learning Resource Center');
            $mail->addAddress($get_email, $get_name); // Recipient's email address

        // Email content settings
        $mail->isHTML(true);
        $mail->Subject = 'Here is your link to Reset the password of your MCC-LRC Account';
        $mail->Body = "
        <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        width: 80%;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 20px;
                        border-bottom: 1px solid #ddd;
                    }
                    .logo {
                        max-width: 150px;
                        height: auto;
                    }
                    .content {
                        padding: 20px 0;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #007bff;
                        text-decoration: none;
                        color: white;
                        border-radius: 4px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <img src='https://mcc-lrc.com/images/mcc-logo.png' alt='Logo'>
                    </div>
                    <div class='content'>
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Click the button below to reset it:</p>
                        <p><a style='color: white;' href='https://mcc-lrc.com/password-change.php?token=" . urlencode($token) . "&email=" . urlencode($get_email) . "' class='button'>Reset Password</a></p>
                        <p>If you did not request a password reset, please ignore this email.</p>
                    </div>
                </div>
            </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if (isset($_POST['password_reset_link'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());

    // User table check
    $check_email_user = "SELECT firstname, email FROM user WHERE email='$email'";
    $check_email_run_user = mysqli_query($con, $check_email_user);

    if (mysqli_num_rows($check_email_run_user) > 0) {
        $row = mysqli_fetch_array($check_email_run_user);
        $get_name = $row['firstname'];
        $get_email = $row['email'];

        $update_token_user = "UPDATE user SET verify_token='$token', token_used=0 WHERE email='$get_email'";
        $update_token_run_user = mysqli_query($con, $update_token_user);

        if ($update_token_run_user) {
            if (send_password_reset($get_name, $get_email, $token)) {
                $_SESSION['status'] = 'We e-mailed you a password reset link';
                $_SESSION['status_code'] = 'success';
                header('Location: password-reset');
                exit(0);
            } else {
                $_SESSION['status'] = 'Email sending failed. Please try again.';
                $_SESSION['status_code'] = 'error';
                header('Location: password-reset');
                exit(0);
            }
        }
    }

    // Faculty table check
    $check_email_faculty = "SELECT firstname, email FROM faculty WHERE email='$email'";
    $check_email_run_faculty = mysqli_query($con, $check_email_faculty);

    if (mysqli_num_rows($check_email_run_faculty) > 0) {
        $row = mysqli_fetch_array($check_email_run_faculty);
        $get_name = $row['firstname'];
        $get_email = $row['email'];

        $update_token_faculty = "UPDATE faculty SET verify_token='$token', token_used=0 WHERE email='$get_email'";
        $update_token_run_faculty = mysqli_query($con, $update_token_faculty);

        if ($update_token_run_faculty) {
            if (send_password_reset($get_name, $get_email, $token)) {
                $_SESSION['status'] = "We e-mailed you a password reset link";
                $_SESSION['status_code'] = "success";
                header('Location: password-reset');
                exit(0);
            } else {
                $_SESSION['status'] = "Email sending failed. Please try again.";
                $_SESSION['status_code'] = "error";
                header('Location: password-reset');
                exit(0);
            }
        }
    } else {
        $_SESSION['status'] = "No email found";
        $_SESSION['status_code'] = "error";
        header('Location: password-reset');
        exit(0);
    }
}

if (isset($_POST['password-change'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // User table check
    $check_email_user = "SELECT email, token_used FROM user WHERE email='$email'";
    $check_email_run_user = mysqli_query($con, $check_email_user);

    if (mysqli_num_rows($check_email_run_user) > 0) {
        $row = mysqli_fetch_array($check_email_run_user);
        $get_email = $row['email'];
        $token_used = $row['token_used'];

        // Check if token is used
        if ($token_used == 0) {
            $update_password_user = "UPDATE user SET password='$hashed_password', token_used=1 WHERE email='$get_email'";
            $update_password_run_user = mysqli_query($con, $update_password_user);

            if ($update_password_run_user) {
                $_SESSION['status'] = 'Password successfully changed.';
                $_SESSION['status_code'] = 'success';
                header('Location: login');
                exit(0);
            } else {
                $_SESSION['status'] = 'Failed to update the password. Please try again.';
                $_SESSION['status_code'] = 'error';
                header('Location: password-change');
                exit(0);
            }
        } else {
            $_SESSION['status'] = 'Link already been used. Please request a new password reset link.';
            $_SESSION['status_code'] = 'error';
            header('Location: password-reset');
            exit(0);
        }
    }

    // Faculty table check
    $check_email_faculty = "SELECT email, token_used FROM faculty WHERE email='$email'";
    $check_email_run_faculty = mysqli_query($con, $check_email_faculty);

    if (mysqli_num_rows($check_email_run_faculty) > 0) {
        $row = mysqli_fetch_array($check_email_run_faculty);
        $get_email = $row['email'];
        $token_used = $row['token_used'];

        // Check if token is used
        if ($token_used == 0) {
            $update_password_faculty = "UPDATE faculty SET password='$hashed_password', token_used=1 WHERE email='$get_email'";
            $update_password_run_faculty = mysqli_query($con, $update_password_faculty);

            if ($update_password_run_faculty) {
                $_SESSION['status'] = 'Password successfully changed.';
                $_SESSION['status_code'] = 'success';
                header('Location: login');
                exit(0);
            } else {
                $_SESSION['status'] = 'Failed to update the password. Please try again.';
                $_SESSION['status_code'] = 'error';
                header('Location: password-change');
                exit(0);
            }
        } else {
            $_SESSION['status'] = 'Link already been used. Please request a new password reset link.';
            $_SESSION['status_code'] = 'error';
            header('Location: password-reset');
            exit(0);
        }
    } else {
        $_SESSION['status'] = 'Something went wrong.';
        $_SESSION['status_code'] = 'error';
        header('Location: password-change.php');
        exit(0);
    }
}
?>
