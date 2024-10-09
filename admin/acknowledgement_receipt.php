<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

$student_id = $_GET['student_id'];

$user_query = mysqli_query($con,"SELECT * FROM user WHERE student_id_no = '$student_id' ");
$user_row = mysqli_fetch_array($user_query);
?>



<main id="main" class="main">
     <div class="pagetitle">
          <h1>Circulation</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item"><a href="circulation">Circulation</a></li>
                    <li class="breadcrumb-item active"><a href="circulation_return">Return Book</a></li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row justify-content-center">
               <div class="col-lg-9">
                    <div class="card">
                         <div class="card-header d-flex justify-content-center
                         ">
                              <!-- <img src="assets/img/mcc_header.png" alt=""> -->
                         </div>

                         <div class="card-body">

                              <?php 
                              
                                  $return_query= mysqli_query($con,"SELECT * from return_book 
                                  LEFT JOIN book ON return_book.book_id = book.book_id 
                                  LEFT JOIN user ON return_book.user_id = user.user_id 
                                  where return_book.return_book_id order by return_book.return_book_id DESC");
                                       $return_count = mysqli_num_rows($return_query);
                                       
                                  $count_penalty = mysqli_query($con,"SELECT sum(book_penalty) FROM return_book ");
                                  $count_penalty_row = mysqli_fetch_array($count_penalty);
                                  
                                  $return_row= mysqli_fetch_array ($return_query);
							$id=$return_row['return_book_id'];
                            
                             ?>
                              <h6 class="text-center fs-5 mt-5">This to Acknowledge that <span
                                        class="text-dark fw-bold">
                                        <?php echo $user_row['firstname'].' '.$user_row['middlename'].' '.$user_row['lastname']?></span>
                                   <h6 class="text-center fs-5 mb-4"> has paid the amount of Php<span
                                             class="text-dark fw-bold"> <?php echo $return_row['book_penalty'] ?></span>
                                        for the penalty.
                                   </h6>



                              </h6>
                              <h6 class="my-3 text-dark fw-bold ">Borrowed Books Details</h6>
                              <div class="table-responsive">
                                   <form></form>
                                   <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                             <tr>
                                                  <th>ID</th>
                                                  <th>Title</th>
                                                  <th>Author</th>
                                                  <th>Copyright Date</th>
                                                  <th>Publisher</th>
                                                  <th>Date Borrowed</th>
                                                  <th>Due Date</th>
                                                  <th>Date Returned</th>
                                                  <th>Penalty</th>


                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
							
?>
                                             <tr>
                                             <td class="auto-id" style="text-align: center;"></td>
                                                  <td style="text-transform: capitalize">
                                                       <?php echo $return_row['title']; ?></td>
                                                  <td><?php echo $return_row['author']; ?></td>
                                                  <td><?php echo $return_row['copyright_date']; ?></td>
                                                  <td><?php echo $return_row['publisher']; ?></td>


                                                  <!---	<td style="text-transform: capitalize"><?php // echo $return_row['author']; ?></td>
								<td><?php // echo $return_row['isbn']; ?></td>	-->
                                                  <td><?php echo date("M d, Y h:m:s a",strtotime($return_row['date_borrowed'])); ?>
                                                  </td>
                                                  <?php
								 if ($return_row['book_penalty'] != 'No Penalty'){
									 echo "<td class='' style='width:100px;'>".date("M d, Y",strtotime($return_row['due_date']))."</td>";
								 }else {
									 echo "<td>".date("M d, Y",strtotime($return_row['due_date']))."</td>";
								 }
								
								?>
                                                  <?php
								 if ($return_row['book_penalty'] != 'No Penalty'){
									 echo "<td class='' style='width:100px;'>".date("M d, Y h:m:s a",strtotime($return_row['date_returned']))."</td>";
								 }else {
									 echo "<td>".date("M d, Y h:m:s a",strtotime($return_row['date_returned']))."</td>";
								 }
								
								?>
                                                  <?php
								 if ($return_row['book_penalty'] != 'No Penalty'){
									 echo "<td class='alert alert-warning' style='width:100px;'>Php ".$return_row['book_penalty'].".00</td>";
								 }else {
									 echo "<td>".$return_row['book_penalty']."</td>";
								 }
								
								?>
                                             </tr>

                                             <?php 
							
													
							?>

                                        </tbody>
                                   </table>
                              </div>
                         </div>
                         <div class="card-footer">


                         </div>

                         <form action="" method="POST" class="btn-group m-2">
                              <button type="submit" name="accept" class="btn btn-primary"
                                   value="<?php echo $student_id ?>">Accept</button>
                              <!-- <button type="submit" name="cancel" class="btn btn-secondary"
                                   value="<?php echo $student_id ?>">Cancel</button> -->

                              <a href="circulation_returning?student_id=<?php echo $student_id ?>"
                                   class="btn btn-secondary">Cancel</a>
                         </form>

                    </div>
               </div>
     </section>
</main>
<?php
 if(isset($_POST['accept']))
 {
     echo '<script> location.href="acknowledgement_receipt_print?student_id='.$student_id.'";</script>';  
 }
 
 
 


?>
<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
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