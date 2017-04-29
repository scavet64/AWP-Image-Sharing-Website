<?php
include_once("../php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php 
if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["tmp_name"] != ""){
	
	include_once("../php_parsers/hashtag_parser.php");
	
	$fileName = $_FILES["photo"]["name"];
    $fileTmpLoc = $_FILES["photo"]["tmp_name"];
	$fileType = $_FILES["photo"]["type"];
	$fileSize = $_FILES["photo"]["size"];
	$fileErrorMsg = $_FILES["photo"]["error"];
	$description = mysqli_real_escape_string($db_conx, $_POST['description']);
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();	
	}
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 5242880) {
		header("location: ../message.php?msg=ERROR: Your image file was larger than 1mb");
		exit();	
	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
		header("location: ../message.php?msg=ERROR: Your image file was not jpg, gif or png type");
		exit();
	} else if ($fileErrorMsg == 1) {
		header("location: ../message.php?msg=ERROR: An unknown error occurred");
		exit();
	}

	$moveResult = move_uploaded_file($fileTmpLoc, "../uploads/$db_file_name");
	if ($moveResult != true) {
		header("location: ../message.php?msg=ERROR: File upload failed");
		exit();
	}
	include_once("../php_includes/image_resize.php");
	$target_file = "../uploads/$db_file_name";
	$resized_file = "../uploads/$db_file_name";
	$wmax = 1920;
	$hmax = 1080;
	//img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	$fileLocation = 'uploads/'.$db_file_name;
	
	//FIXME: Make this a transaction
	
	//insert photo into database
	$sql = "insert into photo_files (uploadname, user_id, uploaddate, caption, filelocation) 
	        VALUES ('$db_file_name', '$log_id', now(), '$description', '$fileLocation')";
	$query = mysqli_query($db_conx, $sql); 
	$newPhotoId = mysqli_insert_id($db_conx);
	
	//extract the hashtags
	$matches = getHashtagArray($description)[1];
	//for each hashtag, create if doesnt exist
	foreach ($matches as $value){
		$sql = "SELECT * FROM hashtags
	    		WHERE hashtag_value = ".$value." LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($user_query);
		if($numrows < 1){
			//create the hashtag
			$sql = "insert into hashtags (hashtag_value) 
	        		VALUES ('$value')";
			$query = mysqli_query($db_conx, $sql); 
			$hashtagID = mysqli_insert_id($db_conx);
		} else {
			$row = mysqli_fetch_array($user_query, MYSQLI_ASSOC);
			$hashtagID = $row["hashtag_id"];
		}
		//create bridge between hashtag and photo
		$sql = "insert into photos_hashtags (photo_id, hashtag_id) 
	        	VALUES ('$newPhotoId','$hashtagID')";
		$query = mysqli_query($db_conx, $sql); 
	}
	
	mysqli_close($db_conx);
	header("location: ../index.php");
	exit();
}
?>