<?php

require_once 'fpdf/fpdf.php';
require_once 'fpdi/fpdi.php';

$pdf = new FPDI();

$count = $pdf->setSourceFile('try.pdf');

for ($i = 1; $i <= $count; $i++) {
    $newPage = new FPDI();
    $newPage->setSourceFile('try.pdf');
    $newPage->AddPage();

    // Use $i instead of $pageNumber
    $templateId = $newPage->importPage($i);

    if ($templateId !== 0) { // Check if importPage was successful
        $newPage->useTemplate($templateId);

        // Call Output for each page
        $newPage->Output($i . '.pdf', 'F');
    } else {
        echo "Failed to import page $i\n";
    }
}

echo "PDF split completed.";
