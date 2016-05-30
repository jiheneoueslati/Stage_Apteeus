<!DOCTYPE html>
<html lang="en">
<head>
  <title>Choix</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
    <h2>Choix des éléments</h2> 
  </div>

<?php
 

//Recuperation des tableaux d'activité et de metabolites

$compound = file_get_contents('Fichiers/metabolites.txt');
$compoundtb = unserialize($compound);

$activitea=file_get_contents('Fichiers/activitea.txt');
$activiteatb=unserialize($activitea);

$activiteb=file_get_contents('Fichiers/activiteb.txt');
$activitebtb=unserialize($activiteb);

$view=file_get_contents('Fichiers/view.txt');
$viewtb=unserialize($view);

//Choix des métabolites

echo '<div class="row">';
echo '<div class="col-sm-4">';
echo '<h3>'."Sélectionnez vos Métabolites".'</h3>';
echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($compoundtb)-1; $i++){
echo '<input type="checkbox" name="meta[]" value='.$i.' checked="checked" />';
echo " ".$compoundtb[$i];
echo'<br>';
}


//Choix des activités


echo '<h3>'."Sélectionnez vos Activités".'</h3>';
echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activiteatb)-1; $i++){
echo '<input type="checkbox" name="act[]" value='.$i.' checked="checked"  />';
echo " ".$activiteatb[$i];
echo'<br>';
}
echo "</div>";


//Choix des activités cellules


echo  '<div class="row">';
echo   '<div class="col-sm-4">';
echo   '<h3>'."Sélectionnez vos Activités Cellules".'</h3>';
echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($activitebtb)-1; $i++){
echo '<input type="checkbox" name="actb[]" value='.$i.' checked="checked" />';
echo " ".$activitebtb[$i];
echo'<br>';
}


//Choix des vues cellules


echo   '<h3>'."Sélectionnez vos Vues Cellules".'</h3>';
echo '<form action="etape3_recuperation.php" method="post">';
for ($i = 0; $i <= sizeof($viewtb)-1; $i++){
echo '<input type="checkbox" name="view[]" value='.$i.' checked="checked" />';
echo " ".$viewtb[$i];
echo'<br>';
}
echo "</div>";
echo "<br>";
echo '<input type="submit" class="btn btn-danger"  value="Envoyer" />';
echo'</form>';


?>

</BODY>
</HTML>
