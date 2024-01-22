<?php
require_once('TCPDF/tcpdf.php');
require_once('fpdi/fpdi.php');

$pdf = new FPDI();

// Merging of the existing PDF pages to the final PDF
$pageCount = $pdf->setSourceFile('try.pdf');
for ($i = 1; $i <= $pageCount; $i++) {
    $tplIdx = $pdf->importPage($i, '/MediaBox');
    $pdf->AddPage();
    $pdf->useTemplate($tplIdx);
}

// Add password protection to the final PDF
$password = 'try';
$pdf->setProtection(array('print', 'copy'), $password, $password);

// Output the final PDF with password protection and force download
$pdf->Output('password.pdf', 'D');
?>
