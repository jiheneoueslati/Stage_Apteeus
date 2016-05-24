<?php

//Inclusion de PHPExcel

include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';


//Recuperation du fichier .XLSX de base

$inputFileName = 'etape1_pageform.xlsx';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

//Recuperation des tableaux d'activités et de metabolites

$compound = file_get_contents('Fichiers/metabolites.txt');
$compoundtb = unserialize($compound);

$activitea=file_get_contents('Fichiers/activitea.txt');
$activiteatb=unserialize($activitea);

$activiteb=file_get_contents('Fichiers/activiteb.txt');
$activitebtb=unserialize($activiteb);

$view=file_get_contents('Fichiers/view.txt');
$viewtb=unserialize($view);

//Creation de listes qui stockeront les choix de l'utilisateur

$compoundtbfinal=array();
$activiteatbfinal=array();
$activitebtbfinal=array();
$viewtbfinal=array();

$i=0;
// Recupération des choix 

//Métabolites
foreach($_POST['meta'] as $valeur)
{
    $compoundtbfinal[$i]=$compoundtb[$valeur];
    $i=$i+1;

}

$i=0;
//Activités Métabolites
foreach($_POST['act'] as $valeur)
{
    $activiteatbfinal[$i]=$activiteatb[$valeur];
    $i=$i+1;

}

$i=0;
//Activités Cellules
foreach($_POST['actb'] as $valeur)
{
    $activitebtbfinal[$i]=$activitebtb[$valeur];
    $i=$i+1;

}

$i=0;
//Activités Cellules
foreach($_POST['view'] as $valeur)
{
    $viewtbfinal[$i]=$viewtb[$valeur];
    $i=$i+1;

}

$alphas = array();
$alpha = 'F';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}


//On commence à la suite du document colonne F

$z=0;
$a=0;

//On met les entetes de colonnes composées du nom de l'activité et du métabolite
for ($i = 0; $i <= sizeof($compoundtbfinal)-1; $i++){
for ($j = 0; $j <= sizeof($activiteatbfinal)-1; $j++){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$compoundtbfinal[$z]." ".$activiteatbfinal[$j]);
$a=$a+1;
}
$z=$z+1;
}
//On met les entetes de colonnes composées du nom des activités cellules
$z=0;

for ($i = 0; $i <= sizeof($activitebtbfinal)-1; $i++){
for ($j = 0; $j <= sizeof($viewtbfinal)-1; $j++){
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$activitebtbfinal[$z]." ".$viewtbfinal[$j]);
$a=$a+1;
}
$z=$z+1;
}

// On sauvegarde ces choix dans de nouveaux fichiers .TXT

$xevo = serialize($activiteatbfinal);
file_put_contents('Fichiers/activiteafinal.txt', $xevo);

$metabolites = serialize($compoundtbfinal);
file_put_contents('Fichiers/metabolitesfinal.txt', $metabolites);


$incell =  serialize($activitebtbfinal);
file_put_contents('Fichiers/activitebfinal.txt', $incell);

$view =  serialize($viewtbfinal);
file_put_contents('Fichiers/viewfinal.txt', $view);

//On crée notre nouveau fichier .XLSX

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

//Redirection pour la suite

header('Location:etape4_remplissage.php');

?>
