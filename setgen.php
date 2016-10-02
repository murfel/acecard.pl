<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetAuthor('AceCard.pl');
$pdf->SetTitle('Кувертные карточки');

// unset header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// add fonts to PDF
$LiberationSerif = $pdf->addTTFfont('LiberationSerif-Regular.ttf', 'TrueTypeUnicode', '', 32);
$LiberationSerif = $pdf->addTTFfont('LiberationSerif-Bold.ttf', 'TrueTypeUnicode', '', 32);

$prostynya = $_POST["prostynya"];
$card = explode("\n", $prostynya);
$max = count($card); // количество карточек для печати


if ($_POST["type"] == "n") {
	for ($i=0; $i<$max; $i++) {
	$FontS[$i] = 58;
	$StartY[$i] = 170;
	}

	for ($i=0; $i<$max; $i++) {
		while($FontS>1){
			$pdf->AddPage();
			$pdf->SetFont('LiberationSerif', 'B', $FontS[$i]);
			$pdf->SetY($StartY[$i]);
			$pdf->Write(0, $card[$i], '', 0, 'C', false, 0, false, false, 0);
			
			if($StartY[$i] == ($pdf->GetY())) { // Если строка не выходит за рамки приличия,
				$pdf->deletePage(1); // удалить страницу и перейти к следующей карточке
				break;
			}
			else {
				$FontS[$i]--; // Кегель уменьшается
				$StartY[$i]+=0.35; // Точка начала печати надписи понижается
				// Delete all pages
				$NumPages = $pdf->getNumPages();
				for($j=1; $j<=$NumPages; $NumPages--) {
					$pdf->deletePage($j);
				}
			}
		}
	}
	
	$feed = $_POST["feed"];
	$feedset = !empty($feed);
	$lines = $_POST["lines"] != "no";
	$promo = $_POST["promo"] != "no";
	for($i=0; $i<$max; $i++) {	// Вывод всех карточек с просчитанным кеглем и началом строки
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false);
		$pdf->SetFont('LiberationSerif', 'B', $FontS[$i]);
		$pdf->SetY($StartY[$i]);
		$pdf->Write(0, $card[$i], '', 0, 'C', false, 0, false, false, 0);			
		
		if($lines) {
			$pdf->line(0, 74.25, 210, 74.25);
			$pdf->line(0, 148.5, 210, 148.5);
			$pdf->line(0, 222.75, 210, 222.75);
		}
		
		if($promo) {
			$pdf->SetAutoPageBreak(false);
			$pdf->SetFont('LiberationSerif', '', 12);
			$pdf->SetX(10);
			$pdf->Text(10, 286, 'Создано на AceCard.pl');
		}
		
		if($feedset) {			
			$pdf->SetFont('LiberationSerif', '', 9);
			$pdf->SetAutoPageBreak(true, 155);
			$pdf->setY(74.25);
			$pdf->setCellPaddings(0, 0, 0, 0);
			$pdf->resetColumns();
			$pdf->setEqualColumns(3, 60);
			$pdf->selectColumn();	
			$pdf->StartTransform();
			$pdf->MirrorP(105.1, 110);
			$pdf->Write(0, $feed, '', 0, 'L', true, 0, false, true, 0);
			$pdf->StopTransform();
			$pdf->resetColumns();
			$pdf->selectColumn();
			
			while($pdf->getNumPages() != ($i+1)) {
				$pdf->deletePage($pdf->getNumPages());
			}			
		}	
	
	}
}


else {
	for($i=0; $i<$max; $i+=2) {
		$FontS[$i] = 58;
		if(empty($card[$i+1])) $StartY[$i] = 170;
		else $StartY[$i] = 164;
	}
	
	for($i=0; $i<$max; $i+=2) {
		while($FontS>1){
			$pdf->AddPage();
			$pdf->SetFont('LiberationSerif', 'B', $FontS[$i]);
			$pdf->SetY($StartY[$i]);
			$pdf->Write(0, $card[$i], '', 0, 'C', false, 0, false, false, 0);
			
			if($StartY[$i] == ($pdf->GetY())) { // Если строка не выходит за рамки приличия,
				$pdf->deletePage(1); // удалить страницу и перейти к следующей карточке
				break;
			}
			else {
				$FontS[$i]--; // Кегель уменьшается
				$StartY[$i]+=0.35; // Точка начала печати надписи понижается
				// Delete all pages
				$NumPages = $pdf->getNumPages();
				for($j=1; $j<=$NumPages; $NumPages--) {
					$pdf->deletePage($j);
				}
			}
		}
	}

	$feed = $_POST["feed"];
	$feedset = !empty($feed);
	$lines = $_POST["lines"] != "no";
	$promo = $_POST["promo"] != "no";
	for($i=0; $i<$max; $i+=2) {	// Вывод всех карточек с просчитанным кеглем и началом строки
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false);
		$pdf->SetFont('LiberationSerif', 'B', $FontS[$i]);
		$pdf->SetY($StartY[$i]);
		$pdf->Write(0, $card[$i], '', 0, 'C', false, 0, false, false, 0);			
		$pdf->SetY(189);
		$pdf->SetFont('LiberationSerif', '', min(30, $FontS[$i]));
		$pdf->Write(0, $card[$i+1], '', 0, 'C', false, 0, false, false, 0);	
		
		if($lines) {
			$pdf->line(0, 74.25, 210, 74.25);
			$pdf->line(0, 148.5, 210, 148.5);
			$pdf->line(0, 222.75, 210, 222.75);
		}
		
		if($promo) {
			$pdf->SetAutoPageBreak(false);
			$pdf->SetFont('LiberationSerif', '', 12);
			$pdf->SetX(10);
			$pdf->Text(10, 286, 'Создано на AceCard.pl');
		}
		
		if($feedset) {			
			$pdf->SetFont('LiberationSerif', '', 9);
			$pdf->SetAutoPageBreak(true, 155);
			$pdf->setY(74.25);
			$pdf->setCellPaddings(0, 0, 0, 0);
			$pdf->resetColumns();
			$pdf->setEqualColumns(3, 60);
			$pdf->selectColumn();	
			$pdf->StartTransform();
			$pdf->MirrorP(105.1, 110);
			$pdf->Write(0, $feed, '', 0, 'L', true, 0, false, true, 0);
			$pdf->StopTransform();
			$pdf->resetColumns();
			$pdf->selectColumn();
			
			while($pdf->getNumPages() != ($i/2+1)) {
				$pdf->deletePage($pdf->getNumPages());
			}			
		}	
	
	}
}


$pdf->Output('couvert_cards', 'I');
?>
