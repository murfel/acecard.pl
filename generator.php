<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('AceCard.pl');
$pdf->SetTitle('Кувертная карточка');

// unset header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// add fonts to PDF
$LiberationSerif = $pdf->addTTFfont('LiberationSerif-Regular.ttf', 'TrueTypeUnicode', '', 32);
$LiberationSerif = $pdf->addTTFfont('LiberationSerif-Bold.ttf', 'TrueTypeUnicode', '', 32);

// ---------------------------------------------------------

//Write a name
$FontS = 58; // начальный кегель

// начало напдиси имени
$title = $_POST["title"];
if(!empty($title)) { // если титул введен
	$StartY = 164; 
}
else { // если титул отсутствует
	$StartY = 170;
}


while($FontS>1){
	$pdf->AddPage();
	$pdf->SetY($StartY);
	$pdf->SetFont('LiberationSerif', 'B', $FontS);
	$pdf->Write(0, $_POST["ship_name"], '', 0, 'C', false, 0, false, false, 0);
	
	if($StartY == ($pdf->GetY())) { // Если строка не выходит за рамки приличия,
		break;												// оставить все как есть
	}
	else {
		$FontS--; // Кегель уменьшается
		$StartY+=0.35; // Точка начала печати надписи понижается
		// Delete all pages
		$NumPages = $pdf->getNumPages();
		for($i=1; $i<=$NumPages; $NumPages--) {
			$pdf->deletePage($i);
		}
	}	
}


// Write title
// Calc font size
$FontSt = min(30, $FontS);

while($FontSt>1){
	$pdf->SetY(189);
	$pdf->SetFont('LiberationSerif', '', $FontSt);
	//Write($h, $txt, $link='', $fill=0, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0)
	$pdf->Write(0, $_POST["title"], '', 0, 'C', false, 0, false, false, 0);
	
	if(($pdf->GetY()) == 189) { // Если строка не выходит за рамки приличия,
		break;										// оставить все как есть
	}
	
	else {
		$FontSt--; // Кегель уменьшается
		// Delete all pages
		$NumPages = $pdf->getNumPages();
		for($i=1; $i<=$NumPages; $NumPages--) {
			$pdf->deletePage($i);	
		}
		// And add removed name	
		$pdf->AddPage();
		$pdf->SetY($StartY);
		$pdf->SetFont('LiberationSerif', 'B', $FontS);
		$pdf->Write(0, $_POST["ship_name"], '', 0, 'C', false, 0, false, false, 0);
	}	
}

//-------------------------------------------------------------------------------------------------------------------------------

if($_POST["lines"] != "on") {
	$pdf->line(0, 74.25, 210, 74.25);
	$pdf->line(0, 148.5, 210, 148.5);
	$pdf->line(0, 222.75, 210, 222.75);
}

//$pdf->line(0, 185.625, 210, 185.625);

// Set promo text (if state it below "boring" some errors will ocure)
if($_POST["promo"] != "on") {
	$pdf->SetAutoPageBreak(false);
	$pdf->SetFont('LiberationSerif', '', 12);
	$pdf->SetX(10);
	$pdf->Text(10, 286, 'Created on AceCard.pl'); // x and y taked almost from the roof (I mean, ceiling)
}

// Set inverse side text
if($_POST["boring"] == "on") {

	$pdf->SetFont('LiberationSerif', '', 9);
	// mirror-bottom line
	$pdf->SetAutoPageBreak(true, 155);
	$pdf->setY(74.25);
	$pdf->setCellPaddings(0, 0, 0, 0);
	
	
	if($_POST["feed"]!="") {
		$pdf->resetColumns();
		$pdf->setEqualColumns(3, 60);
		$pdf->selectColumn();	
		$pdf->StartTransform();
		$pdf->MirrorP(105.1, 110);
		$pdf->Write(0, $_POST["feed"], '', 0, 'L', true, 0, false, true, 0);
		$pdf->StopTransform();
		// Delete pages created because of long text
		$NumPages = $pdf->getNumPages();
		for($i=1; $i<$NumPages; $NumPages--) {
			$pdf->deletePage($i+1);
		}
		
	}
	else {
		$pdf->resetColumns();
		$pdf->setEqualColumns(5, 100);
		$pdf->selectColumn();			
		$pdf->StartTransform();
		$pdf->MirrorP(105.1, 110);
		$pdf->Write(0, file_get_contents('uspenskiy.txt', false), '', 0, 'L', true, 0, false, true, 0);
		$pdf->Write(0, 'Э. Упенский', '', 0, 'R', true, 0, false, true, 0);
		$pdf->StopTransform();
	}
	
}

//Close and output PDF document
$pdf->Output('couvert_card.pdf', 'I');

?>
