<?php
require_once '../fpdf/fpdf.php';
require_once '../fpdi/fpdi.php';

$flag = $_POST['flag'];

if ($flag == 1) {
    $tarDir = "../tempL/";
    $uploadFilePath = $tarDir . $_FILES['img']['name'];
    $inputPath = $_FILES['img']['tmp_name'];
    delFiles('../split/'); // Update the directory path here
    move_uploaded_file($inputPath, $uploadFilePath);
    $combinedFilePath = split_pdf($uploadFilePath, '../split/'); // Update the directory path here
    echo json_encode(['filePath' => $combinedFilePath]);
}

function split_pdf($filename, $end_directory = '../split/') // Update the directory path here
{
    if (!is_dir($end_directory)) {
        mkdir($end_directory, 0777, true);
    }

    $pdf = new FPDI();
    $pagecount = $pdf->setSourceFile($filename);

    try {   
        for ($i = 1; $i <= $pagecount; $i++) {
            $new_pdf = new FPDI();

            $new_pdf->setSourceFile($filename);
            $templateId = $new_pdf->importPage($i);

            $size = $new_pdf->getTemplateSize($templateId);
            $width = $size['w'];
            $height = $size['h'];
            $midpoint = $size['h'] / 2;

            $new_height = 125; // New height in millimeters (adjust as needed)

            $new_width = $width * ($new_height / $height); // Calculate new width based on the aspect ratio
            $new_pdf->AddPage('P', array($new_width, $new_height));

            $new_pdf->useTemplate($templateId, 0, 0, $size['w'], $size['h']);

            $new_filename = $end_directory . $i . '.pdf';
            $new_pdf->Output($new_filename, "F");
        }

        return mergeAllFiles($end_directory, 'combined_output.pdf');
    } catch (Exception $e) {
        // Handle exception if needed
    }
}

function mergeAllFiles($directory, $outputFilename)
{
    $pdf = new FPDI();

    $pdfFiles = glob($directory . '*.pdf');

    foreach ($pdfFiles as $pdfFile) {
        $pdf->setSourceFile($pdfFile);

        for ($pageNumber = 1; $pageNumber <= $pdf->setSourceFile($pdfFile); $pageNumber++) {
            $templateId = $pdf->importPage($pageNumber);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage('P', array($size['w'], $size['h']));
            $pdf->useTemplate($templateId);
        }
    }

    $outputPath = $directory . $outputFilename;
    $pdf->Output($outputPath, 'F');

    return 'http://localhost/InternShip/SliceFinal/split/combined_output.pdf';
}

function delFiles($directory)
{
    $pdfFiles = glob($directory . '*.pdf');
    foreach ($pdfFiles as $pdfFile) {
        unlink($pdfFile);
    }
}
?>
