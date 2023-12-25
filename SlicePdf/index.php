
<?php
require_once 'vendor/autoload.php';

use setasign\fpdi\src\Fpdi;

// Path to the input PDF file
$inputPdf = 'input.pdf';

// Create an instance of FPDI
$pdf = new Fpdi();

// Set the source file (input PDF)
$pages = $pdf->setSourceFile($inputPdf);

// Import each page from the source PDF
for ($page = 1; $page <= $pages; $page++) {
    // Add a page to the destination FPDF document
    $pdf->AddPage();

    // Import the page from the source PDF
    $tplId = $pdf->importPage($page);

    // Use the imported page as a template
    $pdf->useTemplate($tplId, 0, 0, 210);

    // Additional FPDF commands for customization if needed
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(10, 10);
    $pdf->Cell(0, 10, 'This is a custom text on the imported page.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output('output.pdf', 'F');

echo 'PDF created successfully.';
