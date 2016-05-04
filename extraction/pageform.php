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
//$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
//mysql_set_charset('utf8', $connexion);
//mysql_select_db($bdname,$connexion);
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('Feuille 1');


mysqli_set_charset('utf8', $connexion);
mysqli_select_db($bdname,$connexion);


$numexp="'10-12'";

$requete= "SELECT Nom_metabolite FROM `resultat_metabolite`,`metabolite` WHERE `resultat_metabolite`.`Id_metabolite` = `metabolite`.`Id_metabolite` AND `resultat_metabolite`.`Num_Experience`= ".$numexp." GROUP BY `metabolite`.`Nom_metabolite`";


//$resultat = mysql_query($requete,$connexion);
$resultat= mysqli_query($connexion,$requete);
$i=0;
$compoundtb=array();

while ($test = mysqli_fetch_assoc($resultat)) {
	$compoundtb[$i]=$test['Nom_metabolite'];
	$i=$i+1;
	}

$requete1="SELECT `Id_metabolite` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." GROUP BY `resultat_metabolite`.`Id_metabolite`";


//$resultat = mysql_query($requete,$connexion);
$resultat1= mysqli_query($connexion,$requete1);
$i=0;
$compoundid=array();

while ($test1 = mysqli_fetch_assoc($resultat1)) {
	$compoundid[$i]=$test1['Id_metabolite'];
	$i=$i+1;
	}

$requete2="SELECT `Id_Activite` FROM `resultat_metabolite` WHERE `resultat_metabolite`.`Num_Experience`= ".$numexp." GROUP BY `resultat_metabolite`.`Id_Activite`";

$resultat2= mysqli_query($connexion,$requete2);
$i=0;
$activitetb=array();

while ($test2 = mysqli_fetch_assoc($resultat2)) {
	$activitetb[$i]=$test2['Id_Activite'];
	$i=$i+1;
	}

$activitetb=array("RT","Area");



// Mettre des titres des colonnes

$objPHPExcel->getActiveSheet()->setCellValue("A1", "Sample Number");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Sample Name");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "Row");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "Col");

$alphas=range('E', 'Z');

$z=0;
$a=0;
for ($i = 0; $i <= sizeof($compoundtb)-1; $i++){
for ($j = 0; $j <= sizeof($activitetb)-1; $j++){
	
$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$compoundtb[$z]." ".$activitetb[$j]);
$a=$a+1;
}
$z=$z+1;
}
//Mettre la suite des info après

$requete2bis="SELECT Nom_Activite FROM `resultat_cellule`,`activite` WHERE `resultat_cellule`.`Num_Experience`= ".$numexp." AND `resultat_cellule`.`Id_activite`=`activite`.`Id_activite` Group BY `resultat_cellule`.`Id_Activite`";

$resultat2bis= mysqli_query($connexion,$requete2bis);

while ($test2bis = mysqli_fetch_assoc($resultat2bis)) {
	$objPHPExcel->getActiveSheet()->setCellValue($alphas[$a]."1",$test2bis['Nom_Activite']);
	$a=$a+1;
	}



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

for($col = 'A'; $col !== 'X'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));



// Echo done
echo date('H:i:s') . " Done writing file.\r\n";
?>