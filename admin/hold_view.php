<?php
// Start output buffering
ob_start();

// Include necessary files
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

$id = $_GET['id'];
$type = $_GET['type'];

// Prepare and execute the query based on type
$query = "";
if ($type == "user") {
    $query = "SELECT * FROM user WHERE user_id = ?";
} else if ($type == "faculty") {
    $query = "SELECT * FROM faculty WHERE faculty_id = ?";
}

if ($query) {
    $user_stmt = $con->prepare($query);
    $user_stmt->bind_param("i", $id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_row = $user_result->fetch_assoc();
} else {
    // Handle invalid type
    $_SESSION['status'] = 'Invalid type parameter.';
    $_SESSION['status_code'] = 'error';
    header("Location: hold_list");
    exit(0);
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Hold Books</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item"><a href="hold_list">Hold Books</a></li>
                <li class="breadcrumb-item active">View Hold Books</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-bg-primary d-flex fw-semibold justify-content-between">
                        <h5 class="m-0 text-white fw-semibold">Recent Hold Books</h5>
                        <a href="hold_list" class="btn btn-success">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="myDataTable" cellpadding="0" cellspacing="0" border="0"
                                   class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Accession No.</th>
                                        <th>Hold Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $borrow_query = "
                                        SELECT holds.hold_id, holds.hold_date, 
                                               book.*, book.book_image, book.title, book.accession_number,
                                               user.user_id, faculty.faculty_id
                                        FROM holds
                                        LEFT JOIN book ON holds.book_id = book.book_id 
                                        LEFT JOIN user ON holds.user_id = user.user_id AND holds.hold_status = 'Hold'
                                        LEFT JOIN faculty ON holds.faculty_id = faculty.faculty_id AND holds.hold_status = 'Hold'
                                        WHERE holds.hold_status = 'Hold' AND (user.user_id = ? OR faculty.faculty_id = ?)
                                        ORDER BY holds.hold_id DESC
                                    ";
                                    
                                    $borrow_stmt = $con->prepare($borrow_query);
                                    $borrow_stmt->bind_param("ii", $id, $id);
                                    $borrow_stmt->execute();
                                    $borrow_result = $borrow_stmt->get_result();
                                    $borrow_count = $borrow_result->num_rows;
                                    while ($borrow_row = $borrow_result->fetch_assoc()) {
                                        $book_title = htmlspecialchars($borrow_row['title']);
                                        $hold_id = $borrow_row['hold_id']; // Added hold_id for action handling
                                    ?>
                                    <tr>
                                    <td class="auto-id" style="text-align: center;"></td>
                                        <td>
                                            <center>
                                                <?php if ($borrow_row['book_image'] != ""): ?>
                                                <img src="../uploads/books_img/<?php echo htmlspecialchars($borrow_row['book_image']); ?>" alt="" width="80px" height="80px">
                                                <?php else: ?>
                                                <img src="../uploads/books_img/book_image.jpg" alt="" width="80px" height="80px">
                                                <?php endif; ?>
                                            </center>
                                        </td>
                                        <td style="text-transform: capitalize"><?php echo $book_title; ?></td>
                                        <td><?php echo htmlspecialchars($borrow_row['accession_number']); ?></td>
                                        <td><?php echo date("M d, Y h:i:s a", strtotime($borrow_row['hold_date'])); ?></td>
                                        <td>
                                            <form action="" method="post" style="display: inline;">
                                                <input type="hidden" name="hold_id" value="<?php echo htmlspecialchars($hold_id); ?>">
                                                <button type="submit" class="btn btn-success" name="approved">Approved</button>
                                            </form>
                                            <form action="" method="post" style="display: inline;">
                                                <input type="hidden" name="hold_id" value="<?php echo htmlspecialchars($hold_id); ?>">
                                                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php if ($borrow_count <= 0) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                No Hold Books at this Moment
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
// Redirect or header operations
if (isset($_POST['approved'])) {
    $hold_id = mysqli_real_escape_string($con, $_POST['hold_id']);
    
    // Approve the hold
    $update_query = "UPDATE holds SET hold_status = 'Approved' WHERE hold_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("i", $hold_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        // Update book status
        $book_query = "UPDATE book SET status_hold = '' WHERE book_id = (SELECT book_id FROM holds WHERE hold_id = ?)";
        $book_stmt = $con->prepare($book_query);
        $book_stmt->bind_param("i", $hold_id);
        $book_stmt->execute();
        
        $_SESSION['status'] = "Hold has been approved.";
        $_SESSION['status_code'] = "success";
        header("Location: hold_list");
        ob_end_flush(); // Flush and turn off buffering
        exit(0);
    } else {
        $_SESSION['status'] = "Failed to approve the hold.";
        $_SESSION['status_code'] = "error";
        header("Location: hold_list");
        ob_end_flush(); // Flush and turn off buffering
        exit(0);
    }
}

if (isset($_POST['cancel'])) {
    $hold_id = mysqli_real_escape_string($con, $_POST['hold_id']);
    
    // Retrieve book_id before deleting the hold
    $book_query = "SELECT book_id FROM holds WHERE hold_id = ?";
    $book_stmt = $con->prepare($book_query);
    $book_stmt->bind_param("i", $hold_id);
    $book_stmt->execute();
    $book_result = $book_stmt->get_result();
    $book_row = $book_result->fetch_assoc();
    $book_id = $book_row['book_id'];
    
    // Cancel the hold
    $delete_query = "DELETE FROM holds WHERE hold_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $hold_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        // Update book status
        $book_update_query = "UPDATE book SET status_hold = '' WHERE book_id = ?";
        $book_update_stmt = $con->prepare($book_update_query);
        $book_update_stmt->bind_param("i", $book_id);
        $book_update_stmt->execute();
        
        $_SESSION['status'] = "Hold has been canceled.";
        $_SESSION['status_code'] = "success";
        header("Location: hold_list");
        ob_end_flush(); // Flush and turn off buffering
        exit(0);
    } else {
        $_SESSION['status'] = "Failed to cancel the hold.";
        $_SESSION['status_code'] = "error";
        header("Location: hold_list");
        ob_end_flush(); // Flush and turn off buffering
        exit(0);
    }
}
?>

<?php
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
?>

<script>
var select_box_element = document.querySelector('#select_box');
dselect(select_box_element, {
    search: true
});

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
</script>
