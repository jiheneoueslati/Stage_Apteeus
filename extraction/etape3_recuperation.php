<?php

//$compound=$objPHPExcel->getActiveSheet()->getCell('A243')->getValue();
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


$inputFileName = 'etape1_pageform.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


$myfile = fopen("metabolites.txt", "r") or die("Unable to open file!");
$compound=fgets($myfile);
$compoundtb = unserialize($compound);
fclose($myfile);

//$activite=$objPHPExcel->getActiveSheet()->getCell('A242')->getValue();
$myfile = fopen("activite.txt", "r") or die("Unable to open file!");
$activite=fgets($myfile);
$activitetb=unserialize($activite);
fclose($myfile);

//$activitebis=$objPHPExcel->getActiveSheet()->getCell('A244')->getValue();
$myfile = fopen("activitebis.txt", "r") or die("Unable to open file!");
$activitebis=fgets($myfile);
$activitebistb=unserialize($activitebis);
fclose($myfile);


$compoundtbfinal=array();
$activitetbfinal=array();
$activitebistbfinal=array();

$i=0;
foreach($_POST['meta'] as $valeur)
{
    $compoundtbfinal[$i]=$compoundtb[$valeur];
    $i=$i+1;

}

$i=0;
foreach($_POST['act'] as $valeur)
{
    $activitetbfinal[$i]=$activitetb[$valeur];
    $i=$i+1;

}

$alphas=range('E', 'Z');

$z=0;
$a=0;
for ($i = 0; $i <= sizeof($compoundtbfinal)-1; $i++){
for ($j = 0; $j <= sizeof($activitetbfinal)-1; $j++){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$compoundtbfinal[$z]." ".$activitetbfinal[$j]);
$a=$a+1;
}
$z=$z+1;
}


$xevo = serialize($activitetbfinal);
$myfile = fopen("activitefinal.txt", "w") or die("Unable to open file!");
$txt = $xevo;
fwrite($myfile, $txt);
fclose($myfile);


$metabolites = serialize($compoundtbfinal);
$myfile = fopen("metabolitesfinal.txt", "w") or die("Unable to open file!");
$txt = $metabolites;
fwrite($myfile, $txt);
fclose($myfile);


$incell = serialize($activitebisfinal);
$myfile = fopen("activitebisfinal.txt", "w") or die("Unable to open file!");
$txt = $incell;
fwrite($myfile, $txt);
fclose($myfile);


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