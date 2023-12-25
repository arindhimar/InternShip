<?php

function split_pdf($filename, $end_directory = false)
{
    require_once 'fpdf/fpdf.php';
    require_once 'fpdi/fpdi.php';

    $end_directory = $end_directory ? $end_directory . date("d-m-Y__H-i-s") . "/" : './';
    $new_path = preg_replace('/[\\/]+/', '/', $end_directory . '/' . substr($filename, 0, strrpos($filename, '/')));

    if (!is_dir($new_path)) {
        // Will make directories under end directory that don't exist
        // Provided that end directory exists and has the right permissions
        mkdir($new_path, 0777, true);
    }

    $pdf = new FPDI();
    $pagecount = $pdf->setSourceFile($filename);

    // Split the first page into a new PDF
    try {
        $new_pdf = new FPDI();
        $new_pdf->AddPage();

        $new_pdf->setSourceFile($filename);
        $templateId = $new_pdf->importPage(1);//only half of the content must be taken from this page vertically

        // Get the original page size
        $size = $new_pdf->getTemplateSize($templateId);
        $width = $size['w'];
        $height = $size['h'];

        // Take only the upper half of the first page
        $newHeight = $height / 2;
        $new_pdf->useTemplate($templateId, 0, 0, $width, $newHeight);

        $new_filename = $end_directory . str_replace('.pdf', '', $filename) . '_upper_half_first_page.pdf';
        $new_pdf->Output($new_filename, "F");

        echo "File saved: " . $new_filename . "<br />\n";
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

// Test the function
split_pdf('input.pdf', 'split/');
