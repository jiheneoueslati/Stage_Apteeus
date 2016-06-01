<?php
//header( 'content-type: text/html; charset=utf-8' );

	
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

/*
		for ($i=0;$i<=$nb_fichiers_incell-1;$i++)
		{
			
			lire_fichier_incell($numexp,$Incell[$i],$num_plaques_incell2[$i]);
		}
	
	
// insertion des fichiers Xevo

	

		for ($j=0;$j<=$nb_fichiers_xevo-1;$j++)
		{
			
			lire_fichier_xevo($numexp,$Xevo[$j],$num_plaques_xevo[$j]);
		}*/
//6- Verifier les plaques insérées pour cette expérience
$requete_incell = " SELECT DISTINCT (Num_Plaque) FROM resultat_cellule ORDER BY  Num_Plaque ASC  "; 
$tab_incell=liste_req_sql($requete_incell);	
$requete_xevo = " SELECT DISTINCT (Num_Plaque) FROM resultat_metabolite ORDER BY  Num_Plaque ASC  "; 
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