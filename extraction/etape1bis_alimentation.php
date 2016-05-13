<?php

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


$inputFileName = 'Fichiers/conversion.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$positionconv=array();
$plaqueconv=array();
$i=1;


while (($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue())!=("")){
$positionconv[$i]=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$plaqueconv[$i]=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
$i=$i+1;
}

$inputFileName = 'Fichiers/molecules.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$innmol=array();
$plaquemol=array();
$positionmol=array();
$i=1;


while (($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue())!=("")){
$innmol[$i]=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
$plaquemol[$i]=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$positionmol[$i]=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$i=$i+1;
}




file_put_contents('Fichiers/innmol.txt',serialize($innmol));
file_put_contents('Fichiers/plaquemol.txt',serialize($plaquemol));
file_put_contents('Fichiers/positionmol.txt', serialize($positionmol));
file_put_contents('Fichiers/plaqueconv.txt',serialize($plaqueconv));
file_put_contents('Fichiers/positionconv.txt', serialize($positionconv));

header('Location: etape2bis_conversion.php');

?>