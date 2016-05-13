<?php


$serveurbd = 'localhost';
$bdname    = 'parseur';
$userbd='root@localhost';
$mdpbd='root';

$connexion = new mysqli("localhost", "root", "root", "parseur");
/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Echec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$inputFileName = 'etape2bis_conversion.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();


$inn=array();
$i=2;


while ((($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue())!=("")) or (($objPHPExcel->getActiveSheet()->getCell('B'.($i+1))->getValue())!=(""))or (($objPHPExcel->getActiveSheet()->getCell('B'.($i+3))->getValue())!=(""))){
$inn[($i-2)]=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
$i=$i+1;
}



$inputFileName = 'etape3_recuperation.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();$objPHPExcel->getActiveSheet()->setTitle('TEEB01');$compound = file_get_contents('Fichiers/metabolitesfinal.txt');
$compoundtb = unserialize($compound);

$activite=file_get_contents('Fichiers/activiteafinal.txt');
$activitetb=unserialize($activite);

$activitebis=file_get_contents('Fichiers/activitebfinal.txt');
$activitebistb=unserialize($activitebis);


mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);


$numexpe=$objPHPExcel->getActiveSheet()->getCell('B2')->getValue();
$numexp="'".$numexpe."'";

$requete="SELECT `resultat_metabolite`.`Id_TEE` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp."  Group BY `resultat_metabolite`.`Id_TEE`";

$resultat= mysqli_query($connexion,$requete);


$nbfeuille=1;
$element=0;
while ($test = mysqli_fetch_assoc($resultat)) {

//Reglage du nombre de feuille
$element=$element+1;
if ($element % (241+($nbfeuille-1)*240) ==0){
	$nbfeuille=$nbfeuille+1;
	
}
}
$myfile = fopen("Fichiers/nbfeuille.txt", "w") or die("Unable to open file!");
$txt = $nbfeuille;
fwrite($myfile, $txt);
fclose($myfile);


$o=2;
// Mettre plus de feuilles
for ($i = 1; $i <= $nbfeuille-1; $i++){
	$objWorkSheet1 = clone $objWorkSheetBase;
	$objWorkSheet1->setTitle('TEEB0'.($i+1));
	$objPHPExcel->addSheet($objWorkSheet1);	
}
$a=0;

for ($j = 0; $j <= sizeof($compoundtb)-1; $j++){
//Composants
for ($i = 0; $i <= sizeof($activitetb)-1; $i++){
$compt=0;
//Activité


$requete2="SELECT Valeur FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." and Id_Activite='".$activitetb[$i]."' and Id_Metabolite='".$compoundtb[$j]."' Group BY Id_TEE";


$resultat2= mysqli_query($connexion,$requete2);
$o=2;
$move=0;
$alphas=range('F','Z');
$innid=0;
while ($test2 = mysqli_fetch_assoc($resultat2)) {
//Rentrer les valeurs
//for ($i = 1; $i <= 5; $i++){
$objPHPExcel->setActiveSheetIndex($move);
$objPHPExcel->getActiveSheet()->setCellValue("E".$o,$inn[$innid]);
if ($inn[$innid]==""){
cellColor('E'.$o, 'FF4500');
}
$innid=$innid+1;
$objPHPExcel->getActiveSheet()->setCellValue("B".$o,$numexpe." MAP TEE".($move+1)." E".($o-1));
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].$o,$test2['Valeur']);
//$objPHPExcel->getActiveSheet()->setCellValue("E".$o,$test2['valeur']);
$o=$o+1;
$compt=$compt+1;
if ($compt%240==0){
	$move=$move+1;
	$o=2;
}
}
$a=$a+1;
}
}


for ($i = 0; $i <= sizeof($activitebistb)-1; $i++){

$compt=0;

$requete2="SELECT Valeur FROM `resultat_cellule` WHERE `resultat_cellule`.`Num_Experience`= ".$numexp." and Id_Activite='".$activitebistb[$i]."' Group BY Id_TEE";
$resultat2= mysqli_query($connexion,$requete2);
$o=2;
$move=0;
while ($test2 = mysqli_fetch_assoc($resultat2)) {
//Rentrer les valeurs
//for ($i = 1; $i <= 5; $i++){
$objPHPExcel->setActiveSheetIndex($move);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].$o,$test2['Valeur']);
//$objPHPExcel->getActiveSheet()->setCellValue("E".$o,$test2['valeur']);
$o=$o+1;
$compt=$compt+1;
if ($compt%240==0){
	$move=$move+1;
	$o=2;
}
}
$a=$a+1;
}

file_put_contents('Fichiers/lettrefin.txt', $alphas[$a-1]);
$alphas=range('E','Z');

for ($i = 0; $i <= ($nbfeuille-1); $i++){
$objPHPExcel->setActiveSheetIndex($i);
for ($j = 0; $j <= sizeof($alphas)-1; $j++){
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension($alphas[$j])->setWidth(30);
}
}


// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));



// Echo done
//echo date('H:i:s') . " Done writing file.\r\n";

header('Location: etape5_unzip.php');

?>