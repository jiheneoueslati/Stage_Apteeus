<?php

$archive_name = 'fichier.zip';
$dir_path = 'fichierfinal';

$archive = new PharData($archive_name);
$archive->buildFromDirectory($dir_path); // make path\to\archive\arch1.tar
$archive->compress(Phar::GZ); // make path\to\archive\arch1.tar.gz
unlink($archive_name); // deleting path\to\archive\arch1.tar



header('Location: fin_etape6.php');


?>