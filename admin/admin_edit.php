<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>
<main id="main" class="main">
     <div class="pagetitle">
          <h1>Edit Admin</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin">Admin</a></li>
                    <li class="breadcrumb-item active">Edit Admin</li>
               </ol>
          </nav>
     </div>
     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header d-flex justify-content-end">
                              <a href="admin" class="btn btn-primary">Back</a>
                         </div>
                         <div class="card-body">
                              <?php
                              if(isset($_GET['id']))
                              {
                                   $admin_id = mysqli_real_escape_string($con, $_GET['id']);

                                   $query = "SELECT * FROM admin WHERE admin_id ='$admin_id'"; 
                                   $query_run = mysqli_query($con, $query);

                                   if(mysqli_num_rows($query_run) > 0)
                                   {
                                       $admin = mysqli_fetch_array($query_run);
                                        ?>
                              <form action="admin_code.php" method="POST" enctype="multipart/form-data" onsubmit="return validatePhoneNumber()">

                                   <div class="row d-flex justify-content-center mt-5">
                                        <input type="hidden" name="admin_id" value="<?=$admin['admin_id']?>">

                                        <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <label for="firstname">Firstname</label>
                                                  <input type="text" id="firstname" value="<?=$admin['firstname'];?>" name="firstname" class="form-control" autocomplete="off" required>
                                             </div>
                                             </div>

                                             <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <label for="middlename">Middlename</label>
                                                  <input type="text" id="middlename" value="<?=$admin['middlename'];?>" name="middlename" class="form-control" autocomplete="off" required>
                                             </div>
                                             </div>

                                             <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <label for="lastname">Lastname</label>
                                                  <input type="text" id="lastname" value="<?=$admin['lastname'];?>" name="lastname" class="form-control" autocomplete="off" required>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="row d-flex justify-content-center">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Address</label>
                                                  <input type="text" id="address" value="<?=$admin['address'];?>" name="address"
                                                       class="form-control" autocomplete="off">
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Phone Number</label>
                                                  <input type="tel"
                                                       value="<?=$admin['phone_number'];?>" id="phone_number" name="phone_number"
                                                       placeholder="09xxxxxxxxx" id="phone_number"
                                                       class="form-control format_number" autocomplete="off"
                                                       maxlength="11" oninput="validatePhoneNumber()">
                                             </div>
                                        </div>

                                   </div>

                                   <div class="row d-flex justify-content-center">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Email</label>
                                                  <input type="email" value="<?=$admin['email'];?>" name="email"
                                                       class="form-control" autocomplete="off" id="email" readonly>
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Profile Image</label>
                                                  <input type="hidden" name="old_admin_image"
                                                       value="<?=$admin['admin_image'];?>">
                                                  <input type="file" name="admin_image" class="form-control"
                                                       autocomplete="off" id="admin_image">
                                             </div>
                                        </div>
                                   </div>

                                   <div class="row d-flex justify-content-center">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="admin_type">Admin Type</label>
                                                  <select id="admin_type" name="admin_type" class="form-control" required>
                                                       <option value="" disabled>--Select Type--</option>
                                                       <option value="Admin" <?= $admin['admin_type'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                                       <option value="Staff" <?= $admin['admin_type'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>

                         </div>
                         <div class="card-footer d-flex justify-content-end">
                              <div>
                                   <a href="admin" class="btn btn-secondary">Cancel</a>
                                   <button type="submit" name="edit_admin" class="btn btn-primary">Update</button>
                              </div>
                         </div>
                         </form>
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
<script>
 function validateNameInput(inputField) {
        const value = inputField.value;
        const xssPattern = /<[^>]*>/; // Regex pattern to detect HTML tags

        // Check for XSS tags
        if (xssPattern.test(value)) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'Your name cannot contain HTML tags.',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
            inputField.value = ''; // Clear the input field
            inputField.focus(); // Refocus on the input field
        }
    }

    // Attach event listeners to the input fields
    document.getElementById('firstname').addEventListener('input', function() {
        validateNameInput(this);
    });
    document.getElementById('middlename').addEventListener('input', function() {
        validateNameInput(this);
    });
    document.getElementById('lastname').addEventListener('input', function() {
        validateNameInput(this);
    });

    function validatePhoneNumber() {
        const phoneInput = document.getElementById('phone_number');
        const warning = document.getElementById('phone_warning');

        // Remove non-numeric characters
        const cleanedInput = phoneInput.value.replace(/\D/g, '');

        // Check if the input starts with "09"
        if (cleanedInput.length > 09 && !cleanedInput.startsWith('09')) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'Phone number must start with "09".',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                phoneInput.value = ''; // Clear the input
            });
            return;
        } else {
            warning.textContent = ''; // Clear the warning if valid
        }

        // Ensure the maximum length of 11 digits
        if (cleanedInput.length > 11) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'Phone number cannot exceed 11 digits.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                phoneInput.value = cleanedInput.substring(0, 11); // Trim to 11 digits
            });
            return;
        }

        // Set the cleaned input back to the input field
        phoneInput.value = cleanedInput;
    }

function validateAddress() {
        const addressInput = document.getElementById('address').value;
        const addressPattern = /^[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*[A-Za-z\s]+$/; // Regex for "City, Municipality, Province"

        if (!addressPattern.test(addressInput)) {
            Swal.fire({
                title: 'Invalid Address Format!',
                text: 'Please enter the address in the format: "Patao, Bantayan, Cebu".',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                document.getElementById('address').value = ''; // Clear the input
            });
        }
        // No success message for valid addresses
    }

    // Attach event listener to the address input field for the blur event
    document.getElementById('address').addEventListener('blur', validateAddress);

    function validatePhoneNumber() {
        const phoneInput = document.getElementById('phone_number');
        const warning = document.getElementById('phone_warning');

        // Remove non-numeric characters
        const cleanedInput = phoneInput.value.replace(/\D/g, '');

        // Check if the input starts with "09"
        if (cleanedInput.length > 09 && !cleanedInput.startsWith('09')) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'Phone number must start with "09".',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                phoneInput.value = ''; // Clear the input
            });
            return;
        } else {
            warning.textContent = ''; // Clear the warning if valid
        }

        // Ensure the maximum length of 11 digits
        if (cleanedInput.length > 11) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'Phone number cannot exceed 11 digits.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                phoneInput.value = cleanedInput.substring(0, 11); // Trim to 11 digits
            });
            return;
        }

        // Set the cleaned input back to the input field
        phoneInput.value = cleanedInput;
    }

     // Function to check for XSS tags and email domain
     function validateEmail(input) {
        const xssPattern = /<[^>]*>/;
        const validDomain = /@mcclawis\.edu\.ph$/; // Regex for the valid domain

        // Check for XSS tags
        if (xssPattern.test(input)) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'XSS tags are not allowed.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                // Clear the input field
                document.getElementById('email').value = '';
            });
            return true; // Return true if XSS is found
        }

        // Check for valid email domain
        if (!validDomain.test(input)) {
            Swal.fire({
                title: 'Invalid Email!',
                text: 'Please use an email address ending with @mcclawis.edu.ph.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                // Clear the input field
                document.getElementById('email').value = '';
            });
            return true; // Return true if invalid email
        }

        return false; // Return false if everything is valid
    }

    // Attach event listener to the email input field
    document.getElementById('email').addEventListener('input', function() {
        validateEmail(this.value);
    });

    function validateImage() {
        const fileInput = document.getElementById('admin_image');
        const filePath = fileInput.value;
        const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

        // Check if the file extension is valid
        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                title: 'Invalid File Format!',
                text: 'Please upload an image in JPEG, JPG, or PNG format.',
                icon: 'error',
                confirmButtonText: 'Okay'
            }).then(() => {
                fileInput.value = ''; // Clear the input
            });
        }
    }
    document.getElementById('admin_image').addEventListener('blur', validateImage);
</script>
<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');
?>
