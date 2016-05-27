<?php

//Inclusion de PHPExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


//Fonction pour colorer des cellules dans PHPEXCEL

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


//On récupére le fichiers .XLSX avec les données

$inputFileName = 'etape4_remplissage.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$feuille=file_get_contents("Fichiers/nbfeuille.txt");
$lettre=file_get_contents("Fichiers/lettrefin.txt");
$dernierepage=file_get_contents("Fichiers/nombredernierepage.txt");


$alphas = array();
$alpha = 'F';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}


// Calcul des moyennes et ecart type en general


for ($j = 0; $j <= ($feuille-2); $j++){
$a=0;
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."243","=AVERAGE(".$alphas[$a]."2:".$alphas[$a]."241)");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."244","=STDEV(".$alphas[$a]."2:".$alphas[$a]."241)");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."245","=".$alphas[$a]."244*100/".$alphas[$a]."243");
$a=$a+1;
}
}

// Et pour la dernière page

for ($j = ($feuille-1); $j <= ($feuille-1); $j++){
$a=0;
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+3),"=AVERAGE(".$alphas[$a]."2:".$alphas[$a].($dernierepage+1).")");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+4),"=STDEV(".$alphas[$a]."2:".$alphas[$a].($dernierepage+1).")");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+5),"=".$alphas[$a].($dernierepage+4)."*100/".$alphas[$a].($dernierepage+3));
$a=$a+1;
}
}


/*
$position=array();
$b=0;
$positiondmso=array('F22','G15','G16','H4','H15','I9','I10','J9','J22','L4','N22','D4');
for ($j = 2; $j <= (241); $j++){
$col=$objPHPExcel->getActiveSheet()->getCell('C'.$j)->getValue();
$num=$objPHPExcel->getActiveSheet()->getCell('D'.$j)->getValue();	
if (is_numeric(array_search((($col.$num),$positiondmso)))){
	$position[$b]=$j;
}
}
*/	

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

header('Location: etape6_unzip.php');

?>