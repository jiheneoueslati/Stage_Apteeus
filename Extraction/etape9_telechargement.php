<!DOCTYPE html>
<html lang="en">
<head>
  <title>Téléchargement</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron">
    <h2>Téléchargement</h2> 
  </div>


<?php

function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

Delete('fichierfinal');

$exp=file_get_contents("Fichiers/numexp.txt");
rename ("Results.xlsx", 'Results '.$exp.'.xlsx');

echo '<a href="Results '.$exp.'.xlsx">'.'Télecharger le fichier avec formatage conditionnel'.'</a>';
echo '<br>';
echo '<br>';
echo '<a href="etape5_calcul.xlsx">'.'Télecharger le fichier sans formatage conditionnel'.'</a>';


?>
</html>
