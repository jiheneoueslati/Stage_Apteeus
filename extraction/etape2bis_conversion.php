<?php

$inn = file_get_contents('Fichiers/innmol.txt');
$innmol = unserialize($inn);

$position = file_get_contents('Fichiers/positionmol.txt');
$positionmol = unserialize($position);

$plaque = file_get_contents('Fichiers/plaquemol.txt');
$plaquemol = unserialize($plaque);

$position= file_get_contents('Fichiers/positionconv.txt');
$positionconv = unserialize($position);

$plaque = file_get_contents('Fichiers/plaqueconv.txt');
$plaqueconv = unserialize($plaque);


include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('Feuille 1');

$objPHPExcel->getActiveSheet()->setCellValue("A1","Id_TEE");
$objPHPExcel->getActiveSheet()->setCellValue("B1","Nom_molecule");
$objPHPExcel->getActiveSheet()->setCellValue("C1","Num_plaque96");
$objPHPExcel->getActiveSheet()->setCellValue("D1","Position");

for ($id=1; $id<=2200-1; $id++){

$positionplaque=array();
$position="";
$plaque="";
global $positionconv,$plaqueconv;
$plaque=$positionconv[($id+1)];
$position=$plaqueconv[($id+1)];
$positionplaque[1]=$position;
$positionplaque[0]=$plaque;


$keypos=array_keys($positionmol,$position);
$keyplaque=array_keys($plaquemol,$plaque);

$element="";
for ($i=0; $i<=sizeof($keyplaque)-1; $i++){
for ($j=0; $j<=sizeof($keypos)-1; $j++){
	if ($keyplaque[$i]==$keypos[$j]){
		$element=$innmol[($keyplaque[$i])];
	}
}
}


$objPHPExcel->getActiveSheet()->setCellValue("A".($id+1),$id);
$objPHPExcel->getActiveSheet()->setCellValue("B".($id+1),$element);
$objPHPExcel->getActiveSheet()->setCellValue("C".($id+1),$positionconv[$id+1]);
$objPHPExcel->getActiveSheet()->setCellValue("D".($id+1),$plaqueconv[$id+1]);

}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

header('Location: depart.php');


?>
