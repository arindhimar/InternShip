<?php
require_once 'fpdf/fpdf.php';
require_once 'fpdi/fpdi.php';

function mergeAllFiles($outputFilename)
{
    $pdf = new FPDI();

    // Get all PDF files in the current directory
    $pdfFiles = glob('*.pdf');

    foreach ($pdfFiles as $pdfFile) {
        $pdf->setSourceFile($pdfFile);

        // Loop through all pages of the current PDF file
        for ($pageNumber = 1; $pageNumber <= $pdf->setSourceFile($pdfFile); $pageNumber++) {
            // Import the page and get its size
            $templateId = $pdf->importPage($pageNumber);
            $size = $pdf->getTemplateSize($templateId);

            if($size['w']>$size['h']){
                $pdf->AddPage('L', array($size['w'], $size['h']));
            }
            else{
                $pdf->AddPage('P', array($size['h'], $size['w']));
            }

            // Add a new page to the merged PDF with the size of the current page
            


            

            // Use the imported template on the newly added page
            $pdf->useTemplate($templateId);
        }
    }

    // Specify the output path for the merged PDF
    $outputPath = $outputFilename;

    // Output the merged PDF to the specified file
    $pdf->Output($outputPath, 'F');

    return $outputFilename;
}

// Example usage
$outputFilename = 'combined_output.pdf';
mergeAllFiles($outputFilename);
