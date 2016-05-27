<?php

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


$inputFileName = 'Fichiers/conversion.xlsx';

/** Load $inputFileName to a PHPExcel Object  **/
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$positionconv=array();
$plaqueconv=array();
$i=1;


while (($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue())!=("")){
$position_384conv[$i]=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
$plaque_384conv[$i]=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();	
$position_96conv[$i]=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$plaque_96conv[$i]=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
$i=$i+1;
}

$inputFileName = 'Fichiers/molecules.xlsx';

/** Load $inputFileName to a PHPExcel Object  **/
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$innmol=array();
$plaquemol=array();
$positionmol=array();
$TEE=array();
$i=1;


while (($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue())!=("")){
$innmol[$i]=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
$plaquemol[$i]=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$positionmol[$i]=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
$TEE[$i]=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();

$i=$i+1;
}




file_put_contents('Fichiers/innmol.txt',serialize($innmol));
file_put_contents('Fichiers/plaquemol.txt',serialize($plaquemol));
file_put_contents('Fichiers/positionmol.txt', serialize($positionmol));
file_put_contents('Fichiers/plaque96conv.txt',serialize($plaque_96conv));
file_put_contents('Fichiers/position96conv.txt', serialize($position_96conv));
file_put_contents('Fichiers/position384conv.txt',serialize($plaque_384conv));
file_put_contents('Fichiers/plaque384conv.txt', serialize($position_384conv));
file_put_contents('Fichiers/TEE.txt', serialize($TEE));



header('Location: etape2bis_conversion.php');

?>
