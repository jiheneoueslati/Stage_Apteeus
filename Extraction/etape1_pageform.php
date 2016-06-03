<?php

//Connexion à la BDD


$bdname    = 'sages_femmes_jo';
$serveurbd = 'localhost';
$userbd='root';
$mdpbd='root';


$connexion = new mysqli($serveurbd, $userbd, $mdpbd, $bdname);
mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);

//Renommer le fichier precedent pour ne pas saturer le serveur

$exp=file_get_contents("Fichiers/numexp.txt");
rename ('Results '.$exp.'.xlsx', 'Results.xlsx');

//Inclusion de phpExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('Feuille 1');

//Recuperation du numéro de l'expérience

$numexpe=$_POST['numexp'];
$numexp="'".$numexpe."'";

//Mise en forme du document .XLSX

$objPHPExcel->getActiveSheet()->setCellValue("A1", "Sample Number");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Sample Name");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "Row");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "Col");
$objPHPExcel->getActiveSheet()->setCellValue("E1", "INN");
$objPHPExcel->getActiveSheet()->setCellValue("E243","Mean");
$objPHPExcel->getActiveSheet()->setCellValue("E244", "SD");
$objPHPExcel->getActiveSheet()->setCellValue("E245", "CV");
$objPHPExcel->getActiveSheet()->setCellValue("B247","CTRL DMSO");
$objPHPExcel->getActiveSheet()->setCellValue("E247","Mean");
$objPHPExcel->getActiveSheet()->setCellValue("E248", "SD");
$objPHPExcel->getActiveSheet()->setCellValue("E249", "CV");

//Ajout des positions dans le document

$alphaplaque=range('C', 'N');
$pos=-1;
$o=2;
$posalpha=range(3,22);
$range=0;
for ($i = 1; $i <= 240; $i++){
if (($i-1) % 20 == 0){
	$pos=$pos+1;
	$range=0;
	}

$objPHPExcel->getActiveSheet()->setCellValue("B".$o,$numexpe);
$objPHPExcel->getActiveSheet()->setCellValue("C".$o, $alphaplaque[$pos]);
$objPHPExcel->getActiveSheet()->setCellValue("D".$o, $posalpha[$range]);
$range=$range+1;
$o=$o+1;
}
	
//Requete Metabolite

$requete1="SELECT `Metabolite_Id` FROM `metabolite_results` WHERE `metabolite_results`.`Experiment_Num`= ".$numexp." GROUP BY `metabolite_results`.`Metabolite_Id`";

$resultat1= mysqli_query($connexion,$requete1);
//$resultat1= mysql_query($requete1,$connexion);
$i=0;
$compoundid=array();
while ($test1 = mysqli_fetch_assoc($resultat1)) {
//while ($test1 = mysql_fetch_array($resultat1)) {
	$compoundid[$i]=$test1['Metabolite_Id'];
	$i=$i+1;
	}
	

//Requete Activity Metabolite

$compoundtb=$compoundid;
$requete2="SELECT `Activity` FROM `metabolite_results` WHERE `metabolite_results`.`Experiment_Num`= ".$numexp." GROUP BY `metabolite_results`.`Activity`";

$resultat2= mysqli_query($connexion,$requete2);
//$resultat2= mysql_query($requete2,$connexion);
$i=0;
$Activitytb=array();

while ($test2 = mysqli_fetch_assoc($resultat2)) {
//while ($test2 = mysql_fetch_array($resultat2)) {
	$Activitytb[$i]=$test2['Activity'];
	$i=$i+1;
	}


//Requete Activity Cellule

$requete2bis="SELECT Activity FROM `cell_results` WHERE `Experiment_Num`= ".$numexp." Group BY `Activity`";
$actincell=array();
$q=0;
$resultat2bis= mysqli_query($connexion,$requete2bis);
//$resultat2bis= mysql_query($requete2bis,$connexion);
while ($test2bis = mysqli_fetch_assoc($resultat2bis)) {
//while ($test2bis = mysql_fetch_array($resultat2bis)) {
	$actincell[$q]=$test2bis['Activity'];
	$q=$q+1;
	}

//Requete View Cellule

$requete2bis="SELECT View FROM `cell_results` WHERE `Experiment_Num`= ".$numexp." Group BY `View`";
$actview=array();
$q=0;
$resultat2bis= mysqli_query($connexion,$requete2bis);
//$resultat2bis= mysql_query($requete2bis,$connexion);
while ($test2bis = mysqli_fetch_assoc($resultat2bis)) {
//while ($test2bis = mysql_fetch_array($resultat2bis)) {
	$actview[$q]=$test2bis['View'];
	$q=$q+1;
	}

//Requete numéro plaque

$requete2bis="SELECT `Plate_Num` FROM `metabolite_results` WHERE `Experiment_Num`= ".$numexp." Group BY `Plate_Num`";
$numplaque=array();
$q=0;
$resultat2bis= mysqli_query($connexion,$requete2bis);
//$resultat2bis= mysql_query($requete2bis,$connexion);
while ($test2bis = mysqli_fetch_assoc($resultat2bis)) {
//while ($test2bis = mysql_fetch_array($resultat2bis)) {
	$numplaque[$q]=$test2bis['Plate_Num'];
	$q=$q+1;
	}

//Sauvegarde des fichiers .TXT

$xevo = serialize($Activitytb);
file_put_contents('Fichiers/Activitya.txt', $xevo);
$metabolites = serialize($compoundid);
file_put_contents('Fichiers/metabolites.txt', $metabolites);
$incell = serialize($actincell);
file_put_contents('Fichiers/Activityb.txt', $incell);
$view = serialize($actview);
file_put_contents('Fichiers/view.txt', $view);
file_put_contents('Fichiers/numexp.txt', $numexpe);
$numplaque= serialize($numplaque);
file_put_contents('Fichiers/numplaque.txt', $numplaque);
//Enregistrement du fichier

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

//Redirection 


header('Location:etape2_choix.php');



?>
