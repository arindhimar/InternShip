<?php
require_once '../fpdf/fpdf.php';
require_once '../fpdi/fpdi.php';

require_once 'PDF_Rotate.php';


$flag = $_POST['flag'];

if ($flag == 1) {
    $tarDir = "../tempL/";
    $uploadFilePath = $tarDir . $_FILES['img']['name'];
    $inputPath = $_FILES['img']['tmp_name'];

    $new_pdf = new PDF_Rotate();
    $pagecount = $new_pdf->setSourceFile($uploadFilePath);
  

    
    for ($i = 1; $i <= $pagecount; $i++) {
        $new_pdf->AddPage();
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
            
            $new_pdf->Rect(-335, 95, $size['w'], $size['h'] , 'F');

        }

        if($i+1<=$pagecount){
            $i=$i+1;

            $templateId = $new_pdf->importPage($i);

            $new_pdf->useTemplate($templateId, -275, 116, $newWidth*1.4, $newHeight*1.4);

            $new_pdf->Rect(-335, 202, $size['w'], $size['h'] , 'F');

        }

    }


    
    $new_filename = 'label-cropped'.$_FILES['img']['name'];

    $combinedFilePath='split/'.$new_filename;

    $new_pdf->Output('../split/'.$new_filename, "F");



    

        
    
    echo json_encode(['filePath' => $combinedFilePath]);
}


function delFiles($directory)
{
    $pdfFiles = glob($directory . '*.pdf');
    foreach ($pdfFiles as $pdfFile) {
        unlink($pdfFile);
    }
}
?>
