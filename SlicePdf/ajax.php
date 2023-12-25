<?php

$flag=$_POST['flag'];

if ($flag == 1) {
    $tarDir = "tempL/";
    $uploadFilePath = $tarDir . $_FILES['img']['name'];

    move_uploaded_file($_FILES['img']['tmp_name'], $uploadFilePath);

    $inputPath = $tarDir . $_FILES['img']['name'];

    // Open the multi-page image
    $imagick = new Imagick();
    $imagick->readImage($inputPath);

    // Get the total number of pages
    $totalPages = $imagick->getNumberImages();

    // Iterate through each page and save it as a separate file
    for ($i = 0; $i < $totalPages; $i++) {
        // Set the iterator to the current page
        $imagick->setIteratorIndex($i);

        // Get the image data of the current page
        $pageImage = $imagick->getImageBlob();

        // Save the current page as a separate file
        $outputPath = $tarDir . 'page_' . ($i + 1) . '.png'; // Adjust the file format if needed
        file_put_contents($outputPath, $pageImage);
    }

    // Close the Imagick instance
    $imagick->clear();
}

// ... (your existing code)
?>
