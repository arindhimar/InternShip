<?php

require_once '../fpdf/fpdf.php';
require_once '../fpdi/fpdi.php';
require_once 'PDF_Rotate.php';

$flag = $_POST['flag'];

function getFile()
{
    $tarDir = "../tempL/"; //orignal File
    $fileName = $_FILES['img']['name']; //File Name
    $uploadFilePath = $tarDir . $fileName; //Target Path
    $inputPath = $_FILES['img']['tmp_name']; //temp file location
    if (move_uploaded_file($inputPath, $uploadFilePath)) { //moving to temporary location
        return 1;
    } else {
        return 0;
    }
}

function getFiles()
{
    $tarDir = "../tempL/"; // Original File
    $uploadedFiles = $_FILES['img']; // Array of files

    $successCount = 0;

    // If only one file is uploaded, make it an array for consistent handling
    if (!is_array($uploadedFiles['name'])) {
        $uploadedFiles = array(
            'name' => array($uploadedFiles['name']),
            'tmp_name' => array($uploadedFiles['tmp_name']),
        );
    }

    // Loop through each file in the array
    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        $fileName = $uploadedFiles['name'][$i]; // File Name
        $uploadFilePath = $tarDir . $fileName; // Target Path
        $inputPath = $uploadedFiles['tmp_name'][$i]; // Temp file location

        // Move each file to the target location
        if (move_uploaded_file($inputPath, $uploadFilePath)) {
            $successCount++;
        }
    }

    // Check if all files were successfully moved
    if ($successCount === count($uploadedFiles['name'])) {
        return 1;
    } else {
        return 0;
    }
}

function mergeAllFiles()
{
    $uploadedFiles = $_FILES['img']; // Array of files

    $uploadedFileNames = [];
    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        $uploadedFileNames[] = $uploadedFiles['name'][$i]; // Add file name to the array
    }

    $dir = "../tempL/";

    $pdf = new Fpdi();

    foreach ($uploadedFileNames as $fileName) {
        $pdfFile = $dir . $fileName;

        if (file_exists($pdfFile)) {
            $totalPages=$pdf->setSourceFile($pdfFile);

            // Iterate through each page of the current PDF
            //  = $pdf->getNumberOfPages();
            for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
                $templateId = $pdf->importPage($pageNumber);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage('P', array($size['w'], $size['h']));
                $pdf->useTemplate($templateId);
            }
        } else {
            // Handle the case where the file does not exist
            // You might want to log an error or take appropriate action
        }
    }

    $outputPath = '../split/';
    $pdf->Output($outputPath.'merged_pdf.pdf', 'F');

    echo json_encode(['filePath' => 'split/'.'merged_pdf.pdf']);


}

function amazonlabel($controlFlag)
{
    $tarDir = "../tempL/"; //orignal File
    $fileName = $_FILES['img']['name']; //File Name
    $uploadFilePath = $tarDir . $fileName; //Target Path
    $inputPath = $_FILES['img']['tmp_name']; //temp file location

    //Initializing Class(FPDI)
    $new_pdf = new PDF_Rotate();
    $pagecount = $new_pdf->setSourceFile($uploadFilePath);


    if ($controlFlag == 1) { //Thermal Meesho
        for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {

            if ($pageNo % 2 != 0) {

                // Specify the page you want to import
                $templateId = $new_pdf->importPage($pageNo);


                // Set the page size for the new PDF
                $new_pdf->AddPage('P', [102, 152]);

                // Set the dimension of the page
                $new_pdf->useTemplate($templateId, 0, 0, 102, 152);
            }
        }

        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file

    } else if ($controlFlag == 0) { //A4 4x4

        for ($i = 1; $i <= $pagecount; $i++) {
            if ($i % 2 != 0) {
                $templateId = $new_pdf->importPage($i);

                $new_pdf->AddPage();

                $size = $new_pdf->getTemplateSize($templateId);
                $width = $size['w'];
                $height = $size['h'];

                // Adjust width and height for a 2x2 layout
                $newWidth = $width / 2;
                $newHeight = $height / 2;


                $new_pdf->useTemplate($templateId, 0, 0, $newWidth, $newHeight);

                if ($i + 2 <= $pagecount) {
                    $i = $i + 2;
                    $templateId = $new_pdf->importPage($i);
                    $new_pdf->useTemplate($templateId, 100, 0, $newWidth, $newHeight);
                }


                if ($i + 2 <= $pagecount) {
                    $i = $i + 2;
                    $templateId = $new_pdf->importPage($i);
                    $new_pdf->useTemplate($templateId, 0, 143, $newWidth, $newHeight);
                }


                if ($i + 2 <= $pagecount) {
                    $i = $i + 2;
                    $templateId = $new_pdf->importPage($i);
                    $new_pdf->useTemplate($templateId, 100, 143, $newWidth, $newHeight);
                }
            }
        }


        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file
    }

    echo json_encode(['filePath' => $croppedFilePath]);
}

function flipkartlabel($controlFlag)
{

    $tarDir = "../tempL/"; //orignal File
    $fileName = $_FILES['img']['name']; //File Name
    $uploadFilePath = $tarDir . $fileName; //Target Path
    $inputPath = $_FILES['img']['tmp_name']; //temp file location

    //Initializing Class(FPDI)
    $new_pdf = new PDF_Rotate();
    $pagecount = $new_pdf->setSourceFile($uploadFilePath);


    if ($controlFlag == 1) { //Thermal Meesho
        for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {


            // Specify the page you want to import
            $templateId = $new_pdf->importPage($pageNo);


            // Set the page size for the new PDF
            $new_pdf->AddPage('P', [127, 92]);

            // Set the dimension of the page
            $new_pdf->useTemplate($templateId, -60, -8);
        }

        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file

    } else if ($controlFlag == 0) { //A4 4x4

        for ($i = 1; $i <= $pagecount; $i++) {

            //Adding new page
            $new_pdf->AddPage();
            $templateId = $new_pdf->importPage($i); //importing page

            $size = $new_pdf->getTemplateSize($templateId);
            $width = $size['w'];
            $height = $size['h'];

            // Adjust width and height for a 2x2 layout
            $newWidth = $width / 2;
            $newHeight = $height / 2;

            // Rotate the page by 90 degrees
            // $new_pdf->Rotate(90);
            $new_pdf->useTemplate($templateId, -65, -8, $newWidth * 2.3, $newHeight * 2.3); //importing & resizing the template


            $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

            $new_pdf->Rect(-127, 148, $size['w'], $size['h'], 'F');



            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;

                $new_pdf->useTemplate($templateId, 35, -8, $newWidth * 2.3, $newHeight * 2.3); //importing & resizing the template


                $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

                $new_pdf->Rect(30, 148, $size['w'], $size['h'], 'F');
            }

            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;

                $templateId = $new_pdf->importPage($i);

                $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

                $new_pdf->useTemplate($templateId, -65, 141, $newWidth * 2.3, $newHeight * 2.3); //importing & resizing the template

                $new_pdf->Rect(-335, 95, $size['w'], $size['h'], 'F'); //Cutting the bottom part

            }

            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;
                $templateId = $new_pdf->importPage($i);

                $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

                $new_pdf->useTemplate($templateId, 35, 141, $newWidth * 2.3, $newHeight * 2.3); //importing & resizing the template


            }
        }


        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file
    }

    echo json_encode(['filePath' => $croppedFilePath]);
}

function meesholabel($controlFlag)
{


    $tarDir = "../tempL/"; //orignal File
    $fileName = $_FILES['img']['name']; //File Name
    $uploadFilePath = $tarDir . $fileName; //Target Path
    $inputPath = $_FILES['img']['tmp_name']; //temp file location

    //Initializing Class(FPDI)
    $new_pdf = new PDF_Rotate();
    $pagecount = $new_pdf->setSourceFile($uploadFilePath);


    if ($controlFlag == 1) { //Thermal Meesho
        for ($pageNo = 1; $pageNo <= $pagecount; $pageNo++) {


            // Specify the page you want to import
            $templateId = $new_pdf->importPage($pageNo);


            // Set the page size for the new PDF
            $new_pdf->AddPage('L', [210, 128]);


            // Set the dimension of the page
            $new_pdf->useTemplate($templateId, 0, 0);
        }

        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file

    } else if ($controlFlag == 0) { //A4 4x4

        for ($i = 1; $i <= $pagecount; $i++) {

            //Adding new page
            $new_pdf->AddPage();
            $templateId = $new_pdf->importPage($i); //importing page

            $size = $new_pdf->getTemplateSize($templateId);
            $width = $size['w'];
            $height = $size['h'];

            // Adjust width and height for a 2x2 layout
            $newWidth = $width / 2;
            $newHeight = $height / 2;

            // Rotate the page by 90 degrees
            $new_pdf->Rotate(90);
            $new_pdf->useTemplate($templateId, -125, 8, $newWidth * 1.4, $newHeight * 1.4); //importing & resizing the template


            $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

            $new_pdf->Rect(-130, 95, $size['w'], $size['h'], 'F');



            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;

                $templateId = $new_pdf->importPage($i);

                $new_pdf->useTemplate($templateId, -125, 116, $newWidth * 1.4, $newHeight * 1.4); //importing & resizing the template

                $new_pdf->Rect(-130, 202, $size['w'], $size['h'], 'F'); //Cutting the bottom part

            }

            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;

                $templateId = $new_pdf->importPage($i);

                $new_pdf->SetFillColor(255, 255, 255); //Setting color to white

                $new_pdf->useTemplate($templateId, -275, 8, $newWidth * 1.4, $newHeight * 1.4); //importing & resizing the template

                $new_pdf->Rect(-335, 95, $size['w'], $size['h'], 'F'); //Cutting the bottom part

            }

            if ($i + 1 <= $pagecount) { //check if maximum pages is reached
                $i = $i + 1;

                $templateId = $new_pdf->importPage($i);

                $new_pdf->useTemplate($templateId, -275, 116, $newWidth * 1.4, $newHeight * 1.4); //importing & resizing the template

                $new_pdf->Rect(-335, 202, $size['w'], $size['h'], 'F'); //Cutting the bottom part

            }
        }


        $new_filename = 'label-cropped' . $fileName; //cropped file name
        $croppedFilePath = 'split/' . $new_filename;
        $new_pdf->Output('../split/' . $new_filename, "F"); //generating the file
    }

    echo json_encode(['filePath' => $croppedFilePath]);
}

function delFile()
{
    $tarDir = "../tempL/"; // Original File
    $fileName = $_FILES['img']['name']; // File Name
    $delFilePath = $tarDir . $fileName; // Target Path
    unlink($delFilePath);


    $new_filename = 'label-cropped' . $fileName; // Cropped file name
    $delFilePath = '../split/' . $new_filename;
    unlink($delFilePath);
}

function delFiles()
{
    $tarDir = "../tempL/"; // Original File

    if (isset($_FILES['img']['name']) && is_array($_FILES['img']['name'])) {
        foreach ($_FILES['img']['name'] as $fileName) {
            $delFilePath = $tarDir . $fileName; // Target Path
            unlink($delFilePath);

            $newFilename = 'merged_pdf.pdf'; // Cropped file name
            $delFilePath = '../split/' . $newFilename;
            unlink($delFilePath);
        }
    }
}


function imageConversion($controlFlag1, $controlFlag2)
{
    if ($controlFlag1 != $controlFlag2) {
        $tarDir = "../tempL/"; // Original File
        $fileName = $_FILES['img']['name']; // File Name
        $uploadFilePath = $tarDir . $fileName; // Target Path
        $inputPath = $_FILES['img']['tmp_name']; // Temp file location

        // Check if the uploaded file is an image
        $imageInfo = getimagesize($uploadFilePath);
        if (!$imageInfo) {
            echo json_encode(['error' => 'Invalid image file']);
            return;
        }

        // Create an image resource based on the file type
        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($uploadFilePath);
                break;
            case 'image/jpg':
                $image = imagecreatefromjpeg($uploadFilePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($uploadFilePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($uploadFilePath);
                break;
            default:
                echo json_encode(['error' => 'Unsupported image type']);
                return;
        }

        // Perform any additional image processing or modifications here

        // Create a new image with the same dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        $newImage = imagecreatetruecolor($width, $height);

        // Copy the original image onto the new image
        imagecopy($newImage, $image, 0, 0, 0, 0, $width, $height);

        // Save the new image based on controlflag2
        switch ($controlFlag2) {
            case 8:
                $outputPath = '../split/' .'converted_image.webp';
                imagewebp($newImage, $outputPath, 100); // 100 is the quality (0-100)
                $outputPath = 'split/'  . 'converted_image.webp';
                break;
            case 6:
                $outputPath = '../split/' .'converted_image.jpeg';
                imagejpeg($newImage, $outputPath, 100); // 100 is the quality (0-100)
                $outputPath = 'split/'  . 'converted_image.jpeg';

                break;
            case 7:
                $outputPath = '../split/' . 'converted_image.png';
                imagepng($newImage, $outputPath, 0); // 0 is the compression level (no compression)
                $outputPath = 'split/'  . 'converted_image.png';

                break;
            default:
                echo json_encode(['error' => 'Unsupported conversion type']);
                imagedestroy($image);
                imagedestroy($newImage);
                return;
        }

        // Free up resources
        imagedestroy($image);
        imagedestroy($newImage);

        echo json_encode(['filePath' => $outputPath]);
    } else {
        echo json_encode(['error' => 'Control flags are equal']);
    }
}



















//Function Calls
if ($flag == 0) {
    delFile();
}


if ($flag == 100) {//merge delete
    delFiles();
    
}

if ($flag == 1) { //meesho
    $ctrlFlag = $_POST['ctrlflag'];
    if (getFile() == 1) { //check if file is moved successfully!!
        meesholabel($ctrlFlag);
    } else {
        //something went wrong!!
    }
} else if ($flag == 2) { //flipkart
    $ctrlFlag = $_POST['ctrlflag'];
    if (getFile() == 1) { //check if file is moved successfully!!
        flipkartlabel($ctrlFlag);
    } else {
        //something went wrong!!
    }
} else if ($flag == 3) { //amazon
    $ctrlFlag = $_POST['ctrlflag'];
    if (getFile() == 1) { //check if file is moved successfully!!
        amazonlabel($ctrlFlag);
    } else {
        //something went wrong!!
    }   
}else if($flag==4){
    getFiles();
    try {
        $outputFilename = 'merged.pdf';
        $folderPath = 'tempL/';
        mergeAllFiles();
    } catch (\Exception $e) {
        echo json_encode(['filePath' => 'Error: ' . $e->getMessage()]);
    }
}else if($flag==5){
    getFile();
    imageConversion($flag,$_POST['ctrlflag']);
    // imageConversion($flag,8);

    
}
