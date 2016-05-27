<?php
function unzip_file($file, $destination) {
	// Créer l'objet (PHP 5 >= 5.2)
	$zip = new ZipArchive() ;
	// Ouvrir l'archive
	if ($zip->open($file) !== true) {
		return 'Impossible douvrir larchive';
	}
	// Extraire le contenu dans le dossier de destination
	$zip->extractTo($destination);
	// Fermer l'archive
	$zip->close();
	// Afficher un message de fin
	//echo 'Archive extrait';
}
 
unzip_file('etape5_calcul.xlsx','fichierfinal');

header('Location:etape7_format.php');

?>