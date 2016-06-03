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
//header( 'content-type: text/html; charset=utf-8' );

	set_time_limit(0); 
	include('fonctions.php');// appel à la page contenant toutes le fcts
		connexxion();
	//1- créer liste des noms des fichiers xevo et incell
	
	$Xevo=array_fichiers('./Xevo', 'txt');
	$nb_fichiers_xevo=count ($Xevo);
	$num_plaques_xevo=num_plaque_list($Xevo);
	
	$Incell=array_fichiers('./Incell', 'txt');
	$nb_fichiers_incell=count ($Incell);
	$num_plaques_incell2=num_plaque_list($Incell);
		
	//2- déterminer la mois et le numéro de l'expérience et les stocker dans des variables
	$fichier1=$Xevo[0]; // le premier fichier dans la liste des fichiers xevo
	$numexper= mois_annee_numexperience($fichier1); // extraire la mois(jour,mois), l'année et le num de l'expérience
	
	$numexp= $numexper[2];
	
	
// insertion des fichiers incell


		for ($i=0;$i<=$nb_fichiers_incell-1;$i++)
		{
			
			lire_fichier_incell($numexp,$Incell[$i],$num_plaques_incell2[$i]);
		}
	
	
// insertion des fichiers Xevo

	

		for ($j=0;$j<=$nb_fichiers_xevo-1;$j++)
		{
			
			lire_fichier_xevo($numexp,$Xevo[$j],$num_plaques_xevo[$j]);
		}
//6- Verifier les plaques insérées pour cette expérience
$requete_incell = 'SELECT distinct(`Plate_Num`) FROM `cell_results` WHERE `Experiment_Num`="'.$numexp.'"'; 
$tab_incell=liste_req_sql($requete_incell);	

$requete_xevo = 'SELECT distinct(`Plate_Num`) FROM `metabolite_results` WHERE `Experiment_Num`="'.$numexp.'"';
$tab_xevo=liste_req_sql($requete_xevo);	
echo "<h2><pre>les plaques inséres pour l'expérience  ".'"'.$numexp.'"'."  sont :<br><ul>";
echo " <li>Pour Xevo   : ";	
foreach($tab_incell as $ti)
{
	echo '"'.$ti[0].'"'." ";
}
echo "<li>Pour Incell : ";
foreach($tab_xevo as $tx)
{
	echo '"'.$tx[0].'"'." ";
}

	
	echo "</ul></pre></h2> <br>";
//7- supprimer les fichiers xevo et incell des sous répertoires
		for ($i=0;$i<=$nb_fichiers_xevo-1;$i++)
		{
			unlink($Xevo[$i]); 
			unlink($Incell[$i]);
		}
	

?>