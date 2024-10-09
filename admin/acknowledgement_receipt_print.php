<?php 
include('authentication.php');

$student_id = $_GET['student_id'];
$book_ids = explode(',', $_GET['borrow_book_id']);

$user_query = $con->prepare("SELECT * FROM user WHERE student_id_no = ?");
$user_query->bind_param("s", $student_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user_row = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <meta name="robots" content="noindex, nofollow" />
     <link rel="icon" href="./assets/img/mcc-logo.png">
     <link href="https://fonts.gstatic.com" rel="preconnect" />
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i"
          rel="stylesheet" />
     <!-- Bootstrap CSS -->
     <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

     <!-- Boxicons Icon -->
     <link href="assets/css/boxicons.min.css" rel="stylesheet" />

     <!-- Remixicon Icon -->
     <link href="assets/css/remixicon.css" rel="stylesheet" />

     <!-- Bootstrap Icon -->
     <link rel="stylesheet" href="assets/font/bootstrap-icons.css">

     <!-- Alertify JS link -->
     <link rel="stylesheet" href="assets/css/alertify.min.css" />
     <link rel="stylesheet" href="assets/css/alertify.bootstraptheme.min.css" />
     <!-- Datatables -->
     <link rel="stylesheet" href="assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">

     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap5.min.css" />

     <!-- Custom CSS -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <!-- Animation -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />
     <!-- Loader -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />

     <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">
     
     <style>
         @media print {
             .print-button {
                 display: none;
             }
             #back {
                display: none;
            }
             @page {
                 margin: 0;
             }
             body {
                 margin: 0;
                 padding: 20px;
             }
         }
     </style>

</head>

<body>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                    <div class="card-body">
                    <div class="text-start mt-3">
                    <a href="circulation_returning?student_id=<?php echo $student_id; ?>" id="back" style="margin-left:20px;" class="btn btn-primary">Back</a>
                    </div>
                        <div class="text-end mt-5">
                            <h5>Date: <?php echo date('F d, Y'); ?></h5>
                        </div>
                        <div class="text-center mt-5">
                            <h4 style="font-weight:bold;">Return Slip</h4>
                        </div>
                        <div class="text-center mt-5">
                            <h5>This to acknowledge that <span style="font-weight: 700;"><?php echo $user_row['firstname'].' '.$user_row['middlename'].' '.$user_row['lastname']; ?></span>
                        <br>has returned the following books below:</h5>
                        </div>
                        <div class="table-responsive mt-5">
                            <table border="2" cellpadding="2" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="5" style="font-size:15px; font-weight:bold;text-align:center;" >BORROWED BOOK DETAILS</th>
                                </tr>
                                <tr>
                                <th style="font-size:15px;">ID</th>
                                    <th style="font-size:15px;">Title</th>
                                    <th style="font-size:15px;">Author</th>
                                    <th style="font-size:15px;">Date Borrowed</th>
                                    <th style="font-size:15px;">Due Date</th>
                                    <th style="font-size:15px;">Date Returned</th>
                                    <th style="font-size:15px;">Penalty</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach ($book_ids as $book_id) {
                                        $return_query = $con->prepare("
                                            SELECT book.title, book.author, borrow_book.date_borrowed, borrow_book.due_date, borrow_book.date_returned, borrow_book.book_penalty 
                                            FROM borrow_book 
                                            LEFT JOIN book ON borrow_book.book_id = book.book_id
                                            WHERE borrow_book.borrow_book_id = ? AND borrow_book.user_id = ?
                                        ");
                                        $return_query->bind_param("ii", $book_id, $user_row['user_id']);
                                        $return_query->execute();
                                        $return_result = $return_query->get_result();
                                        
                                        if ($return_result->num_rows > 0) {
                                            while ($return_row = $return_result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                    <td class="auto-id" style="text-align: center;"></td>
                                        <td><?php echo htmlspecialchars($return_row['title']); ?></td>
                                        <td style="text-transform: capitalize"><?php echo htmlspecialchars($return_row['author']); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($return_row['date_borrowed'])); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($return_row['due_date'])); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($return_row['date_returned'])); ?></td>
                                        <td><?php echo htmlspecialchars($return_row['book_penalty']);?></td>
                                    </tr>
                                    <?php 
                                            }
                                        } else {
                                            echo '<tr><td colspan="5" class="text-center">No return records found for this book</td></tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-5">
                            <div class="col text-end">
                                <p>__________________________</p>
                                <p style="margin-right: 50px;">Signature</p>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button onclick="window.print()" class="btn btn-primary print-button">Print</button>
                        </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
include('./includes/script.php');
include('./message.php');   
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
</script>
</body>
</html>
