<?php


//Connexion à la BDD

//$serveurbd = 'formationweb-peda.univ-lille3.fr';
//$userbd    = 'joueslati';
//$mdpbd     = 'toto';
$bdname    = 'sages_femmes_jo';
$serveurbd = 'localhost';
//$bdname    = 'parseur';
$userbd='root';
$mdpbd='root';

$connexion = new mysqli($serveurbd, $userbd, $mdpbd, $bdname);
//$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
//mysql_set_charset('utf8', $connexion);
//mysql_select_db($bdname,$connexion);
mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);

//Inclusion de phpExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('Feuille 1');

//Recuperation du numéro de l'expérience

$numexp=$_POST['numexp'];
$numexp="'".$numexp."'";

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
$objPHPExcel->getActiveSheet()->setCellValue("A".$o, $i);
$objPHPExcel->getActiveSheet()->setCellValue("B".$o,$numexp[1].$numexp[2]."-".$numexp[4].$numexp[5]);
$objPHPExcel->getActiveSheet()->setCellValue("C".$o, $alphaplaque[$pos]);
$objPHPExcel->getActiveSheet()->setCellValue("D".$o, $posalpha[$range]);
$range=$range+1;
$o=$o+1;
}
	
//Requete Metabolite

$requete1="SELECT `Id_Metabolite` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." GROUP BY `resultat_metabolite`.`Id_Metabolite`";

$resultat1= mysqli_query($connexion,$requete1);
//$resultat1= mysql_query($requete1,$connexion);
$i=0;
$compoundid=array();
while ($test1 = mysqli_fetch_assoc($resultat1)) {
//while ($test1 = mysql_fetch_array($resultat1)) {
	$compoundid[$i]=$test1['Id_Metabolite'];
	$i=$i+1;
	}
	

//Requete Activite Metabolite

$compoundtb=$compoundid;
$requete2="SELECT `Activite` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." GROUP BY `resultat_metabolite`.`Activite`";

$resultat2= mysqli_query($connexion,$requete2);
//$resultat2= mysql_query($requete2,$connexion);
$i=0;
$activitetb=array();

while ($test2 = mysqli_fetch_assoc($resultat2)) {
//while ($test2 = mysql_fetch_array($resultat2)) {
	$activitetb[$i]=$test2['Activite'];
	$i=$i+1;
	}


//Requete Activite Cellule

$requete2bis="SELECT Activite FROM `resultat_cellule` WHERE `Num_Experience`= ".$numexp." Group BY `Activite`";
$actincell=array();
$q=0;
$resultat2bis= mysqli_query($connexion,$requete2bis);
//$resultat2bis= mysql_query($requete2bis,$connexion);
while ($test2bis = mysqli_fetch_assoc($resultat2bis)) {
//while ($test2bis = mysql_fetch_array($resultat2bis)) {
	$actincell[$q]=$test2bis['Activite'];
	$q=$q+1;
	}


//Sauvegarde des fichiers .TXT

$xevo = serialize($activitetb);
file_put_contents('Fichiers/activitea.txt', $xevo);
$metabolites = serialize($compoundid);
file_put_contents('Fichiers/metabolites.txt', $metabolites);
$incell = serialize($actincell);
file_put_contents('Fichiers/activiteb.txt', $incell);


//Enregistrement du fichier

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

//Redirection 

header('Location:etape2_choix.php');



?>
