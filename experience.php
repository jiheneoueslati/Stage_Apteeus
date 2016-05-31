<html >
<head>
  <meta charset="utf-8"><link rel="stylesheet" type="text/css" href="style_Apteeus.css">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="format_date.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
</head>

<body>

<form method="post">
<h2>Ajouter une expérience</h2>
Date: <input type="text" id="datepicker" name="date">
<h4>Le type</h4>
<input type="radio" name="type_exp" value="Screening">Screening<br>
<input type="radio" name="type_exp" value="DRC-Hit">DRC-Hit   <br>
<h4>Le numéro de l'expérience  <input type="text" name="numexp"></h4>
<h4>L'indentifiant de la cellule  <input type="text" name="cellule"></h4>
<h4>La température (°C)  <input type="text" name="température"></h4>
<h4>Le temps d'incubations en s	<input type="text" name="temps_incubation"></h4><br><br>
<input type="submit" name="insertion" value="insérer">
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
	$sql= "insert into  experience (Num_Experience, Type, Date, Id_Cellule, Temperature, Temps_Incubation) values ('$numexp','$type_exp' ,'$date','$cellule', $température,$temps_incubation)";
	mysql_query($sql);
}