<?php 
include('authentication.php');
include('includes/header.php'); 
include('includes/sidebar.php'); 
?>

<style>
.data_table {
    background: #fff;
    padding: 15px;
    border-radius: 5px;
}

.data_table .btn {
    padding: 5px 10px;
    margin: 10px 3px 10px 0;
}

.sname, .dated, .tname{
    display: none;
}
.span {
    margin-top: -40px;
    margin-bottom: 20px;
}

@media print {
    body * {
        visibility: hidden;
    }

    .alert {
        border: none;
        margin-bottom: -70px;
        font-weight: bold;
    }
    .table, #head, .pull-left,
    .alert, .sname,
    .dated, .tname, .table *{
        visibility: visible;
    }
    .sname{
        display: block;
        position: fixed;
        top: 80px;
        left: 20px;
        font-weight: bold;
    }
    .dated {
        display: block;
        position: fixed;
        top: 50px;
        right: 0;
        font-size: 15px;
    }
    .data_table{
        position: fixed;
        left: 0px;
        top: 170px;
        right: 0px;
    }
    .tname {
            display: block;
            position: fixed;
            left: 0;
            top: 170px;
            right: 0;
            font-weight: bold;
            text-align: center;
            font-size: 20px;
        }
}
</style>

<main id="main" class="main">
    <?php 
    $page = basename($_SERVER['SCRIPT_NAME']); 
    ?>
    <div class="pagetitle">
        <h1>Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item active">Report</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                            <li class="nav-item border border-info border-end-0 rounded-start">
                                <a class="nav-link <?= $page == 'report' ? 'active' : '' ?>" href="report">All Transaction</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $page == 'report_penalty' ? 'active' : '' ?>" href="report_penalty">Penalty Report</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-end align-items-center mt-2">
                            <div class="text-start">
                                <button onclick="exportToPDF()" class="btn btn-danger pdf-button">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> <b>Export to PDF</b>
                                </button>
                                <button onclick="exportToExcel()" class="btn btn-success excel-button">
                                    <i class="bi bi-file-earmark-excel-fill"></i> <b>Export to Excel</b>
                                </button>
                                <button onclick="window.print()" class="btn btn-primary print-button">
                                    <i class="bi bi-printer-fill"></i> <b>Print</b>
                                </button>
                            </div>
                            <form action="" method="POST" class="col-12 col-md-5 d-flex">
                                <?php date_default_timezone_set('Asia/Manila'); ?>
                                <div class="form-group form-group-sm">
                                    <label for="from_date"> <small>From Date</small></label>
                                    <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group form-group-sm mx-1">
                                    <label for="to_date"> <small>To Date</small></label>
                                    <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="filter_attendance"> <small>Click to Filter</small></label>
                                    <button type="submit" name="filter_attendance" id="filter_attendance" class="btn text-white fw-semibold btn-info btn-sm d-block">Filter</button>
                                </div>
                            </form>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="data_table">
                                        <?php
                                        // Fetch admin name
                                        $admin_query = mysqli_query($con, "SELECT admin_name FROM report LIMIT 1") or die(mysqli_connect_error());
                                        $admin_row = mysqli_fetch_array($admin_query);
                                        $admin = $admin_row['admin_name'] ?? 'Admin';

                                        // Handle date filtering
                                        if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
                                            $from_date = $_POST['from_date'];
                                            $to_date = $_POST['to_date'];

                                            $return_query = mysqli_query($con, "SELECT * FROM return_book 
                                                LEFT JOIN book ON return_book.book_id = book.book_id 
                                                LEFT JOIN user ON return_book.user_id = user.user_id
                                                WHERE book_penalty > 1 AND date_returned BETWEEN '$from_date' AND '$to_date' 
                                                ORDER BY return_book.return_book_id DESC") or die(mysqli_connect_error());
                                            
                                            $count_penalty_query = mysqli_query($con, "SELECT SUM(book_penalty) as total_penalty FROM return_book WHERE book_penalty > 0 AND date_returned BETWEEN '$from_date' AND '$to_date'") or die(mysqli_connect_error());
                                            $count_penalty_row = mysqli_fetch_array($count_penalty_query);
                                        } else {
                                            $return_query = mysqli_query($con, "SELECT * FROM return_book 
                                                LEFT JOIN book ON return_book.book_id = book.book_id 
                                                LEFT JOIN user ON return_book.user_id = user.user_id
                                                WHERE book_penalty > 0 
                                                ORDER BY return_book.return_book_id DESC") or die(mysqli_connect_error());

                                            $count_penalty_query = mysqli_query($con, "SELECT SUM(book_penalty) as total_penalty FROM return_book WHERE book_penalty > 0") or die(mysqli_connect_error());
                                            $count_penalty_row = mysqli_fetch_array($count_penalty_query);
                                        }
                                        ?>
                                        
                                        <table id="myDataTable2" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                        <div class="pull-left">
                                        <h5 class="dated">Date: <?php echo date('F d, Y'); ?></h5>
                                                <h1 class="sname">MCC Learning Resource Center</h1>
                                                <h2 class="tname">Report Penalty</h2>
                                                <br>
                                                <br>
                                                <div class="span">
                                                    <div class="alert alert-info mt-2 p-1">
                                                        <i class="icon-credit-card icon-large"></i>&nbsp;Total Amount of Penalty: Php <?= number_format($count_penalty_row['total_penalty'], 2) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Penalty Amount</th>
                                                    <th>Received from</th>
                                                    <th>Person In Charge</th>
                                                    <th>Due Date</th>
                                                    <th>Date Returned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($return_row = mysqli_fetch_array($return_query)) { ?>
                                                <tr>
                                                <td class="auto-id" style="text-align: center;"></td>
                                                    <td class="<?= ($return_row['book_penalty'] != 'No Penalty') ? 'alert alert-warning' : ''; ?>" style="width:100px;">
                                                        Php <?= number_format($return_row['book_penalty'], 2) ?>
                                                    </td>
                                                    <td style="text-transform: capitalize"><?= htmlspecialchars($return_row['firstname'] . " " . $return_row['lastname']) ?></td>
                                                    <td><?= htmlspecialchars($admin) ?></td>
                                                    <td><?= date("M d, Y", strtotime($return_row['due_date'])) ?></td>
                                                    <td><?= date("M d, Y", strtotime($return_row['date_returned'])) ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    async function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.autoTable({
            html: '#myDataTable2',
            styles: { fontSize: 8 },
            headStyles: { fillColor: [0, 0, 0] },
            startY: 20
        });

        doc.save('penalty_report.pdf');
    }

    function exportToExcel() {
        var wb = XLSX.utils.book_new();
        var ws_data = [
            ['Penalty Amount', 'Received From', 'Person In Charge', 'Due Date', 'Date Returned']
        ];

        var table = document.querySelector('#myDataTable2 tbody');
        var rows = table.querySelectorAll('tr');

        rows.forEach(function(row) {
            var cells = row.querySelectorAll('td');
            var row_data = [];
            cells.forEach(function(cell) {
                row_data.push(cell.innerText);
            });
            ws_data.push(row_data);
        });

        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        XLSX.writeFile(wb, "penalty_report.xlsx");
    }

    document.addEventListener('DOMContentLoaded', function () {
    // Function to add auto-increment ID to a table
    function addAutoIncrementId(tableSelector) {
        const table = document.querySelector(tableSelector);
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                const idCell = row.querySelector('.auto-id');
                if (idCell) {
                    idCell.textContent = index + 1;
                }
            });
        }
    }

    // Apply auto-increment IDs to the specific tables
    addAutoIncrementId('#myDataTable tbody');
    addAutoIncrementId('#myDataTable2 tbody');
});
</script>

<?php 
include('includes/footer.php');
include('includes/script.php');
include('../message.php');   
?>
