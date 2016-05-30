<?php
$feuille=file_get_contents("Fichiers/nbfeuille.txt");
$lettre=file_get_contents("Fichiers/lettrefin.txt");
$rt=file_get_contents("Fichiers/rtposition.txt");
$rtarray=unserialize($rt);


$alphas = array();
$alpha = 'E';
while ($alpha !== 'AZ') {
    $alphas[] = $alpha++;
}

for ($j = (sizeof($rtarray)-1); $j >= 0; $j--){
$keyrt=array_search($rtarray[$j],$alphas);
array_splice($alphas,$keyrt, 1);
}


$a=0;
$formattingpart="";


While ($alphas[$a]!=$lettre){
$formattingpart=$formattingpart.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].'241"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';
$a=$a+1;
}

$formattingpart=$formattingpart.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].'241"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';



$formattingpart=$formattingpart.'<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/><extLst><ext uri="{64002731-A6B0-56B0-2670-7721B7C09600}" xmlns:mx="http://schemas.microsoft.com/office/mac/excel/2008/main"><mx:PLV Mode="0" OnePage="0" WScale="0"/></ext></extLst></worksheet>';

$fin=file_get_contents("Fichiers/nombredernierepage.txt");


$formattingpartfin="";

$a=0;

While ($alphas[$a]!=$lettre){
$formattingpartfin=$formattingpartfin.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].($fin+1).'"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';
$a=$a+1;
}

$formattingpartfin=$formattingpartfin.'<conditionalFormatting sqref="'.$alphas[$a].'1:'.$alphas[$a].($fin+1).'"><cfRule type="colorScale" priority="'.($a+1).'"><colorScale><cfvo type="min"/><cfvo type="percentile" val="50"/><cfvo type="max"/><color rgb="FFF8696B"/><color rgb="FFFFEB84"/><color rgb="FF63BE7B"/></colorScale></cfRule></conditionalFormatting>';



$formattingpartfin=$formattingpartfin.'<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/><extLst><ext uri="{64002731-A6B0-56B0-2670-7721B7C09600}" xmlns:mx="http://schemas.microsoft.com/office/mac/excel/2008/main"><mx:PLV Mode="0" OnePage="0" WScale="0"/></ext></extLst></worksheet>';


for ($j = 1; $j <= ($feuille-1); $j++){

$section = file_get_contents("fichierfinal/xl/worksheets/sheet".$j.".xml");


$debut = explode("<sheetProtection", $section);

$final=$debut[0].$formattingpart;
	
$myfile = fopen("fichierfinal/xl/worksheets/sheet".$j.".xml", "w") or die("Unable to open file!");
fwrite($myfile, $final);
fclose($myfile);
}

$section = file_get_contents("fichierfinal/xl/worksheets/sheet".($feuille).".xml");


$debut = explode("<sheetProtection", $section);

$final=$debut[0].$formattingpartfin;
	
$myfile = fopen("fichierfinal/xl/worksheets/sheet".($feuille).".xml", "w") or die("Unable to open file!");
fwrite($myfile, $final);
fclose($myfile);

header('Location:etape8_zip.php');
?> 
