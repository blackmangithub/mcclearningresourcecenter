<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 

// Check which tab to show
$activeTab = isset($_GET['tab']) && $_GET['tab'] == 'copies' ? 'copies-tab' : 'details-tab';
$activeTabPane = isset($_GET['tab']) && $_GET['tab'] == 'copies' ? 'copies-tab-pane' : 'details-tab-pane';
?>

<style>
    .wrap-text {
        word-break: break-word;
        line-height: 1.5;
    }
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        position: relative;
    }
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #000;
        margin: 0 -5px;
    }
    .divider span {
        display: inline-block;
        padding: 0 10px;
        font-weight: bold;
        margin-left: -8px;
        font-size: 20px;
    }
    .subject-list {
        list-style-type: none; /* Remove default list styling */
        padding-left: 0; /* Remove default padding */
        margin: 0; /* Remove default margin */
    }
    .subject-list li {
        display: flex;
        align-items: flex-start; /* Align items at the start */
        margin-bottom: 5px; /* Space between items */
    }
    .subject-list li::before {
        content: '•'; /* Bullet character */
        font-size: 20px; /* Size of the bullet */
        color: #000; /* Color of the bullet */
        margin-right: 10px; /* Space between bullet and text */
    }
    .subject-list li .text {
        flex: 1; /* Allow text to take remaining space */
        word-wrap: break-word; /* Ensure text wraps */
    }
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>View Book</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="books.php">Book Collection</a></li>
                <li class="breadcrumb-item active">View Book</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <!-- Back Button -->
                            <a href="books.php" class="btn btn-primary" style="margin-top:10px;margin-bottom:-30px;">Back</a>
                        </div>
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="nav-item">
                                <!-- Book Details Tab -->
                                <button class="nav-link <?= $activeTab == 'details-tab' ? 'active' : '' ?>" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane">Book Details</button>
                            </li>
                            <li class="nav-item">
                                <!-- Copies Tab -->
                                <button class="nav-link <?= $activeTab == 'copies-tab' ? 'active' : '' ?>" id="copies-tab" data-bs-toggle="tab" data-bs-target="#copies-tab-pane">Copies</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade <?= $activeTabPane == 'details-tab-pane' ? 'show active' : '' ?>" id="details-tab-pane">
                                <div class="card-body">
                                    <?php
                                    if (isset($_GET['title']) || isset($_GET['copyright_date'])) {
                                        $book_title = mysqli_real_escape_string($con, $_GET['title']);
                                        $copyright_date = mysqli_real_escape_string($con, $_GET['copyright_date']);
                                        $query = "SELECT book.*, category.classname, COUNT(book.accession_number) AS copy_count,
                                                  SUM(CASE WHEN book.status = 'available' THEN 1 ELSE 0 END) AS available_count 
                                                  FROM book 
                                                  LEFT JOIN category ON book.category_id = category.category_id 
                                                  WHERE book.title = '$book_title' AND book.copyright_date = '$copyright_date'";
                                        $query_run = mysqli_query($con, $query);

                                        if (mysqli_num_rows($query_run) > 0) {
                                            $book = mysqli_fetch_array($query_run);
                                            ?>
                                            <div class="row">
                                                <div class="col-12 col-md-5 d-flex align-items-center justify-content-center my-4">
                                                    <?php if ($book['book_image'] != ""): ?>
                                                        <img src="../uploads/books_img/<?= $book['book_image']; ?>" alt="" width="250px" height="250px">
                                                    <?php else: ?>
                                                        <img src="../uploads/books_img/book_image.jpg" alt="" width="250px" height="250px">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-12 col-md-7 my-4">
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Title</span>
                                                        <p class="d-inline">: <?= $book['title']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Author</span>
                                                        <p class="d-inline">: <?= $book['author']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Copyright Date</span>
                                                        <p class="d-inline">: <?= $book['copyright_date']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Publisher</span>
                                                        <p class="d-inline">: <?= $book['publisher']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">ISBN</span>
                                                        <p class="d-inline">: <?= $book['isbn']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Place of Publication</span>
                                                        <p class="d-inline">: <?= $book['place_publication']; ?></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <span class="fw-semibold">Copy</span>
                                                        <p class="d-inline">: <?= $book['available_count']; ?> of <?= $book['copy_count']; ?> available</p>
                                                    </div>
                                                    <div class="mb-3 mt-2">
                                                        <span class="fw-semibold">Call Number</span>
                                                        <p class="d-inline">: <?= $book['call_number']; ?></p>
                                                    </div>
                                                    <div class="divider">
                                                        <span>Explore!</span>
                                                    </div>                                  
                                                    <div class="mb-3 mt-1">
                                                        <div class="text-with-circles">
                                                            <ul class="subject-list">
                                                                <?php
                                                                    // Get the subjects from each field and split them into arrays
                                                                    $subjects = explode("\n", htmlspecialchars($book['subject']));
                                                                    $subjects1 = explode("\n", htmlspecialchars($book['subject1']));
                                                                    $subjects2 = explode("\n", htmlspecialchars($book['subject2']));

                                                                    // Combine all subjects into one array
                                                                    $all_subjects = array_merge($subjects, $subjects1, $subjects2);

                                                                    // Output each subject as a list item
                                                                    foreach ($all_subjects as $subject) {
                                                                        // Check if the subject is not empty
                                                                        if (!empty(trim($subject))) {
                                                                            echo '<li><span class="text">' . nl2br(trim($subject)) . '</span></li>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            echo "No such title found";
                                        }
                                    }  
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade <?= $activeTabPane == 'copies-tab-pane' ? 'show active' : '' ?>" id="copies-tab-pane">
                                <div class="table-responsive">
                                    <br>
                                    <!-- Copies Table -->
                                    <table id="myDataTable" class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Accession No.</th>
                                                <th>Barcode</th>
                                                <th>Status</th>
                                                <th>LRC Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT book.*, category.classname 
                                                      FROM book 
                                                      LEFT JOIN category ON book.category_id = category.category_id 
                                                      WHERE book.title = '$book_title' AND book.copyright_date = '$copyright_date'";
                                            $query_run = mysqli_query($con, $query);

                                            if (mysqli_num_rows($query_run) > 0) {
                                                while ($book = mysqli_fetch_assoc($query_run)) {
                                                    ?>
                                                    <tr>
                                                        <td><?= $book['accession_number']; ?></td>
                                                        <td><?= $book['barcode']; ?></td>
                                                        <td style="text-transform: capitalize"><?= $book['status']; ?></td>
                                                        <td><?= $book['classname']; ?></td>
                                                        <td>
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $book['accession_number']; ?>">Edit</button>
                                                            <!-- Delete Button -->
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('<?= $book['accession_number']; ?>')">Delete</button>
                                                        </td>
                                                    </tr>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal<?= $book['accession_number']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">Edit Copies</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="books_code.php" method="POST" onsubmit="return validateForm(this);">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="old_accession_number" value="<?= $book['accession_number']; ?>">
                                                                        <div class="mb-3">
                                                                            <label for="accession_number" class="form-label">Accession Number</label>
                                                                            <input type="number" name="accession_number" class="form-control" value="<?= $book['accession_number']; ?>" pattern="[0-9]*" title="Please enter only numbers" required oninput="validateNumberInput(this)">
                                                                            <div id="accession_number_error" class="text-danger" style="display: none;"></div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="category_id" class="form-label">LRC Location</label>
                                                                            <select name="category_id" class="form-select" required>
                                                                                <?php
                                                                                // Fetch categories from the database
                                                                                $categories_query = "SELECT category_id, classname FROM category";
                                                                                $categories_result = mysqli_query($con, $categories_query);
                                                                                while ($category = mysqli_fetch_assoc($categories_result)) {
                                                                                    $selected = ($category['category_id'] == $book['category_id']) ? 'selected' : '';
                                                                                    echo "<option value='{$category['category_id']}' {$selected}>{$category['classname']}</option>";
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="remarks" class="form-label">Remarks</label>
                                                                            <select name="remarks" class="form-select">
                                                                                <option value="available" <?= $book['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                                                                <option value="missing" <?= $book['status'] == 'missing' ? 'selected' : ''; ?>>Missing</option>
                                                                                <option value="damage" <?= $book['status'] == 'damage' ? 'selected' : ''; ?>>Damage</option>
                                                                                <option value="storage room" <?= $book['status'] == 'storage room' ? 'selected' : ''; ?>>Storage Room</option>
                                                                            </select>
                                                                        </div>
                                                                        <input type="hidden" name="title" value="<?= $book_title; ?>">
                                                                        <input type="hidden" name="accession_number_check" value="true">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" name="update_accession_number" class="btn btn-primary">Save changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="6">No records found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <!-- Additional footer content if needed -->
                        </div>
                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add auto-increment ID to Books Table
    let booksTable = document.querySelector('#myDataTable tbody');
    let bookRows = booksTable.querySelectorAll('tr');
    bookRows.forEach((row, index) => {
        row.querySelector('.auto-id').textContent = index + 1;
    });

    // Handle status change
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const accessionNumber = this.getAttribute('data-accession');
            const newStatus = this.value;
            updateStatus(accessionNumber, newStatus);
        });
    });
});

function validateNumberInput(input) {
    // Filter out non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
}

function validateForm(form) {
    var accessionNumber = form.accession_number.value.trim();
    var errorElement = document.getElementById('accession_number_error');
    var saveButton = form.querySelector('button[type="submit"]');
    
    // Clear previous error
    errorElement.textContent = '';
    errorElement.style.display = 'none';
    saveButton.disabled = false;
    
    // Check if accession number exists
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "books_code.php", false); // Synchronous request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("accession_number=" + encodeURIComponent(accessionNumber) + "&accession_number_check=true");
    
    if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.exists) {
            errorElement.textContent = "This accession number already exists.";
            errorElement.style.display = 'block';
            saveButton.disabled = true;
            return false;
        }
    }
    return true;
}

// function updateStatus(accessionNumber, newStatus) {
//     var xhr = new XMLHttpRequest();
//     xhr.open("POST", "books_code.php", true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.send("accession_number=" + encodeURIComponent(accessionNumber) + "&status=" + encodeURIComponent(newStatus) + "&update_status=true");
    
//     xhr.onload = function() {
//         if (xhr.status === 200) {
//             var response = JSON.parse(xhr.responseText);
//             if (response.success) {
//                 Swal.fire('Success', 'Status updated successfully.', 'success');
//             } else {
//                 Swal.fire('Error', 'Failed to update status.', 'error');
//             }
//         }
//     };
// }

function confirmDelete(accessionNumber) {
    Swal.fire({
        title: 'Are you sure to delete this?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the deletion
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'books_code.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'accession_number';
            input.value = accessionNumber;

            var submit = document.createElement('input');
            submit.type = 'hidden';
            submit.name = 'delete_book';
            submit.value = 'true';

            form.appendChild(input);
            form.appendChild(submit);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
