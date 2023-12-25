<?php
require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

function displayFirstPage($pdfFilePath) {
    try {
        $pdf = new FPDI();
        $pageCount = $pdf->setSourceFile($pdfFilePath);

        // Import the first page from the source PDF:
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);//getting the page 


            $pdf->AddPage();//getting a new page

            

            $pdf->Output($i.".pdf", 'F');

        }



    } catch (Exception $e) {
        echo "Error processing PDF: " . $e->getMessage();
    }
}

$pdfFilePath = 'input.pdf';
displayFirstPage($pdfFilePath);
