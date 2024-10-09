<?php
include('authentication.php');
include('includes/header.php');
include('includes/sidebar.php'); 
?>
<main id="main" class="main">
     <div class="pagetitle d-flex align-items-center justify-content-between">
          <div class="">
               <h1>View Faculty and Staff</h1>
               <nav>
                    <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="users">Users</a></li>
                         <li class="breadcrumb-item"><a href="user_faculty">Faculty & Staff</a></li>
                         <li class="breadcrumb-item active">View Faculty & Staff</li>
                    </ol>

               </nav>
          </div>
          <div>
               <a href="user_faculty" class="btn btn-primary">Back</a>
          </div>

     </div>
     <section class="section profile">
          <div class="row">
               <?php
               if(isset($_GET['id']))
               {
                    $faculty_id = mysqli_real_escape_string($con, $_GET['id']);

               $query = "SELECT * FROM faculty WHERE faculty_id = '$faculty_id'";
               $query_run = mysqli_query($con, $query);
                
               if(mysqli_num_rows($query_run) > 0)
               {
                    $user = mysqli_fetch_array($query_run);
                    ?>


               <div class="col-xl-4">
                    <div class="card">
                         <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                         <center>
                                   <?php if($user['qr_code'] != ""): ?>
                                   <img src="../qrcodes/<?php echo $user['qr_code']; ?>" alt=""
                                        width="150px" height="150px">
                                   <?php else: ?>
                                   <img src="assets/img/admin.png" class="rounded-circle" alt="" width="250px"
                                        height="250px">
                                   <?php endif; ?>
                              </center>

                              <h2><?=$user['firstname'].' '.$user['lastname'];?></h2>
                              <h3 style="text-transform: uppercase;"><?=$user['role_as'];?></h3>

                         </div>
                    </div>
                    <div class="card">
                         <div class="card-body profile-card pt-3 d-flex flex-column ">
                              <hr class="text-info">
                              <div class="label"><span>Department</span>
                                   &nbsp;&nbsp;&nbsp;&emsp;&emsp;<?=$user['course'];?></div>

                              <hr class="text-info">
                         </div>
                    </div>
               </div>
               <div class=" col-xl-8">
                    <div class="card">
                         <div class="card-body pt-3">
                              <ul class="nav nav-tabs nav-tabs-bordered border-info">
                                   <li class="nav-item"> <button
                                             class="nav-link active text-info border-info fw-semibold"
                                             data-bs-toggle="tab" data-bs-target="#profile-overview">Profile
                                             Details</button>
                                   </li>

                              </ul>
                              <div class="tab-content pt-2">
                                   <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                        <!-- <h5 class="card-title">Profile Details</h5> -->
                                        <div class="row mt-3">
                                             <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                             <div class="col-lg-9 col-md-8" style="text-transform:capitalize;">
                                                  <?=$user['firstname'].' '.$user['middlename'].' '.$user['lastname'];?>
                                             </div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Gender</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['gender'];?></div>
                                        </div>

                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Birthdate</div>
                                             <div class="col-lg-9 col-md-8">
                                                  <?= date("M d, Y",strtotime($user['birthdate'])); ?>
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Address</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['address'];?></div>
                                        </div>

                                        <!-- <hr>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Course</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['course'];?></div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Year Level</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['year_level'];?></div>
                                        </div> -->




                                   </div>



                              </div>
                              <ul class="nav nav-tabs nav-tabs-bordered border-info">
                                   <li class="nav-item"> <button
                                             class="nav-link active text-info border-info fw-semibold"
                                             data-bs-toggle="tab" data-bs-target="#profile-overview">Contact
                                             Details</button>
                                   </li>

                              </ul>
                              <div class="tab-content pt-2">
                                   <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                        <!-- <h5 class="card-title">Profile Details</h5> -->
                                        <div class="row mt-3">
                                             <div class="col-lg-3 col-md-4 label">Phone Number</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['cell_no'];?></div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Email</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['email'];?>
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Emergency Contact Person</div>
                                             <div class="col-lg-9 col-md-8" style="text-transform:capitalize;"><?=$user['contact_person'];?>
                                             </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-lg-3 col-md-4 label">Emergency Contact Number Person</div>
                                             <div class="col-lg-9 col-md-8"><?=$user['person_cell_no'];?>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <?php
                              }
                              else
                              {
                                   echo "No such ID found";
                              }

                         }  
                         ?>
     </section>
</main>
<?php
include('includes/footer.php');
include('./includes/script.php');
?>