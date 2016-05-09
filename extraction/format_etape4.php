<?php
$section = file_get_contents('fichierfinal/xl/worksheets/sheet1.xml', NULL, NULL, 0, 300000);


$debut = explode("<sheetProtection", $section);

$formatting = file_get_contents('sheetcf.xml', NULL, NULL, 0, 300000);


$final=$debut[0].$formatting;


$myfile = fopen("fichierfinal/xl/worksheets/sheet1.xml", "w") or die("Unable to open file!");
fwrite($myfile, $final);
fclose($myfile);

header('Location: zip_etape5.php');
?> 
