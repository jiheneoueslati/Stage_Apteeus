<?php	
//////////////////////////////////////////////////////////////////uploader les fichiers Xevo et Incell ; fromulaire upload files
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
	}
	else
	{		
	echo "Succés d'insertion:  &nbsp" .$nb_fichiers_xevo."&nbspfichiers Xevo et &nbsp".$nb_fichiers_incell."&nbspfichiers Incell <br>";
	
	//2- déterminer la date et le numéro de l'expérience et les stocker dans des variables
	$fichier1=$Xevo[0]; // le premier fichier dans la liste des fichiers xevo
	$date_annee_numexp= date_annee_numexperience($fichier1); // extraire la date(jour,mois), l'année et le num de l'expérience
	$date= $date_annee_numexp[0];
	$année= $date_annee_numexp[1];
	$numexp= $date_annee_numexp[2];
	
/*	//3- Mnt que nous disposons de toutes les propriètés de la table expérience; insertion de l'expérience dans la bdd
	connexxion();
	$sql= "insert into expérience (Num_Expérience, Type, Date, Année, Id_Cellule, Température, Temps_Incubation) values ('$numexp','$type_exp' ,'$date', $année,'$cellule', $température,$temps_incubation)";
	mysql_query($sql);
	//5- remplir la table des initialisations Id_TEE
	$T_init_TEE=initialiser_TEE($nb_fichiers_xevo);
	
	//4- lire les fichiers xevo en boucle 
	for ($i=0;$i<=$nb_fichiers_xevo-1;$i++)
	{
		$fich1=$Xevo[$i];	// modifier fichier 1,2,3...
		$init_TEE=$T_init_TEE[$i];  // commencer à partir de lindice 0; Id_TEE=0, 240, 480...
		lire_fichier_xevo($fich1,$init_TEE);
	}
	
	//5- lire les fichiers incell en boucle
	for ($i=0;$i<=$nb_fichiers_xevo-1;$i++)
	{
		lire_fichier_incell($numexp,$Incell[$i],$T_init_TEE[$i]);
	}
	//6- supprimer les fichiers xevo et incell des sous répertoires
	
	for ($i=0;$i<=$nb_fichiers_xevo-1;$i++)
	{
		unlink($Xevo[$i]); 
		unlink($Incell[$i]);
	}*/
	}
}

?>