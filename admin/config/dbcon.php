<?php 

$host = "localhost";
// $username = "u510162695_mcclrc";
// $password = "1Mcclrc_pass";
// $database = "u510162695_mcclrc";
$username = "root";
$password = "";
$database = "mcclrc";

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  // echo "Connected Successfully";
}
?>
