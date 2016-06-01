<!DOCTYPE html>
<html lang="en">
<head>
  <title>Choix</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="bootstrap/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li><a href="../experience.php">Ajouter une expérience</a></li>
           <li><a href="../upload.php">Uploader des fichiers</a></li>
      <li><a href="creationfichier.php">Extraction de fichiers</a></li>
      <li><a href="index.php">Mise à jour du fichier molécule</a></li>
        <li class="active"><a href="miseajourdmso.php">Tests DMSO</a></li>
    </ul>
  </div>
</nav>



<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="logo.png">

</div>

<div class="row" style=margin:10px;>
<div class="col-sm-8" style=margin:10px;>

      <h3>Mettre à jour les tests DMSO pour la dernière plaque</h3>
      <form action="dmsoderniereplaque.php" method="post">
<p>
    <input type="text" name="position" placeholder="ex: C5,D3..."/>
    <input type="submit" value="Valider" class="btn btn-danger" />
</p>
</form>
<?php
$position = file_get_contents('Fichiers/listepositionderplaque.txt');
$positiontb = unserialize($position);

$keys=array_keys($positiontb); 


echo "Actuellement pour la derniere plaque les tests DMSO sont en: ";
echo "<br>";
for ($i = 0; $i <= sizeof($positiontb)-1; $i++){
if ($positiontb!=""){
echo "<h5>";	
echo $positiontb[$keys[$i]];
echo "</h5>";
echo " ";
	}
}	
?>


    </div>
    </div>
    </div>
</body>
</html>