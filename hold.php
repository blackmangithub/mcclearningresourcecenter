<?php
ob_start(); // Start output buffering
include('includes/header.php');
include('includes/navbar.php');
include('admin/config/dbcon.php');

if (!isset($_SESSION['auth'])) {
    header('Location: .');
    exit(0);
}

if ($_SESSION['auth_role'] != "student" && $_SESSION['auth_role'] != "faculty" && $_SESSION['auth_role'] != "staff") {
    header("Location:1");
    exit(0);
}
?>

<style>
     .center {
          text-align: center;
          margin-top: -20px;
          margin-bottom: 20px;
     }
</style>

<div class="container">
    <div class="row">
        <div class="col-xl-12">
            <div class="card mt-2" data-aos="zoom-in">
                <div class="card-body pt-3">
                    <div>
                        <div id="profile-overview">
                            <div class="row mt-3">

                                <?php
                                $user_id = $_SESSION['auth_stud']['stud_id'];
                                $role = $_SESSION['auth_role'];
                                $query = "SELECT * FROM holds 
                                          LEFT JOIN book ON holds.book_id = book.book_id
                                          WHERE (user_id = '$user_id' OR faculty_id = '$user_id') AND hold_status = 'Hold' 
                                          ORDER BY hold_id DESC";

                                $query_run = mysqli_query($con, $query);
                                $book_count = mysqli_num_rows($query_run); // Count the number of held books

                                // Define maximum number of books a user can hold
                                $max_books_hold = 5;

                                if ($book_count > 0) {
                                    echo '<h5 class="center">Hold books : ' . $book_count . ' / ' . $max_books_hold . '</h5>'; // Display the count
                                    foreach ($query_run as $hold) {
                                        $hold_book = $hold['hold_id'];
                                        $book_hold = $hold['book_id'];
                                ?>

                                <div class="col-lg-3 col-md-3 label text-center mb-3">
                                    <?php if ($hold['book_image'] != ""): ?>
                                    <img src="uploads/books_img/<?php echo htmlspecialchars($hold['book_image']); ?>" width="100px" alt="">
                                    <?php else: ?>
                                    <img src="uploads/books_img/book_image.jpg" alt="">
                                    <?php endif; ?>
                                </div>
                                <div class="col-lg-6 col-md-6 label">
                                    <div>
                                        <?= htmlspecialchars($hold['title'] . ' ' . $hold['copyright_date'] . ' by ' . $hold['author']); ?>
                                        <br>
                                        Accession No. <b><?= htmlspecialchars($hold['accession_number']); ?></b>
                                    </div>
                                    <div class="text-muted">
                                        <?= date("M d, Y", strtotime($hold['hold_date'])); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 label text-center mb-3">
                                    <form action="" method="POST">
                                        <button type="submit" value="<?= htmlspecialchars($hold['hold_id']); ?>"
                                            class="btn btn-danger" name="cancel_hold">
                                            Cancel
                                        </button>
                                    </form>
                                </div>

                                <?php
                                    }
                                } else {
                                    echo '<div class="col-lg-12 col-md-12">
                                              <div class="text-center">No held books</div>
                                          </div>';
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['cancel_hold'])) {
    $holdbook_id = mysqli_real_escape_string($con, $_POST['cancel_hold']);

    $query = "SELECT book_id FROM holds WHERE hold_id = '$holdbook_id'";
    $result = mysqli_query($con, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $book_id = $row['book_id'];

        mysqli_begin_transaction($con);

        $update_query = "UPDATE book SET status_hold = '' WHERE book_id = '$book_id'";
        $delete_query = "DELETE FROM holds WHERE hold_id = '$holdbook_id'";

        $update_result = mysqli_query($con, $update_query);
        $delete_result = mysqli_query($con, $delete_query);

        if ($update_result && $delete_result) {
            mysqli_commit($con);
            $_SESSION['status'] = "Book hold cancelled successfully";
            $_SESSION['status_code'] = "success";
            header("Location: hold");
            exit(0);
        } else {
            mysqli_rollback($con);
            $_SESSION['status'] = "There was something wrong";
            $_SESSION['status_code'] = "warning";
            header("Location: hold");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Hold not found";
        $_SESSION['status_code'] = "error";
        header("Location: hold");
        exit(0);
    }
}

include('includes/footer.php');
include('includes/script.php');
include('message.php');
ob_end_flush(); // End output buffering and flush output
?>
