<?php
include('authentication.php');
include('includes/header.php');
include('includes/sidebar.php'); 
?>
<?php 
if (isset($_SESSION['auth_admin']['admin_id']))
{
     $id_session=$_SESSION['auth_admin']['admin_id'];

 }
 ?>
<main id="main" class="main">
     <div class="pagetitle">
          <h1>Profile</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Profile</li>
               </ol>
          </nav>
     </div>
     <section class="section profile">
          <div class="row">
               <?php
               $query = "SELECT * FROM admin WHERE admin_id = ?";
               $stmt = $con->prepare($query);
               $stmt->bind_param("s", $id_session);
               $stmt->execute();
               $query_run = $stmt->get_result();
               
               if ($query_run->num_rows > 0) {
                   $row = $query_run->fetch_array(MYSQLI_ASSOC);
                   // Continue with your code to use $row...
               } else {
                   // Handle case where no admin is found
                   $_SESSION['status'] = 'Admin not found';
                   $_SESSION['status_code'] = "error";
                   header("Location: admin_profile");
                   exit(0);
               }
                
               ?>
               <div class="col-xl-4">
                    <div class="card">
                         <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                              <?php if($row['admin_image'] != ""): ?>
                              <img src="../uploads/admin_profile/<?php echo $row['admin_image']; ?>" alt=""
                                   class="rounded-circle">
                              <?php else: ?>
                              <img src="../assets/admin_profile/admin.png" alt="" class="rounded-circle">
                              <?php endif; ?>
                              <h2><?= $_SESSION['auth_admin']['admin_name']; ?></h2>
                              <h3>Admin</h3>

                         </div>
                    </div>
               </div>
               <div class="col-xl-8">
                    <div class="card">
                         <div class="card-body pt-3">
                              <ul class="nav nav-tabs nav-tabs-bordered">
                                   <li class="nav-item"> <button class="nav-link active text-info border-info"
                                             data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                   </li>

                              </ul>
                              <div class="tab-content pt-2">
                                   <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                        <h5 class="card-title">Profile Details</h5>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                             <div class="col-lg-9 col-md-8">
                                                  <?=$row['firstname'].' '.$row['middlename'].' '.$row['lastname'];?>
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Address</div>
                                             <div class="col-lg-9 col-md-8"><?=$row['address'];?></div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Phone</div>
                                             <div class="col-lg-9 col-md-8"><?=$row['phone_number'];?></div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Email</div>
                                             <div class="col-lg-9 col-md-8"><a href="/cdn-cgi/l/email-protection"
                                                       class="__cf_email__"
                                                       data-cfemail="e982c788878d8c9b9a8687a98c91888499858cc78a8684"><?=$row['email'];?></a>
                                             </div>
                                        </div>
                                   </div>



                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </section>
</main>
<?php
include('includes/footer.php');
include('./includes/script.php');
?>