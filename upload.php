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
<body>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li ><a href="experience.php">Ajouter une expérience</a></li>
            <li class="active"><a href="upload.php">Uploader des fichiers</a></li>
      <li><a href="Extraction/creationfichier.php">Extraction de fichiers</a></li>
      <li><a href="Extraction/index.php">Mise à jour du fichier molécule</a></li>
        <li><a href="Extraction/miseajourdmso.php">Tests DMSO</a></li>
    </ul>
  </div>
</nav>




<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="Extraction/logo.png">

</div>



<?php
// Liste de sélection des num exp
include('fonctions.php');
$requete = " SELECT DISTINCT (Num_Experience) FROM experience ORDER BY  Num_Experience ASC  "; 
$tab=liste_req_sql($requete);
	
echo '<div class="row" style=margin:20px;>';
echo '<div class="col-sm-6" style=margin:20px;>';
echo '<h2>Sélectionner un numéro expérience:</h2>
<form method="post">';
	echo '<select name="numexp" onchange="this.form.submit()">',"n";
	echo '<option value="select_numexp"> Num_Experience </option>';
	foreach($tab as $e)// affichage des villes dans la liste
	{
        echo '<option value="'.$e[0].'">'.$e[0].'</option>';
    } 
     echo '</select>',"<br/>";
echo '</form>';
if(isset($_POST['numexp'])) 
{
	$num=$_POST['numexp'];
	echo $num;
}
echo '</div>';
echo '</div>';

// zone de sélection des fichiers
	
echo '<div class="row" style=margin:20px;>';
echo '<div class="col-sm-6" style=margin:20px;>';
echo'
<h2>Sélectionner les fichiers:</h2>
<br>
<form  method="post" enctype="multipart/form-data">
Les fichiers Xevo:<INPUT name="file[]" type="file" multiple /><br>
Les fichiers InCell:<INPUT name="file2[]" type="file" multiple /><br>
<INPUT type="submit" name= "upload" value="Upload" class="btn btn-danger">
</form>';


if(isset($_POST['upload'])) 
{
	$uploads_dir = './Xevo';
	foreach ($_FILES["file"]["error"] as $key => $error)
	{
		if ($error == UPLOAD_ERR_OK) 
		{
			$tmp_name = $_FILES["file"]["tmp_name"][$key];
			$name = $_FILES["file"]["name"][$key];
			move_uploaded_file($tmp_name, "$uploads_dir/$name");
		}
	}
	$uploads_dir2 = './Incell';
	foreach ($_FILES["file2"]["error"] as $key => $error) 
	{
		if ($error == UPLOAD_ERR_OK)
		{
			$tmp_name = $_FILES["file2"]["tmp_name"][$key];
			$name = $_FILES["file2"]["name"][$key];
			move_uploaded_file($tmp_name, "$uploads_dir2/$name");
		}
	}

	//1- Vérifier le nombre des fichiers à uploader
	// lecture des fichiers Xevo
	$Xevo=array_fichiers('./Xevo', 'txt');
	$nb_fichiers_xevo=count ($Xevo);
	$num_plaques_xevo=num_plaque_list($Xevo);
	$Incell=array_fichiers('./Incell', 'txt');
	$Incell_xls=array_fichiers('./Incell', 'xls');
	
	
	if((count($Incell))>(count($Incell_xls))) // condition pour savoir si les fichiers uploadés sont xls ou txt
	{
		$num_plaques_incell=num_plaque_list($Incell); //si txt continuer l'exécution du code
	}
	else
	{
		$num_plaques_incell=num_plaque_list($Incell_xls); // si xls convertir en txt et continuer l'exécution
		require_once 'Extraction/PHPExcel/IOFactory.php';
		xls_to_txt('./Incell',$Incell_xls,$num_plaques_incell);
		$Incell=array_fichiers('./Incell', 'txt');
	}
		$nb_fichiers_incell=count($Incell);
			
	if($num_plaques_incell!=$num_plaques_xevo)
	{
		echo "Echec d'insertion: Les numéros des plaques ne sont pas compatibles";
		for ($i=0;$i<=$nb_fichiers_xevo-1;$i++) // vider le dossier xevo
		{
			unlink($Xevo[$i]); 
		}
		for ($i=0;$i<=$nb_fichiers_incell-1;$i++) // vider le dossier incell
		{
			unlink($Incell[$i]);
		}
	}
	else
	{
	
		echo'<a href="echantillon.php" target="iframe_a">Afficher un échantillon avant insertion</a>';
	
	}
	
	
	
}
echo '</div>';
echo '</div>';
?>


</body>
</html>
