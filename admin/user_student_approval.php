<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php');
?>

<main id="main" class="main">
     <div class="pagetitle d-flex justify-content-between">
          <div>
               <h1>Manage Users</h1>
               <nav>
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="users">Users</a></li>
                         <li class="breadcrumb-item"><a href="user_student">Students</a></li>
                         <li class="breadcrumb-item active">Student Approval</li>
                    </ol>
               </nav>
          </div>
     </div>
     
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="m-0 text-dark fw-semibold">Students Approval</h5>
                              <a href="user_student" class="btn btn-primary">Back</a>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive mt-3">
                                   <table id="myDataTable" class="table table-bordered table-striped table-sm">
                                        <thead>
                                             <tr>
                                                  <th><center>ID</center></th>
                                                  <th><center>Student Profile</center></th>
                                                  <th><center>Full Name</center></th>
                                                  <th><center>Student No</center></th>
                                                  <th><center>Course</center></th>
                                                  <th><center>Action</center></th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $query = "SELECT * FROM user WHERE status = 'pending' ORDER BY user_id ASC";
                                             $query_run = mysqli_query($con, $query);
                                             
                                             if(mysqli_num_rows($query_run))
                                             {
                                                  foreach($query_run as $user)
                                                  {
                                                       ?>
                                             <tr>
                                                  <td class="auto-id" style="text-align: center;"></td>
                                                  <td>
                                                       <center>
                                                            <?php if($user['profile_image'] != ""): ?>
                                                            <img src="../uploads/profile_images/<?php echo $user['profile_image']; ?>"
                                                                 alt="image" width="120px" height="100px">
                                                            <?php else: ?>
                                                            <img src="uploads/books_img/book_image.jpg" alt=""
                                                                 width="120px" height="100px">
                                                            <?php endif; ?>
                                                       </center>
                                                  </td>

                                                  <td style="text-transform: capitalize;">
                                                       <center>
                                                       <?=$user['firstname'].' '.$user['middlename'].' '.$user['lastname']?>
                                                       </center>
                                                  </td>
                                                  <td>
                                                       <center>
                                                       <?=$user['student_id_no'];?>
                                                       </center>
                                                  </td>
                                                  <td>
                                                       <center>
                                                       <?=$user['course'];?>
                                                       </center>
                                                  </td>

                                                  <td class="justify-content-center">
                                                       <center>
                                                       <form action="user_student_code.php" method="POST">
                                                            <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                                                            <input type="submit" name="approved" value="Approve" class="btn btn-success">
                                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#denyReasonModal" onclick="setUserId(<?= $user['user_id']; ?>)">Deny</button>
                                                       </form>
                                                       </center>
                                                  </td>
                                             </tr>
                                             <?php
                                                  }
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

<!-- Deny Reason Modal -->
<div class="modal fade" id="denyReasonModal" tabindex="-1" aria-labelledby="denyReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="denyReasonModalLabel">Reason for Denial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="denyForm" action="user_student_code.php" method="POST">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="mb-3">
                        <label for="deny_reason" class="form-label">Enter Reason for Denial</label>
                        <textarea class="form-control" id="deny_reason" name="deny_reason" rows="3" required></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="deny" class="btn btn-danger">Deny</button>
            </div>
                </form>
        </div>
    </div>
</div>

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

function setUserId(userId) {
    document.getElementById('user_id').value = userId;
}

</script>

<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
