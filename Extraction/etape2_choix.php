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

<div class="container-fluid" style="background-color:#33383c;color:#fff;height:45px;">
 <h4>Choix des éléments</h4>
</div>

<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="logo.png">

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

echo '<div class="row" style=margin:20px;>';
echo '<div class="col-sm-4" style=margin:20px;>';
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
if ($i!=(sizeof($activiteatb)-1)){
echo'<br>';
}
}
echo "</div>";


//Choix des activités cellules


echo  '<div class="row" style=margin:10px;>';
echo   '<div class="col-sm-4" style=margin:10px;>';
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
echo "<br>";
echo '<input type="submit" class="btn btn-danger"  value="Envoyer" />';
echo'</form>';


?>

</BODY>
</HTML>
