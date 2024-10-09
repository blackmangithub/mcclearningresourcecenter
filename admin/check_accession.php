<?php
include('authentication.php');

// Get the accession number from the request
$accession_number = $_POST['accession_number'];

// Prepare and execute the query to check if the accession number exists
$query = "SELECT COUNT(*) AS count FROM book WHERE accession_number = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $accession_number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Return the result as JSON
echo json_encode(array('exists' => $row['count'] > 0));

$stmt->close();
$con->close();
?>