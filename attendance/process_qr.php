<?php
include('../admin/config/dbcon.php');

if (isset($_POST['text'])) {
    $qr_code = $_POST['text'];

    // Query to select student based on student_id_no
    $student_query = "SELECT * FROM user WHERE student_id_no = '$qr_code'";
    $student_query_run = mysqli_query($con, $student_query);

    // Query to select faculty based on username
    $faculty_query = "SELECT * FROM faculty WHERE username = '$qr_code'";
    $faculty_query_run = mysqli_query($con, $faculty_query);

    $date_log = date("Y-m-d");

    if (mysqli_num_rows($student_query_run) > 0) {
        $user = mysqli_fetch_assoc($student_query_run);

        // Check for existing log entry for today
        $student_id = $user['student_id_no'];
        $log_check_query = "SELECT * FROM user_log WHERE student_id = '$student_id' AND date_log = '$date_log' AND time_out = ''";
        $log_check_query_run = mysqli_query($con, $log_check_query);

        if (mysqli_num_rows($log_check_query_run) > 0) {
            // Update the existing log with time_out
            $log_update_query = "UPDATE user_log SET time_out = NOW() WHERE student_id = '$student_id' AND date_log = '$date_log' AND time_out = ''";
            $log_update_query_run = mysqli_query($con, $log_update_query);

            if ($log_update_query_run) {
                header("Location:index.php");
                exit();
            } else {
                header("Location:qr_scanner.php");
                exit("Failed to update time out for student.");
            }
        } else {
            // Insert student log into user_log table
            $firstname = $user['firstname'];
            $middlename = $user['middlename'];
            $lastname = $user['lastname'];
            $course = $user['course'];
            $year_level = $user['year_level'];

            $log_insert_query = "INSERT INTO user_log (student_id, firstname, middlename, lastname, time_log, date_log, time_out, course, year_level, role) VALUES ('$student_id', '$firstname', '$middlename', '$lastname', NOW(), '$date_log', '', '$course', '$year_level', 'student')";
            $log_insert_query_run = mysqli_query($con, $log_insert_query);

            if ($log_insert_query_run) {
                header("Location:index.php");
                exit();
            } else {
                header("Location:qr_scanner.php");
                exit("Failed to insert log for student.");
            }
        }
    } elseif (mysqli_num_rows($faculty_query_run) > 0) {
        $user = mysqli_fetch_assoc($faculty_query_run);

        // Check for existing log entry for today
        $username = $user['username'];
        $log_check_query = "SELECT * FROM user_log WHERE student_id = '$username' AND date_log = '$date_log' AND time_out = ''";
        $log_check_query_run = mysqli_query($con, $log_check_query);

        if (mysqli_num_rows($log_check_query_run) > 0) {
            // Update the existing log with time_out
            $log_update_query = "UPDATE user_log SET time_out = NOW() WHERE student_id = '$username' AND date_log = '$date_log' AND time_out = ''";
            $log_update_query_run = mysqli_query($con, $log_update_query);

            if ($log_update_query_run) {
                header("Location:index.php");
                exit();
            } else {
                header("Location:qr_scanner.php");
                exit("Failed to update time out for faculty.");
            }
        } else {
            // Insert faculty log into user_log table
            $firstname = $user['firstname'];
            $middlename = $user['middlename'];
            $lastname = $user['lastname'];
            $course = $user['course'];

            $log_insert_query = "INSERT INTO user_log (student_id, firstname, middlename, lastname, time_log, date_log, time_out, course, year_level, role) VALUES ('$username', '$firstname', '$middlename', '$lastname', NOW(), '$date_log', '', '$course', 'faculty', 'faculty')";
            $log_insert_query_run = mysqli_query($con, $log_insert_query);

            if ($log_insert_query_run) {
                header("Location:index.php");
                exit();
            } else {
                header("Location:qr_scanner.php");
                exit("Failed to insert log for faculty.");
            }
        }
    } else {
        exit("User not found");
    }
} else {
    exit("No QR code provided");
}
?>
