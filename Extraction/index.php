<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Accueil</title>
 
    </head>
    <body> 

<p>
    Veuillez taper un numéro d'expérience :
</p>

<form action="etape1_pageform.php" method="post">
<p>
    <input type="text" name="numexp" placeholder="ex: 10-12 ou 10-13..."/>
    <input type="submit" value="Valider" />
</p>
</form>

<p>
    Vous pouvez aussi choisir de mettre à jour le fichier de correspondance entre molécules et positions.
</p>

<a href="etape1bis_alimentation.php">Mise à jour des fichiers de correspondance</a>

<br>
<img src="Fichiers/molecule.png" border="0" />
<br>
<p>molecules.xlsx à inserer dans Extraction/Fichiers</p>

<p>
 Si vous voulez rajouter ou enlever un test DMSO pour la dernière plaque
</p>

<form action="dmsoderniereplaque.php" method="post">
<p>
    <input type="text" name="position" placeholder="ex: C5,D3..."/>
    <input type="submit" value="Valider" />
</p>
</form>
<?php
$position = file_get_contents('Fichiers/listepositionderplaque.txt');
$positiontb = unserialize($position);

$keys=array_keys($positiontb); 


echo "Actuellement pour la derniere plaque les tests DMSO sont en: ";
for ($i = 0; $i <= sizeof($positiontb)-1; $i++){
if ($positiontb!=""){
echo $positiontb[$keys[$i]];
echo " ";
	}
}	
?>

</body>
</html>
