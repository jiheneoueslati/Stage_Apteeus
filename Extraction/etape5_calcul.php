<?php

//Inclusion de PHPExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';



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
$objPHPExcel->getActiveSheet()->freezePane('F2');
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."243","=AVERAGE(".$alphas[$a]."2:".$alphas[$a]."241)");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."244","=STDEV(".$alphas[$a]."2:".$alphas[$a]."241)");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."245","=".$alphas[$a]."244*100/".$alphas[$a]."243");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."249","=".$alphas[$a]."248*100/".$alphas[$a]."247");
$a=$a+1;
}
}

// Et pour la dernière page

for ($j = ($feuille-1); $j <= ($feuille-1); $j++){
$a=0;
$objPHPExcel->getActiveSheet()->freezePane('F2');
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+3),"=AVERAGE(".$alphas[$a]."2:".$alphas[$a].($dernierepage+1).")");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+4),"=STDEV(".$alphas[$a]."2:".$alphas[$a].($dernierepage+1).")");
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+5),"=".$alphas[$a].($dernierepage+4)."*100/".$alphas[$a].($dernierepage+3));
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+9),"=".$alphas[$a].($dernierepage+8)."*100/".$alphas[$a].($dernierepage+7));
$a=$a+1;
}
}

//Pour le calcul dmso des premieres pages

$a=0;
$txt="(";
$objPHPExcel->setActiveSheetIndex(0);
for ($j = 2; $j <= (241); $j++){
$num=$objPHPExcel->getActiveSheet()->getCell('D'.$j)->getValue();
$col=$objPHPExcel->getActiveSheet()->getCell('C'.$j)->getValue();
$nom=$objPHPExcel->getActiveSheet()->getCell('E'.$j)->getValue();	
if ($nom=="DMSO"){
$txt=$txt."F".$j.",";
}
}
$txt=substr($txt, 0, -1);
$txt=$txt.')';

$alphas = array();
$alpha = 'F';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}

for ($j =0; $j <= ($feuille-2); $j++){
$a=0;
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$txtbis=str_replace("F", $alphas[($a)], $txt);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."247","=AVERAGE".$txtbis);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."248","=STDEV".$txtbis);
$a=$a+1;
}
}

//Pour le calcul dmso des dernieres pages

$a=0;
$txt="(";
$objPHPExcel->setActiveSheetIndex($feuille-1);
for ($j = 2; $j <= ($dernierepage+1); $j++){
$num=$objPHPExcel->getActiveSheet()->getCell('D'.$j)->getValue();
$col=$objPHPExcel->getActiveSheet()->getCell('C'.$j)->getValue();
$nom=$objPHPExcel->getActiveSheet()->getCell('E'.$j)->getValue();	
if ($nom=="DMSO"){
$txt=$txt."F".$j.",";
}
}
$txt=substr($txt, 0, -1);
$txt=$txt.')';

$alphas = array();
$alpha = 'F';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}

for ($j=0; $j <= ($feuille-1); $j++){
$a=0;
$objPHPExcel->setActiveSheetIndex($j);
While ($alphas[$a]!=$lettre){
$txtbis=str_replace("F", $alphas[($a)], $txt);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+7),"=AVERAGE".$txtbis);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].($dernierepage+8),"=STDEV".$txtbis);
$a=$a+1;
}
}


//Enregistrement du fichier

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

header('Location: etape6_unzip.php');

?>