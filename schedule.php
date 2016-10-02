<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Северская гимназия');
$pdf->SetTitle('Индивидуальные учебные планы');

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

/**
* @link http://gist.github.com/385876
*/
function csv_to_array($filename='iups.csv', $delimiter=',')
{
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

$data = csv_to_array();
//var_dump($data);


$tbl = '
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td rowspan="3">COL 1 - ROW 1<br />COLSPAN 3</td>
        <td>Предметы</td>
        <td>Часов в неделю</td>
    </tr>
    <tr>
        <td rowspan="2">COL 2 - ROW 2 - COLSPAN 2<br />text line<br />text line<br />text line<br />text line</td>
        <td>COL 3 - ROW 2</td>
    </tr>
    <tr>
       <td>COL 3 - ROW 3</td>
    </tr>

</table>
';


for ($i = 0; $i < count($data); $i++) {
    if ($data[$i]["Укажите ваш пол"] == "Женский")
        $gender = "цы ";
    else
        $gender = "ка ";
    
    
    $title = "Индивидуальный учебный план\nучени" . $gender . $data[$i]["Выберите ваш класс"][0] . $data[$i]["Выберите ваш класс"][1] . " класса " . $data[$i]["Выберите ваш класс"][5] . $data[$i]["Выберите ваш класс"][6] . "\nМБОУ «Северская гимназия»\n" . $data[$i]["Укажите ФИО в родительном падеже"];
    
    $teacher = "Дегтяренко Лариса Владимировна";
    $parent = "Мурашкина Раиса Афлисуновна";
    $student = "Мурашкина Наталья Александровна";
    
    $subscript =
"Завуч старшей школы Егорова Наталья Леонидовна
Классный руководитель " . $teacher . "
Родитель " . $parent . "
Учащийся " . $student; 
    
    
    $pdf->AddPage();
    $pdf->SetFont('LiberationSerif', '', 14);
    $pdf->Write(0, $title, '', 0, 'C', false, 0, false, false, 0);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->writeHTML($tbl);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->Write(0, $subscript, '', 0, 'L', false, 0, false, false, 0);   
}


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('iups', 'I');

?>