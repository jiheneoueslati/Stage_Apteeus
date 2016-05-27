<!DOCTYPE html>
<html>
  <head>
    <title>Page de Téléchargement</title>
        <meta charset="utf-8" />
  </head>
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
