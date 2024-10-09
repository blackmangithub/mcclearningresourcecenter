<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Circulation</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item"><a href="circulation">Circulation</a></li>
                <li class="breadcrumb-item active">Faculty Staff Borrow Book</li>
            </ol>
        </nav>
    </div>
    <section class="section ">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-bg-primary">
                        <i class="bi bi-book"></i> Borrow Book
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-md-4 mt-4">
                                <form action="" method="GET">
                                    <div class="input-group mb-3 input-group-sm">
                                        <input type="text" name="firstname" value="<?php if(isset($_GET['firstname'])){echo $_GET['firstname'];}?>" class="form-control" placeholder="Enter Faculty/Staff First Name" aria-label="Firstname" aria-describedby="basic-addon1" autofocus required onblur="sanitizeInput(this)">
                                        <button type="submit" class="input-group-text bg-primary text-white" id="basic-addon1">Search</button>
                                    </div>
                                </form>
                            </div>

                            <?php
                            if(isset($_GET['firstname'])) {
                                $firstname = mysqli_real_escape_string($con, $_GET['firstname']);

                                $query = "SELECT * FROM faculty WHERE firstname='$firstname'";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0) {
                                    foreach($query_run as $row) {
                                        // Redirect to the borrowing page with the employee_id parameter
                                        $firstname = $row['firstname'];
                                        echo ('<script> location.href="circulation_faculty_borrowing?firstname='.$firstname.'";</script>');
                                    }
                                } else {
                                    echo ('<script> location.href="circulation_faculty_borrow";</script>');
                                    $_SESSION['message_error'] = 'No Faculty Found';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
                <div class="card">
                    <div class="card-header text-dark fw-semibold">
                        Recent Borrowed Books
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="myDataTable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                                <thead>
                                    <tr>
                                        <th>Acc #</th>
                                        <th>Image</th>
                                        <th>Barcode</th>
                                        <th>Borrower Name</th>
                                        <th>Title</th>
                                        <th>Date Borrowed</th>
                                        <th>Due Date</th>
                                        <th>Date Returned</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $borrow_query = mysqli_query($con,"SELECT * FROM borrow_book LEFT JOIN book ON borrow_book.book_id = book.book_id LEFT JOIN faculty ON borrow_book.faculty_id = faculty.faculty_id WHERE borrowed_status = 'borrowed' ORDER BY borrow_book.borrow_book_id DESC");
                                    $borrow_count = mysqli_num_rows($borrow_query);
                                    while($borrow_row = mysqli_fetch_array($borrow_query)){
                                        $id = $borrow_row ['borrow_book_id'];
                                        $book_id = $borrow_row ['book_id'];
                                        $faculty_id = $borrow_row ['faculty_id'];
                                    ?>
                                    <?php
                                    if(isset($faculty_id))
                                    {
                                    ?>
                                    <tr>
                                    <td><?php echo $borrow_row['accession_number']; ?></td>
                                        <td>
                                            <center>
                                                <?php if($borrow_row['book_image'] != ""): ?>
                                                <img src="../uploads/books_img/<?php echo $borrow_row['book_image']; ?>" alt="" width="80px" height="80px">
                                                <?php else: ?>
                                                <img src="../uploads/books_img/book_image.jpg" alt="" width="80px" height="80px">
                                                <?php endif; ?>
                                            </center>
                                        </td>
                                        <td><?php echo $borrow_row['barcode']; ?></td>
                                        <td style="text-transform: capitalize"><?php echo $borrow_row['firstname']." ".$borrow_row['lastname']; ?></td>
                                        <td style="text-transform: capitalize"><?php echo $borrow_row['title']; ?></td>
                                        <td><?php echo date("M d, Y h:i:s a",strtotime($borrow_row['date_borrowed'])); ?></td>
                                        <td><?php echo date("M d, Y h:i:s a",strtotime($borrow_row['due_date'])); ?></td>
                                        <td><?php echo ($borrow_row['date_returned'] == "0000-00-00 00:00:00") ? "Pending" : date("M d, Y h:m:s a",strtotime($borrow_row['date_returned'])); ?></td>
                                        <?php
                                        if ($borrow_row['borrowed_status'] != 'returned') {
                                            echo "<td class='alert alert-success' style='text-transform: capitalize'>".$borrow_row['borrowed_status']."</td>";
                                        } else {
                                            echo "<td  class='alert alert-danger' style='text-transform: capitalize'>".$borrow_row['borrowed_status']."</td>";
                                        }
                                        ?>
                                    </tr>
                                    <?php }  } 
                                    if ($borrow_count <= 0){
                                        echo '
                                            <table style="float:right;">
                                                <tr>
                                                    <td style="padding:10px;" class="alert alert-danger">No Books Borrowed at this Moment</td>
                                                </tr>
                                            </table>
                                        ';
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </section>
</main>

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

function sanitizeInput(element) {
    const sanitizedValue = element.value.replace(/<\/?[^>]+(>|$)/g, "");
    element.value = sanitizedValue;
}
</script>
