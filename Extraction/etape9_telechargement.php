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


<div class="container-fluid" style="background-color:#33383c;color:#fff;height:45px;">
 <h4>Téléchargement</h4>
</div>

<div class="container-fluid" style="background-color:#D40000;color:#fff;height:60px;">
<img src="logo.png">

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




echo '<div class="col-sm-6" style=margin:30px;>';
echo'<a href="Results '.$exp.'.xlsx">';
echo'</span>';
echo '<h3>';
echo'Téléchargement du fichier avec formatage conditionnel';
echo '</h3>';
echo '<h1>';
echo '<span class="glyphicon glyphicon-download">';
echo '</h1>';
echo'</span>';
echo '</div>';





echo '<div class="col-sm-6" style=margin:30px;>';
echo "<br>";
echo'<a href="etape5_calcul.xlsx">';
echo '<h3>';
echo'Téléchargement du fichier sans formatage conditionnel';
echo '</h3>';
echo '<h1>';
echo '<span class="glyphicon glyphicon-download">';
echo '</h1>';
echo'</span>';
echo '</div>';


?>
</html>
