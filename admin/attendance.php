<?php 
include('authentication.php');
include('includes/header.php'); 
include('./includes/sidebar.php'); 
?>

<style>
.data_table {
     background: #fff;
     padding: 15px;
     /* box-shadow: 1px 3px 5px #aaa; */
     border-radius: 5px;
}

.data_table .btn {
     padding: 5px 10px;
     margin: 10px 3px 10px 0;
}
.sname, .dated, .tname{
    display: none;
}

@media print {
    body *{
     visibility: hidden;
    }
    #myDataTabele, .sname, .dated, .tname, #myDataTable *{
     visibility: visible;
    } 
    .sname{
    display: block;
    position: fixed;
            left: 0px;
            top: 30px;
            font-weight: bold;
     }
     .dated{
    display: block;
    position: fixed;
            top: 10px;
            right: 0px;
            font-size: 15px;
     }
     .data_table{
        position: fixed;
        left: 0px;
        top: 100px;
        right: 0px;
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
<main id="main" class="main">
     <div class="pagetitle" data-aos="fade-down">

          <h1>Attendance</h1>

          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Home</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
               </ol>
          </nav>
     </div>

     <section class="section dashboard">
          <div class="row">
               <div class="col-lg-12">
                    <div class="row">
                         <div class="row">
                         <div data-aos="fade-down" class="col-12">
                              <div class="card recent-sales overflow-auto  border-3 border-top border-info">

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
                                             <form action="" method="POST" class="col-12 col-md-5 d-flex ">

                                                  <?php date_default_timezone_set('Asia/Manila'); ?>
                                                  <div class="form-group form-group-sm">
                                                       <label for=""> <small>From Date</small></label>
                                                       <input type="date" name="from_date" id="disable_date"
                                                            class="form-control form-control-sm"></input>
                                                  </div>

                                                  <div class="form-group form-group-sm mx-2">
                                                       <label for=""> <small>To Date</small></label>
                                                       <input type="date" name="to_date" id="disable_date2"
                                                            class="form-control form-control-sm"></input>
                                                  </div>
                                                  <div class="form-group form-group-sm">
                                                       <label for=""> <small>Click to Filter</small></label>
                                                       <button type="submit" name="filter_attendance"
                                                            class="btn text-white fw-semibold btn-info btn-sm d-block">Filter</button>
                                                  </div>

                                             </form>

                                        </div>

                                        <div class="container">
                                             <div class="row">
                                                  <div class="col-12">

                                                       <div class="data_table">
                                                            <table id="myDataTable"
                                                                 class="table table-striped table-bordered">
                                                                 <h5 class="dated">Date: <?php echo date('F d, Y'); ?></h5>
                                                <h1 class="sname">MCC Learning Resource Center</h1>
                                                <h2 class="tname">Attendance List</h2>
                                                <br>
                                                                 <thead>
                                                                      <tr>
                                                                      <th>Date</th>
                                                                                     <th>Time in</th>
                                                                                     <th>Full Name</th>
                                                                                     <th>Program</th>
                                                                                     <th>Time out</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      <?php
                                 
                                                       
                                 if(isset($_POST['from_date']) && isset($_POST['to_date']))
                                 {
                                      $from_date = $_POST['from_date'];
                                      $to_date = $_POST['to_date'];

                                      $query = "SELECT * FROM user_log WHERE date_log BETWEEN '$from_date' AND '$to_date' ORDER BY date_log DESC";
                                      $query_run = mysqli_query($con, $query);

                                      if(mysqli_num_rows($query_run) > 0 )
                                      {
                                           foreach($query_run as $row)
                                           {
                                 ?>
                                                          <tr>
                                                               <?php date_default_timezone_set('Asia/Manila'); ?>
                                                               
                                                               <td><?= $row['firstname'].' '.$row['middlename'].' '.$row['lastname']; ?>
                                                               </td>
                                                               <td><?= date("h:i:s a", strtotime($row['time_log'])); ?>
                                                               </td>
                                                               <td><?= date("M d, Y", strtotime($row['date_log'])); ?>
                                                               </td>
                                                               <td><?=$row['year_level'].' - '.$row['course']; ?></td>
                                                               <td><?= date("h:i:s a", strtotime($row['time_out'])); ?></td>
                                                          </tr>
                                                          <?php      }
                            }
                            
                       }
                       else
                       {
                       
                            $result= mysqli_query($con,"SELECT * FROM user_log ORDER BY date_log DESC");
                            while ($row= mysqli_fetch_array ($result) ){
                           
                                                  ?>
                                                                      <tr>
                                                                                     <?php date_default_timezone_set('Asia/Manila'); ?>
                                                                                     <td><?= date("M d, Y", strtotime($row['date_log'])); ?>
                                                                                     </td>
                                                                                     <td><?= date("h:i:s a", strtotime($row['time_log'])); ?>
                                                                                     </td>
                                                                                     <td><?= $row['firstname'].' '.$row['middlename'].' '.$row['lastname']; ?>
                                                                                     </td>
                                                                                     <td><?=$row['year_level'].' - '.$row['course']; ?></td>
                                                                                     <td><?= date("h:i:s a", strtotime($row['time_out'])); ?></td>
                                                                                </tr>
                                                                      <?php } 
                                                       }
                                                 
                                                       ?>

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
               </div>

          </div>
     </section>
</main>

<script>
        async function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.autoTable({
                html: '#myDataTable',
                styles: { fontSize: 8 },
                headStyles: { fillColor: [0, 0, 0] },
                startY: 20
            });

            doc.save('attendance_report.pdf');
        }

        function exportToExcel() {
            var wb = XLSX.utils.book_new();
            var ws_data = [
                ['Date', 'Time in', 'Full Name', 'Program', 'Time out']
            ];

            var table = document.querySelector('#myDataTable tbody');
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
            XLSX.writeFile(wb, "attendance_report.xlsx");
        }
    </script>


<?php 
include('./includes/footer.php');
include('./includes/script.php');
include('../message.php');

    
?>