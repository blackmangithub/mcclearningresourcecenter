<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

?>


<main id="main" class="main">
     <div class="pagetitle">
          <h1>Web OPAC</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item active">Web OPAC</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-end">
                              <a href="web_opac_add" class="btn btn-primary"><i class="bi bi-journal-plus"></i>
                                   Upload Book</a>
                         </div>
                         <div class="card-body">
                              <div class="table-responsive mt-3">
                                   <table id="myDataTable" class="table table-bordered table-striped table-sm">
                                        <thead>
                                             <tr>
                                                  <th>Book Image</th>
                                                  <th>Title</th>
                                                  <th>Author</th>
                                                  <th>Copyright Date</th>
                                                  <th>Publisher</th>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $query = "SELECT * FROM web_opac";
                                             $query_run = mysqli_query($con, $query);
                                             
                                             if(mysqli_num_rows($query_run))
                                             {
                                                  foreach($query_run as $book)
                                                  {
                                                       ?>
                                             <tr>
                                                  <td>
                                                       <center>

                                                            <?php if($book['opac_image'] != ""): ?>
                                                            <img src="../uploads/ebook_img/<?=$book['opac_image'];?>"
                                                                 alt="" width="60px" height="60px">
                                                            <?php else: ?>
                                                            <img src="../uploads/ebook_img/book_image.jpg" alt=""
                                                                 width="60px" height="60px">
                                                            <?php endif; ?>
                                                       </center>
                                                  </td>
                                                  <td><?=$book['title'];?></td>
                                                  <td><?=$book['author'];?></td>
                                                  <td><?=$book['copyright_date'];?></td>
                                                  <td><?=$book['publisher'];?></td>
                                                  <td class=" justify-content-center">
                                                       <div class="btn-group" style="background: #DFF6FF;  ">
                                                            <!-- View Book Action-->
                                                            <a href="web_opac_view?id=<?=$book['web_opac_id']; ?>"
                                                                 name="view_book"
                                                                 class="viewweb_opacBtn btn btn-sm  border text-primary"
                                                                 data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                 title="View Book">
                                                                 <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                            <!-- Edit web_opac Action-->
                                                            <a href="web_opac_edit?id=<?= $book['web_opac_id']; ?>"
                                                                 name="update_book"
                                                                 class="btn btn-sm  border text-success"
                                                                 data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                 title="Edit Book">
                                                                 <i class="bi bi-pencil-fill"></i>
                                                            </a>
                                                            <!-- Delete web_opac Action-->
                                                            <form action="web_opac_code.php" method="POST">
                                                                 <button type="submit" name="delete_book"
                                                                      value="<?=$book['web_opac_id'];?>"
                                                                      class="btn btn-sm  border text-danger"
                                                                      data-bs-toggle="tooltip"
                                                                      data-bs-placement="bottom" title="Delete Book">
                                                                      <i class="bi bi-trash-fill"></i>
                                                                 </button>
                                                            </form>
                                                       </div>
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
<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
?>