<?php
$pos=$_POST['position'];
$pos = explode(",", $pos);


$position = file_get_contents('Fichiers/listepositionderplaque.txt');
$positiontb = unserialize($position);


for ($i=0; $i<=sizeof($pos);$i++){
$key = array_search($pos[$i], $positiontb);

$taille=sizeof($positiontb);
if (is_numeric($key)){
 unset($positiontb[$key]);

}
else
{
	
	$positiontb[($taille)]=$pos[$i];
}

}

$position =  serialize($positiontb);
file_put_contents('Fichiers/listepositionderplaque.txt', $position);

header('Location:miseajourdmso.php');
?>
