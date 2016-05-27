<?php

$inn = file_get_contents('Fichiers/innmol.txt');
$innmol = unserialize($inn);

$position = file_get_contents('Fichiers/positionmol.txt');
$positionmol = unserialize($position);

$plaque = file_get_contents('Fichiers/plaquemol.txt');
$plaquemol = unserialize($plaque);

$position= file_get_contents('Fichiers/position96conv.txt');
$position96conv = unserialize($position);

$plaque = file_get_contents('Fichiers/plaque96conv.txt');
$plaque96conv = unserialize($plaque);

$position= file_get_contents('Fichiers/position384conv.txt');
$position384conv = unserialize($position);

$plaque = file_get_contents('Fichiers/plaque384conv.txt');
$plaque384conv = unserialize($plaque);

$TEE = file_get_contents('Fichiers/TEE.txt');
$TEE = unserialize($TEE);
$idarray=array();
$teearray=array();
$teearray[1]="TEE classe";
$plaque_384array=array();
$position_384array=array();
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('Feuille 1');

$objPHPExcel->getActiveSheet()->setCellValue("A1","INN");
$objPHPExcel->getActiveSheet()->setCellValue("B1","Position_384");
$objPHPExcel->getActiveSheet()->setCellValue("C1","Num_plaque384");
$objPHPExcel->getActiveSheet()->setCellValue("D1","TEE");
$inntrie=array();
for ($id=1; $id<=3200-1; $id++){
$positionplaque=array();
$position="";
$plaque="";
$plaque=$position96conv[($id+1)];
$position=$plaque96conv[($id+1)];
$positionplaque[1]=$position;
$positionplaque[0]=$plaque;
$result=0;
$f=0;
$keypos=array_keys($positionmol,$position);
$keyplaque=array_keys($plaquemol,$plaque);
$elementtab=array();
$element="";
for ($i=0; $i<=sizeof($keyplaque)-1; $i++){
for ($j=0; $j<=sizeof($keypos)-1; $j++){
	if ($keyplaque[$i]==$keypos[$j]){
		$element=$innmol[($keyplaque[$i])];
	$objPHPExcel->getActiveSheet()->setCellValue("B".($id+1),$plaque384conv[$id+1]);
$plaque_384array[$id]=$plaque384conv[$keyplaque[$i]];
	$objPHPExcel->getActiveSheet()->setCellValue("C".($id+1),$position384conv[$id+1]);
$position_384array[$id]=$position384conv[$keyplaque[$i]];
	$teearray[$id+1]=$TEE[$keyplaque[$i]];
	
	$objPHPExcel->getActiveSheet()->setCellValue("D".($id+1),$TEE[$keyplaque[$i]]);

			}
}
}
$idarray[$id]=$id;
$inntrie[$id-1]=$element;
$objPHPExcel->getActiveSheet()->setCellValue("A".($id+1),$element);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

file_put_contents('Fichiers/TEEclasse.txt', serialize($teearray));
file_put_contents('Fichiers/innclasse.txt', serialize($inntrie));

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));


header('Location:index.php');


?>
