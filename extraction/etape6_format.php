<?php

$feuille=file_get_contents("Fichiers/nbfeuille.txt");
$lettre=file_get_contents("Fichiers/lettrefin.txt");

$alphas=range('E','Z');
$a=0;
$formattingpart="";


While ($alphas[$a]!=$lettre){
$formattingpart=$formattingpart.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].'1048576"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';
$a=$a+1;
}

$formattingpart=$formattingpart.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].'1048576"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';



$formattingpart=$formattingpart.'<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/><extLst><ext uri="{64002731-A6B0-56B0-2670-7721B7C09600}" xmlns:mx="http://schemas.microsoft.com/office/mac/excel/2008/main"><mx:PLV Mode="0" OnePage="0" WScale="0"/></ext></extLst></worksheet>';


for ($j = 1; $j <= $feuille; $j++){

$section = file_get_contents("fichierfinal/xl/worksheets/sheet".$j.".xml");


$debut = explode("<sheetProtection", $section);

$final=$debut[0].$formattingpart;
	
$myfile = fopen("fichierfinal/xl/worksheets/sheet".$j.".xml", "w") or die("Unable to open file!");
fwrite($myfile, $final);
fclose($myfile);

}

header('Location:etape7_zip.php');
?> 
