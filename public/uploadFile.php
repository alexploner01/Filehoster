<?php

use models\DateiInfos;

require __DIR__."/../classes/DateiInfos.php";

$target_dir = "../uploads/";

if(isset($_FILES["fileToUpload"])) {

  $filename = $_FILES["fileToUpload"]["name"];
  $file_hashname = sha1(basename($filename . random_bytes(50)));
  $target_file = $target_dir . $file_hashname;

  $uploadOk = 1;

// Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }


// Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		
        $password = "";
        // Check if password is set
        if(isset($_POST["inputPassword"])) {
            $password = password_hash($_POST["inputPassword"], PASSWORD_DEFAULT);
        }

        $inputTime = "";
        // Check if time is set
        if(isset($_POST["inputTime"])) {
            $inputTime = $_POST["inputTime"];
        }

        // Hier wird der Model DateiInfos initialisiert und dann wird die Exception geworfen
        $dateiinfos = new models\DateiInfos();



        if ($dateiinfos->insertFile($filename, $file_hashname, $password, $inputTime) == 0) {
            echo "your link:\t";
            echo "<a href='/download/".$file_hashname."'>click here</a>";
        }
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }

} else {
  $target_file = null;
  echo "No File added";
}


