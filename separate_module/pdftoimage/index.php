<?php
require_once 'fpdf/fpdf.php';


function addImageToPDF($pdf, $imagePath) {
    $pdf->AddPage();
    $pdf->Image($imagePath, 10, 10, 190);
}

function convertImagesToPDF($imagePaths, $outputFilePath) {
    $pdf = new FPDF();
    
    foreach ($imagePaths as $imagePath) {
        addImageToPDF($pdf, $imagePath);
    }

    $pdf->Output($outputFilePath, 'F');
}

$imagePaths = [
    'img (1).jpg',
    'img (2).jpg',
    'img (3).jpg',
    'img (4).jpg',
    'img (5).jpg',
];


$outputFilePath = 'output.pdf';

convertImagesToPDF($imagePaths, $outputFilePath);

?>