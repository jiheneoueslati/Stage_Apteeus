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
//////////////////////////////////////////////////////////////// fonction retourne,dans un array,les fichiers sous répertoire selon extention 
/* 
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

//$Xevo=array_fichiers('./Xevo', 'txt'); // ou array_fichiers('../stage', 'xls'); //liste des fichiers InCell
//echo '<pre>',print_r($Xevo),'</pre>';
//$Incell=array_fichiers('./Incell', 'xls');// liste des fichiers Xevo



//////////////////////////////////////fonction extraire la date à partier d'un fichier Xevo
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

////////////////////////////////////////////////////////////// fct supprimant les lignes brouillantes dans un fichier
function supp_lignes_brouil($dossier,$fich)
{

$fich1=$dossier."/".$fich;
$fich2=$dossier."/"."1_".$fich;
$tab="";
if ($monfichier = fopen($fich1 ,'r'))
{
	$nouv_fich=fopen($fich2,'w');
    $row = 1; // Variable pour numéroter les lignes 
      // Lecture du fichier ligne par ligne
    while (($ligne = fgets($monfichier)) !== FALSE)
    {
        // Si la ligne est égal  la ligne à expression régulière
        if ((!preg_match('#Compound#',$ligne))&& (!preg_match('#Name#',$ligne)) && (!preg_match('#Printed#',$ligne)))
        {
            fputs($nouv_fich, $ligne);
        }
        // Sinon, on réécri la ligne
        
        $row++;    
    }  
	    fclose($monfichier);
	unlink($fich1); //unlink des fichiers initiaux
        fclose($nouv_fich);
}
}

//supp_lignes_brouil("Xevo","10-12 TEE3.txt");

////////////////////////////////////////////////////////////// fct supprimant les lignes vides dans un fichier
function supp_lignes_vides($fich)
{
	$filearray = file($fich, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	return $filearray;
}
//$f=supp_lignes_vides("Xevo/10-12 TEE1.txt"); // Attention il faut mettre le nom des nouveaux fichiers(suite à la fct précédente les noms des fichiers changent)
//foreach ($f as $f2)
//{
//	echo $f2."<br>";
//}*/
?>