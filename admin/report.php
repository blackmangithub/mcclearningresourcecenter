<?php 
require_once('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>
<style>
    .sname, .dated, .tname {
        display: none;
    }
    @media print {
        body {
            visibility: hidden;
        }
        #myDataTable, #myDataTable2, .sname, .dated, .tname, #myDataTable *, #myDataTable2 * {
            visibility: visible;
        }
        #myDataTable, #myDataTable2 {
            position: fixed;
            left: 0px;
            top: 180px;
            right: 0px;
        }
        .sname {
            display: block;
            position: fixed;
            left: 0px;
            top: 30px;
            font-weight: bold;
        }
        .dated {
            display: block;
            position: fixed;
            top: 10px;
            right: 0px;
            font-size: 15px;
        }
        .tname {
            display: block;
            position: fixed;
            left: 0;
            top: 130px;
            right: 0;
            font-weight: bold;
            text-align: center;
            font-size: 20px;
        }
    }
</style>
<main id="main" class="main" data-aos="fade-down">
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
                            <li class="nav-item">
                                <a class="nav-link <?=$page == 'report' || $page == 'report_faculty' ? 'active': '' ?>" href="report">All Transaction</a>
                            </li>
                            <li class="nav-item  border border-info border-start-0 rounded-end">
                                <a class="nav-link <?=$page == 'report_penalty' ? 'active': '' ?>" href="report_penalty">Penalty Report</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="nav-item">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#student-tab-pane">Students</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#faculty-tab-pane">Faculty Staff</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="student-tab-pane">
                                    <div class="text-start mt-4">
                                        <button onclick="exportToPDF('myDataTable', 'student')" class="btn btn-danger pdf-button">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> <b>Export to PDF</b>
                                        </button>
                                        <button onclick="exportToExcel('myDataTable', 'student')" class="btn btn-success excel-button">
                                            <i class="bi bi-file-earmark-excel-fill"></i> <b>Export to Excel</b>
                                        </button>
                                        <button onclick="window.print()" class="btn btn-primary print-button">
                                            <i class="bi bi-printer-fill"></i> <b>Print</b>
                                        </button>
                                    </div>
                                    <br><br>
                                    <h5 class="dated">Date: <?php echo date('F d, Y'); ?></h5>
                                    <h1 class="sname">MCC Learning Resource Center</h1>
                                    <h2 class="tname">Student Report</h2>
                                    <table id="myDataTable" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Book Title</th>
                                                <th>Task</th>
                                                <th>Person In Charge</th>
                                                <th>Date Transaction</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result= mysqli_query($con,"SELECT * from report 
                                            LEFT JOIN book ON report.book_id = book.book_id 
                                            LEFT JOIN user ON report.user_id = user.user_id
                                            order by report.report_id DESC ");
                                            while ($row= mysqli_fetch_array ($result) ){
                                                $id=$row['report_id'];
                                                $book_id=$row['book_id'];
                                                $user_name=$row['firstname']." ".$row['lastname'];
                                                $admin =$row['admin_name'];
                                            ?>
                                            <?php if(isset($row['user_id'])) :?>
                                            <tr>
                                            <td class="auto-id" style="text-align: center;"></td>
                                                <td><?php echo $user_name; ?></td>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo $row['detail_action']; ?></td>
                                                <td><?php echo $row['admin_name']; ?></td>
                                                <td><?php echo date("M d, Y h:i:s a",strtotime($row['date_transaction'])); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="faculty-tab-pane">
                                    <div class="text-start mt-4">
                                        <button onclick="exportToPDF('myDataTable2', 'faculty')" class="btn btn-danger pdf-button">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> <b>Export to PDF</b>
                                        </button>
                                        <button onclick="exportToExcel('myDataTable2', 'faculty')" class="btn btn-success excel-button">
                                            <i class="bi bi-file-earmark-excel-fill"></i> <b>Export to Excel</b>
                                        </button>
                                        <button onclick="window.print()" class="btn btn-primary print-button">
                                            <i class="bi bi-printer-fill"></i> <b>Print</b>
                                        </button>
                                    </div>
                                    <br><br>
                                    <h5 class="dated">Date: <?php echo date('F d, Y'); ?></h5>
                                    <h1 class="sname">MCC Learning Resource Center</h1>
                                    <h2 class="tname">Faculty Report</h2>
                                    <table id="myDataTable2" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Book Title</th>
                                                <th>Task</th>
                                                <th>Person In Charge</th>
                                                <th>Date Transaction</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result= mysqli_query($con,"SELECT * from report 
                                            LEFT JOIN book ON report.book_id = book.book_id 
                                            LEFT JOIN faculty ON report.faculty_id = faculty.faculty_id
                                            order by report.report_id DESC ");
                                            while ($row= mysqli_fetch_array ($result) ){
                                                $id=$row['report_id'];
                                                $book_id=$row['book_id'];
                                                $faculty_name=$row['firstname']." ".$row['lastname'];
                                                $admin =$row['admin_name'];
                                            ?>
                                            <?php if(isset($row['faculty_id'])) :?>
                                            <tr>
                                                <td><?php echo $faculty_name; ?></td>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo $row['detail_action']; ?></td>
                                                <td><?php echo $row['admin_name']; ?></td>
                                                <td><?php echo date("M d, Y h:i:s a",strtotime($row['date_transaction'])); ?></td>
                                            </tr>
                                            <?php endif; ?>
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
    </section>
</main>

<script>
    async function exportToPDF(tableId, reportType) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.autoTable({
            html: '#' + tableId,
            styles: { fontSize: 8 },
            headStyles: { fillColor: [0, 0, 0] },
            startY: 20
        });

        const fileName = reportType === 'student' ? 'student_report.pdf' : 'faculty_report.pdf';
        doc.save(fileName);
    }

    function exportToExcel(tableId, reportType) {
        var wb = XLSX.utils.book_new();
        var ws_data = [
            ['Name', 'Book Title', 'Task', 'Person In Charge', 'Date Transaction']
        ];

        var table = document.getElementById(tableId);
        var rows = table.querySelectorAll('tbody tr');

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

        const fileName = reportType === 'student' ? 'student_report.xlsx' : 'faculty_report.xlsx';
        XLSX.writeFile(wb, fileName);
    }

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

<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');   
?>
