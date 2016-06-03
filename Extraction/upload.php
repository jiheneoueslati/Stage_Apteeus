<?php
$target_dir = "Depot_Molecules/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

// Allow certain file formats
if($imageFileType != "xlsx") {
    echo "Sorry, only xlsx files are allowed.";
     echo "<br>";
    $uploadOk = 0;
}
// Check file name

if ($_FILES["fileToUpload"]["name"] != "molecules.xlsx") {
    echo "Sorry, molecules.xlsx only accepted .";
    echo "<br>";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
     echo "<br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
     echo "<br>";
// if everything is ok, try to upload file
} 
 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
 	
 }

header('Location: etape1bis_alimentation.php');

?>