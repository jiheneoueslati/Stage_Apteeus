<link rel="stylesheet" type="text/css" href="style_Apteeus.css">
<meta charset="utf-8" />
<?php
include('fonctions.php');
		

	$Xevo=array_fichiers('./Xevo', 'txt');
	$fich2= $Xevo[0];
	$numexper= mois_annee_numexperience($fich2); // extraire la mois(jour,mois), l'année et le num de l'expérience
	$numexp_echantillon= $numexper[2];
	$listnumplaq=num_plaque_list($Xevo);
	$num_plaque2_echantillon=$listnumplaq[0];
		
		
	$liste_conversiontxt=liste_de_conversion('Extraction/convert.txt');
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
	
	for ($i=0;$i<=count($comp)-1;$i++) // les composants_echantillon(métabolites)	
	{
		$explod_composants[] = explode(":",$comp[$i]);// séparer le num compound du nom, j'ai utilisé l'espace au lieu ":" car le fichier3 ne cintient ":"
		$composants_echantillon[]=$explod_composants[$i][1]; // je sauvegarde que les noms des composants_echantillon ils sont à l'indice 3 car j'ai utilisé l'espace comme séparateur et la ligne contient 3 espace 
	}
	
	foreach($fich as $f) //table des activités 
		{
			if(preg_match('#Name#',$f))//les lignes des entêtes colonnes)
			{
				$activites[]=$f;
			}
		}
		 
	$n=count($valeurs); //nb lignes valeurs =960
	$c=count($comp); //nb  composants_echantillon =4
		
	for ($i=0;$i<=$n-1;$i++) // décomposer chaque ligne valeur par le délimitateur "tabulation"
	{
		$explod_valeurs_echantillon[$i]= explode("	",$valeurs[$i]);
	}
	// => Le champs $explod_valeurs_echantillon[2] contient le num_exp 10-12, id_cellule MAP, numplaque TEE2_echantillon et id pos_echantillon E1, je les stocke dans un array avec explod 'espace'
	
	for ($i=0;$i<=$n-1;$i++)
	{
		$num_exp_val[$i]= explode(" ",$explod_valeurs_echantillon[$i][2]);
	}
	for ($i=0;$i<=$n-1;$i++)// remplir la liste des positions
	{
		$pos_echantillon[$i]= explode(":",$valeurs[$i]); // pour chaque ligne on a 2 colonne (les valeurs et les position)
	}
	
	// décomposer chaque ligne activité par le délimitateur "tabulation"	pour récupérer les activités
	$explod_activites_echantillon = explode("	",$activites[0]);// pas de boucle en lignes les actvités à déterminer une seule fois à partier de la ligne1 indice0 //la boucle sera faite pour parcourir cette table '$$explod_activites_echantillon' de l'indice 3 à $a-2
	$a=count($explod_activites_echantillon); // =8 :les activités + 4(vide, #, Name, Vial) => nb activitées: $na=$a-4;
	
	$metabolite_echantillon="";
	$k=-1;
	//déterminer les TEE à partir des fichiers du sous répertoire "Extraction/Fichiers"
	
		echo '<h2>Voici un échantillon de ce que vous allez insérer</h2>
		
		<table><tr><th>num_exp<th>TEE<th>metabolite<th>activite<th>valeur<th>num_plaque<th>position<th>num_passage</tr>';
	
	for($i=0;$i<=2;$i++)//nb lignes
	{
		//remplir variable Id_TEE:
		$num_passage_echantillon = ($num_plaque2_echantillon-1)*240+$explod_valeurs_echantillon[$i][0];
		//remplir variable metabolite_echantillon:
		if($explod_valeurs_echantillon[$i][0]==1)
		{
			$k=$k+1;
			$metabolite_echantillon=$composants_echantillon[$k];
     	}
		
		//remplir variables activites et valeurs associées
		for($j=3;$j<=$a-2;$j++)
		{
			
			$position_pos_echantillon=$pos_echantillon[$i][1];// extraire la position: indice 1 dans la liste pos_echantillon
			$position2_echantillon=trim(str_replace(',','',$position_pos_echantillon)); //supprimer le virgule et les espaces pour pouvoir comparer 
			$activite2_echantillon=trim($explod_activites_echantillon[$j]);
			$valeur2_echantillon=$explod_valeurs_echantillon[$i][$j];
			$TEE2_echantillon=nom_TEE ($num_plaque2_echantillon,$position2_echantillon,$liste_conversiontxt); // appel à la fonction qui détermine le TEE de la molécule à partir du num de la plaque et la pos_echantillon
			if(empty($TEE2_echantillon))
			{
				$array_DMSO=array("D4","F22","G15","G16","H4","H15","I9","I10","J9","J22","L4","N22");
				if(in_array($position1, $array_DMSO))
				{
					$TEE2_echantillon="DMSO";
				}
				else {$TEE2_echantillon="Empty";}
				if (($nb_lignes_fichier_incell<240))
				{
					$array_DMSO_derniere_plaque=file_get_contents('Extraction/Fichiers/listepositionderplaque.txt');
					$array_DMSO_derniere_plaque=unserialize($array_DMSO_derniere_plaque);
					if(in_array($position1, $array_DMSO_derniere_plaque))
					{
						$TEE2_echantillon="DMSO";
					}
									else {$TEE2_echantillon="Empty";}

				
				}
			}
			$ligne= '<tr><td>'.$numexp_echantillon.'<td>'.$TEE2_echantillon.'<td>'.$metabolite_echantillon.'<td>'.$activite2_echantillon .'<td>'.$valeur2_echantillon.'<td>'.$num_plaque2_echantillon.'<td>'.$position2_echantillon.'<td>'.$num_passage_echantillon .'</tr>';
		    echo $ligne;
		}
				
		
	
	}
	
	echo '</table>
	
<form action="parseur.php" method="post" >
<input type="submit" name="insérer" value="insérer"/>
</form></ul>';


		
?>