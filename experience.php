<!DOCTYPE html>
<html lang="en">
<head>
  <title>Choix</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="Extraction/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="Extraction/style.css">
  <script src="Extraction/bootstrap/jquery.min.js"></script>
  <script src="Extraction/bootstrap/js/bootstrap.min.js"></script>
</head>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li class="active"><a href="experience.php">Ajouter une expérience</a></li>
             <li><a href="upload.php">Uploader des fichiers</a></li>
      <li><a href="Extraction/creationfichier.php">Extraction de fichiers</a></li>
      <li><a href="Extraction/index.php">Mise à jour du fichier molécule</a></li>
        <li><a href="Extraction/miseajourdmso.php">Tests DMSO</a></li>
    </ul>
  </div>
</nav>





<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="Extraction/logo.png">

</div>



<body>


<div class="row" style=margin:20px;>
<div class="col-sm-6" style=margin:20px;>
<form method="post">
<h2>Ajouter une expérience</h2>
Date <input type="date" name="date">


 
 
 
<h4>Le type</h4>
<input type="radio" name="type_exp" value="Screening">Screening<br>
<input type="radio" name="type_exp" value="DRC-Hit">DRC-Hit   <br>
<h4>Le numéro de l'expérience  <input type="text" name="numexp"></h4>
<h4>L'indentifiant de la cellule  <input type="text" name="cellule"></h4>
<h4>La température (°C)  <input type="text" name="température"></h4>
<h4>Le temps d'incubations en s	<input type="text" name="temps_incubation"></h4><br><br>
<input type="submit" name="insertion" value="insérer">
<div class="row" style=margin:20px;>
<div class="col-sm-6" style=margin:20px;>
<?php
include('fonctions.php');
if ((isset($_POST['type_exp']))&& (isset($_POST['température']))&& (isset($_POST['temps_incubation']))&& (isset($_POST['cellule']))&& (isset($_POST['date'])))
{

	$type_exp=$_POST['type_exp'];
	$numexp=$_POST['numexp'];
	$date=$_POST['date'];
	$date = implode('-', array_reverse(explode('/', $date)));// mettre la date au format de phpmyadmin
	$température=$_POST['température'];
	$temps_incubation=$_POST['temps_incubation'];
	$cellule=$_POST['cellule'];

	//3- Mnt que nous disposons de toutes les propriètés de la table expérience; insertion de l'expérience dans la bdd
	connexxion();
	$sql= "insert into  experiment (Experiment_Num,Type,Date,Cell_Id,Temperature,Incubation_Time) values ('$numexp','$type_exp' ,'$date','$cellule', $température,$temps_incubation)";
	mysql_query($sql,connexxion());
}
?>
</form>
</div>
</div>

</body>
</html>
