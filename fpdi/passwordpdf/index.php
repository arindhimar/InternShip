<?php
error_reporting( E_ALL ^ E_DEPRECATED );
//http://www.setasign.de/products/pdf-php-solutions/fpdi-protection-128/

function pdfEncrypt ($origFile, $password, $destFile){

	require_once('fpdi/FPDI_Protection.php');
	$pdf = new FPDI_Protection();

	$pdf->FPDF("P", "in", array('8.27','11.69'));

	$pagecount = $pdf->setSourceFile($origFile);

	for ($loop = 1; $loop <= $pagecount; $loop++) {
   	 	$tplidx = $pdf->importPage($loop);
    	$pdf->addPage();
    	$pdf->useTemplate($tplidx);
	}

	$pdf->SetProtection(array(),$password);
	$pdf->Output($destFile,'F');

	return $destFile;
}

	$password = "a2zwebhelp";
	$origFile = "AuditReport.pdf";
	$destFile = "AuditReport_p.pdf";
	
	pdfEncrypt($origFile, $password, $destFile );
	
?>