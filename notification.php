<?php 
ini_set('session.cookie_httponly', 1);
include('includes/header.php');
include('includes/navbar.php');
include('admin/config/dbcon.php');

if (!isset($_SESSION['auth'])) {
    header('Location: .');
    exit(0);
}
if($_SESSION['auth_role'] != "student" && $_SESSION['auth_role'] != "faculty" && $_SESSION['auth_role'] != "staff") {
    header("Location:1");
    exit(0);
}

// Function to mark notification as read
if(isset($_POST['mark_read'])) {
    $hold_id = mysqli_real_escape_string($con, $_POST['hold_id']);
    $update_query = "DELETE FROM holds WHERE hold_id = '$hold_id'";
    mysqli_query($con, $update_query);
}

// Function to mark overdue notification as read
if(isset($_POST['mark_overdue_read'])) {
    $borrow_id = mysqli_real_escape_string($con, $_POST['borrow_id']);
    $update_query = "UPDATE borrow_book SET notification_status = 'Read' WHERE borrow_book_id = '$borrow_id'";
    mysqli_query($con, $update_query);
}

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mt-4" data-aos="fade-up" style="height: 70vh">
                <?php 
                // Notification for approved holds
                $name_hold = $_SESSION['auth_stud']['stud_id'];
                $query_notif = "SELECT holds.hold_id, book.title, hold_status, book.accession_number
                                FROM holds 
                                LEFT JOIN book ON holds.book_id = book.book_id
                                WHERE hold_status = 'Approved' AND (user_id = '$name_hold' OR faculty_id = '$name_hold')
                                ORDER BY hold_id DESC";
                $query_run = mysqli_query($con, $query_notif);
                
                if(mysqli_num_rows($query_run) > 0)
                {
                    foreach($query_run as $holdlist)
                    {
                        $book_title = $holdlist['title'];
                        $accession_number = $holdlist['accession_number'];
                        $hold_id = $holdlist['hold_id'];
                        $hold_status = $holdlist['hold_status'];
                ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        Your hold book <b><?php echo $book_title; ?></b> (Accession Number: <?php echo $accession_number; ?>) is approved. Please go to the library and borrow it.
                    </div>
                    <?php if ($hold_status == 'Approved'): ?>
                    <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="hold_id" value="<?php echo $hold_id; ?>">
                        <button type="submit" name="mark_read" class="btn btn-transparent" style="border:none;"><i style="font-size:30px;border:none;" class="bi bi-check-circle-fill"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php
                    }
                }
                ?>

                <?php
                // Notifications for overdue books
                $borrow_query = mysqli_query($con, "SELECT borrow_book.borrow_book_id, book.title, book.accession_number, borrow_book.due_date, borrow_book.notification_status
                                                    FROM borrow_book
                                                    LEFT JOIN book ON borrow_book.book_id = book.book_id
                                                    WHERE borrowed_status = 'borrowed' AND notification_status = 'Unread' AND (user_id = '$name_hold' OR faculty_id = '$name_hold')
                                                    ORDER BY borrow_book_id DESC");
                
                if(mysqli_num_rows($borrow_query) > 0)
                {
                    while($borrow_row = mysqli_fetch_array($borrow_query))
                    {
                        $timezone = "Asia/Manila";
                        if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
                        $cur_date = date("Y-m-d H:i:s");
                        $due_date = $borrow_row['due_date'];
                        $curr_date = date('Y-m-d H:i:s', strtotime($cur_date));
                        $duee_date = date('Y-m-d H:i:s', strtotime($due_date . ' -1 day'));

                        if ($duee_date < $curr_date)
                        {
                ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        Please return this book <b><?php echo $borrow_row['title']; ?></b> (Accession Number: <?php echo $borrow_row['accession_number']; ?>) before <?php echo date("M d, Y", strtotime($borrow_row['due_date'])); ?>.
                    </div>
                    <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="borrow_id" value="<?php echo $borrow_row['borrow_book_id']; ?>">
                        <button type="submit" name="mark_overdue_read" class="btn btn-transparent" style="border:none;"><i style="font-size:30px;border:none;" class="bi bi-check-circle-fill"></i></button>
                    </form>
                </div>
                <?php
                        }
                    }
                }
                else
                {
                    // Uncomment the following line if you want to show a message when there are no notifications
                    // echo '<div class="alert alert-info text-center" role="alert">No Notifications</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/script.php');
include('message.php'); 
?>
