<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<style>
     #facultybadge {
          position: relative;
          top: -15px;
          left: 20px;
     }
</style>

<main id="main" class="main">
     <div class="pagetitle">
          <h1>Manage Faculty Staff</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="users">Users</a></li>
                    <li class="breadcrumb-item active">Faculty Staff</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-between align-items-center">
                              <a href="user_faculty_approval" class="btn btn-primary position-relative">
                                   <i class="bi bi-people-fill"></i>
                                   Faculty Approval
                                   <?php
                                   // Example query to count pending faculty approvals
                                   $sql = "SELECT COUNT(*) AS pending_faculty_count FROM faculty WHERE (role_as = 'faculty' OR role_as = 'staff') AND status = 'pending'";
                                   $result = mysqli_query($con, $sql);
                                   $row = mysqli_fetch_assoc($result);
                                   $pendingfacultyCount = $row['pending_faculty_count'];

                                   if ($pendingfacultyCount > 0) {
                                        echo '<span class="badge bg-danger" id="facultybadge">' . $pendingfacultyCount . '</span>';
                                   }
                                   ?>
                              </a>
                              <a href="users" class="btn btn-primary position-relative">Back</a>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive mt-3">
                                   <table id="myDataTable" class="table table-bordered table-striped table-sm">
                                        <thead>
                                             <tr>
                                                  <th>Full Name</th>
                                                  <th>Gender</th>
                                                  <th>Department</th>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $query = "SELECT * FROM faculty WHERE status IN ('approved', 'blocked') AND (role_as = 'faculty' OR role_as = 'staff') ORDER BY faculty_id ASC";
                                             $query_run = mysqli_query($con, $query);

                                             if(mysqli_num_rows($query_run)) {
                                                  foreach($query_run as $user) {
                                                       ?>
                                                       <tr>
                                                            <td style="text-transform:capitalize;"><?=$user['lastname'].',  '.$user['firstname'].' '.$user['middlename'];?></td>
                                                            <td><?=$user['gender'];?></td>
                                                            <td><?=$user['course'];?></td>
                                                            <td class="justify-content-center">
                                                                 <center>
                                                                 <div class="btn-group" style="background: #DFF6FF;">
                                                                      <button type="button" class="btn btn-sm border dropdown-toggle text-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                                                           <i class="bi bi-gear-fill"></i>
                                                                      </button>
                                                                      <ul class="dropdown-menu">
                                                                           <!-- View faculty Action -->
                                                                           <li><a href="user_faculty_view?id=<?=$user['faculty_id'];?>" class="dropdown-item text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Faculty">
                                                                                <i class="bi bi-eye-fill"></i> View
                                                                           </a></li>
                                                                           <!-- Edit faculty Action -->
                                                                           <li><a href="#" class="dropdown-item text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Faculty" onclick="loadFacultyData('<?=$user['faculty_id'];?>')">
                                                                                <i class="bi bi-pencil-fill"></i> Edit
                                                                           </a></li>
                                                                           <!-- Block/Unblock faculty Action -->
                                                                           <?php if($user['status'] == 'approved'): ?>
                                                                                <li><a href="#" class="dropdown-item text-warning" onclick="confirmBlock('<?=$user['faculty_id'];?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Block Faculty">
                                                                                     <i class="bi bi-lock-fill"></i> Block
                                                                                </a></li>
                                                                           <?php else: ?>
                                                                                <li><a href="#" class="dropdown-item text-success" onclick="confirmUnblock('<?=$user['faculty_id'];?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Unblock Faculty">
                                                                                     <i class="bi bi-unlock-fill"></i> Unblock
                                                                                </a></li>
                                                                           <?php endif; ?>
                                                                           <!-- Delete faculty Action -->
                                                                           <li><a href="#" class="dropdown-item text-danger" onclick="confirmDelete('<?=$user['faculty_id'];?>')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Faculty">
                                                                                <i class="bi bi-trash-fill"></i> Delete
                                                                           </a></li>
                                                                           <!-- Generate ID Card Action -->
                                                                           <li><a href="user_faculty_id?user_id=<?php echo $user['faculty_id']?>" target="_blank" class="dropdown-item text-info" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Generate Library ID">
                                                                                <i class="bi bi-card-heading"></i> Generate Library ID
                                                                           </a></li>
                                                                      </ul>
                                                                 </div>
                                                                 </center>
                                                            </td>
                                                       </tr>
                                                       <?php
                                                  }
                                             } else {
                                                  echo "No records found";
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
<!-- Edit Faculty Modal -->
<div class="modal fade" id="editFacultyModal" tabindex="-1" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFacultyModalLabel">Edit Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editFacultyForm" method="POST" action="user_faculty_code.php">
          <input type="hidden" name="edit_faculty_id" id="editFacultyId">
          <div class="mb-3">
            <label for="editLName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLName" name="edit_last_name" style="text-transform:capitalize;" required>
          </div>
          <div class="mb-3">
            <label for="editFName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFName" name="edit_first_name" style="text-transform:capitalize;" required>
          </div>
          <div class="mb-3">
            <label for="editMName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="editMName" name="edit_middle_name" style="text-transform:capitalize;">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Delete Faculty Modal -->
<div class="modal fade" id="deleteFacultyModal" tabindex="-1" aria-labelledby="deleteFacultyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteFacultyModalLabel">Delete Faculty</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="deleteFacultyForm" method="POST" action="user_faculty_code.php">
          <input type="hidden" value="<?= $user['user_id']; ?>" name="delete_faculty_id" id="deletefacultyId">
          <div class="mb-3">
          <label for="deleteReason" class="form-label">Reason for Delete</label>
          <textarea class="form-control" id="deleteReason" name="delete_reason" rows="4" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
?>

<script>
function confirmDelete(userId) {
     fetch('user_faculty_code.php?id=' + userId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('deleteFacultyId').value = data.faculty_id;

            var myModal = new bootstrap.Modal(document.getElementById('deleteFacultyModal'));
            myModal.show();
        })
        .catch(error => {
            console.error('Error fetching faculty data:', error);
        });
}

function loadStudentData(facultyId) {
    fetch('user_faculty_code.php?id=' + facultyId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editFacultyId').value = data.faculty_id;
            document.getElementById('editLName').value = data.lastname;
            document.getElementById('editFName').value = data.firstname;
            document.getElementById('editMName').value = data.middlename;

            var myModal = new bootstrap.Modal(document.getElementById('editFacultyModal'));
            myModal.show();
        })
        .catch(error => {
            console.error('Error fetching faculty data:', error);
        });
}

function confirmBlock(facultyId, status) {
    let action = status === 'approved' ? 'block' : 'unblock';
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to ${action} this faculty/staff!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes`
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the block/unblock
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'user_faculty_code.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = status === 'approved' ? 'block_faculty' : 'unblock_faculty';
            input.value = facultyId;

            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

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
