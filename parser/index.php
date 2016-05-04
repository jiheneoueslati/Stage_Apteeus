<html>
<head>
</head>
<body>
<ul>
<form action="upload.php" method="post" enctype="multipart/form-data">
Les fichiers Xevo:<INPUT name="file[]" type="file" multiple /><br><br>
Les fichiers InCell:<INPUT name="file2[]" type="file" multiple /><br><br>
<INPUT type="submit" value="Upload">
</ul>
</form>

</body>
</html>
<?php	
//include('functions.php');


//$fich=file("Xevo/10-12 TEE1.txt");

header('Content-type: text/html; charset=utf-8');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////  supprimer les lignes vides du fichier et déterminer le nombre de lignes
/*foreach($fich as $f)
	{
		if(preg_match('#MAP#',$f))
			{
				$tab[]=$f;
			}
	}
	print_r(explode($tab[0],"\t"));
$n=count($tab); // nb lignes (positions)
	foreach($fich as $f)
	{
		if(preg_match('#Compound#',$f))
			{
				$comp[]=$f;
			}
	}
$c=count($comp)-1; //nb  composants

$l=$n/$c; // nb lignes par bloc



for ($i=0;$i<=$l;$i++)
{
	$explod1[$i]= explode($tab[$i],"	");
}

$filearray2 = file($fich, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
echo $filearray2 [0][0];
foreach ($filearray2 as $f2)
{
	echo $f2."<br>";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
////// ////////////test //////////////////////////////////////////////////////////lecture fichier
$fich=file("Xevo/10-12 TEE1.txt");
$filearray = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$tab=array();// table des lignes 10-12
$ff=array();// table des composants
foreach($fich as $f)
	{
		if(preg_match('#Compound#',$f))
		{$ff[]=$f;}
	}
	
	print_r($ff);
echo'<br>';
foreach($fich as $f)
	{
		if(preg_match('#Name#',$f))
		{$name=$f;}// contenu entetes colonnes
	}
	$activité=explode("	",$name);
	echo count($activité);
	print_r($activité); // les activités commencent à partir de l'indice 3 jusqu'à (count(activité-2))
echo'<br>';
foreach($fich as $f)
	{
		if(preg_match('/10-12/',$f))
			{
				$tab[]=$f;
			}
	}
	$metabolite=$ff[1];
	$id_TEE=0;
$array1=array();// table pour les lignes découpées par tablulation
for($i=0;$i<=239;$i++) //$l-1
	{
		$array1[$i]=explode('	',$tab[$i]); // séparateur 'tabulation' pour chaque ligne de position
		$experience[$i]=explode(" ",$array1[$i][2]); // une deuxième séparation pour le champs indice num 2 avec séparateur espace, contient num exp, num plaq(à partie de TEE sinon on peut le déterminer à partie du numfichier), index/plaque
		
		$num=$array1[$i][0];
		$activit1=$array1[$i][3];//area
		$activite2=$array1[$i][4];//rt
		$activit3=$array1[$i][5];//sn
		$num_experience=$experience[$i][0];
	}


//$sql= "insert into résultat_métabolite (Num_Expérience, Id_TEE, Id_Métabolite, Id_Activité, Valeur, Concentration) values ('$num_experience',$id_TEE+1 ,'$metabolite', )";
//mysql_query($sql);
		print_r($experience[0]);
	echo $array1[0][0].'<br>';//indice
	echo $array1[0][1].'<br>';
	echo $array1[0][2].'<br>';// il faut le découper par 'espace' pour récupérer le num de plaque TEEi 
	echo $array1[0][3].'<br>';//RT
	echo $array1[0][4].'<br>';//AREA
	echo $array1[0][5].'<br>';
	echo $array1[0][6].'<br>';
	echo $array1[0][7].'<br>';// Position 
	
	echo $array1[2][0].'<br>';//indice
	echo $array1[2][1].'<br>';
	echo $array1[2][2].'<br>';// il faut le découper par 'espace' pour récupérer le num de plaque TEEi 
	echo $array1[2][3].'<br>';//RT
	echo $array1[2][4].'<br>';//AREA
	echo $array1[2][5].'<br>';
	echo $array1[2][6].'<br>';
	echo $array1[2][7].'<br>';// Position 
	
	echo $array1[99][0].'<br>';//indice
	echo $array1[99][1].'<br>';
	echo $array1[99][2].'<br>';// il faut le découper par 'espace' pour récupérer le num de plaque TEEi 
	echo $array1[99][3].'<br>';//RT
	echo $array1[99][4].'<br>';//AREA
	echo $array1[99][5].'<br>';
	echo $array1[99][6].'<br>';
	echo $array1[99][7].'<br>';// Position 
	
	







?>


