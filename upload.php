<link rel="stylesheet" type="text/css" href="style_Apteeus.css">
<?php
// Liste de sélection des num exp
include('fonctions.php');
$requete = " SELECT DISTINCT (Num_Experience) FROM experience ORDER BY  Num_Experience ASC  "; 
$tab=liste_req_sql($requete);
	
echo '<h2>Liste des numéros des expériences dans la BDD:</h2>
<form method="post">';
	echo '<select name="numexp" onchange="this.form.submit()">',"n";
	echo '<option value="select_numexp"> Num_Experience </option>';
	foreach($tab as $e)// affichage des villes dans la liste
	{
        echo '<option value="'.$e[0].'">'.$e[0].'</option>';
    } 
     echo '</select>',"<br/>";
echo '</form><br><br>';
if(isset($_POST['numexp'])) 
{
	$num=$_POST['numexp'];
	echo $num;
}

// zone de sélection des fichiers
echo'
<h2>Sélectionner les fichiers:</h2>
<br><br>
<form  method="post" enctype="multipart/form-data">
Les fichiers Xevo:<INPUT name="file[]" type="file" multiple /><br><br>
Les fichiers InCell:<INPUT name="file2[]" type="file" multiple /><br><br>
<INPUT type="submit" name= "upload" value="Upload">
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
	
		echo'<h3>Les fichiers sont uploadés sur le serveur </h3><a href="echantillon.php" target="iframe_a">Afficher un échantillon avant insertion</a>';
	
	}
	
	
	
}
?>