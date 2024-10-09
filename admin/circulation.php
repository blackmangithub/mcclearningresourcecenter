<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

?>


<main id="main" class="main" data-aos="fade-down">
     <div class="pagetitle">
          <h1>Circulation</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item active">Circulation</li>
               </ol>
          </nav>
     </div>
     <section class="section ">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card ">
                         <div class="card-header">
                              <div class="h4 text-dark fw-semibold">Student</div>
                         </div>
                         <div class="card-body">
                              <div class="row">

                                   <div class="col-12 col-md-6 mt-3">
                                        <a href="circulation_borrow">
                                             <div
                                                  class="card bg-primary text-white p-4 d-flex flex-row justify-content-between">
                                                  <h2>Borrow Book</h2>
                                                  <i class="bi bi-book fs-1"></i>
                                             </div>
                                        </a>
                                   </div>

                                   <div class="col-12 col-md-6 mt-3">
                                        <a href="circulation_return">
                                             <div
                                                  class="card bg-primary text-white p-4 d-flex flex-row justify-content-between">
                                                  <h2>Return Book</h2>
                                                  <i class="bi bi-book fs-1"></i>
                                             </div>
                                        </a>
                                   </div>

                              </div>
                         </div>
                    </div>
                    <div class="card ">
                         <div class="card-header">
                              <div class="h4 text-dark fw-semibold">Faculty Staff</div>
                         </div>
                         <div class="card-body">
                              <div class="row">

                                   <div class="col-12 col-md-6 mt-3">
                                        <a href="circulation_faculty_borrow">
                                             <div
                                                  class="card bg-primary text-white p-4 d-flex flex-row justify-content-between">
                                                  <h2>Borrow Book</h2>
                                                  <i class="bi bi-book fs-1"></i>
                                             </div>
                                        </a>
                                   </div>

                                   <div class="col-12 col-md-6 mt-3">
                                        <a href="circulation_faculty_return">
                                             <div
                                                  class="card bg-primary text-white p-4 d-flex flex-row justify-content-between">
                                                  <h2>Return Book</h2>
                                                  <i class="bi bi-book fs-1"></i>
                                             </div>
                                        </a>
                                   </div>

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