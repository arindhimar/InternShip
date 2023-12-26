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
            $midpoint = $size['h'] / 2;

            // Use the entire page
            $new_pdf->useTemplate($templateId, 0, 0, $width, $height);

            $new_pdf->SetFillColor(255, 255, 255);

            $new_pdf->Rect(0, $midpoint-23, $size['w'], $size['h'] - $midpoint, 'F');


            if($i<$pagecount){
                $i = $i + 1; // Increment here

                $templateId = $new_pdf->importPage($i);

                $new_pdf->useTemplate($templateId, 0, $midpoint+25, $width, $midpoint*2);
            }



            $new_filename = $end_directory . $i . '.pdf';
            $new_pdf->Output($new_filename, "F");

            echo "File saved: " . $new_filename . "<br />\n";
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

split_pdf('test.pdf', 'split/');

?>
