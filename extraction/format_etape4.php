<?php


$myfile = fopen("nbfeuille.txt", "r") or die("Unable to open file!");
$feuille=fgets($myfile);
fclose($myfile);


for ($j = 1; $j <= $feuille; $j++){

$section = file_get_contents("fichierfinal/xl/worksheets/sheet".$j.".xml", NULL, NULL, 0, 300000);


$debut = explode("<sheetProtection", $section);

$formatting = file_get_contents('sheetcf.xml', NULL, NULL, 0, 300000);


$final=$debut[0].$formatting;
	
$myfile = fopen("fichierfinal/xl/worksheets/sheet".$j.".xml", "w") or die("Unable to open file!");
fwrite($myfile, $final);
fclose($myfile);

}

header('Location: zip_etape5.php');
?> 
