<?php

function split_pdf($filename, $end_directory = 'split/')
{
    require_once 'fpdf/fpdf.php';
    require_once 'fpdi/fpdi.php';

    // Ensure the end directory exists
    if (!is_dir($end_directory)) {
        mkdir($end_directory, 0777, true);
    }

    $pdf = new FPDI();
    $pagecount = $pdf->setSourceFile($filename);

    // Split every page into a new PDF
    try {
        for ($i = 1; $i <= $pagecount; $i++) {
            $new_pdf = new FPDI();
            $new_pdf->AddPage();

            $new_pdf->setSourceFile($filename);

            $templateId = $new_pdf->importPage($i);

            // Get the original page size
            $size = $new_pdf->getTemplateSize($templateId);
            $width = $size['w'];
            $height = $size['h'];

            // Use the entire page
            $new_pdf->useTemplate($templateId, 0, 0, $width, $height);

            $new_filename = $end_directory . $i . '.pdf';
            $new_pdf->Output($new_filename, "F");

            echo "File saved: " . $new_filename . "<br />\n";
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

// Test the function
split_pdf('test.pdf', 'split/');
