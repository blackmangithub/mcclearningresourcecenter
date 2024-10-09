<?php 
include('authentication.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" href="./assets/img/mcc-logo.png">
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i"
        rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/boxicons.min.css" rel="stylesheet" />
    <link href="assets/css/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/alertify.min.css" />
    <link rel="stylesheet" href="assets/css/alertify.bootstraptheme.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/buttons.bootstrap5.min.css" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">
    <style>
        @media print {
            .print-button, #back, .pdf-button,
            .excel-button {
                display: none;
            }
            @page {
                margin: 0;
            }
            body {
                margin: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-start mt-3">
                    <a href="report.php" id="back" style="margin-left:20px;" class="btn btn-primary">Back</a>
                </div>
                <div class="text-start mt-5">
                    <button onclick="exportToPDF()" style="margin-left:20px;" class="btn btn-danger pdf-button">
                        <i class="bi bi-file-earmark-pdf-fill"></i> <b>Export to PDF</b>
                    </button>
                    <button onclick="exportToExcel()" style="margin-left:10px;" class="btn btn-success excel-button">
                        <i class="bi bi-file-earmark-excel-fill"></i> <b>Export to Excel</b>
                    </button>
                    <button onclick="window.print()" style="margin-left:10px;" class="btn btn-primary print-button">
                        <i class="bi bi-printer-fill"></i> <b>Print</b>
                    </button>
                </div>

                <div class="text-end mt-5">
                    <h5>Date: <?php echo date('F d, Y'); ?></h5>
                </div>
                <div class="text-center mt-5">
                    <h4 style="font-weight:bold;">PENALTY REPORT</h4>
                </div>
                <div id="content" class="table-responsive mt-5" style="margin-left: 20px;margin-right: 20px;">
                    <table border="2" cellpadding="2" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="font-size:15px;">Penalty Amount</th>
                                <th style="font-size:15px;">Received From</th>
                                <th style="font-size:15px;">Person In Charge</th>
                                <th style="font-size:15px;">Due Date</th>
                                <th style="font-size:15px;">Date Returned</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $return_query = "
                                    SELECT report.date_transaction, report.detail_action, report.admin_name, book.title, user.firstname, user.lastname
                                    FROM report
                                    LEFT JOIN book ON report.book_id = book.book_id
                                    LEFT JOIN user ON report.user_id = user.user_id
                                    ORDER BY report.report_id DESC
                                ";
                                $return_result = mysqli_query($con, $return_query);
                                
                                if ($return_result->num_rows > 0) {
                                    while ($return_row = $return_result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($return_row['firstname']." ".$return_row['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($return_row['title']); ?></td>
                                <td><?php echo htmlspecialchars($return_row['detail_action']); ?></td>
                                <td><?php echo htmlspecialchars($return_row['admin_name']); ?></td>
                                <td><?php echo date("M d, Y h:i:s a", strtotime($return_row['date_transaction'])); ?></td>
                            </tr>
                            <?php 
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No report records found.</td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        async function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.autoTable({
                html: '#content table',
                styles: { fontSize: 8 },
                headStyles: { fillColor: [0, 0, 0] },
                startY: 20
            });

            doc.save('student_report.pdf');
        }

        function exportToExcel() {
            var wb = XLSX.utils.book_new();
            var ws_data = [
                ['Name', 'Book Title', 'Task', 'Person In Charge', 'Date Transaction']
            ];

            var table = document.querySelector('#content table tbody');
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
            XLSX.writeFile(wb, "student_report.xlsx");
        }
    </script>

<?php 
include('./includes/script.php');
include('./message.php');   
?>
</body>
</html>
