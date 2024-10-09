<?php

//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {
     
    // Load table data from file
    public function LoadData() {
        // Read file lines
        require('config/dbcon.php');
     //    $select ="SELECT * FROM user ORDER BY user_id DESC";
     //    $query = mysqli_query($con, $select);
     //  return $query;


      $query= mysqli_query($con,"select * from return_book 
							LEFT JOIN book ON return_book.book_id = book.book_id 
							LEFT JOIN user ON return_book.user_id = user.user_id 
							where return_book.return_book_id order by return_book.return_book_id DESC") or die (mysqli_error());
								
								
							return $query;
    }

    // Colored table
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        $this->SetFont('times', 'BI', 12);
        $this->Ln(0);
        $this->Cell(180, 15, 'Date : '.date("j / n / Y"), 0, 1, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(3);
       
        
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 15, 'RETURNED BOOKS', 0,1, 'C', 0, '', false, 'M', 'M');
        // Header
        $w = array(30, 30, 30, 30, 30, 30);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');


        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row['barcode'], 'LR', 0, 'L');
            $this->Cell($w[1], 6, $row['firstname'].' '.$row['middlename'].' '.$row['lastname'], 'LR', 0, 'L');
            $this->Cell($w[2], 6, $row['title'], 'LR', 0, 'L');
            $this->Cell($w[3], 6, $row['date_borrowed'], 'LR', 0, 'L');
            $this->Cell($w[4], 6, $row['due_date'], 'LR', 0, 'L');
            $this->Cell($w[5], 6, $row['date_returned'], 'LR', 0, 'L');
           
            $this->Ln();
           
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
// $pdf->SetTitle('TCPDF Example 011');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 011', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

// column titles
$header = array('Barcode', 'Borrower Name', 'Title', 'Borrowed Books', 'Due Date', 'Date Returned');

// data loading
$data = $pdf->LoadData();


// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('returned_books.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>