<?php
function connexxion ()
{ 
	$serveurbd = 'localhost';
$userbd    = 'root';
$mdpbd     = '';
$bdname    = 'sages_femmes_jo';
	$connexion = mysql_connect($serveurbd,$userbd,$mdpbd);
	mysql_set_charset('utf8', $connexion);
	mysql_select_db($bdname,$connexion);
	return $connexion;
}
 ////********* fonction prend en paramètre un dossier et retourne une liste contenant les noms des fichiers dans le dosssier ******////
 set_time_limit ( 1000 );
function array_fichiers($path, $extension) //$Xevo=array_fichiers('./Xevo', 'txt');
{
    $files = array();
 
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file)
    {
        if (pathinfo($file, PATHINFO_EXTENSION) == $extension)
            $files[] = (string)$file;
    }
 
    return $files;
}

require_once 'Extraction/PHPExcel/IOFactory.php';
function xls_to_txt($path,$file_list,$list_plaque)	
{
	for($i=0;$i<=count($file_list)-1;$i++)
	{
		$objPHPExcel = PHPExcel_IOFactory::load("$file_list[$i]");
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'csv');
		$objWriter->setDelimiter("\t");
		$objWriter->save($path.'\IncellTEE'.$list_plaque[$i].'.txt');// les noms des fichiers seront incel1, incell2... 
		unlink($file_list[$i]);
	}
}

////**** fonction récupére le num_expérience et la date
function mois_annee_numexperience($fich) // $fich va etre détermineé par la fct array_fichiers lindice 0 qui contient le premier fichier à uploader
{
	$filearray = file($fich); // à remplacer: $fich par indice 0 de la tab $files
	$mois="";$annee="";
	$num_exp="";
	foreach($filearray as $f)
		{
		   	if(preg_match('#Printed#',$f))
			{
				$mois= substr($f,12,3); //extraire 3 ~c à paritr de la pos 12
				$annee= substr($f,28,4);
			}
			$tab_date_num[0]=trim($mois);
			$tab_date_num[1]=trim($annee);
			if(preg_match('#TEE#',$f))
			{
				$Name=explode("	",$f);
			}
		}
		$Name=explode(" ",$Name[2]);
		if(preg_match('#-#',$Name[0]))
		{
			$num_exp=$Name[0];
		}
		else
		{
			$num_exp=$Name[1];
		}
		$tab_date_num[2]=trim($num_exp);
	return $tab_date_num;
}
//////***** 2 fonction pour traduire le position en TEE  *****//////
//1- replir la listes bidimentionnel avec indice colonne [0]=> INN [1]=> Position_384,[2]=> Num_plaque384,[3]=> TEE

	function liste_de_conversion($fichier_conversiontxt)
{
	$fich=file($fichier_conversiontxt);
	foreach($fich as $f)
	{
		$list[]=explode("	",$f);
	}
	return $list;
}
//2- parcourir les lsite
function nom_TEE ($numero_plaque,$la_position,$conversion_array)// TEE = molécules
{
	foreach($conversion_array as $ca)
	{
		if(($ca[1]==$numero_plaque)&&($ca[2]==$la_position))
		{
			return $ca[3];
		}
	}
	
}

////////////**********************fonction extrait les num plaque à partir des noms des fichiers ****************************///////////////////////////////////////
function num_plaque_list($file_list)
{
	foreach($file_list as $fl)
	{
		$num_p = explode("TEE", $fl);
		$num_p = substr($num_p[1],0,2);
		$deuxieme_chiffre=substr($num_p,1,1);
		if (is_numeric($deuxieme_chiffre))
		{
			$num_plaquelist=$num_p;
		}
		else
		{
			$num_plaquelist=substr($num_p,0,1);
		}
	$list_num_plaque[]=$num_plaquelist;
	}
	return $list_num_plaque;
}


/////////////************** fonction lit un seul fichier incell***********///////////////
function lire_fichier_incell($num_exp,$fich1,$num_plaque1)// $fich: indice tab noms fichiers; $init_Pos: indice tab initialisation TEE, le num exp est en paramétre car ne se trouve pas dans le fichier et on doit linsérer dans la bdd  
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
	
	$liste_conversiontxt=liste_de_conversion('Extraction/convert.txt');
	foreach ($ttt as $t) // lire toutes les lignes
	{
		for ($i=2;$i<=$nb_activités-1;$i++) // lire toutes les colonnes contenant des acctivités (commencent à partir de la colonne num 3 donc l'indice num 2 ) 
		{	
			
			$v=trim($vue[$i]);
			$a=trim($activite_cellules[$i]);
			$tar=trim($target[$i]);
			$val=str_replace(',','.',$t[$i]); // remplacer la virgule par un point pour qu'on puisse l'afficher dans la base avec float	
			$positionx=str_replace(' - ','',$t[0]);// extraire la position pour la passer en paramètre dans la fonction nom_TEE
			$position1=trim(substr($positionx,0,3)); // indice 0 3 caractètres
			$TEE1=nom_TEE($num_plaque1,$position1,$liste_conversiontxt);
		
			echo '<pre>'.$num_exp.' - '.$TEE1.' - '.$v.' - '.$a.' - '.$val. ' - '.$num_plaque1.' - '.$position1.'</pre>';
				//$sql= "insert into resultat_cellule (Num_Experience,TEE,View,Activite,Target,Valeur,Num_Plaque,Position) values ('$num_exp','$TEE1','$v','$a','$tar',$val,$num_plaque1,'$position1')";
				//mysql_query($sql);
		}
	}
}
/////////////************** fonction lit un seul fichier xevo***********////////////////
function lire_fichier_xevo($numexp,$fich2,$num_plaque2)
{
	
	$fich=file($fich2);
		
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
		$explod_composants[] = explode(":",$comp[$i]);// séparer le num compound du nom, j'ai utilisé l'espace au lieu ":" car le fichier3 ne cintient ":"
		$composants[]=$explod_composants[$i][1]; // je sauvegarde que les noms des composants ils sont à l'indice 3 car j'ai utilisé l'espace comme séparateur et la ligne contient 3 espace 
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
	$nb_lignes_fichier=$n/$c;
	
		
	for ($i=0;$i<=$n-1;$i++) // décomposer chaque ligne valeur par le délimitateur "tabulation"
	{
		$explod_valeurs[$i]= explode("	",$valeurs[$i]);
	}
	// => Le champs $explod_valeurs[2] contient le num_exp 10-12, id_cellule MAP, numplaque TEE1 et id pos E1, je les stocke dans un array avec explod 'espace'
	
	for ($i=0;$i<=$n-1;$i++)
	{
		$num_exp_val[$i]= explode(" ",$explod_valeurs[$i][2]);
	}
	for ($i=0;$i<=$n-1;$i++)// remplir la liste des positions
	{
		$pos[$i]= explode(":",$valeurs[$i]); // pour chaque ligne on a 2 colonne (les valeurs et les position)
	}
	
	// décomposer chaque ligne activité par le délimitateur "tabulation"	pour récupérer les activités
	$explod_activites = explode("	",$activites[0]);// pas de boucle en lignes les actvités à déterminer une seule fois à partier de la ligne1 indice0 //la boucle sera faite pour parcourir cette table '$explod_activites' de l'indice 3 à $a-2
	$a=count($explod_activites); // =8 :les activités + 4(vide, #, Name, Vial) => nb activitées: $na=$a-4;
	
	$metabolite="";
	$k=-1;
	//déterminer les TEE à partir des fichiers du sous répertoire "Extraction/Fichiers"
	
	
	
	for($i=0;$i<=$n-1;$i++)//nb lignes
	{
		//remplir variable $num_passage
		
			$num_passage =($num_plaque2-1)*240+$explod_valeurs[$i][0];
		
		
		//remplir variable metabolite:
		if($explod_valeurs[$i][0]==1)
		{
			$k=$k+1;
			$metabolite=$composants[$k];
     	}
			$liste_conversiontxt=liste_de_conversion('Extraction/convert.txt');
		//remplir variables activites et valeurs associées
		for($j=3;$j<=$a-2;$j++)
		{
			
			$position_pos=$pos[$i][1];// extraire la position: indice 1 dans la liste pos
			$position2=trim(str_replace(',','',$position_pos)); //supprimer le virgule et les espaces pour pouvoir comparer 
			$activite2=trim($explod_activites[$j]);
			$valeur2=$explod_valeurs[$i][$j];
			$TEE2=nom_TEE ($num_plaque2,$position2,$liste_conversiontxt); // appel à la fonction qui détermine le TEE de la molécule à partir du num de la plaque et la pos
			// controle positions DMSO
			if(empty($TEE2))
			{
				$array_DMSO=array("D4","F22","G15","G16","H4","H15","I9","I10","J9","J22","L4","N22");
				if(in_array($position2, $array_DMSO))
				{
					$TEE2="DMSO";
				}
				else {$TEE2="Empty";}
				if (($nb_lignes_fichier<240))
				{
					$array_DMSO_derniere_plaque=file_get_contents('Extraction/Fichiers/listepositionderplaque.txt');
					$array_DMSO_derniere_plaque=unserialize($array_DMSO_derniere_plaque);
					if(in_array($position2, $array_DMSO_derniere_plaque))
					{
						$TEE2="DMSO";
					}
									else {$TEE2="Empty";}

				
				}
			}
			
			$ligne= $numexp.','.$TEE2.','.$metabolite.','.$activite2 .','.$valeur2.','.$num_plaque2.','.$position2.','.$num_passage ;
		    echo $ligne."<br>";
				//$sql= "insert into resultat_metabolite (Num_Experience, TEE, Id_Metabolite, Activite, Valeur,Num_Plaque, Position, Num_Passage) values ('$numexp','$TEE2','$metabolite','$activite2',$valeur2,$num_plaque2,'$position2',$num_passage)";
				//mysql_query($sql);
		}
	
	
	}
	
}
function liste_req_sql($req){// fonction qui stocke une requete dans un tableau
	$resultat = mysql_query($req,connexxion());
	$yourArray = array();
	$index = 0;
	while($ligne = mysql_fetch_array($resultat)) {
		$yourArray[$index] = $ligne;
		$index++;
	}
	return $yourArray;
}
?>