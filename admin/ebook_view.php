<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

?>

<main id="main" class="main">
     <div class="pagetitle">
          <h1>View Book</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item"><a href="books">Ebooks</a></li>
                    <li class="breadcrumb-item active">View Book</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-end">
                              <a href="books" class="btn btn-primary">
                                   Back
                              </a>
                         </div>
                         <div class="card-body">
                              <?php
                              if(isset($_GET['id']))
                              {
                                   $book_id = mysqli_real_escape_string($con, $_GET['id']);

                                   $query = "SELECT * FROM web_opac WHERE web_opac_id ='$book_id'"; 
                                   $query_run = mysqli_query($con, $query);

                                   if(mysqli_num_rows($query_run) > 0)
                                   {
                                       $book = mysqli_fetch_array($query_run);
                                        ?>

                              <div class="col-12">
                                   <div class="row mt-4">
                                        <div class="col-md-5 d-flex align-items-center justify-content-center my-4">


                                             <?php if($book['opac_image'] != ""): ?>
                                             <img src="../uploads/ebook_img/<?=$book['opac_image'];?>" alt=""
                                                  width="250px" height="250px">
                                             <?php else: ?>
                                             <img src="../uploads/ebooks_img/book_image.jpg" alt="" width="200px"
                                                  height="200px">
                                             <?php endif; ?>


                                        </div>
                                        <div class="col-md-7 mt-3">
                                             <div class="col">
                                                  <span class=" fw-semibold">Title
                                                  </span>
                                                  <p class="">:&nbsp;<?=$book['title'];?></p>
                                             </div>

                                             <div class="col">
                                                  <span class="fw-semibold">Author</span>
                                                  <p>:&nbsp;<?=$book['author'];?></p>
                                             </div>



                                             <div class="col">
                                                  <span class="fw-semibold">Copyright Date
                                                  </span>
                                                  <p class="">:&nbsp;<?=$book['copyright_date'];?>
                                                  </p>
                                             </div>

                                             <div class="col">
                                                  <span class="fw-semibold">Publisher </span>
                                                  <p class="">:&nbsp;<?=$book['publisher'];?></p>
                                             </div>

                                        </div>

                                   </div>
                              </div>

                         </div>
                         <div class="card-footer d-flex justify-content-end">


                              <?php
                              }
                              else
                              {
                                   echo "No such ID found";
                              }

                         }  
                         ?>
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