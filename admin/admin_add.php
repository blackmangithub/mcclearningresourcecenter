<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<style>
    .progress {
        background-color: #e9ecef;
    }
    .progress-bar {
        transition: width 0.4s ease;
    }
</style>

<main id="main" class="main">
     <div class="pagetitle">
          <h1>Add Admin</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin">Admin</a></li>
                    <li class="breadcrumb-item active">Add Admin</li>
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

                              <form action="admin_code.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

                                   <div class="row d-flex justify-content-center mt-5">

                                        <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <label for="">First Name</label>
                                                  <input type="text" id="firstname" name="firstname" class="form-control" required>
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <div class="d-flex justify-content-between">
                                                       <label for="">Middle Name</label>
                                                       <span class=" text-muted"><small>(Optional)</small></span>
                                                  </div>
                                                  <input type="text" id="middlename" name="middlename" class="form-control">
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Last Name</label>
                                                  <input type="text" id="lastname" name="lastname" class="form-control" required>
                                             </div>
                                        </div>

                                   </div>

                                   <div class="row d-flex justify-content-center">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Email</label>
                                                  <input type="email" id="email" name="email" class="form-control" required>
                                             </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Phone Number</label>
                                                  <input type="tel" id="phone_number" name="phone_number"
                                                       placeholder="09xxxxxxxxx" class="form-control format_number" maxlength="11" oninput="validatePhoneNumber()" required>
                                                  <small id="phone_warning" class="text-danger"></small>
                                             </div>
                                        </div>

                                   </div>

                                   <div class="row d-flex justify-content-center">

                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="">Address</label>
                                                  <input type="text" id="address" name="address" class="form-control" required>
                                             </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                             <div class="mb-3 mt-2">
                                                  <div class="d-flex justify-content-between">
                                                       <label for="">Profile Image</label>
                                                       <span class=" text-muted"><small>(Optional)</small></span>
                                                  </div>
                                                  <input type="file" id="admin_image" name="admin_image" class="form-control">
                                             </div>
                                        </div>

                                   </div>

                                   <div class="row d-flex justify-content-center">
                                        <div class="col-12 col-md-5">
                                             <div class="mb-3 mt-2">
                                                  <label for="password">Password</label>
                                                  <input type="password" id="password" name="password" class="form-control" style="margin-bottom: 5px;" minlength="8" required oninput="checkPasswordStrength()">
                                                  <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                                  <label class="form-check-label" for="showPassword">Show Password</label>
                                                  <small id="password_warning" class="text-danger"></small>
                                                  <div id="password_strength" class="progress mt-2" style="height: 5px; display: none;">
                                                       <div id="strength_bar" class="progress-bar" role="progressbar" style="width: 0;"></div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                             <div class="mb-3 mt-2">
                                                  <label for="admin_type">Admin Type</label>
                                                  <select id="admin_type" name="admin_type" class="form-control" required>
                                                  <option value="" id="optionLabel" disabled selected>--Select Type--</option>
                                                       <option value="Admin">Admin</option>
                                                       <option value="Staff">Staff</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                         </div>
                         <div class="card-footer d-flex justify-content-end">
                              <div>
                                   <a href="admin" class="btn btn-secondary">Cancel</a>
                                   <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                              </div>
                         </div>
                         </form>

                    </div>
               </div>
          </div>
     </section>
</main>
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}

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

 // Function to check for XSS tags
 function checkForXSS(input) {
        const xssPattern = /<[^>]*>/;
        if (xssPattern.test(input)) {
            Swal.fire({
                title: 'Invalid Input!',
                text: 'XSS tags are not allowed.',
                icon: 'error',
                confirmButtonText: 'Okay'
               }).then(() => {
                // Clear the input fields
                document.getElementById('firstname').value = '';
                document.getElementById('middlename').value = '';
                document.getElementById('lastname').value = '';

                // Optionally, refresh the page after clearing the inputs
                location.reload();
            });
            return true; // Return true if XSS is found
        }
        return false; // Return false if no XSS
    }

    // Attach event listeners to input fields
    document.getElementById('firstname').addEventListener('input', function() {
        checkForXSS(this.value);
    });

    document.getElementById('middlename').addEventListener('input', function() {
        checkForXSS(this.value);
    });

    document.getElementById('lastname').addEventListener('input', function() {
        checkForXSS(this.value);
    });

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

    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('strength_bar');
        const passwordStrength = document.getElementById('password_strength');
        const warning = document.getElementById('password_warning');

        // Show the progress bar when typing
        passwordStrength.style.display = 'block';

        let strength = 0;

        // Check password strength criteria
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++; // special characters

        // Update the progress bar width and color based on strength
        switch (strength) {
            case 0:
                strengthBar.style.width = '0%';
                strengthBar.className = 'progress-bar bg-danger';
                warning.textContent = '';
                break;
            case 1:
                strengthBar.style.width = '25%';
                strengthBar.className = 'progress-bar bg-danger';
                warning.textContent = 'Very Weak';
                break;
            case 2:
                strengthBar.style.width = '50%';
                strengthBar.className = 'progress-bar bg-warning';
                warning.textContent = 'Weak';
                break;
            case 3:
                strengthBar.style.width = '75%';
                strengthBar.className = 'progress-bar bg-info';
                warning.textContent = 'Moderate';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.className = 'progress-bar bg-success';
                warning.textContent = 'Strong';
                break;
            default:
                strengthBar.style.width = '0%';
                strengthBar.className = 'progress-bar bg-danger';
                warning.textContent = '';
        }
    }

    function validatePasswordStrength() {
        const password = document.getElementById('password').value;
        let strength = 0;

        // Check password strength criteria
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++; // special characters

        // Show SweetAlert if the password is not strong
        if (strength < 4 && password.length > 0) {
            Swal.fire({
                title: 'Weak Password!',
                text: 'Your password must be stronger. Include uppercase letters, lowercase letters, numbers, and special characters.',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
        }
    }

    // Add event listener for the password input
    document.getElementById('password').addEventListener('blur', validatePasswordStrength);
</script>
<?php 
include('./includes/footer.php');
include('includes/script.php');
include('../message.php');
?>
