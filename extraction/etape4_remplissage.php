<?php

//Connexion à la BDD

$serveurbd = 'formationweb-peda.univ-lille3.fr';
$userbd    = 'joueslati';
$mdpbd     = 'toto';
$bdname    = 'sages_femmes_jo';
$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
mysql_set_charset('utf8', $connexion);
mysql_select_db($bdname,$connexion);
/*
$bdname    = 'sages_femmes_jo';
$serveurbd = 'localhost';
$userbd='root';
$mdpbd='root';

$connexion = new mysqli($serveurbd, $userbd, $mdpbd, $bdname);
mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);
*/
//Inclusion de PHPExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


//Fonction pour colorer des cellules dans PHPEXCEL

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

//Recupération du fichier de conversion pour avoir le nom des molécules

$inputFileName = 'etape2bis_conversion.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();
$inn=array();
$i=2;

//On stocke le nom des molécules par ordre dans la plaque 
//Tant qu'il y en a,en considérant qu'il peut y avoir des trous

while ((($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue())!=("")) or (($objPHPExcel->getActiveSheet()->getCell('A'.($i+1))->getValue())!=(""))or (($objPHPExcel->getActiveSheet()->getCell('A'.($i+3))->getValue())!=(""))){
$inn[($i-2)]=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
$i=$i+1;
}

//On récupére le fichiers .XLSX avec les activités cellules et metabolites

$inputFileName = 'etape3_recuperation.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$objWorkSheetBase = $objPHPExcel->getSheet();
$objPHPExcel->getActiveSheet()->setTitle('TEEB01');

//On récupére les fichiers des activités choisies

$compound = file_get_contents('Fichiers/metabolitesfinal.txt');
$compoundtb = unserialize($compound);
$activite=file_get_contents('Fichiers/activiteafinal.txt');
$activitetb=unserialize($activite);
$activitebis=file_get_contents('Fichiers/activitebfinal.txt');
$activitebistb=unserialize($activitebis);
$position=file_get_contents('Fichiers/position384conv.txt');
$positiontb=unserialize($position);
$view=file_get_contents('Fichiers/viewfinal.txt');
$viewtb=unserialize($view);

// On recupere le numero de l'experience sur le fichier .XLSX

$numexpe=$objPHPExcel->getActiveSheet()->getCell('B2')->getValue();
$numexp="'".$numexpe."'";

// Requete pour connaitre le nombre de position, ce qui nous donnera le nombre de feuilles .

$requete="SELECT `resultat_metabolite`.`Position`,`Num_Plaque`FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience` = ".$numexp." GROUP BY `resultat_metabolite`.`Position`,`Num_Plaque`";

$resultat= mysql_query($requete,$connexion);
//$resultat= mysqli_query($connexion,$requete);


$nbfeuille=1;
$element=0;
//while ($test = mysqli_fetch_assoc($resultat)) {
while ($test = mysql_fetch_array($resultat)) {
//Reglage du nombre de feuille
$element=$element+1;
if ($element % (241+($nbfeuille-1)*240) ==0){
	$nbfeuille=$nbfeuille+1;
	
}
}

// Requete pour connaitre le nombre de position, ce qui nous donnera le nombre de feuilles.

$requete="SELECT `resultat_cellule`.`Position`,`Num_Plaque`FROM `resultat_cellule` WHERE `resultat_cellule`.`Num_Experience` = ".$numexp." GROUP BY `resultat_cellule`.`Position`,`Num_Plaque`";

$resultat= mysql_query($requete,$connexion);
//$resultat= mysqli_query($connexion,$requete);


$nbfeuillebis=1;
$element=0;
//while ($test = mysqli_fetch_assoc($resultat)) {
while ($test = mysql_fetch_array($resultat)) {


$element=$element+1;
if ($element % (241+($nbfeuillebis-1)*240) ==0){
	$nbfeuillebis=$nbfeuillebis+1;
	
}
}


if ($nbfeuillebis>$nbfeuille){
$nbfeuille=$nbfeuillebis;
}

// On stocke le nombre de feuilles dans un fichier

$myfile = fopen("Fichiers/nbfeuille.txt", "w") or die("Unable to open file!");
$txt = $nbfeuille;
fwrite($myfile, $txt);
fclose($myfile);


//On crée le nombre de feuilles dont on a besoin dans le document 

$o=2;
for ($i = 1; $i <= $nbfeuille-1; $i++){
	$objWorkSheet1 = clone $objWorkSheetBase;
	$objWorkSheet1->setTitle('TEEB0'.($i+1));
	$objPHPExcel->addSheet($objWorkSheet1);
	
}
$a=0;

//On remplit ce document avec les valeurs pour chaque feuille et chaque activité métabolite

for ($j = 0; $j <= sizeof($compoundtb)-1; $j++){
//Composants
for ($i = 0; $i <= sizeof($activitetb)-1; $i++){
$compt=0;
//Activité


$requete2="SELECT Valeur, Position, Num_Plaque FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." and Activite='".$activitetb[$i]."' and Id_Metabolite='".$compoundtb[$j]."' Group BY Num_Plaque,Position";


$resultat2= mysql_query($requete2,$connexion);
//$resultat2= mysqli_query($connexion,$requete2);


$o=0;
$move=0;

$alphas = array();
$alpha = 'F';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}

$innid=0;
$pos=0;
$idposition=2;


//while ($test2 = mysqli_fetch_assoc($resultat2)) {
while ($test2 = mysql_fetch_array($resultat2)) {
if (array_search($test2['Position'], $positiontb)!=""){
$idposition= array_search($test2['Position'], $positiontb);
}
else{
$idposition=$idposition+1;
}
$objPHPExcel->setActiveSheetIndex($move);
if (($j==0) and ($i==0)){
$objPHPExcel->getActiveSheet()->setCellValue("E".$idposition,$inn[($idposition-2)+(240*$move)]);
if ($inn[($idposition-2)+(240*$move)]==""){
cellColor('A'.$idposition, 'FF4500');
cellColor('E'.$idposition, 'FF4500');
$objPHPExcel->getActiveSheet()->setCellValue("E".$idposition,"DMSO");
}
$innid=$innid+1;
$objPHPExcel->getActiveSheet()->setCellValue("B".$idposition,$numexpe." MAP TEE".($move+1)." E".$idposition);
}
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].$idposition,$test2['Valeur']);
$compt=$compt+1;
if ($compt%240==0){
	$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."243",'=AVERAGE('.$alphas[$a].'2:'.$alphas[$a].'241)');
	$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."244",'=STDEV('.$alphas[$a].'2:'.$alphas[$a].'241)');
	$move=$move+1;
	$pos=$pos+1;
	$o=240*$pos;
}
}
$a=$a+1;
}
}



for ($j = 0; $j <= sizeof($viewtb); $j++){
for ($i = 0; $i <= sizeof($activitebistb)-1; $i++){


$compt=0;

$requete2="SELECT Valeur, Position, Num_Plaque FROM `resultat_cellule` WHERE `resultat_cellule`.`Num_Experience`= ".$numexp." and View='".$viewtb[$j]."' and Activite='".$activitebistb[$i]."' Group BY Num_Plaque,Position";

$idposition=2;

$resultat2= mysql_query($requete2,$connexion);
//$resultat2= mysqli_query($connexion,$requete2);
$o=0;
$move=0;
$pos=0;
//while ($test2 = mysqli_fetch_assoc($resultat2)) {
while ($test2 = mysql_fetch_array($resultat2)) {
if (array_search($test2['Position'], $positiontb)!=""){
$idposition= array_search($test2['Position'], $positiontb);
}
else{
$idposition=$idposition+1;
}
$objPHPExcel->setActiveSheetIndex($move);
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a].$idposition,$test2['Valeur']);
$compt=$compt+1;
if ($compt%240==0){
	$move=$move+1;
	$pos=$pos+1;
	$o=240*$pos;
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."243",'=AVERAGE('.$alphas[$a].'2:'.$alphas[$a].'241)');
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."244",'=STDEV('.$alphas[$a].'2:'.$alphas[$a].'241)');
}
}
$a=$a+1;
}
}

// On stocke la lettre de fin qui nous servira  pour savoir ou s'arreter dans le formatage conditionnel

file_put_contents('Fichiers/lettrefin.txt', $alphas[$a-1]);

// On ajuste la taille des colonnes pour toutes les feuilles 

$alphas = array();
$alpha = 'E';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}


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
$objPHPExcel->setActiveSheetIndex($nbfeuille-1);
for ($j = 241; $j >= 1; $j--){
if (($objPHPExcel->getActiveSheet()->getCell('E'.($j))->getValue())==("")){
$objPHPExcel->getActiveSheet()->removeRow($j);
}
}
// On sauvegarde le fichier .XLSX

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Redirection

header('Location: etape5_unzip.php');

?>
