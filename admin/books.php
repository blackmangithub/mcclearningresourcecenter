<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<style>
    #hover:hover {
        text-decoration: underline;
    }
</style>

<main id="main" class="main" data-aos="fade-down">
    <?php $page = basename($_SERVER['SCRIPT_NAME']); ?>
    <div class="pagetitle">
        <h1>Collection of Books</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item active">Book Collection</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="nav-item">
                                    <!-- Book Tab -->
                                    <button class="nav-link active" id="books-tab" data-bs-toggle="tab" data-bs-target="#books-tab-pane">Books</button>
                                </li>
                                <li class="nav-item">
                                    <!-- Ebook Tab -->
                                    <button class="nav-link" id="ebooks-tab" data-bs-toggle="tab" data-bs-target="#ebooks-tab-pane">Ebooks</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="books-tab-pane">
                                    <div class="d-flex justify-content-end my-2">
                                        <!-- Add Book Button -->
                                        <a href="book_add" class="btn btn-primary">
                                            <i class="bi bi-journal-plus"></i> Add Book
                                        </a>
                                    </div>
                                    <div class="table-responsive">
                                        <!-- Books Table -->
                                        <table id="myDataTable" class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Image</th>
                                                    <th>Title</th>
                                                    <th>Author</th>
                                                    <th>Copyright Date</th>
                                                    <th>Publisher</th>
                                                    <th>Call Number</th>
                                                    <th>Copies</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "
                                                SELECT 
                                                    book.*, 
                                                    COUNT(book.accession_number) AS copy_count, 
                                                    SUM(CASE WHEN book.status = 'available' THEN 1 ELSE 0 END) AS available_count 
                                                FROM book 
                                                GROUP BY book.title, book.copyright_date
                                                ORDER BY book.title DESC, book.copyright_date DESC";
                                                $query_run = mysqli_query($con, $query);

                                                if (mysqli_num_rows($query_run)) {
                                                    foreach ($query_run as $book) {
                                                        ?>
                                                        <tr>
                                                            <td class="auto-id" style="text-align: center;"></td>
                                                            <td>
                                                                <center>
                                                                    <?php if ($book['book_image'] != ""): ?>
                                                                        <img src="../uploads/books_img/<?php echo htmlspecialchars($book['book_image']); ?>" alt="" width="60px" height="60px">
                                                                    <?php else: ?>
                                                                        <img src="../uploads/books_img/book_image.jpg" alt="" width="60px" height="60px">
                                                                    <?php endif; ?>
                                                                </center>
                                                            </td>
                                                            <td><?= htmlspecialchars($book['title']); ?></td>
                                                            <td><?= htmlspecialchars($book['author']); ?></td>
                                                            <td><?= htmlspecialchars($book['copyright_date']); ?></td>
                                                            <td><?= htmlspecialchars($book['publisher']); ?></td>
                                                            <td><?= htmlspecialchars($book['call_number']); ?></td>
                                                            <td>
                                                                <a href="book_views?title=<?= urlencode($book['title']); ?>&copyright_date=<?= urlencode($book['copyright_date']); ?>&tab=copies" id="hover" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Copies">
                                                                <?= htmlspecialchars($book['available_count']); ?> of <?= htmlspecialchars($book['copy_count']); ?> available
                                                                </a>
                                                            </td>
                                                            <td class="justify-content-center">
                                                                <div class="btn-group" style="background: #DFF6FF;">
                                                                    <!-- View Book Action -->
                                                                    <a href="book_views?title=<?= urlencode($book['title']); ?>&copyright_date=<?= urlencode($book['copyright_date']); ?>" class="viewBookBtn btn btn-sm border text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Book">
                                                                        <i class="bi bi-eye-fill"></i>
                                                                    </a>
                                                                    <!-- Edit Book Action -->
                                                                    <a href="book_edit?title=<?= urlencode($book['title']); ?>&copyright_date=<?= urlencode($book['copyright_date']); ?>" class="btn btn-sm border text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Book">
                                                                        <i class="bi bi-pencil-fill"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9' style='text-align: center;'>No records found</td></tr>";
                                                }                                           
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="ebooks-tab-pane">
                                    <div class="d-flex justify-content-end my-2">
                                        <!-- Add Ebook Button -->
                                        <a href="ebook_add.php" class="btn btn-primary"><i class="bi bi-journal-plus"></i> Upload Ebook</a>
                                    </div>
                                    <div class="table-responsive">
                                        <!-- Ebooks Table -->
                                        <table id="myDataTable2" class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
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

                                                if (mysqli_num_rows($query_run)) {
                                                    foreach ($query_run as $book) {
                                                        ?>
                                                        <tr>
                                                            <td class="auto-id" style="text-align: center;"></td>
                                                            <td>
                                                                <center>
                                                                    <?php if ($book['opac_image'] != ""): ?>
                                                                        <img src="../uploads/ebook_img/<?= htmlspecialchars($book['opac_image']); ?>" alt="" width="60px" height="60px">
                                                                    <?php else: ?>
                                                                        <img src="../uploads/ebook_img/book_image.jpg" alt="" width="60px" height="60px">
                                                                    <?php endif; ?>
                                                                </center>
                                                            </td>
                                                            <td><?= htmlspecialchars($book['title']); ?></td>
                                                            <td><?= htmlspecialchars($book['author']); ?></td>
                                                            <td><?= htmlspecialchars($book['copyright_date']); ?></td>
                                                            <td><?= htmlspecialchars($book['publisher']); ?></td>
                                                            <td class="justify-content-center">
                                                                <div class="btn-group" style="background: #DFF6FF;">
                                                                    <!-- View Ebook Action -->
                                                                    <a href="ebook_view.php?id=<?= urlencode($book['web_opac_id']); ?>" class="viewweb_opacBtn btn btn-sm border text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Ebook">
                                                                        <i class="bi bi-eye-fill"></i>
                                                                    </a>
                                                                    <!-- Edit Ebook Action -->
                                                                    <a href="ebook_edit.php?id=<?= urlencode($book['web_opac_id']); ?>" class="btn btn-sm border text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Ebook">
                                                                        <i class="bi bi-pencil-fill"></i>
                                                                    </a>
                                                                    <!-- Delete Ebook Action -->
                                                                    <form action="ebooks_code.php" method="POST" style="display: inline;">
                                                                        <button type="submit" name="delete_book" value="<?= htmlspecialchars($book['web_opac_id']); ?>" class="btn btn-sm border text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete Ebook">
                                                                            <i class="bi bi-trash-fill"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' style='text-align: center;'>No records found</td></tr>";
                                                }                                           
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add auto-increment ID to Books Table
    let booksTable = document.querySelector('#myDataTable tbody');
    let bookRows = booksTable.querySelectorAll('tr');
    bookRows.forEach((row, index) => {
        row.querySelector('.auto-id').textContent = index + 1;
    });

    // Add auto-increment ID to Ebooks Table
    let ebooksTable = document.querySelector('#myDataTable2 tbody');
    let ebookRows = ebooksTable.querySelectorAll('tr');
    ebookRows.forEach((row, index) => {
        row.querySelector('.auto-id').textContent = index + 1;
    });
});
</script>
