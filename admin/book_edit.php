<?php 
include('authentication.php');
include('./includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<main id="main" class="main">
     <div class="pagetitle">
          <h1>Edit Book</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="books">Book Collection</a></li>
                    <li class="breadcrumb-item active">Edit Book</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-end">
                         </div>
                         <div class="card-body">
                              <?php
                              if(isset($_GET['title']) || isset($_GET['copyright_date']))
                              {
                                   $book_title = mysqli_real_escape_string($con, $_GET['title']);
                                   $copyright_date = mysqli_real_escape_string($con, $_GET['copyright_date']);

                                   $query = "SELECT * FROM book LEFT JOIN category ON book.category_id = category.category_id WHERE title='$book_title' AND copyright_date='$copyright_date'"; 
                                   $query_run = mysqli_query($con, $query);

                                   if(mysqli_num_rows($query_run) > 0)
                                   {
                                        $book = mysqli_fetch_array($query_run);
                              ?>
                              <form id="editBookForm" action="books_code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                                   <div class="row d-flex justify-content-center mt-2">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Title</label>
                                                  <input type="text" name="title" value="<?=$book['title'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Author</label>
                                                  <input type="text" name="author" value="<?=$book['author'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row d-flex justify-content-center">
                                   <div class="col-12 col-md-5">
                                        <div class="mb-2 input-group-sm">
                                             <label for="copyright_date">Copyright Year</label>
                                             <input type="text" id="copyright_date"
                                                       name="copyright_date"
                                                       value="<?= $book['copyright_date']; ?>"
                                                       class="form-control"
                                                       autocomplete="off"
                                                       pattern="\d{4}"
                                                       required onblur="sanitizeInput(this)">
                                        </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Publisher</label>
                                                  <input type="text" name="publisher" value="<?=$book['publisher'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row d-flex justify-content-center">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">ISBN</label>
                                                  <input type="text" name="isbn" value="<?=$book['isbn'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Place of Publication</label>
                                                  <input type="text" name="place_publication" value="<?=$book['place_publication'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row d-flex justify-content-center">
                                        <input type="hidden" name="old_title" value="<?=$book['title']?>">
                                        <input type="hidden" name="old_copyright_date" value="<?=$book['copyright_date']?>">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Call Number</label>
                                                  <input type="text" name="call_number" id="book_call_number_edit" value="<?=$book['call_number'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="">Image</label>
                                                  <input type="hidden" name="old_book_image" value="<?=$book['book_image'];?>">
                                                  <input type="file" name="book_image" class="form-control" autocomplete="off" accept=".jpg,.jpeg,.png" onchange="validateImage(this)">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row d-flex justify-content-center">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-2 input-group-sm">
                                                  <label for="subject">Subject/s</label>
                                                  <input type="text" id="subject" name="subject" value="<?=$book['subject'];?>" class="form-control mb-2" onblur="sanitizeInput(this)">
                                                  <input type="text" id="subject" name="subject1" value="<?=$book['subject1'];?>" class="form-control mb-2" onblur="sanitizeInput(this)">
                                                  <input type="text" id="subject" name="subject2" value="<?=$book['subject2'];?>" class="form-control" onblur="sanitizeInput(this)">
                                             </div>
                                        </div>
                                   </div>
                                   </div>
                                   <div class="card-footer d-flex justify-content-end">
                                        <div>
                                             <a href="books.php" class="btn btn-secondary">Cancel</a>
                                             <button type="submit" name="update_book" class="btn btn-primary">Update Book</button>
                                        </div>
                                   </div>
                              </form>
                              <?php 
                                   }
                                   else
                                   {
                                        echo "No such book found";
                                   }
                              }
                              ?>
                         </div>
                    </div>
               </div>
          </div>
     </section>
</main>

<!-- Include SweetAlert CSS and JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');
?>

<script>
    $(document).ready(function() {
        // Restrict input to numeric values only
        $('#copyright_date').on('keypress', function(event) {
            var key = String.fromCharCode(event.which);
            if (!/[0-9]/.test(key)) {
                event.preventDefault();
            }
        });
    });

    // Ensure the year is not greater than the current year
    function validateForm() {
        var inputYear = parseInt($('#copyright_date').val(), 10);
        var currentYear = new Date().getFullYear();
        if (inputYear > currentYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Year',
                text: 'Year cannot be greater than the current year.',
                confirmButtonText: 'OK'
            });
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    function sanitizeInput(element) {
    const sanitizedValue = element.value.replace(/<\/?[^>]+(>|$)/g, "");
    element.value = sanitizedValue;
}

function validateImage(input) {
    const file = input.files[0];
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    
    if (file) {
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file type! Please upload an image file (JPG, JPEG, or PNG).');
            input.value = ''; // Clear the input
        }
    }
}
</script>
