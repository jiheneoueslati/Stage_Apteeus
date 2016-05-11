<?php

//$compound=$objPHPExcel->getActiveSheet()->getCell('A243')->getValue();
$myfile = fopen("metabolites.txt", "r") or die("Unable to open file!");
$compound=fgets($myfile);
$compoundtb = unserialize($compound);
fclose($myfile);

//$activite=$objPHPExcel->getActiveSheet()->getCell('A242')->getValue();
$myfile = fopen("activite.txt", "r") or die("Unable to open file!");
$activite=fgets($myfile);
$activitetb=unserialize($activite);
fclose($myfile);

//$activitebis=$objPHPExcel->getActiveSheet()->getCell('A244')->getValue();
$myfile = fopen("activitebis.txt", "r") or die("Unable to open file!");
$activitebis=fgets($myfile);
$activitebistb=unserialize($activitebis);
fclose($myfile);

echo "Sélectionnez vos Métabolites";
echo'<br>';
echo'<br>';

echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($compoundtb)-1; $i++){
echo '<input type="checkbox" name="meta[]" value='.$i.' checked="checked" />';
echo $compoundtb[$i];
echo'<br>';
}


echo "<br>";
echo "Sélectionnez vos Activités";
echo'<br>';
echo'<br>';

echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activitetb)-1; $i++){
echo '<input type="checkbox" name="act[]" value='.$i.' checked="checked"  />';
echo $activitetb[$i];
echo'<br>';
}

echo'<br>';
echo "Sélectionnez vos Activités Cellule";
echo'<br>';
echo'<br>';

echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activitebistb)-1; $i++){
echo '<input type="checkbox" name="meta[]" value='.$i.'checked="checked"  />';
echo $activitebistb[$i];
echo'<br>';
}

echo '<br>';
echo '<input type="submit" value="Envoyer" />';



echo'</form>';
?>