<?php
require('fpdf/fpdf.php');

class CustomPDF extends FPDF {
    // Override the header method if needed
    function Header() {
        // Your header code here
    }

    // Override the footer method if needed
    function Footer() {
        // Your footer code here
    }
}

// Path to the input PDF file
$inputPdf = 'input.pdf';

// Create an instance of CustomPDF
$pdf = new CustomPDF();

// Open the input PDF
$pdf->Open();

// Get the total number of pages in the input PDF
$pages = $pdf->setSourceFile($inputPdf);

// Set a chunk size (e.g., 10 pages) to process the PDF in smaller parts
$chunkSize = 10;

for ($startPage = 1; $startPage <= $pages; $startPage += $chunkSize) {
    // Create a new output PDF for each chunk
    $pdf->AddPage();

    // Process the PDF in chunks
    for ($page = $startPage; $page <= min($startPage + $chunkSize - 1, $pages); $page++) {
        // Import the page from the input PDF
        $template = $pdf->importPage($page);

        // Use the imported page as a template
        $pdf->useTemplate($template);
    }

    // Output the PDF to a new file
    $outputPdf = 'output_chunk_' . $startPage . '_to_' . min($startPage + $chunkSize - 1, $pages) . '.pdf';
    $pdf->Output($outputPdf, 'F');

    // Clear the imported pages to free up memory
    $pdf->reset();
}

echo 'PDFs created successfully.';
