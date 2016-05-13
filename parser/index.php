<html>
<head>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
<h2>L'expérience</h2>

<h4>Le type</h4>
<input type="radio" name="type_exp" value="Screening">Screening<br>
<input type="radio" name="type_exp" value="DRC-Hit">DRC-Hit   <br>
<h4>L'indentifiant de la cellule</h4>
<input type="text" name="cellule">
<h4>La température (°C)</h4>
<input type="text" name="température">
<h4>Le temps d'incubations en s</h4>	
<input type="text" name="temps_incubation"><br>
 
<h2>Sélectionner les fichiers:</h2>
Les fichiers Xevo:<INPUT name="file[]" type="file" multiple /><br><br>
Les fichiers InCell:<INPUT name="file2[]" type="file" multiple /><br><br>
<INPUT type="submit" name= "valider" value="Upload">

</form>

</body>
</html>



