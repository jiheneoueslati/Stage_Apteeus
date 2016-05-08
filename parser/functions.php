<?php 

///////////////////////////////////////////////////////////////////////////////////////// fonction connexion à la BD

function connexxion (){ 
	$serveurbd = 'localhost';
	$userbd    = 'root';
	$mdpbd     = '';
	$bdname    = 'parser';

	$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
	mysql_set_charset('utf8', $connexion);
	mysql_select_db($bdname,$connexion);
	return $connexion;
}
//////////////////////////////////////////////////////////////// fonction retourne,dans un array,les fichiers des sous répertoires selon extention 

function array_fichiers($path, $extension)
{
    $files = array();
 
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) == $extension)
            $files[] = (string)$file;
    }
 
    return $files;
}

/////////////////////////*****************fonction extraire la date à partier d'un fichier Xevo****************//////////////////////////////
function date_experience($fich)
{
	$filearray = file($fich); // à remplacer: $fich par indice 0 de la tab $files
	$date="";
	foreach($filearray as $f)
		{
		   	if(preg_match('#Printed#',$f))
			{
				$date= substr($f, 8);
			}
		}
	return $date;
}
//$date= date_experience($Xevo[0]); // lire la date à partir du ficheir Xevo num 1
//echo $date;

//////////////////***********************fonction construisant une table contenant les initialisation des TEE pour chaque ficheir****************//////////////////////////////////////////////

function initialiser_TEE($nb_fichiers) // fonction pour initialiser les compteurs TEE selon le nombre de fichiers (fich1:240, fich2:480, fich3:720...)
{
	$i=0;
	$TEE=0;
	while($i<=$nb_fichiers)
	{
		$tab_init_TEE[]= $TEE;
		$TEE=$TEE+240;
		$i++;
	}
	return($tab_init_TEE);
}

/////////////////////////**************************************fonction qui lit 1 fichier********************//////////////////////////////////////////////

function lire_fichier($fich1,$init_TEE, $fich2)// $fich: indice tab noms fichiers; $init_TEE: indice tab initialisation TEE
{
	$fich=file($fich1);
		
	foreach($fich as $f) // table des lignes valeurs 
	{
		if(preg_match('#TEE#',$f))// demander de taper dans une zone de texte
			{
				$valeurs[]=$f;
			}
	}
	
	foreach($fich as $f) // table des compound
	{
		if(preg_match('#^Compound [0-9]: #',$f)) // les lignes contenant les num et nom des compounds (!!! faut vérifier le cas des cellules GM comment ils vont apparaître dans les fichiers Xevo)
			{
				$comp[]=$f;
			}
	}
	
	for ($i=0;$i<=count($comp)-1;$i++) // les composants(métabolites)	
	{
		$explod_composants[] = explode(":",$comp[$i]);// séparer le num compound du nom
		$composants[]=$explod_composants[$i][1]; // je sauvegarde que les noms des composants (je garde pas le num)
	}
	
	foreach($fich as $f) //table des activités 
		{
			if(preg_match('#Name#',$f))//les lignes des entêtes colonnes)
			{
				$activites[]=$f;
			}
		}
		 
	$n=count($valeurs); //nb lignes valeurs =960
	$c=count($comp); //nb  composants =4
		
	for ($i=0;$i<=$n-1;$i++) // décomposer chaque ligne valeur par le délimitateur "tabulation"
	{
		$explod_valeurs[$i]= explode("	",$valeurs[$i]);
	}
	// => Le champs $explod_valeurs[2] contient le num_exp 10-12, id_cellule MAP, numplaque TEE1 et id pos E1, je les stocke dans un array 'position' avec explod 'espace'
	
	for ($i=0;$i<=$n-1;$i++)
	{
		$position[$i]= explode(" ",$explod_valeurs[$i][2]);
	}
	// décomposer chaque ligne activité par le délimitateur "tabulation"	pour récupérer les activités
	$explod_activites = explode("	",$activites[0]);// pas de boucle en lignes les actvités à déterminer une seule fois à partier de la ligne1 indice0 //la boucle sera faite pour parcourir cette table '$explod_activites' de l'indice 3 à $a-2
	$a=count($explod_activites); // =8 :les activités + 4(vide, #, Name, Vial) => nb activitées: $na=$a-4;
	
	
	$nouv=fopen($fich2,'a');
	$metabolite="";
	$k=-1;

	for($i=0;$i<=$n-1;$i++)//nb lignes
	{
		//remplir variable Id_TEE:
		$Id_TEE =$init_TEE + $explod_valeurs[$i][0];
		//remplir variable num_Experience:
		$num_Experience=$position[$i][0];
		//remplir variable metabolite:
		if($explod_valeurs[$i][0]==1)
		{
			$k=$k+1;
			$metabolite=$composants[$k];
     	}
			
		//remplir variables activites et valeurs associées
		for($j=3;$j<=$a-2;$j++)
		{
			$activite=$explod_activites[$j];
			$valeur=$explod_valeurs[$i][$j];
			$ligne= $activite.";".$valeur.";"." ".";".$num_Experience.";".$Id_TEE.";".$metabolite;
			fputs($nouv, $ligne);// remplir les fichiers pour les importer dans la base
			 
		}
	
	}
	return ($fich2);
}

//Essais fcts
//1- Compter le nombre des fichiers déplacés !!! faire de controle de saise: il faut que nb xevo==nb incell
$Xevo=array_fichiers('./Xevo', 'txt'); // ou array_fichiers('../stage', 'xls'); //liste des fichiers InCell
$Incell=array_fichiers('./Incell', 'xls');
$nom_fichier1=substr($Xevo[0], 1, 15);
$nom_fichier=substr($nom_fichier1,-9);// récupere nom fichier pour l'utiliser dans la boucle 3-
$nb_fichiers=count ($Xevo);
//2- construire la table contenant les initialisations des TEE
$T_init_TEE=initialiser_TEE($nb_fichiers);
//3- exécuter la fonction qui lit 1 fichier
for ($i=1;$i<=$nb_fichiers;$i++)
{
	$fich1="Xevo/".$nom_fichier.$i.".txt"; // modifier
	$init_TEE=$T_init_TEE[$i-1];  // commencer à partir de lindice 0; Id_TEE=0
	$fich2="Xevo2/".$nom_fichier.$i.".txt";
	lire_fichier($fich1,$init_TEE, $fich2);
}




?>