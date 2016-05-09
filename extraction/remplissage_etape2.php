<?php


$serveurbd = 'localhost';
$bdname    = 'test2';
$userbd='root@localhost';
$mdpbd='root';

$connexion = new mysqli("localhost", "root", "root", "test2");
/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Echec de la connexion : %s\n", mysqli_connect_error());
    exit();
}

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


$inputFileName = 'pageform_etape1.xlsx';/** Load $inputFileName to a PHPExcel Object  **/$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();$objPHPExcel->getActiveSheet()->setTitle('TEE');

mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);

$numexp="'10-12'";

$requete="SELECT `resultat_metabolite`.`Id_TEE` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp."  Group BY `resultat_metabolite`.`Id_TEE`";

$resultat= mysqli_query($connexion,$requete);



$nbfeuille=0;
$element=0;
while ($test = mysqli_fetch_assoc($resultat)) {
//Reglage du nombre de feuille
$element=$element+1;
if ($element % 240 ==0){
	$nbfeuille=$nbfeuille+1;
	
}
}

//Bug dans la requete
$nbfeuille=3;

$o=2;
// Mettre plus de feuilles
for ($i = 1; $i <= $nbfeuille-1; $i++){
	$objWorkSheet1 = clone $objWorkSheetBase;
	$objWorkSheet1->setTitle('Feuille');
	$objPHPExcel->addSheet($objWorkSheet1);	
}
$a=0;


for ($j = 1; $j <= 4; $j++){
//Composants (1:C22)
for ($i = 1; $i <= 2; $i++){
$compt=0;
//Activité (1: RT : 2 Area)
$requete2="SELECT Valeur FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." and Id_Activite=".$i." and Id_Metabolite=".$j." Group BY Id_TEE";


$resultat2= mysqli_query($connexion,$requete2);
$o=2;
$move=0;
$alphas=range('E','Z');
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
}


for ($i = 6; $i <= 9; $i++){
$compt=0;
//Activité (1: RT : 2 Area)
$requete2="SELECT Valeur FROM `resultat_cellule` WHERE `resultat_cellule`.`Num_Experience`= ".$numexp." and Id_Activite=".$i." Group BY Id_TEE";


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


// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));



// Echo done
//echo date('H:i:s') . " Done writing file.\r\n";

header('Location: unzip_etape3.php');

?>