<?php
include('authentication.php');

// Fetch and sanitize GET parameter
$id=intval($_GET['faculty_id']);
$query=mysqli_query($con, "select * from faculty where faculty_id='$id'");
$row = mysqli_fetch_assoc($query);
$numb=mysqli_num_rows($query); 
// Check if record exists
if ($row) {
    // Extract necessary data for printing
    $names = $row['firstname'].' '.$row['lastname'];
    $email = $row['email'];
    $contact = $row['cell_no'];
    $location_address = $row['address'];
    $profile = $row['profile_image'];
    $qrcode = $row['qr_code'];
    $contact_person = $row['contact_person'];
    $person_cell_no = $row['person_cell_no'];
    $course = $row['course'];
    $bdate = $row['birthdate'];
    $type = $row['role_as'];

    // Generate barcode
    //$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
    //$code = $Bar->getBarcode($serial, $Bar::TYPE_CODE_128);
}
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
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
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
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
     <link href="assets/css/sweetalert2.min.css" rel="stylesheet" />

     <!-- Animation -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />
     <!-- Loader -->
     <link rel="stylesheet" href="https://www.cssportal.com/css-loader-generator/" />

     <link rel="stylesheet" href="assets/css/bootstrap-datepicker.min.css">

</head>

<body>

<style>
  body {
    background:cornflowerblue; /* Set the background of the body to white */
  }

  #bg {
    height: 450px;
    margin: 60px;
    float: left;
  }

  #id {
    width: 250px;
    height: 450px;
    position: absolute;
    opacity: 0.88;
    font-family: sans-serif;
    transition: 0.4s;
    background-color: #FFFFFF;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.6);
    transition: 0.4s;
  }

  #id::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: url('../admin/images/Foxrocklogo1.jpg');
    background-repeat: repeat-x;
    background-size: 250px 450px;
    opacity: 0.2;
    z-index: -1;
    text-align: center;
  }

  .container {
    font-size: 12px;
    font-family: sans-serif;
  }

  .id-1 {
    transition: 0.4s;
    width: 250px;
    height: 450px;
    background: #FFFFFF; /* Set the background color to white */
    text-align: center;
    font-size: 16px;
    font-family: sans-serif;
    float: left;
    margin: auto;
    margin-left: 270px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.6);
    transition: 0.4s;
  }

  .img-very-small {
    width: 30px;
    height: 30px;
  }
</style>

<script type="text/javascript">	
 		
 	window.print();
  setTimeout(function(){
    window.close()
  },750)
 </script>

<div id="bg">
    <div id="id">
        <br>
            <center>
                <table>
                    <tr class="d-flex align-items justify-content-between"> 
                        <td>
                        <img src="assets/img/mcc-logo.png" alt="Avatar"  width='60px' height='60px' alt=''>
                        </td>
                        <td><p style="font-size:10px;text-align:center;"><b>MADRIDEJOS COMMUNITY COLLEGE<br>LEARNING RESOURCE CENTER</b><br><small>BUNAKAN, MADRIDEJOS, CEBU</small></p></td>
                    </tr>        
                </table>     
            </center>
            <br>
            <center>
                <?php      
                    if ($profile != "") {
                        //echo "<img src='../uploads/$profile' height='175px' width='200px' alt='' style='border: 2px solid black; border-radius: 60%;'>";
                        echo "<img src='../uploads/profile_images/$profile' alt='' style='border: 2px solid black; width: 150px; height: 150px;'>";
                        } else {
                        echo "<img src='assets/img/image.png' height='150px' width='150px' alt='' style='border: 2px solid black; border-radius: 50%;'>";
                        }
                ?> 
            </center> 
            <br>
            <div class="container" align="center">
                <p style="font-size:20px;font-weight:bold;text-transform:capitalize;color:black;"><?php if(isset($names)){ $namez=$names;echo$namez;} ?></p>
                <p style="font-weight:bold;color:black;text-transform:capitalize;"><?php echo $type; ?></p>
                <br>
            </div>
            <div style="background-color:white;">
                <p style="font-size:30px;font-weight:bold;color:black;text-align:center;"><?php echo $course; ?></p>
            </div>
    </div>

    <div class="id-1">
        <p class="text-end" style="font-size:10px;margin-top:15px;margin-right:7px;font-weight:bold;">Birthdate: <?php echo date('F j, Y', strtotime($bdate)); ?></p>
        <br>
        <br>
        <center>
            <img src="../qrcodes/<?php echo htmlspecialchars($qrcode); ?>" alt="Avatar" width="210px" height="180px" >
            <br>
            <br>
            <br>
                <div class="container" align="center">
                    <p style="color:black;">In case of emergency, please notify:</p>
                    <p class="text-start" style="color:black;margin-left:15px;font-size:14px;font-weight:bold;"><?php echo $contact_person; ?></p>
                    <p class="text-start" style="color:black;margin-left:15px;margin-top:-10px;font-size:14px;font-weight:bold;"><?php echo $person_cell_no; ?></p>
        </center>
                </div>
    </div>
</div>


<?php
include('./includes/script.php');
?>

    </body>
    </html>