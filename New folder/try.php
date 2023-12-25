<?php

require_once 'fpdf/fpdf.php';
require_once 'fpdi/fpdi.php';

// Input PDF file
$inputPdf = 'input.pdf';

// Output PDF files for the top and bottom halves
$outputTopPdf = 'output_top.pdf';
$outputBottomPdf = 'output_bottom.pdf';

// Create a new instance of FPDI for the top half
$pdfTop = new FPDI();
$pdfTop->AddPage();
$pdfTop->setSourceFile($inputPdf);
$tplIdx = $pdfTop->importPage(1); // Assuming cutting the first page
$size = $pdfTop->getTemplateSize($tplIdx);
$midpoint = $size['h'] / 2;
$pdfTop->useTemplate($tplIdx, 0, 0, 0, $midpoint * 2);

// Set drawing color to white
$pdfTop->SetFillColor(255, 255, 255);

// Clear the bottom half of the page by drawing a white rectangle
$pdfTop->Rect(0, $midpoint, $size['w'], $size['h'] - $midpoint, 'F');

$pdfTop->useTemplate($tplIdx, 0, $midpoint, 0, $midpoint * 2);


// Output the top half to a new PDF file
$pdfTop->Output($outputTopPdf, 'F');

echo "PDF cut successfully!";
