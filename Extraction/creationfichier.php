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
      <li class="active"><a href="creationfichier.php">Extraction de fichiers</a></li>
      <li><a href="index.php">Mise à jour du fichier molécule</a></li>
        <li><a href="miseajourdmso.php">Tests DMSO</a></li>
    </ul>
  </div>
</nav>


<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="logo.png">
</div>



<div class="row" style=margin:20px;>
<div class="col-sm-8" style=margin:20px;>
  <div class="tab-content">
      <h3>Veuillez entrer un numéro d'expérience </h3>

<form action="etape1_pageform.php" method="post">
<p>
    <input  type="text" name="numexp" placeholder="ex: 10-12 ou 10-13..."/>
    <input type="submit" value="Valider"  class="btn btn-danger" />
</p>
</form>

    </div>
    </div>
    </div>
    

 
</body>
</html>


