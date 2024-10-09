<?php 
include('authentication.php');

require('./tcpdf/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 36);
$pdf->Cell(0,22, 'Laxmi Academy', 0, 1, 'C', 0, '', false, 'M', 'M');
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 15, 'Rampur Road, Shivneri Nagar', 0,1, 'C', 0, '', false, 'M', 'M');
$pdf->Cell(0, 15, 'Degloor Dist Nanded',  0,1, 'C', 0, '', false, 'M', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(72, 15, 'E-Mail : santosh@jswebapp.com', 0, 0, 'L', 0, '', false, 'M', 'M');


$pdf->Line(10, 49, 200, 49);
$pdf->Line(10, 51, 200, 51);

$pdf->SetFont('times', 'BI', 12);
$pdf->Ln(20);
$pdf->Cell(180, 15, 'Date : '.date("j / n / Y"), 0, 1, 'R', 0, '', 0, false, 'M', 'M');
$pdf->Ln(3);

$pdf->Cell(90, 10, 'Batch : One', 0, 0, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90, 10, 'Student No : 10', 0, 1, 'L', 0, '', 0, false, 'M', 'M');

$pdf->Ln(3);
$pdf->Cell(90, 10, 'Batch : One', 0, 0, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90, 10, 'Medium : One', 0, 1, 'L', 0, '', 0, false, 'M', 'M');

$pdf->Ln(3);
$pdf->Cell(90, 10, 'Gender : Male', 0, 0, 'L', 0, '', 0, false, 'M', 'M');
$pdf->Cell(90, 10, 'Mobile : 10', 0, 1, 'L', 0, '', 0, false, 'M', 'M');


$tbl = <<<EOD
     <table border="1" cellpadding="2" cellspacing="2">
          <tr>
               <th colspan="4" align="center" style="font-size:18px; font-weight: bold;">STUDENT DETAILS</th>
          </tr>
          <tr>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Student ID
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Full Name
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Course
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Year Level
               </td>
               
          </tr>
     </table>
 EOD;

 $sql = "SELECT * FROM user ORDER BY user_id DESC";
 if($result = mysqli_query($con, $sql))
 {
     while($row = mysqli_fetch_assoc($result))
     {
        
         
               $student_id = $row['student_id_no'];
         



          $tbl .= <<<EOD
         <tr>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Full Name
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Course
               </td>
               <td width="25%" style="text-align:center; vertical-align:middle; font-weight:bold;">Year Level
               </td>
               
          </tr>
          EOD;
     }
 }



 
         
  

 $pdf->writeHTML($tbl, true,false,false,false,'');
$pdf->Output('example.pdf', 'I');
?>