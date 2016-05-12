<?php

//$compound=$objPHPExcel->getActiveSheet()->getCell('A243')->getValue();
$myfile = fopen("Fichiers/metabolites.txt", "r") or die("Unable to open file!");
$compound=fgets($myfile);
$compoundtb = unserialize($compound);
fclose($myfile);

//$activite=$objPHPExcel->getActiveSheet()->getCell('A242')->getValue();
$myfile1 = fopen("Fichiers/activitea.txt", "r") or die("Unable to open file!");
$activitea=fgets($myfile1);
$activiteatb=unserialize($activitea);
fclose($myfile1);

//$activitebis=$objPHPExcel->getActiveSheet()->getCell('A244')->getValue();
$myfile2 = fopen("Fichiers/activiteb.txt", "r") or die("Unable to open file!");
$activiteb=fgets($myfile2);
$activitebtb=unserialize($activiteb);
fclose($myfile2);

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
for ($i = 0; $i <= sizeof($activiteatb)-1; $i++){
echo '<input type="checkbox" name="act[]" value='.$i.' checked="checked"  />';
echo $activiteatb[$i];
echo'<br>';
}
/*
echo'<br>';
echo "Sélectionnez vos Activités Cellule";
echo'<br>';
echo'<br>';

echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activitebtb)-1; $i++){
echo '<input type="checkbox" name="actb[]" value='.$i.'checked="checked"  />';
echo $activitebtb[$i];
echo'<br>';
}

echo '<br>';
*/
echo '<input type="submit" value="Envoyer" />';



echo'</form>';
?>