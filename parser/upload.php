<?php	
//////////////////////////////////////////////////////////////////uploader les fichiers Xevo et Incell ; fromulaire upload files

$uploads_dir = './Xevo';
foreach ($_FILES["file"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["file"]["tmp_name"][$key];
        $name = $_FILES["file"]["name"][$key];
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
}

$uploads_dir2 = './Incell';
foreach ($_FILES["file2"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["file2"]["tmp_name"][$key];
        $name = $_FILES["file2"]["name"][$key];
        move_uploaded_file($tmp_name, "$uploads_dir2/$name");
    }
}



?>