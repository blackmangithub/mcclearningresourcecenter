<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

if (isset($_SESSION['auth_admin']['admin_id'])) {
    $id_session = $_SESSION['auth_admin']['admin_id'];
}

$firstname = $_GET['firstname'];

$faculty_query = "SELECT * FROM faculty WHERE firstname = ?";
$faculty_stmt = $con->prepare($faculty_query);
$faculty_stmt->bind_param("s", $firstname);
$faculty_stmt->execute();
$faculty_result = $faculty_stmt->get_result();
$faculty_row = $faculty_result->fetch_assoc();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Circulation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item"><a href="circulation">Circulation</a></li>
                <li class="breadcrumb-item"><a href="circulation_faculty_return">Faculty/Staff Return Book</a></li>
                <li class="breadcrumb-item active">Return Book</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="col-12 col-md-6 mt-2">
                            <!-- Form to handle book return -->
                            <form method="post" action="" onsubmit="return validateForm()">
                                <input type="hidden" name="date_returned" value="<?php echo date('Y-m-d'); ?>" />
                                <input type="hidden" name="faculty_id" value="<?php echo $faculty_row['faculty_id']; ?>">
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($faculty_result->num_rows > 0) {
                        ?>
                        <div class="text-muted mt-3">Faculty/Staff Name&nbsp;: &nbsp;<span class="h5 text-primary p-0 m-0 text-uppercase fw-semibold"><?php echo $faculty_row['firstname'].' '.$faculty_row['middlename'].' '.$faculty_row['lastname']; ?></span></div>
                        <div class="text-muted">Department&emsp;&emsp;&emsp;&ensp;&nbsp;:&ensp;<span class="text-dark"><?php echo $faculty_row['course']; ?></span></div>
                        <?php
                        } else {
                            echo "No rows returned";
                        }
                        ?>

                        <div class="table-responsive">
                                <table class="table">
                                    <thead class="border-top border-dark border-opacity-25">
                                        <tr>
                                            <th>Select</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Copyright Date</th>
                                            <th>Publisher</th>
                                            <th>Barcode</th>
                                            <th>Date Borrowed</th>
                                            <th>Due Date</th>
                                            <th>Penalty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $borrow_query = "SELECT * FROM borrow_book LEFT JOIN book ON borrow_book.book_id = book.book_id WHERE faculty_id = ? AND borrowed_status = 'borrowed' ORDER BY borrow_book_id DESC";
                                        $borrow_stmt = $con->prepare($borrow_query);
                                        $borrow_stmt->bind_param("i", $faculty_row['faculty_id']);
                                        $borrow_stmt->execute();
                                        $borrow_result = $borrow_stmt->get_result();
                                        $borrow_count = $borrow_result->num_rows;

                                        while ($borrow_row = $borrow_result->fetch_assoc()) {
                                            $due_date = $borrow_row['due_date'];

                                            $timezone = "Asia/Manila";
                                            date_default_timezone_set($timezone);
                                            $cur_date = date("Y-m-d");
                                            $date_returned = date("Y-m-d");

                                            // Exclude Sundays from due date calculation
                                            $adjusted_due_date = strtotime($due_date);
                                            while (date('N', $adjusted_due_date) == 7) {
                                                $adjusted_due_date = strtotime("+1 day", $adjusted_due_date);
                                            }
                                            $due_date = date("Y-m-d", $adjusted_due_date);

                                            $penalty = 0;
                                            if ($date_returned > $due_date) {
                                                $current_date = strtotime($due_date);
                                                $end_date = strtotime($date_returned);

                                                while ($current_date < $end_date) {
                                                    $current_date = strtotime("+1 day", $current_date);
                                                    if (date('N', $current_date) != 7) {
                                                        $penalty += 5;
                                                    }
                                                }
                                            } else {
                                                $penalty = 'No Penalty';
                                            }
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_books[]" value="<?php echo $borrow_row['borrow_book_id']; ?>">
                                            </td>
                                            <td>
                                                <center>
                                                    <?php if ($borrow_row['book_image'] != ""): ?>
                                                    <img src="../uploads/books_img/<?php echo $borrow_row['book_image']; ?>" alt="" width="80px" height="80px">
                                                    <?php else: ?>
                                                    <img src="../uploads/books_img/book_image.jpg" alt="" width="80px" height="80px">
                                                    <?php endif; ?>
                                                </center>
                                            </td>
                                            <td><?php echo $borrow_row['title']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $borrow_row['author']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $borrow_row['copyright_date']; ?></td>
                                            <td><?php echo $borrow_row['publisher']; ?></td>
                                            <td><?php echo $borrow_row['barcode']; ?></td>
                                            <td><?php echo date("M d, Y ", strtotime($borrow_row['date_borrowed'])); ?></td>
                                            <td><?php echo date('M d, Y ', strtotime($borrow_row['due_date'])); ?></td>
                                            <td><?php echo $penalty; ?></td>
                                        </tr>
                                        <?php 
                                        }
                                        if ($borrow_count <= 0) {
                                            echo '
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td style="padding:10px;" class="alert alert-danger text-center">No books borrowed</td>
                                                    </tr>
                                                </table>
                                            ';
                                        } 
                                        ?>
                                    </tbody>
                                </table>
                                <div class="text-end">
                                <button type="submit" name="return_selected" class="btn btn-primary">Return Selected Books</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function validateForm() {
    const checkboxes = document.querySelectorAll('input[name="selected_books[]"]:checked');
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Books Selected',
            text: 'Please select at least one book to return.',
            confirmButtonText: 'OK'
        });
        return false;
    }
    return true;
}
</script>

<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('./message.php');   

if (isset($_POST['return_selected'])) {
    $faculty_id = $_POST['faculty_id'];
    $date_returned = $_POST['date_returned'];
    $selected_books = $_POST['selected_books'];
    $book_ids_str = implode(",", $selected_books);
    $borrow_query = "SELECT * FROM borrow_book WHERE faculty_id = ? AND borrowed_status = 'borrowed' AND borrow_book_id IN ($book_ids_str)";
    $borrow_stmt = $con->prepare($borrow_query);
    $borrow_stmt->bind_param("i", $faculty_id);
    $borrow_stmt->execute();
    $borrow_result = $borrow_stmt->get_result();

    $book_ids = [];
    while ($borrow_row = $borrow_result->fetch_assoc()) {
        $borrow_book_id = $borrow_row['borrow_book_id'];
        $book_id = $borrow_row['book_id'];
        $date_borrowed = $borrow_row['date_borrowed'];
        $due_date = $borrow_row['due_date'];

        $book_update_query = "UPDATE book SET status = 'Available' WHERE book_id = ?";
        $book_update_stmt = $con->prepare($book_update_query);
        $book_update_stmt->bind_param("i", $book_id);
        $book_update_stmt->execute();

        $timezone = "Asia/Manila";
        date_default_timezone_set($timezone);
        $cur_date = date("Y-m-d");
        $date_returned_now = date("Y-m-d");

        // Adjust due date to exclude Sundays
        $adjusted_due_date = strtotime($due_date);
        while (date('N', $adjusted_due_date) == 7) {
            $adjusted_due_date = strtotime("+1 day", $adjusted_due_date);
        }
        $due_date = date("Y-m-d", $adjusted_due_date);

        $penalty = 0;
        if ($date_returned > $due_date) {
            $current_date = strtotime($due_date);
            $end_date = strtotime($date_returned);

            while ($current_date < $end_date) {
                $current_date = strtotime("+1 day", $current_date);
                if (date('N', $current_date) != 7) {
                    $penalty += 5;
                }
            }
        } else {
            $penalty = 'No Penalty';
        }

        $borrow_update_query = "UPDATE borrow_book SET borrowed_status = 'returned', date_returned = ?, book_penalty = ? WHERE borrow_book_id = ? AND faculty_id = ? AND book_id = ?";
        $borrow_update_stmt = $con->prepare($borrow_update_query);
        $borrow_update_stmt->bind_param("siiii", $date_returned_now, $penalty, $borrow_book_id, $faculty_id, $book_id);
        $borrow_update_stmt->execute();

        $return_insert_query = "INSERT INTO return_book (faculty_id, book_id, date_borrowed, due_date, date_returned, book_penalty) VALUES (?, ?, ?, ?, ?, ?)";
        $return_insert_stmt = $con->prepare($return_insert_query);
        $return_insert_stmt->bind_param("iisssi", $faculty_id, $book_id, $date_borrowed, $due_date, $date_returned_now, $penalty);
        $return_insert_stmt->execute();

        $book_ids[] = $borrow_book_id;

        $report_history_query = "SELECT * FROM admin WHERE admin_id = ?";
        $report_history_stmt = $con->prepare($report_history_query);
        $report_history_stmt->bind_param("i", $id_session);
        $report_history_stmt->execute();
        $report_history_result = $report_history_stmt->get_result();
        $report_history_row1 = $report_history_result->fetch_assoc();
        $admin_row1 = $report_history_row1['firstname']." ".$report_history_row1['middlename']." ".$report_history_row1['lastname'];

        $report_insert_query = "INSERT INTO report (book_id, faculty_id, admin_name, detail_action, date_transaction) VALUES (?, ?, ?, 'Returned Book', NOW())";
        $report_insert_stmt = $con->prepare($report_insert_query);
        $report_insert_stmt->bind_param("iis", $book_id, $faculty_id, $admin_row1);
        $report_insert_stmt->execute();
    }

    if ($penalty === 'No Penalty') {
        echo '<script>location.href="return_faculty_slip?firstname='.$firstname.'&borrow_book_id='.implode(',', $book_ids).'";</script>';
    } else {
        echo '<script>location.href="acknowledgement_receipt_print_faculty?firstname='.$firstname.'&borrow_book_id='.implode(',', $book_ids).'";</script>';
    }
}
?>
