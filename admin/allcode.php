<?php
session_start();


include('admin/config/dbcon.php');

if(isset($_POST['change_password']))
{
     $current_password = $_POST['current_password'];
    $newpassword = $_POST['newpassword'];
    $renewpassword = $_POST['renewpassword'];

     // Prepare the query to validate the current password
    $validate_query = "SELECT * FROM user WHERE password = md5(?)";
    $stmt = $con->prepare($validate_query);
    $stmt->bind_param("s",$current_password);
    $stmt->execute();
    $validate_query_run = $stmt->get_result();

    if ($validate_query_run->num_rows > 0) {
     if ($newpassword === $renewpassword) {
         // Prepare the update password query
         $change_pass = "UPDATE user SET password = md5(?), confirm_password = md5(?)";
         $stmt = $con->prepare($change_pass);
         $stmt->bind_param("ss", $newpassword, $renewpassword);
         $change_pass_run = $stmt->execute();

         if ($change_pass_run) {
             $_SESSION['message_success'] = '<small>Password updated successfully</small>';
             header("Location: myprofile");
             exit(0);
         } else {
             $_SESSION['message_error'] = 'Password not updated';
             header("Location: myprofile");
             exit(0);
         }
     } else {
         $_SESSION['message_error'] = '<small>Password and confirm password do not match</small>';
         header("Location: myprofile");
         exit(0);
     }
 } else {
     $_SESSION['message_error'] = 'Current password does not match';
     header("Location: myprofile");
     exit(0);
 }
}


if (isset($_SESSION['auth_stud']['stud_id']))
{
     $id_session=$_SESSION['auth_stud']['stud_id'];

 }
                
          


 if (isset($_POST['save_changes'])) {
     $firstname = $_POST['firstname'];
     $middlename = $_POST['middlename'];
     $lastname = $_POST['lastname'];
     $address = $_POST['address'];
     $phone = $_POST['phone'];
     $email = $_POST['email'];
 
     // Prepare the update user information query
     $query = "UPDATE user SET firstname = ?, middlename = ?, lastname = ?, address = ?, cell_no = ?, email = ? WHERE user_id = ?";
     $stmt = $con->prepare($query);
     $stmt->bind_param("ssssssi", $firstname, $middlename, $lastname, $address, $phone, $email, $id_session);
     $query_run = $stmt->execute();
 
     if ($query_run) {
         $_SESSION['message_success'] = 'Updated Successfully';
         header("Location: myprofile");
         exit(0);
     } else {
         $_SESSION['message_error'] = 'Not Updated';
         header("Location: myprofile");
         exit(0);
     }
 }
 
 // Logout
 if (isset($_POST['logout_btn'])) {
     unset($_SESSION['auth']);
     unset($_SESSION['auth_role']);
     unset($_SESSION['auth_stud']);
 
     $_SESSION['message_success'] = "Logout Successfully";
     header("Location: ../admin_login");
     exit(0);
 }
?>