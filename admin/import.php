<?php
include('authentication.php');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Increase memory limit and execution time
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];
    
    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        $chunkSize = 500; // Process 500 rows at a time
        $totalRows = count($data);

        for ($start = 1; $start < $totalRows; $start += $chunkSize) {
            $end = min($start + $chunkSize, $totalRows);

            for ($i = $start; $i < $end; $i++) {
                $row = $data[$i];

                $firstname = mysqli_real_escape_string($con, $row[0]);
                $lastname = mysqli_real_escape_string($con, $row[1]);
                $username = mysqli_real_escape_string($con, $row[2]);

                $ms_query = "INSERT INTO ms_account (firstname,lastname,username) VALUES ('$firstname', '$lastname', '$username')";
                $result = mysqli_query($con, $ms_query);
                if(!$result) {
                    $_SESSION['status'] = "Error importing row $i: " . mysqli_error($con);
                    $_SESSION['status_code'] = "error";
                    header('Location:ms_account');
                    exit(0);
                }
            }
        }

        $_SESSION['status'] = "File imported successfully.";
        $_SESSION['status_code'] = "success";
        header('Location:ms_account');
        exit(0);
    }
    else
    {
        $_SESSION['status'] = "Invalid file type. Please upload an Excel or CSV file.";
        $_SESSION['status_code'] = "error";
        header('Location:ms_account');
        exit(0);
    }
}
?>