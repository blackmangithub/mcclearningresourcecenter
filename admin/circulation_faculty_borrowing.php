<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

error_reporting(0);

if (isset($_SESSION['auth_admin']['admin_id'])) {
     $id_session = $_SESSION['auth_admin']['admin_id'];
}
$firstname = $_GET['firstname'];

$faculty_query = mysqli_query($con, "SELECT * FROM faculty WHERE firstname = '$firstname'");
$faculty_row = mysqli_fetch_array($faculty_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Add SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Circulation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item"><a href="circulation">Circulation</a></li>
                <li class="breadcrumb-item"><a href="circulation_faculty_borrow">Faculty Staff Borrow Book</a></li>
                <li class="breadcrumb-item active">Borrow Book</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="col-12 col-md-6 mt-2">
                            <form action="" method="POST">
                                <div class="input-group mb-3 input-group-sm">
                                    <span class="input-group-text bg-primary text-white" id="basic-addon1">ACCESSION NO.</span>
                                    <input type="text" name="barcode" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1" autofocus required onblur="sanitizeInput(this)">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $query = "SELECT * FROM faculty WHERE firstname ='$firstname'";
                        $query_run = mysqli_query($con, $query);

                        if ($query_run) {
                            $row = mysqli_fetch_array($query_run);
                        ?>
                        <div class="text-muted mt-3">Faculty/Staff Name: <span class="h5 text-primary p-0 m-0 text-uppercase fw-semibold"><?php echo htmlspecialchars($row['firstname'].' '.$row['middlename'].' '.$row['lastname']); ?></span></div>
                        <div class="text-muted mb-5">Department: <span class="text-dark"><?php echo htmlspecialchars($row['course']); ?></span></div>
                        <?php
                        } else {
                            echo "No rows returned";
                        }
                        ?>

                        <div class="table-responsive">
                            <table class="table">
                                <form method="POST" action="">
                                    <thead class="border-top border-dark border-opacity-25">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Copyright Date</th>
                                            <th>Publisher</th>
                                            <th>Copy</th>
                                            <th>Barcode</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <?php 
                                        if (isset($_POST['barcode'])) {
                                            $barcode = mysqli_real_escape_string($con, $_POST['barcode']);
                                            $book_query = mysqli_query($con, "SELECT * FROM book WHERE accession_number = '$barcode' AND status = 'Available'");
                                            $book_row = mysqli_fetch_array($book_query);

                                            if (!$book_row) {
                                                echo '<tr><td class="alert alert-info" colspan="8">No match for the barcode entered or the book is unavailable!</td></tr>';
                                            } elseif (empty($barcode)) {
                                                echo '<tr><td class="alert alert-info" colspan="8">Enter the correct details!</td></tr>';
                                            } else {
                                        ?>
                                        <tr>
                                            <input type="hidden" name="faculty_id" value="<?php echo htmlspecialchars($faculty_row['faculty_id']); ?>">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_row['book_id']); ?>">
                                            <td class="auto-id" style="text-align: center;"></td>
                                            <td>
                                                <center>
                                                    <?php if (!empty($book_row['book_image'])): ?>
                                                        <img src="../uploads/books_img/<?php echo htmlspecialchars($book_row['book_image']); ?>" alt="" width="80px" height="80px">
                                                    <?php else: ?>
                                                        <img src="../uploads/books_img/book_image.jpg" alt="" width="80px" height="80px">
                                                    <?php endif; ?>
                                                </center>
                                            </td>
                                            <td><?php echo htmlspecialchars($book_row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($book_row['author']); ?></td>
                                            <td><?php echo htmlspecialchars($book_row['copyright_date']); ?></td>
                                            <td><?php echo htmlspecialchars($book_row['publisher']); ?></td>
                                            <td><?php echo htmlspecialchars($book_row['copy']); ?></td>
                                            <td><?php echo htmlspecialchars($book_row['barcode']); ?></td>
                                            <td><button type="submit" name="borrow" class="btn btn-primary"><i class="fa fa-check"></i> Borrow</button></td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                    <?php
                                    $allowable_days_query = mysqli_query($con, "SELECT * FROM allowed_days WHERE allowed_days_id = 1");
                                    $allowable_days_row = mysqli_fetch_assoc($allowable_days_query);

                                    $timezone = "Asia/Manila";
                                    if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
                                    $cur_date = date("Y-m-d");
                                    $date_borrowed = date("Y-m-d");
                                    $due_date = strtotime($cur_date);
                                    $due_date = strtotime("+".$allowable_days_row['no_of_days']." day", $due_date);
                                    $due_date = date('Y-m-d', $due_date);
                                    ?>
                                    <input type="hidden" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>" />
                                    <input type="hidden" name="date_borrowed" value="<?php echo htmlspecialchars($date_borrowed); ?>" />

                                    <?php 
                                    if (isset($_POST['borrow'])) {
                                        $faculty_id = mysqli_real_escape_string($con, $_POST['faculty_id']);
                                        $book_id = mysqli_real_escape_string($con, $_POST['book_id']);
                                        $date_borrowed = mysqli_real_escape_string($con, $_POST['date_borrowed']);
                                        $due_date = mysqli_real_escape_string($con, $_POST['due_date']);

                                        $category_query = mysqli_query($con, "SELECT title, category_id FROM book WHERE book_id = $book_id");
                                        $category_data = mysqli_fetch_assoc($category_query);
                                        $book_title = $category_data['title'];
                                        $category_id = $category_data['category_id'];

                                        if ($category_id == 5) {
                                            $due_date = date('Y-m-d', strtotime($date_borrowed . ' + 7 days'));
                                        } elseif ($category_id == 1 || $category_id == 2) {
                                            $due_date = date('Y-m-d', strtotime($date_borrowed . ' + 3 days'));
                                        }

                                        $title_check_query = mysqli_query($con, "SELECT COUNT(*) AS title_count FROM borrow_book 
                                             INNER JOIN book ON borrow_book.book_id = book.book_id 
                                             WHERE borrow_book.faculty_id = '$faculty_id' 
                                             AND book.title = '$book_title' 
                                             AND borrow_book.borrowed_status = 'borrowed'");
                                        $title_count = mysqli_fetch_assoc($title_check_query);

                                        if ($title_count['title_count'] > 0) {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: 'You have already borrowed this book!',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    window.location = 'circulation_faculty_borrowing?firstname=" . urlencode($firstname) . "';
                                                });
                                            </script>";
                                        } else {
                                            $trapBookCount = mysqli_query($con, "SELECT count(*) AS books_allowed FROM borrow_book WHERE faculty_id = '$faculty_id' AND borrowed_status = 'borrowed'");
                                            $countBorrowed = mysqli_fetch_assoc($trapBookCount);

                                            $bookCountQuery = mysqli_query($con, "SELECT count(*) AS book_count FROM borrow_book WHERE faculty_id = '$faculty_id' AND borrowed_status = 'borrowed' AND book_id = $book_id");
                                            $bookCount = mysqli_fetch_assoc($bookCountQuery);

                                            $allowed_book_query = mysqli_query($con, "SELECT * FROM allowed_book WHERE allowed_book_id = 2");
                                            $allowed = mysqli_fetch_assoc($allowed_book_query);

                                            if ($countBorrowed['books_allowed'] == $allowed['qntty_books']) {
                                                echo "<script>
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Limit Reached',
                                                        text: 'You are allowed up to " . $allowed['qntty_books'] . " books!',
                                                        confirmButtonText: 'OK'
                                                    }).then(() => {
                                                        window.location = 'circulation_faculty_borrowing?firstname=" . urlencode($firstname) . "';
                                                    });
                                                </script>";
                                            } elseif ($bookCount['book_count'] == 1) {
                                                echo "<script>
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Already Borrowed',
                                                        text: 'This book has already been borrowed!',
                                                        confirmButtonText: 'OK'
                                                    }).then(() => {
                                                        window.location = 'circulation_faculty_borrowing?firstname=" . urlencode($firstname) . "';
                                                    });
                                                </script>";
                                            } else {
                                                mysqli_query($con, "UPDATE book SET status = 'Unavailable' WHERE book_id = '$book_id'");
                                                mysqli_query($con, "INSERT INTO borrow_book(faculty_id, book_id, date_borrowed, due_date, borrowed_status) VALUES ('$faculty_id', '$book_id', '$date_borrowed', '$due_date', 'borrowed')");

                                                $report_history = mysqli_query($con, "SELECT * FROM admin WHERE admin_id = $id_session");
                                                $report_history_row = mysqli_fetch_array($report_history);
                                                $admin_row = htmlspecialchars($report_history_row['firstname'] . " " . $report_history_row['middlename'] . " " . $report_history_row['lastname']);    

                                                mysqli_query($con, "INSERT INTO report (book_id, faculty_id, admin_name, detail_action, date_transaction) VALUES ('$book_id', '$faculty_id', '$admin_row', 'Borrowed Book', NOW())");

                                                echo "<script>
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success',
                                                        text: 'Book Borrowed Successfully',
                                                        confirmButtonText: 'OK'
                                                    }).then(() => {
                                                        window.location = 'circulation_faculty_borrowing?firstname=" . urlencode($firstname) . "';
                                                    });
                                                </script>";
                                            }
                                        }
                                    }
                                    ?>
                                </form>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('message.php');   
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
     // Add auto-increment ID to Books Table
     let booksTable = document.querySelector('#myDataTable tbody');
     let bookRows = booksTable.querySelectorAll('tr');
     bookRows.forEach((row, index) => {
          row.querySelector('.auto-id').textContent = index + 1;
     });

     // Add auto-increment ID to Ebooks Table
     let ebooksTable = document.querySelector('#myDataTable2 tbody');
     let ebookRows = ebooksTable.querySelectorAll('tr');
     ebookRows.forEach((row, index) => {
          row.querySelector('.auto-id').textContent = index + 1;
     });
});

function sanitizeInput(element) {
    const sanitizedValue = element.value.replace(/<\/?[^>]+(>|$)/g, "");
    element.value = sanitizedValue;
}
</script>
</body>
</html>
