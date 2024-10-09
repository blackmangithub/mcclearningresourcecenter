<?php
include('authentication.php');



// Delete Book
if(isset($_POST['delete_book']))
{
     $book_id = mysqli_real_escape_string($con, $_POST['delete_book']);

     $check_img_query = "SELECT * FROM web_opac WHERE web_opac_id ='$book_id'";
     $img_result = mysqli_query($con, $check_img_query);
     $result_data = mysqli_fetch_array($img_result);

     $opac_image = $result_data['opac_image'];
     $ebook = $result_data['ebook'];

     $query = "DELETE FROM web_opac WHERE web_opac_id ='$book_id'";
     $query_run = mysqli_query($con, $query);

     if($query_run)
     {
     
          if(file_exists('../uploads/ebook_img/'.$opac_image) || file_exists('../uploads/ebook/'.$ebook))
          {
               unlink("../uploads/ebook_img/".$opac_image);
               unlink("../uploads/ebook/".$ebook);
          }

          $_SESSION['status'] = 'Book Deleted successfully';
          $_SESSION['status_code'] = "success";
          header("Location: books");
          exit(0);
     }
     else
     {
          $_SESSION['status'] = 'Book not Deleted';
          $_SESSION['status_code'] = "error";
          header("Location: books");
          exit(0);
     }
}

// Update Book
if(isset($_POST['update_book']))
{
     $book_id =mysqli_real_escape_string($con, $_POST['web_opac_id']);

     $opac_title = mysqli_real_escape_string($con, $_POST['title']);
     $author = mysqli_real_escape_string($con, $_POST['author']);
     $copyright_date = mysqli_real_escape_string($con, $_POST['copyright_date']);
     $publisher = mysqli_real_escape_string($con, $_POST['publisher']);

     $old_opac_filename = $_POST['old_opac_image'];
     $old_ebook_filename = $_POST['old_ebook'];

     $opac_image = $_FILES['opac_image']['name'];
     $ebook = $_FILES['ebook']['name'];


     $update_opac_filename = "";
     $update_ebook_filename = "";
     if($opac_image != NULL || $ebook != NULL)
     {
           // Rename the Image
          $opac_extension = pathinfo($opac_image, PATHINFO_EXTENSION);
          $opac_filename = time().'.'.$opac_extension;
     
          $ebook_extension = pathinfo($ebook, PATHINFO_EXTENSION);
          $ebook_filename = time().'.'.$ebook_extension;

          $update_opac_filename =  $opac_filename;
          $update_ebook_filename =  $ebook_filename;
     }
     else
     {
          $update_opac_filename = $old_opac_filename;
          $update_ebook_filename = $old_ebook_filename;
     }

     $query = "UPDATE web_opac SET opac_image='$update_opac_filename', ebook='$update_ebook_filename', title='$opac_title',  author='$author', copyright_date='$copyright_date', publisher='$publisher' WHERE web_opac_id = '$book_id'";
     $query_run = mysqli_query($con, $query);

     if($query_run)
     {
          if($opac_image != NULL || $ebook != NULL)
          {
               if(file_exists('../uploads/ebook_img/'.$old_opac_filename) || file_exists('../uploads/ebook/'.$old_ebook_filename))
               {
                    unlink("../uploads/ebook_img/".$old_opac_filename);
                    unlink("../uploads/ebook/".$old_ebook_filename);
               }
          }
          move_uploaded_file($_FILES['opac_image']['tmp_name'], '../uploads/ebook_img/'.$opac_filename);
          move_uploaded_file($_FILES['ebook']['tmp_name'], '../uploads/ebook/'.$ebook_filename);

          $_SESSION['status'] = 'Book Updated successfully';
          $_SESSION['status_code'] = "success";
          header("Location: books");
          exit(0);
     }
     else
     {
          $_SESSION['status'] = 'Book not Updated';
          $_SESSION['status_code'] = "error";
          header("Location: books");
          exit(0);
     }
     
}

// Add Book
if(isset($_POST['upload_book']))
{
     
     $title = mysqli_real_escape_string($con, $_POST['title']);
     $author = mysqli_real_escape_string($con, $_POST['author']);
     $copyright_date = mysqli_real_escape_string($con, $_POST['copyright_date']);
     $publisher = mysqli_real_escape_string($con, $_POST['publisher']);
     $opac_image = $_FILES['opac_image']['name'];
     $ebook = $_FILES['ebook']['name'];


     if($opac_image != "" || $ebook != "")
     {
          
          // Rename the Image
          $ebook_extension = pathinfo($ebook, PATHINFO_EXTENSION);
          $ebook_filename = time().'.'.$ebook_extension;
     
          $opac_extension = pathinfo($opac_image, PATHINFO_EXTENSION);
          $opac_filename = time().'.'.$opac_extension;
     
         
     
          $query = "INSERT INTO web_opac (opac_image, ebook, title, author, copyright_date, publisher, added_at) VALUES ('$opac_filename', '$ebook_filename', '$title', '$author', '$copyright_date', '$publisher',  NOW())";
          $query_run = mysqli_query($con, $query);
     
         
          if($query_run)
          {
               move_uploaded_file($_FILES['opac_image']['tmp_name'], '../uploads/ebook_img/'.$opac_filename);
               move_uploaded_file($_FILES['ebook']['tmp_name'], '../uploads/ebook/'.$ebook_filename);
     
               $_SESSION['status'] = 'Book Added successfully';
               $_SESSION['status_code'] = "success";
               header("Location: books");
               exit(0);
          }
          else
          {
               $_SESSION['status'] = 'Book not Added';
               $_SESSION['status_code'] = "error";
               header("Location: books");
               exit(0);
          }
     }
     else
     {
          $query = "INSERT INTO web_opac (opac_image, ebook, title, author, copyright_date, publisher, added_at) VALUES ('$opac_image', '$ebook', '$title', '$author', '$copyright_date', '$publisher',  NOW())";
          $query_run = mysqli_query($con, $query);
     
         
          if($query_run)
          {
               move_uploaded_file($_FILES['opac_image']['tmp_name'], '../uploads/ebook_img/'.$_FILES['opac_image']['name']);
               move_uploaded_file($_FILES['ebook']['tmp_name'], '../uploads/ebook/'.$_FILES['ebook']['name']);
     
               $_SESSION['status'] = 'Book Added successfully';
               $_SESSION['status_code'] = "success";
               header("Location: books");
               exit(0);
          }
          else
          {
               $_SESSION['status'] = 'Book not Added';
               $_SESSION['status_code'] = "error";
               header("Location: books");
               exit(0);
          }
     }
    
     
}
?>