<?php
require_once '../fpdf/fpdf.php';
require_once '../fpdi/fpdi.php';

$flag = $_POST['flag'];

if ($flag == 1) {
    $tarDir = "../tempL/";
    $uploadFilePath = $tarDir . $_FILES['img']['name'];
    $inputPath = $_FILES['img']['tmp_name'];


    $pdf = new Fpdi();

    // Set the source PDF file
    $pdf->setSourceFile($uploadFilePath);

    // Get the number of pages in the source PDF
    $numPages = $pdf->setSourceFile($uploadFilePath);

    // echo $uploadFilePath;

    // echo $numPages;

    // echo 'meesho-label.pdf';

    // echo "hello!!;";



    for ($pageNo = 1; $pageNo <= $numPages; $pageNo++) {
    
        
        // Specify the page you want to import
        $templateId = $pdf->importPage($pageNo);

        
        // Set the page size for the new PDF
        $pdf->AddPage('L', [210,128]);
        
        
        // Set the dimension of the page
        $pdf->useTemplate($templateId,0,0);

        
    }

    // // Output the cropped PDF (you can save it to a file or display it)
    $pdf->Output('../split/meesho-label.pdf', 'F'); // 'F' saves the cropped PDF to a file

    echo 'http://localhost/InternShip/SliceFinal/split/meesho-label.pdf';


}
