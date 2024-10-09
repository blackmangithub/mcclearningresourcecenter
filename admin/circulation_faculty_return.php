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
                    <li class="breadcrumb-item active">Faculty/Staff Return Book</li>
               </ol>
          </nav>
     </div>
     <section class="section ">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header text-bg-primary">
                              <i class="bi bi-book"></i> Return Book
                         </div>
                         <div class="card-body">
                              <div class="row d-flex justify-content-center">
                                   <div class="col-12 col-md-4 mt-4">
                                        <form action="" method="GET">
                                             <div class="input-group mb-3 input-group-sm">
                                                  <input type="text" name="firstname"
                                                       value="<?php if(isset($_GET['firstname'])){echo $_GET['firstname'];}?>"
                                                       class="form-control" placeholder="Enter Faculty/Staff First Name"
                                                       aria-label="firstname" aria-describedby="basic-addon1" autofocus
                                                       required onblur="sanitizeInput(this)">
                                                  <button class="input-group-text bg-primary text-white"
                                                       id="basic-addon1">Search</button>
                                             </div>
                                        </form>
                                   </div>

                                   <?php
                                  if(isset($_GET['firstname']))
                                  {
                                   $firstname = $_GET['firstname'];

                                   $query = "SELECT * FROM faculty WHERE firstname='$firstname'";
                                   $query_run = mysqli_query($con, $query);

                                   if(mysqli_num_rows($query_run) > 0)
                                   {
                                        foreach($query_run as $row)
                                        {
                                             $firstname = $_GET['firstname'];
                                             echo ('<script>location.href="circulation_faculty_returning?firstname='.$firstname.'";</script>');
                                        }
                                   }
                                   else
                                   {
                                        $_SESSION['message_error'] = 'No Name Found';
                                        echo ('<script> location.href="circulation_borrow";</script>');
                                   }
                                  }
                                   ?>
                              </div>
                         </div>
                         <div class="card-footer"></div>
                    </div>
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-item-center">
                              <span class="text-dark fw-semibold">Recent Returned Books</span>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive">
                                   <?php
							$return_query= mysqli_query($con,"SELECT * from return_book 
							LEFT JOIN book ON return_book.book_id = book.book_id 
							LEFT JOIN faculty ON return_book.faculty_id = faculty.faculty_id 
							WHERE return_book.return_book_id ORDER BY return_book.return_book_id DESC");
								$return_count = mysqli_num_rows($return_query);
								
							$count_penalty = mysqli_query($con,"SELECT sum(book_penalty) FROM return_book ");
							$count_penalty_row = mysqli_fetch_array($count_penalty);
							?>

                                   <table id="myDataTable" cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped table-bordered">

                                        <thead>
                                             <tr>
                                                  <th>ID</th>
                                                  <th>Image</th>
                                                  <th>Barcode</th>
                                                  <th>Borrower Name</th>
                                                  <th>Title</th>
                                                  <th>Date Borrowed</th>
                                                  <th>Due Date</th>
                                                  <th>Date Returned</th>
                                                  <th>Penalty</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
							while ($return_row = mysqli_fetch_array($return_query)) {
								$id = $return_row['return_book_id'];
?>
                                             <?php if (isset($return_row['faculty_id'])) : ?>
                                             <tr>
                                             <td class="auto-id" style="text-align: center;"></td>
                                                  <td>
                                                       <center>
                                                            <?php if ($return_row['book_image'] != ""): ?>
                                                            <img src="../uploads/books_img/<?php echo $return_row['book_image']; ?>"
                                                                 alt="" width="80px" height="80px">
                                                            <?php else: ?>
                                                            <img src="../uploads/books_img/book_image.jpg" alt=""
                                                                 width="80px" height="80px">
                                                            <?php endif; ?>
                                                       </center>
                                                  </td>
                                                  <td><?php echo $return_row['barcode']; ?></td>
                                                  <td style="text-transform: capitalize">
                                                       <?php echo $return_row['firstname'] . " " . $return_row['middlename'] . " " . $return_row['lastname']; ?>
                                                  </td>
                                                  <td style="text-transform: capitalize">
                                                       <?php echo $return_row['title']; ?></td>
                                                  <td><?php echo date("M d, Y", strtotime($return_row['date_borrowed'])); ?>
                                                  </td>
                                                  <td><?php echo date("M d, Y", strtotime($return_row['due_date'])); ?>
                                                  </td>
                                                  <td><?php echo date("M d, Y", strtotime($return_row['date_returned'])); ?>
                                                  </td>
                                                  <td>
                                                       <?php if ($return_row['book_penalty'] != 'No Penalty'): ?>
                                                       <div>
                                                            ₱ <?php echo $return_row['book_penalty']; ?>.00
                                                       </div>
                                                       <?php else: ?>
                                                       <?php echo $return_row['book_penalty']; ?>
                                                       <?php endif; ?>
                                                  </td>
                                             </tr>
                                             <?php endif; ?>
                                             <?php 
							}
							if ($return_count <= 0){
								echo '
									<table style="float:right;">
										<tr>
											<td style="padding:10px;" class="alert alert-danger">No Books returned at this moment</td>
										</tr>
									</table>
								';
							} 							
							?>
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
