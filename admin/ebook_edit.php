<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

?>
<main id="main" class="main">
     <div class="pagetitle">
          <h1>Edit Book</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item"><a href="books">Ebooks</a></li>
                    <li class="breadcrumb-item active">Edit Book</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row ">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-end">

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
                              <form action="ebooks_code.php" method="POST" enctype="multipart/form-data" class="">

                                   <div class="row d-flex justify-content-center mt-5">
                                        <input type="hidden" name="web_opac_id" value="<?=$book['web_opac_id'];?>">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Title</label>
                                                  <input type="text" name="title" value="<?=$book['title'];?>" class="
                                                       form-control">
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Author</label>
                                                  <input type="text" name="author" value="<?=$book['author'];?>" class="
                                                       form-control">
                                             </div>
                                        </div>



                                   </div>

                                   <div class="row d-flex justify-content-center">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Copyright Date</label>
                                                  <input type="text" name="copyright_date"
                                                       value="<?=$book['copyright_date'];?>" class=" form-control">
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Publisher</label>
                                                  <input type="text" name="publisher" value="<?=$book['publisher'];?>"
                                                       class=" form-control">
                                             </div>
                                        </div>



                                   </div>
                                   <div class="row d-flex justify-content-center">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">EBook</label>
                                                  <input type="hidden" name="old_ebook" value="<?=$book['ebook'];?>">
                                                  <input type="file" name="ebook" class="form-control">
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Image Display</label>
                                                  <input type="hidden" name="old_opac_image"
                                                       value="<?=$book['opac_image'];?>">
                                                  <input type="file" name="opac_image" class="form-control">
                                             </div>
                                        </div>
                                   </div>




                         </div>
                         <div class="card-footer d-flex justify-content-end">
                              <div>
                                   <a href="books" class="btn btn-secondary">Cancel</a>
                                   <button type="submit" name="update_book" class="btn btn-primary">Update Book</button>
                              </div>
                         </div>
                         </form>
                         <div class="card-footer"></div>
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