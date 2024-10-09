<?php
include('authentication.php');
use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';
    require 'phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';

function sendEmail($student_email, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mcclearningresourcecenter@gmail.com';
            $mail->Password   = 'qxbi jqnf hgfn lkih'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587;

            $mail->setFrom('mcclearningresourcecenter@gmail.com', 'MCC Learning Resource Center');
            $mail->addAddress($student_email); 

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

if (isset($_POST['deny'])) {
    global $con;

    // Sanitize and validate inputs
    $student_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $deny_reason = mysqli_real_escape_string($con, $_POST['deny_reason']);
    if (empty($deny_reason) || strlen($deny_reason) > 255) {
        $_SESSION['status'] = 'Invalid reason provided.';
        $_SESSION['status_code'] = "error";
        header("Location: user_student_approval");
        exit(0);
    }

    // Fetch email
    $email_query = "SELECT email FROM user WHERE user_id=?";
    if ($stmt = mysqli_prepare($con, $email_query)) {
        mysqli_stmt_bind_param($stmt, 'i', $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $email_row = mysqli_fetch_assoc($result);

        if ($email_row) {
            $student_email = $email_row['email'];

            // Update account status
            $used_query = "UPDATE ms_account SET used=0 WHERE username=?";
            if ($stmt = mysqli_prepare($con, $used_query)) {
                mysqli_stmt_bind_param($stmt, 's', $student_email);
                mysqli_stmt_execute($stmt);
            }

                // Delete user account
                $query = "DELETE FROM user WHERE user_id=?";
                if ($stmt = mysqli_prepare($con, $query)) {
                    mysqli_stmt_bind_param($stmt, 'i', $student_id);
                    if (mysqli_stmt_execute($stmt)) {
                        // Prepare email
                        $subject = "Account Denied Notification";
                        $message = " <html>
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
                                    <h1 style='color:#dc3545;text-align:center;'>Your Account has been Denied!!!</h1>
                                    <p>Dear Student,</p>
                                    <p>Your MCC-LRC account registration has been denied. Below is the reason for denial:</p>
                                    <p><strong>Reason:</strong> {$deny_reason}</p>
                                    <p>Please contact the library for more details.</p>
                                    <p>You can also contact us on our facebook page <a href='https://www.facebook.com/MCCLRC' target='_blank'>Madridejos Community College - Learning Resource Center</a>.</p>
                                    <p>Thank you.</p>
                                </div>
                            </div>
                        </body>
                    </html>";

                    // Send email
                    if (sendEmail($student_email, $subject, $message)) {
                        $_SESSION['status'] = 'Student Denied Successfully';
                        $_SESSION['status_code'] = "success";
                    } else {
                        $_SESSION['status'] = 'Unable to send notification email.';
                        $_SESSION['status_code'] = "error";
                    }
                } else {
                    $_SESSION['status'] = 'Student not Denied';
                    $_SESSION['status_code'] = "error";
                }
            } else {
                $_SESSION['status'] = 'Error preparing delete statement.';
                $_SESSION['status_code'] = "error";
            }
        } else {
            $_SESSION['status'] = 'Student Not Found';
            $_SESSION['status_code'] = "error";
        }
    } else {
        $_SESSION['status'] = 'Error preparing email query.';
        $_SESSION['status_code'] = "error";
    }

    // Redirect at the end
    header("Location: user_student_approval");
    exit(0);
}

// Student Approval
if(isset($_POST['approved'])) {
    $student_id = $_POST['user_id'];

    // Fetch student email
    $email_query = "SELECT email FROM user WHERE user_id='$student_id'";
    $email_result = mysqli_query($con, $email_query);
    $email_row = mysqli_fetch_assoc($email_result);
    $student_email = $email_row['email'];

    $query = "UPDATE user SET status = 'approved' WHERE user_id = '$student_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Send email notification
        $subject = "Account Approved Notification";
        $message = " <html>
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
                        <h1 style='color:#198754;text-align:center;'>Your Account has been Approved.</h1>
                        <p>Dear Student,</p>
                        <p>Your MCC-LRC account registration has been approved. You can now log in to your account.</p>
                        <p><a  style='color: white;' href='http://mcc-lrc.com/login' class='button'>Login</a></p>
                        <p>Thank you.</p>
                    </div>
                </div>
            </body>
        </html>
        ";
        sendEmail($student_email, $subject, $message);

        $_SESSION['status'] = 'Student approved successfully';
        $_SESSION['status_code'] = "success";
        header("Location: user_student_approval");
        exit(0);
    } else {
        $_SESSION['status'] = 'Student not approved';
        $_SESSION['status_code'] = "error";
        header("Location: user_student_approval");
        exit(0);
    }
}

// Block student
if(isset($_POST['block_student'])) {
    $user_id = $_POST['block_student'];
    $query = "UPDATE user SET status='blocked' WHERE user_id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Fetch student email
        $email_query = "SELECT email FROM user WHERE user_id='$user_id'";
        $email_result = mysqli_query($con, $email_query);
        $email_row = mysqli_fetch_assoc($email_result);
        $student_email = $email_row['email'];

        // Send email notification
        $subject = "Account Blocked Notification";
        $message = " <html>
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
                        <h1 style='color:#dc3545;text-align:center;'>Your Account has been Blocked!!!</h1>
                        <p>Dear Student,</p>
                        <p>Your MCC-LRC account has been blocked for a while. Please contact the library for more details.</p>
                        <p>You can also contact us on our facebook page <a href='https://www.facebook.com/MCCLRC' target='_blank'>Madridejos Community College - Learning Resource Center</a>.</p>
                        <p>Thank you.</p>
                    </div>
                </div>
            </body>
        </html>
        ";
        sendEmail($student_email, $subject, $message);

        $_SESSION['status'] = "Student has been blocked successfully.";
        $_SESSION['status_code'] = "success";
        header("Location: user_student");
        exit(0);
    } else {
        $_SESSION['status'] = "Something went wrong.";
        $_SESSION['status_code'] = "error";
        header("Location: user_student");
        exit(0);
    }
}

// Unblock student
if(isset($_POST['unblock_student'])) {
    $user_id = $_POST['unblock_student'];
    $query = "UPDATE user SET status='approved' WHERE user_id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Fetch student email
        $email_query = "SELECT email FROM user WHERE user_id='$user_id'";
        $email_result = mysqli_query($con, $email_query);
        $email_row = mysqli_fetch_assoc($email_result);
        $student_email = $email_row['email'];

        // Send email notification
        $subject = "Account Unblocked Notification";
        $message = " <html>
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
                        <h1 style='color:#198754;text-align:center;'>Your Account has been Unblocked.</h1>
                        <p>Dear Student,</p>
                        <p>Your MCC-LRC account has been unblocked. You can now log in to your account.</p>
                        <p>Thank you.</p>
                    </div>
                </div>
            </body>
        </html>
        ";

        sendEmail($student_email, $subject, $message);

        $_SESSION['status'] = "Student has been unblocked successfully.";
        $_SESSION['status_code'] = "success";
        header("Location: user_student");
        exit(0);
    } else {
        $_SESSION['status'] = "Something went wrong.";
        $_SESSION['status_code'] = "error";
        header("Location: user_student");
        exit(0);
    }
}

// Delete Action
if (isset($_POST['delete_student_id'])) {
    global $con; 

    $student_id = mysqli_real_escape_string($con, $_POST['delete_student_id']);
    $delete_reason = mysqli_real_escape_string($con, $_POST['delete_reason']);

    // Fetch the student's email
    $email_query = "SELECT email FROM user WHERE user_id=?";
    $stmt = mysqli_prepare($con, $email_query);
    mysqli_stmt_bind_param($stmt, 'i', $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $email_row = mysqli_fetch_assoc($result);

    if ($email_row) {
        $student_email = $email_row['email'];

        // Update the MS account status
        $used_query = "UPDATE ms_account SET used=0 WHERE username=?";
        $stmt = mysqli_prepare($con, $used_query);
        mysqli_stmt_bind_param($stmt, 's', $student_email);
        mysqli_stmt_execute($stmt);

        // Delete the user
        $query = "DELETE FROM user WHERE user_id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $student_id);
        $query_run = mysqli_stmt_execute($stmt);

        if ($query_run) {
            // Prepare and send email notification
            $subject = "Account Delete Notification";
            $message = "<html>
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
                            <h1 style='color:#dc3545;text-align:center;'>Your Account has been Deleted!!!</h1>
                            <p>Dear Student,</p>
                            <p>Your MCC-LRC account has been deleted. Below is the reason for deletion:</p>
                            <p><strong>Reason:</strong> {$delete_reason}</p>
                            <p>Please contact the library for more details.</p>
                            <p>You can also contact us on our Facebook page <a href='https://www.facebook.com/MCCLRC' target='_blank'>Madridejos Community College - Learning Resource Center</a>.</p>
                            <p>Thank you.</p>
                        </div>
                    </div>
                </body>
            </html>";

            if (sendEmail($student_email, $subject, $message)) {
                $_SESSION['status'] = 'Student Deleted Successfully and Email Sent';
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = 'Student Deleted Successfully';
                $_SESSION['status_code'] = "success";
            }
        } else {
            $_SESSION['status'] = 'Student Deletion Failed';
            $_SESSION['status_code'] = "error";
        }

        header("Location: user_student");
        exit(0);
    } else {
        $_SESSION['status'] = 'Student Not Found';
        $_SESSION['status_code'] = "error";
        header("Location: user_student");
        exit(0);
    }
}

// Edit Student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_student_id'])) {
        $studentId = mysqli_real_escape_string($con, $_POST['edit_student_id']);
        $lName = mysqli_real_escape_string($con, $_POST['edit_last_name']);
        $fName = mysqli_real_escape_string($con, $_POST['edit_first_name']);
        $mName = mysqli_real_escape_string($con, $_POST['edit_middle_name']);

        $sql = "UPDATE user SET firstname='$fName', lastname='$lName', middlename='$mName' WHERE user_id='$studentId'";
        if (mysqli_query($con, $sql)) {
            $_SESSION['status'] = "Updated successfully.";
            $_SESSION['status_code'] = "success";
            header('Location: user_student');
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM user WHERE user_id = '$userId'";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
}
?>
