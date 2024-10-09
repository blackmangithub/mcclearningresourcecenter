<?php 
include('includes/header.php');
include('includes/navbar.php');
include('admin/config/dbcon.php');

if (!isset($_SESSION['auth'])) {
     header('Location: .');
     exit(0);
}

if($_SESSION['auth_role'] != "student" && $_SESSION['auth_role'] != "faculty" && $_SESSION['auth_role'] != "staff")
{
  header("Location:1");
  exit(0);
}
?>
<div class="container">
     <div class="row">
          <div class="col-md-12">
               <div class="card mt-4" data-aos="fade-up">
                    <div class="card-header">
                         <h4 class="text-muted">EBooks</h4>
                    </div>
                    <div class="card-body">
                         <div class="row row-cols-1 row-cols-md-3 g-2 mt-3">

                              <?php
                    $query = "SELECT * FROM web_opac";
                    $query_run = mysqli_query($con, $query);
                    
                    if(mysqli_num_rows($query_run) > 0)
                    {
                         foreach($query_run as $ebook)
                         {
                              ?>

                              <div class="col-md-3">
                                   <div class="card h-100 shadow">
                                        <a href="web_opac_view_pdf?id=<?=$ebook['web_opac_id']; ?>">
                                             <img src="uploads/ebook_img/<?=$ebook['opac_image'];?>"
                                                  class="card-img-top" alt="...">
                                             <!-- <div class="card-body">
                                             <h5 class="card-title fw-semibold text-uppercase"><?=$ebook['title'];?>
                                             </h5>
                                             <p class="card-text"><?=$ebook['author'];?></p>

                                        </div>
                                        <div class="card-footer">
                                             <form action="home_code" method="post">
                                                  <a href="web_opac_view_pdf?id=<?=$ebook['web_opac_id']; ?>"
                                                       name="viewpdf" class="btn text-white btn-info">Read
                                                       Book</a>
                                             </form>
                                        </div> -->
                                        </a>
                                   </div>
                              </div>



                              <?php
                         }
                    }
                    ?>


                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php
include('includes/footer.php');
include('includes/script.php');
include('message.php'); 
?>