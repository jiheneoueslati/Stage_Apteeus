<?php

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


$inputFileName = 'etape1_pageform.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

$compound = file_get_contents('Fichiers/metabolites.txt');
$compoundtb = unserialize($compound);

$activitea=file_get_contents('Fichiers/activitea.txt');
$activiteatb=unserialize($activitea);

$activiteb=file_get_contents('Fichiers/activiteb.txt');
$activitebtb=unserialize($activiteb);


$compoundtbfinal=array();
$activiteatbfinal=array();
$activitebtbfinal=array();

$i=0;
foreach($_POST['meta'] as $valeur)
{
    $compoundtbfinal[$i]=$compoundtb[$valeur];
    $i=$i+1;

}

$i=0;
foreach($_POST['act'] as $valeur)
{
    $activiteatbfinal[$i]=$activiteatb[$valeur];
    $i=$i+1;

}

$i=0;
foreach($_POST['actb'] as $valeur)
{
    $activitebtbfinal[$i]=$activitebtb[$valeur];
    $i=$i+1;

}


$alphas=range('F', 'Z');

$z=0;
$a=0;
for ($i = 0; $i <= sizeof($compoundtbfinal)-1; $i++){
for ($j = 0; $j <= sizeof($activiteatbfinal)-1; $j++){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$compoundtbfinal[$z]." ".$activiteatbfinal[$j]);
$a=$a+1;
}
$z=$z+1;
}

for ($f = 0; $f <= sizeof($activitebtbfinal)-1; $f++){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$activitebtbfinal[$f]);
$a=$a+1;
}

$xevo = serialize($activiteatbfinal);
file_put_contents('Fichiers/activiteafinal.txt', $xevo);

$metabolites = serialize($compoundtbfinal);
file_put_contents('Fichiers/metabolitesfinal.txt', $metabolites);


$incell =  serialize($activitebtbfinal);
file_put_contents('Fichiers/activitebfinal.txt', $incell);

//$xevo = serialize($activitetbfinal);
//$objPHPExcel->getActiveSheet()->setCellValue("A242",$xevo);
//$metabolites = serialize($compoundtbfinal);
//$objPHPExcel->getActiveSheet()->setCellValue("A243",$metabolites);
//$incell = serialize($actincell);
// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

header('Location:etape4_remplissage.php');

?>