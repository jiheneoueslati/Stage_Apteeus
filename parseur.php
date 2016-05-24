<?php

header( 'content-type: text/html; charset=utf-8' );
if ((isset($_POST['type_exp']))&& (isset($_POST['température']))&& (isset($_POST['temps_incubation']))&& (isset($_POST['cellule'])))
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
	$type_exp=$_POST['type_exp'];
	$température=$_POST['température'];
	$temps_incubation=$_POST['temps_incubation'];
	$cellule=$_POST['cellule'];
	
	include('fonctions.php');// appel à la page contenant toutes le fcts
	//1- Vérifier le nombre des fichiers à uploader
	$Xevo=array_fichiers('./Xevo', 'txt');
	$nb_fichiers_xevo=count ($Xevo);
	$Incell=array_fichiers('./Incell', 'txt');
	$nb_fichiers_incell=count ($Incell);
	if($nb_fichiers_incell!=$nb_fichiers_xevo)
	{
		echo "Echec d'insertion: Le nombre des fichiers Xevo est différent du nombres des fichiers Incell";
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
	echo "Succés d'insertion:  &nbsp" .$nb_fichiers_xevo."&nbspfichiers Xevo et &nbsp".$nb_fichiers_incell."&nbspfichiers Incell <br>";
	
	//2- déterminer la mois et le numéro de l'expérience et les stocker dans des variables
	$fichier1=$Xevo[0]; // le premier fichier dans la liste des fichiers xevo
	$mois_annee_numexp= mois_annee_numexperience($fichier1); // extraire la mois(jour,mois), l'année et le num de l'expérience
	$mois= $mois_annee_numexp[0];
	$année= $mois_annee_numexp[1];
	$numexp= $mois_annee_numexp[2];
	
	//3- Mnt que nous disposons de toutes les propriètés de la table expérience; insertion de l'expérience dans la bdd
	connexxion();
	$sql= "insert into  experience (Num_Experience, Type, Mois, Annee, Id_Cellule, Temperature, Temps_Incubation) values ('$numexp','$type_exp' ,'$mois', $année,'$cellule', $température,$temps_incubation)";
	mysql_query($sql);

// insertion des fichiers incell

		for ($i=0;$i<=$nb_fichiers_incell-1;$i++)
		{
			$fich1=$Incell[$i];
			lire_fichier_incell($numexp,$fich1,$i+1);
		}
	
// insertion des fichiers Xevo
	
		$init_Passage_list=initialiser_passage($nb_fichiers_xevo);
		for ($j=0;$j<=$nb_fichiers_xevo-1;$j++)
		{
			$fich2=$Xevo[$j];
			$init_num_passage=$init_Passage_list[$j];
			lire_fichier_xevo($numexp,$fich2,$init_num_passage,$j+1);
		}
		//6- supprimer les fichiers xevo et incell des sous répertoires
	
		for ($i=0;$i<=$nb_fichiers_xevo-1;$i++)
		{
			unlink($Xevo[$i]); 
			unlink($Incell[$i]);
		}
	}

}
?>