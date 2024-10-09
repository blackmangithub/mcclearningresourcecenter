<?php
include('authentication.php');

$query = "SELECT COUNT(DISTINCT CONCAT(h.user_id, '-', h.faculty_id)) AS total_borrowers
          FROM holds h
          WHERE h.hold_status = 'Hold'";
$query_run = mysqli_query($con, $query);
$total_borrowers = $query_run ? mysqli_fetch_assoc($query_run)['total_borrowers'] : 0;

$user_sql = "SELECT COUNT(*) AS pending_count FROM user WHERE status = 'pending'";
$faculty_sql = "SELECT COUNT(*) AS pending_count FROM faculty WHERE status = 'pending'";

$user_result = mysqli_query($con, $user_sql);
$faculty_result = mysqli_query($con, $faculty_sql);

$user_row = mysqli_fetch_assoc($user_result);
$faculty_row = mysqli_fetch_assoc($faculty_result);

$pendingCount = $user_row['pending_count'] + $faculty_row['pending_count'];

$total_notifications = $total_borrowers + $pendingCount;

echo json_encode(['count' => $total_notifications]);
?>
