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



<div class="container-fluid" style="background-color:#33383c;color:#fff;height:45px;">
 <h4>Extraction depuis la base de données</h4>
</div>

<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="logo.png">

</div>





<div class="container">

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Création de fichiers</a></li>
    <li><a data-toggle="tab" href="#menu1">Tests DMSO</a></li>
    <li><a data-toggle="tab" href="#menu2">Mise à jour du fichier molécules</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Veuillez taper un numéro d'expérience </h3>

<form action="etape1_pageform.php" method="post">
<p>
    <input  type="text" name="numexp" placeholder="ex: 10-12 ou 10-13..."/>
    <input type="submit" value="Valider"  class="btn btn-danger" />
</p>
</form>



    </div>
    <div id="menu1" class="tab-pane fade">
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
    <div id="menu2" class="tab-pane fade">
      <h3>Mettre à jour le fichier de correspondance entre molécules et positions</h3>
<form action="upload.php" method="post" enctype="multipart/form-data">
    Sélectionner molecules.xlsx:
    <input type="file" name="fileToUpload" id="fileToUpload" class="btn btn-link" >
    <input type="submit" value="Upload" name="submit" class="btn btn-danger">
</form>

<br>      
<a href="Fichiers/molecules.png" class="btn btn-danger" role="button">Extrait du fichier à importer</a>

</div>
</div>


</body>
</html>
