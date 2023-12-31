<?php
require_once '../fpdf/fpdf.php';
require_once '../fpdi/fpdi.php';

require_once 'PDF_Rotate.php';


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
            $new_pdf = new PDF_Rotate();
            $new_pdf->AddPage();

            $new_pdf->setSourceFile($filename);

            $templateId = $new_pdf->importPage($i);

            $size = $new_pdf->getTemplateSize($templateId);
            $width = $size['w'];
            $height = $size['h'];

            // Adjust width and height for a 2x2 layout
            $newWidth = $width / 2;
            $newHeight = $height / 2;

            // Rotate the page by 90 degrees
            $new_pdf->Rotate(90);
            $new_pdf->useTemplate($templateId, -125, 8, $newWidth*1.4, $newHeight*1.4);


            $new_pdf->SetFillColor(255, 255, 255);

            $new_pdf->Rect(-130, 95, $size['w'], $size['h'] , 'F');


            if($i+1<=$pagecount){
                $i=$i+1;

                $templateId = $new_pdf->importPage($i);
    
    
                $new_pdf->useTemplate($templateId, -125, 116, $newWidth*1.4, $newHeight*1.4);
    
                $new_pdf->Rect(-130, 202, $size['w'], $size['h'] , 'F');
    
            }

            if($i+1<=$pagecount){
                $i=$i+1;

                $templateId = $new_pdf->importPage($i);
                $new_pdf->SetFillColor(255, 255, 255);

        
    
                $new_pdf->useTemplate($templateId, -275, 8, $newWidth*1.4, $newHeight*1.4);
                $new_pdf->Rect(-330, 95, $size['w'], $size['h'] , 'F');

    
            }

            if($i+1<=$pagecount){
                $i=$i+1;

                $templateId = $new_pdf->importPage($i);
    
    
                $new_pdf->useTemplate($templateId, -275, 116, $newWidth*1.4, $newHeight*1.4);
    
                $new_pdf->Rect(-335, 202, $size['w'], $size['h'] , 'F');
    
    
    
            }
            
            


            // $new_pdf->useTemplate($templateId, -90, 120, $newWidth, $newHeight);


            // Create 2x2 layout
            /*for ($row = 0; $row < 2; $row++) {
                for ($col = 0; $col < 2; $col++) {
                    // Adjust the positioning to fit within the page boundaries
                    $x = $col * $newWidth;
                    $y = $row * $newHeight;
                    $new_pdf->useTemplate($templateId, $x, $y, $newWidth, $newHeight);
                    $new_pdf->Rotate(0);

                    if ($col < 1 && $row < 1) {
                        $i++;
                        $templateId = $new_pdf->importPage($i);
                    }
                }
            }*/

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

    return 'http://localhost/InternShip/4pages/split/combined_output.pdf';
}

function delFiles($directory)
{
    $pdfFiles = glob($directory . '*.pdf');
    foreach ($pdfFiles as $pdfFile) {
        unlink($pdfFile);
    }
}
?>
