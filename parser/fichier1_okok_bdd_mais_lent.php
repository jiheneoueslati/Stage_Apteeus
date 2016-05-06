<?php
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
	connexxion ();
ini_set('max_execution_time', 300);// temps exécution phpmyadmin
$fich=file("Xevo/10-12 TEE1.txt");
$Id_TEE=0;// le fichier 1 j'initialise le Id_TEE à 1
// table des lignes valeurs 
foreach($fich as $f)
	{
		if(preg_match('#MAP#',$f))// demander de taper dans une zone de texte
			{
				$valeurs[]=$f;
			}
	}
// table des compound	
	foreach($fich as $f)
	{
		if(preg_match('#^Compound [0-9]: #',$f)) // les lignes contenant les num et nom des compounds (!!! faut vérifier le cas des cellules GM comment ils vont apparaître dans les fichiers Xevo)
			{
				$comp[]=$f;
			}
	}
	
	// les composants(métabolites)	
	for ($i=0;$i<=count($comp)-1;$i++)
	{
		$explod_composants[] = explode(":",$comp[$i]);// séparer le num compound du nom
		$composants[]=$explod_composants[$i][1]; // je sauvegarde que les noms des composants (je garde pas le num)
	}
	
//table des activités 

	foreach($fich as $f)
		{
			if(preg_match('#Name#',$f))//les lignes des entêtes colonnes)
			{
				$activites[]=$f;
			}
		}	
// nb lignes valeurs
	$n=count($valeurs); //=960
//nb  composants
	$c=count($comp); //=4
// nb de positions 
    $p=$n/$c;    //=240

// décomposer chaque ligne valeur par le délimitateur "tabulation"	
	for ($i=0;$i<=$n-1;$i++)
{
	$explod_valeurs[$i]= explode("	",$valeurs[$i]);
}
// exemple ligne 1 de la table $explod_valeurs, indicej0=1, j1=1, j2=10-12 MAP TEE1 E1, i3=activité1..., i($a)-2=activitén, i($a)-1=numplaquemachine+position
// => Le champs $explod_valeurs[2] contient le num_exp 10-12, id_cellule MAP, numplaque TEE1 et id pos E1, je les stocke dans un array 'position' avec explod 'espace'
	for ($i=0;$i<=$n-1;$i++)
{
	$position[$i]= explode(" ",$explod_valeurs[$i][2]);
}

// décomposer chaque ligne activité par le délimitateur "tabulation"	pour récupérer les activités

	$explod_activites = explode("	",$activites[0]);// pas de boucle en lignes les actvités à déterminer une seule fois à partier de la ligne1 indice0 //la boucle sera faite pour parcourir cette table '$explod_activites' de l'indice 3 à $a-2
// nb activités
	$a=count($explod_activites); // =8 :les activités + 4(vide, #, Name, Vial) => nb activitées: $na=$a-4;
	
//	echo intermédiaires
echo '<pre>',print_r($composants),'<pre>';
echo "explod_activites:     ";print_r($explod_activites);
echo "<br>";
echo "position[1]:      ";print_r($position[1]);
echo "<br>";
echo "explod_valeurs[1]:      ";print_r($explod_valeurs[1]);
echo "<br>";



 // indice 1ier composant
$metabolite="";
$k=-1;	
for($i=0;$i<=$n-1;$i++)//nb lignes
{
	//remplir variable Id_TEE:
	$Id_TEE =$explod_valeurs[$i][0];
	//remplir variable num_Experience:
	$num_Experience=$position[$i][0];
	//remplir variable metabolite:
	
	if($explod_valeurs[$i][0]==1)
		{
			$k=$k+1;
			$metabolite=$composants[$k];
     	}
		//test echo
	echo $num_Experience."=>";
	echo $Id_TEE ."=>".$metabolite;
	//remplir variables activites et valeurs associées
		for($j=3;$j<=$a-2;$j++)
			{
				$activite=$explod_activites[$j];
				echo '>>'.$activite;
				$valeur=$explod_valeurs[$i][$j];
				echo '>>'.$valeur;
				
			  $sql= "insert into résultat_métabolite (Num_Expérience, Id_TEE, Id_Métabolite, Id_Activité, Valeur) values ('$num_Experience',$Id_TEE ,'$metabolite','$activite', $valeur)";
			  mysql_query($sql);
			}
	
}


?>