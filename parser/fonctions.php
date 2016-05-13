<?php

function connexxion ()
{ 
	$serveurbd = 'localhost';
	$userbd    = 'root';
	$mdpbd     = '';
	$bdname    = 'parser';

	$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
	mysql_set_charset('utf8', $connexion);
	mysql_select_db($bdname,$connexion);
	return $connexion;
}

//récupérer la date le numéro de l'expérience à partir du premier fichier dans la liste

function array_fichiers($path, $extension) // les paramétres sont toujours les mêmes pour xevo et les mêmes pour incell
{
    $files = array();
 
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) == $extension)
            $files[] = (string)$file;
    }
 
    return $files;
}

function date_annee_numexperience($fich) // $fich va etre détermineé par la fct array_fichiers lindice 0 qui contient le premier fichier à uploader
{
	$filearray = file($fich); // à remplacer: $fich par indice 0 de la tab $files
	$date="";$annee="";
	$num_exp="";
	foreach($filearray as $f)
		{
		   	if(preg_match('#Printed#',$f))
			{
				$date= substr($f,12,6); //extraire 6 ~c à paritr de la pos 12
				$annee= substr($f,28,4);
			}
			$tab_date_num[0]=$date;
			$tab_date_num[1]=$annee;
			if(preg_match('#TEE#',$f))
			{
				$num_exp= substr($f,8,5); // pour le moment le numéro de l'expérience ne dépasse pas les 5 caractères
			}
			$tab_date_num[2]=$num_exp;
		}
	return $tab_date_num;// une liste contenant la date à lindice0, l'annee à lindice 1 et le numéro de l'expérience à l'indice 2
}


function initialiser_TEE($nb_fichiers) // nb fichiers va etre déterminé par la fct count(array_fichiers)
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

/////////////////////////**************************************fonction qui lit 1 fichier Résultats Cellules //////////////////////////////////////////////
function lire_fichier_incell($num_exp,$fich1,$init_TEE)// $fich: indice tab noms fichiers; $init_TEE: indice tab initialisation TEE, le num exp est en paramétre car ne se trouve pas dans le fichier et on doit linsérer dans la bdd  
{
	$filearray_incell = file($fich1);
	$vue=explode("	",$filearray_incell[0]); // les vues (noyaux, nucview+...)
		
	
	$activite_cellules=explode("	",$filearray_incell[2]); // les activités (Area, form factor...)

	$target=explode("	",$filearray_incell[3]); // les targets (mean, count, median...)

	for ($i=4;$i<=count($filearray_incell)-1;$i++) // lignes des valeurs
	{
		$ttt[]=explode("	",$filearray_incell[$i]);
	}
	
	$nb_activités=count($activite_cellules); //le nb des activités qui va servir dans la boucle suivante:
	
	$Id_TEE=$init_TEE;
	foreach ($ttt as $t) // lire toutes les lignes
	{
		$Id_TEE=$Id_TEE+1;
		for ($i=2;$i<=$nb_activités-1;$i++) // lire toutes les colonnes contenant des acctivités (commencent à partir de la colonne num 3 donc l'indice num 2 ) 
		{
			echo "<pre>".$num_exp .";".$Id_TEE.";".$vue[$i].";".$activite_cellules[$i].";".$target[$i].";".$t[$i]."</pre>";
			//$sql= "insert into résultat_cellule (Num_Expérience, Id_TEE, Id_Activité, Valeur, Concentration, Id_Channel) values ('$num_Experience',$Id_TEE ,'$act','$val', 0,'$ch')";
			//  mysql_query($sql);
		}
	}
}

/////////////////////////**************************************fonction qui lit 1 fichier Résultats Métabolites ********************//////////////////////////////////////////////

function lire_fichier_xevo($fich1,$init_TEE)// $fich: indice tab noms fichiers; $init_TEE: indice tab initialisation TEE
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
		if(preg_match('#^Compound [0-9]#',$f)) // les lignes contenant les num et nom des compounds (!!! faut vérifier le cas des cellules GM comment ils vont apparaître dans les fichiers Xevo)
			{
				$comp[]=$f;
			}
	}
	
	for ($i=0;$i<=count($comp)-1;$i++) // les composants(métabolites)	
	{
		$explod_composants[] = explode(" ",$comp[$i]);// séparer le num compound du nom, j'ai utilisé l'espace au lieu ":" car le fichier3 ne cintient ":"
		$composants[]=$explod_composants[$i][3]; // je sauvegarde que les noms des composants ils sont à l'indice 3 car j'ai utilisé l'espace comme séparateur et la ligne contient 3 espace 
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
			echo $ligne."<br>";
			// fputs($nouv, $ligne);remplir les fichiers pour les importer dans la base
			// insert into
			 
		}
	
	}

}

?>