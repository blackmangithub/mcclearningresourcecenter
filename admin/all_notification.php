<?php
ob_start(); // Start output buffering
include('authentication.php');
include('includes/header.php');
include('./includes/sidebar.php'); 

// Fetch all holds
$query_alls = "
    SELECT 
        u.user_id, u.firstname AS user_firstname, u.lastname AS user_lastname, 
        f.faculty_id, f.firstname AS faculty_firstname, f.lastname AS faculty_lastname,
        COUNT(h.hold_id) AS num_hold_books
    FROM holds h
    LEFT JOIN user u ON u.user_id = h.user_id
    LEFT JOIN faculty f ON f.faculty_id = h.faculty_id
    WHERE h.hold_status = 'Hold'
    GROUP BY u.user_id, f.faculty_id
    ORDER BY h.hold_id DESC";

$query_alls_stmt = $con->prepare($query_alls);
$query_alls_stmt->execute();
$query_alls_run = $query_alls_stmt->get_result();

// Fetch all pending users
$pending_users_sql = "SELECT user_id, firstname, lastname, profile_image FROM user WHERE status = 'pending'";
$pending_users_stmt = $con->prepare($pending_users_sql);
$pending_users_stmt->execute();
$pending_users_result = $pending_users_stmt->get_result();

// Fetch all pending faculty
$pending_faculty_sql = "SELECT faculty_id, firstname, lastname, profile_image FROM faculty WHERE status = 'pending'";
$pending_faculty_stmt = $con->prepare($pending_faculty_sql);
$pending_faculty_stmt->execute();
$pending_faculty_result = $pending_faculty_stmt->get_result();
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>All Notifications</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item active">All Notifications</li>
            </ol>
        </nav>
    </div>

    <section class="notifications-section">
        <?php if (mysqli_num_rows($query_alls_run) > 0 || mysqli_num_rows($pending_users_result) > 0 || mysqli_num_rows($pending_faculty_result) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($holdlist = mysqli_fetch_assoc($query_alls_run)): ?>
                        <?php
                        $name = $holdlist['user_id'] ? $holdlist['user_firstname'].' '.$holdlist['user_lastname'] : $holdlist['faculty_firstname'].' '.$holdlist['faculty_lastname'];
                        $image = $holdlist['user_id'] ? '../uploads/profile_images/'.$holdlist['profile_image'] : '../uploads/profile_images/'.$holdlist['profile_image'];
                        ?>
                        <tr>
                            <td>Hold</td>
                            <td>
                                <?= $name ?>
                            </td>
                            <td>Hold <span><?= $holdlist['num_hold_books'] ?></span> book(s).</td>
                            <td><a href="hold_list" class="btn btn-primary">View</a></td>
                        </tr>
                    <?php endwhile; ?>

                    <?php while ($user = mysqli_fetch_assoc($pending_users_result)): ?>
                        <tr>
                            <td>Pending Student</td>
                            <td>
                                <img src="<?= $user['profile_image'] ? '../uploads/profile_images/'.$user['profile_image'] : 'assets/img/image.png'; ?>" alt="" width="30px" height="30px" class="rounded-circle">
                                <?= $user['firstname'].' '.$user['lastname'] ?>
                            </td>
                            <td>Pending Approval</td>
                            <td><a href="user_student_approval" class="btn btn-primary">View</a></td>
                        </tr>
                    <?php endwhile; ?>

                    <?php while ($faculty = mysqli_fetch_assoc($pending_faculty_result)): ?>
                        <tr>
                            <td>Pending Faculty</td>
                            <td>
                                <img src="<?= $faculty['profile_image'] ? '../uploads/profile_images/'.$faculty['profile_image'] : 'assets/img/image.png'; ?>" alt="" width="30px" height="30px" class="rounded-circle">
                                <?= $faculty['firstname'].' '.$faculty['lastname'] ?>
                            </td>
                            <td>Pending Approval</td>
                            <td><a href="user_faculty_approval" class="btn btn-primary">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No notifications found.</p>
        <?php endif; ?>
    </section>
</main>

<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
ob_end_flush(); // End output buffering and flush output
?>
