<?php
session_start();
include('../admin/config/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <meta name="robots" content="noindex, nofollow" />
     <link rel="icon" href="./assets/img/mcc-logo.png">
     <title>MCC Learning Resource Center</title>
     <link href="https://fonts.gstatic.com" rel="preconnect" />
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i"
          rel="stylesheet" />
     <!-- Bootstrap CSS -->
     <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

     <!-- Boxicons Icon -->
     <link href="assets/css/boxicons.min.css" rel="stylesheet" />

     <!-- Remixicon Icon -->
     <link href="assets/css/remixicon.css" rel="stylesheet" />

     <!-- Bootstrap Icon -->
     <link rel="stylesheet" href="assets/font/bootstrap-icons.css">

     <!-- Alertify JS link -->
     <link rel="stylesheet" href="assets/css/alertify.min.css" />
     <link rel="stylesheet" href="assets/css/alertify.bootstraptheme.min.css" />
     <!-- Datatables -->
     <link rel="stylesheet" href="assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">

     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap5.min.css" />

     <!-- Custom CSS -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <!-- Animation -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />
     <!-- Loader -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />

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

          #camera {
               position: fixed;
               right: 70px;
               font-size: 40px;
               color: black;
               cursor: pointer;
          }
     </style>
</head>

<body>
     <header id="header" class="header fixed-top d-flex align-items-center">
          <!-- Logo -->
          <div class="d-flex align-items-center">
               <a href="#" class="logo d-flex align-items-center">
                    <img src="assets/img/mcc-logo.png" alt="logo" class=" mx-2" />
                    <span class="d-none d-lg-block mx-2 ">MCC <span class="text-info d-block fs-6">Learning Resource
                              Center</span></span>
               </a>
          </div>
          <div class="d-flex align-items-center">
               <a href="qr_scanner.php" id="camera">
                    <i class="bi bi-camera"></i>
               </a>
          </div>
     </header>
     <main id="main" class="main">
          <section class="section dashboard">
               <div class="row">
                    <div class="col-lg-12">
                         <div class="row">
                              <div class="row">
                                   <h1 style="text-align: center;font-weight: bold;">Attendance List</h1>
                                   <div data-aos="fade-down" class="col-12">
                                        <div class="card recent-sales overflow-auto  border-3 border-top border-info">
                                             <div class="card-body">
                                                  <div class="row d-flex justify-content-around align-items-center mt-2">
                                                       <h5 class="card-title col-12 col-md-3 px-3 text-center">
                                                            Students Attendance
                                                       </h5>
                                                       <form action="" method="POST" class="col-12 col-md-6 d-flex ">

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
                                                                      <table id="example"
                                                                           class="table table-striped table-bordered">
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

     <footer id="footer" class="footer">
          <div class="copyright">
               <!-- &copy; Copyright <strong><span>JanDev</span></strong>. All Rights Reserved -->
               <strong><span>MCC</span></strong>. Learning Resource Center 2.0

          </div>

     </footer>

     <!-- Alertify JS link -->
     <script src="assets/js/alertify.min.js"></script>

     <!-- Format_number -->
     <script src="assets/js/format_number.js"></script>

     <!-- Future Date Disable JS -->
     <script src="assets/js/disable_future_date.js"></script>

     <!-- Bootstrap JS  -->
     <script src="assets/js/bootstrap.bundle.min.js"></script>

     <!-- JQuery JS -->
     <script src="assets/js/jquery-3.6.1.min.js"></script>

     <!-- JQuery Datatables -->
     <script src="assets/js/jquery.dataTables.min.js"></script>

     <!-- Boostrap 5 Datatables -->
     <script src="assets/js/chart.min.js"></script>

     <!-- Chart.js -->
     <script src="assets/js/dataTables.bootstrap5.min.js"></script>

     <!-- Dselect JS -->
     <script src="assets/js/dselect.js"></script>



     <!-- <script src="assets/js/bootstrap.bundle.min.js"></script> -->
     <!-- <script src="assets/js/jquery-3.6.0.min.js"></script> -->
     <script src="assets/js/datatables.min.js"></script>
     <script src="assets/js/pdfmake.min.js"></script>
     <script src="assets/js/vfs_fonts.js"></script>
     <script src="assets/js/custom.js"></script>




     <script type="text/javascript">
          // JQuery DataTable 
          $(document).ready(function() {
               $('#myDataTable').DataTable({

               });
          });
          $(document).ready(function() {
               $('#myDataTable2').DataTable({

               });
          });
          // $(document).ready(function() {
          //      $('#example').DataTable({

          //           dom: 'Bfrtip',
          //           buttons: [
          //                'copy', 'csv', 'excel', 'pdf', 'print'
          //           ]

          //      });
          // var table = $('#example').DataTable({
          //      buttons: [
          //           'copy', 'csv', 'excel', 'pdf', 'print'
          //      ]
          // })

          // table.buttons().container().appenndTo('#example_wrapper .col-md-6:eq(0)');
          // });
          $(document).ready(function() {

               var table = $('#example').DataTable({


               });


               table.buttons().container()
                    .appendTo('#example_wrapper .col-md-6:eq(0)');

          });
     </script>

     <!-- Tooltip link -->
     <script src="assets/js/tooltip.js"></script>
     <!-- Custom JS -->
     <script src="assets/js/main.js"></script>
     <!-- Validate Login Form -->
     <script src="assets/js/validation.js"></script>

     <!-- Loading animation -->
     <script src="assets/js/aos.js"></script>

     <script>
          AOS.init();
     </script>

</body>

</html>
