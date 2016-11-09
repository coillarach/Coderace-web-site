<?php

/* Accepts an uploaded file 
 */

	require_once('classes/content.php');
	require_once('classes/user.php');
	session_start();
	require_once('global.php');
	
	$target = "images/user/" . $_SESSION{user_id} . "/";
	
	if (($_FILES["file"]["type"] == "image/gif" ||
	     $_FILES["file"]["type"] == "image/png" ||
	     $_FILES["file"]["type"] == "image/jpg" ||
	     $_FILES["file"]["type"] == "image/jpeg" ||
	     $_FILES["file"]["type"] == "image/pjpeg" ) &&
		 $_FILES["file"]["size"] < 200000) {
		
		if ($_FILES["file"]["error"] > 0) {
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else {
			if (file_exists($target . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " already exists. ";
			}
			else {
				move_uploaded_file($_FILES["file"]["tmp_name"], $target . $_FILES["file"]["name"]);
				list($width, $height, $type, $attr) = getimagesize($target . $fname);
				if ($width/User::pwidth <= $height/User::pheight)
					$scale = " style='width: 100%; height: auto;' ";
				else
					$scale = " style='height: 100%; width: auto;' ";

				echo "<img src='" . $target . $_FILES{file}{name} . "' " . $scale . " />";
			}
		}
	}
	else {
		echo "Invalid file";
	}
?>