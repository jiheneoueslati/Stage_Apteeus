<?php
$pos=$_POST['position'];

$position = file_get_contents('Fichiers/listepositionderplaque.txt');
$positiontb = unserialize($position);

$key = array_search($pos, $positiontb);

$taille=sizeof($positiontb);
if (is_numeric($key)){
 unset($positiontb[$key]);

}
else
{
	
	$positiontb[($taille)]=$pos;
}



$position =  serialize($positiontb);
file_put_contents('Fichiers/listepositionderplaque.txt', $position);

header('Location:index.php');
?>