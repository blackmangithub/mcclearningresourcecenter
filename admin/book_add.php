<?php 
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add Book</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="books">Book Collection</a></li>
                <li class="breadcrumb-item active">Add Book</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end"></div>
                    <div class="card-body">
                        <form id="addBookForm" action="books_code.php" method="POST" enctype="multipart/form-data" onsubmit="return checkDuplicateAccessionNumbers()">
                            <div class="row d-flex justify-content-center mt-5">
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="title">Title</label>
                                        <input type="text" id="title_search" class="form-control" placeholder="Search for a title" oninput="searchTitles()" autocomplete="off" onblur="sanitizeInput(this)">
                                        <div id="title_results" class="search-results"></div>
                                        <input type="text" name="title" id="title" class="form-control mt-2" placeholder="Or type a new title" required onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="author">Author</label>
                                        <input type="text" name="author" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="isbn">ISBN</label>
                                        <input type="text" name="isbn" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="publisher">Publisher</label>
                                        <input type="text" name="publisher" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="copyright_date">Copyright Year</label>
                                        <input type="text" id="copyright_date" name="copyright_date" onblur="sanitizeInput(this)" class="form-control" autocomplete="off" pattern="\d{4}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="place_publication">Place of Publication</label>
                                        <input type="text" name="place_publication" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <input type="hidden" name="new_barcode" value="<?= $new_barcode ?>">
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="call_number">Call Number</label>
                                        <input type="text" name="call_number" id="book_call_number" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="copy">Copy</label>
                                        <input type="number" name="copy" id="copy" class="form-control" min="1" required onchange="generateAccessionFields()">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-10">
                                    <div class="mb-2 input-group-sm">
                                        <label for="accession_numbers">Accession Numbers</label>
                                        <div id="accession_numbers_container"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <label for="lrc_location">LRC Location</label>
                                        <?php
                                        $category = "SELECT * FROM category";
                                        $category_run = mysqli_query($con, $category);
                                        if(mysqli_num_rows($category_run) > 0) {
                                        ?>
                                        <select name="lrc_location" id="lrc_location" class="form-control">
                                            <option value=""></option>
                                            <?php
                                            foreach($category_run as $category_item) {
                                            ?>
                                            <option value="<?= $category_item['category_id'] ?>">
                                                <?= $category_item['classname'] ?>
                                            </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        }
                                        ?>
                                        <br>
                                        <label for="subject">Subject/s</label>
                                        <input type="text" name="subject" class="form-control mb-2" onblur="sanitizeInput(this)">
                                        <input type="text" name="subject1" class="form-control mb-2" onblur="sanitizeInput(this)">
                                        <input type="text" name="subject2" class="form-control" onblur="sanitizeInput(this)">
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="mb-2 input-group-sm">
                                        <div class="d-flex justify-content-between">
                                            <label for="book_image">Book Image</label>
                                            <span class="text-muted"><small>(Optional)</small></span>
                                        </div>
                                        <input type="file" name="book_image" id="book_image_input" class="form-control">
                                        <input type="text" id="book_image_name" class="form-control mt-2" readonly>
                                        <!-- Hidden input to hold the existing image filename -->
                                        <input type="hidden" name="existing_image" id="existing_image">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <div>
                                    <a href="books.php" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
                                </div>
                            </div>
                        </form>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function sanitizeInput(value) {
    // Remove any HTML tags
    return value.replace(/<\/?[^>]+(>|$)/g, "");
}

function searchTitles() {
    const query = sanitizeInput(document.getElementById('title_search').value);
    const resultsContainer = document.getElementById('title_results');

    if (query.length < 3) {
        resultsContainer.innerHTML = '';
        return;
    }

    $.ajax({
        url: 'search_titles.php',
        type: 'POST',
        data: { query: query },
        success: function(response) {
            const data = JSON.parse(response);
            resultsContainer.innerHTML = data.results.map(book => `
                <div class="search-result" onclick="selectTitle('${book.title}', '${book.author}', '${book.copyright_date}', '${book.publisher}', '${book.isbn}', '${book.place_publication}', '${book.call_number}', '${book.category_id}', '${book.book_image}', '${book.subject}', '${book.subject1}', '${book.subject2}')">
                    ${book.title}
                </div>
            `).join('');
        }
    });
}

function selectTitle(title, author, copyright_date, publisher, isbn, place_publication, call_number, category_id, book_image, subject, subject1, subject2) {
    document.getElementById('title').value = title;
    document.getElementsByName('author')[0].value = author;
    document.getElementsByName('copyright_date')[0].value = copyright_date;
    document.getElementsByName('publisher')[0].value = publisher;
    document.getElementsByName('isbn')[0].value = isbn;
    document.getElementsByName('place_publication')[0].value = place_publication;
    document.getElementsByName('call_number')[0].value = call_number;
    document.getElementsByName('lrc_location')[0].value = category_id;
    document.getElementsByName('subject')[0].value = subject;
    document.getElementsByName('subject1')[0].value = subject1;
    document.getElementsByName('subject2')[0].value = subject2;

    // Set the image if available
    document.getElementById('book_image_name').value = book_image;
    document.getElementById('existing_image').value = book_image;


    document.getElementById('title_results').innerHTML = '';
}

function generateAccessionFields() {
    const copyCount = document.getElementById('copy').value;
    const container = document.getElementById('accession_numbers_container');
    container.innerHTML = '';
    for (let i = 1; i <= copyCount; i++) {
        const input = document.createElement('input');
        input.type = 'number';
        input.name = 'accession_number_' + i;
        input.className = 'form-control mb-2';
        input.placeholder = 'Accession Number ' + i;
        input.required = true;
        container.appendChild(input);
    }
}

$(document).ready(function() {
    // Restrict input to numeric values only
    $('#copyright_date').on('keypress', function(event) {
        var key = String.fromCharCode(event.which);
        if (!/[0-9]/.test(key)) {
            event.preventDefault();
        }
    });

    // Ensure the year is not greater than the current year
    $('#copyright_date').on('change', function() {
        var inputYear = parseInt($(this).val(), 10);
        var currentYear = new Date().getFullYear();
        if (inputYear > currentYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Year',
                text: 'Year cannot be greater than the current year.',
                confirmButtonText: 'OK'
            });
            $(this).val(''); // Clear the input
        }
    });

    // Validate book image file type
    $('#book_image_input').on('change', function() {
        const file = this.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (file && !allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Only JPG, JPEG, PNG, and GIF files are allowed.',
                confirmButtonText: 'OK'
            });
            $(this).val(''); // Clear the input
            $('#book_image_name').val(''); // Clear the filename input
        } else {
            $('#book_image_name').val(file ? file.name : '');
        }
    });
});

function checkDuplicateAccessionNumbers() {
    const accessionNumbers = [];
    const inputs = document.querySelectorAll('[name^="accession_number_"]');
    
    for (let input of inputs) {
        const accessionNumber = input.value;
        if (accessionNumbers.includes(accessionNumber)) {
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Accession Number',
                text: 'Duplicate accession number ' + accessionNumber + ' found.',
            });
            return false; // Prevent form submission
        }
        accessionNumbers.push(accessionNumber);
    }
    
    // Check if any accession number already exists in the database
    for (let accessionNumber of accessionNumbers) {
        if (checkAccessionNumberExists(accessionNumber)) {
            return false; // Prevent form submission if any accession number exists
        }
    }
    
    return true; // Allow form submission
}

function checkAccessionNumberExists(accessionNumber) {
    let exists = false;
    
    $.ajax({
        url: 'check_accession.php',
        type: 'POST',
        async: false,
        data: { accession_number: accessionNumber },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.exists) {
                Swal.fire({
                    icon: 'error',
                    title: 'Accession Number Exists',
                    text: 'Accession number ' + accessionNumber + ' already exists.',
                });
                exists = true;
            }
        }
    });
    
    return exists;
}
</script>

<style>
.search-results {
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    background-color: white;
    z-index: 1000;
}

.search-result {
    padding: 8px;
    cursor: pointer;
}

.search-result:hover {
    background-color: #f1f1f1;
}
</style>
