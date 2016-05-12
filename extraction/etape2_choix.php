<?php

$compound = file_get_contents('Fichiers/metabolites.txt');
$compoundtb = unserialize($compound);

$activitea=file_get_contents('Fichiers/activitea.txt');
$activiteatb=unserialize($activitea);

$activiteb=file_get_contents('Fichiers/activiteb.txt');
$activitebtb=unserialize($activiteb);


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


echo "Sélectionnez vos Activités Cellules";
echo'<br>';
echo'<br>';

echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activitebtb)-1; $i++){
echo '<input type="checkbox" name="actb[]" value='.$i.' checked="checked" />';
echo $activitebtb[$i];
echo'<br>';
}


echo '<input type="submit" value="Envoyer" />';



echo'</form>';
?>