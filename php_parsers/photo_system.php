<?php
include_once("../php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php 
if (isset($_FILES["photo"]["name"]) && $_FILES["photo"]["tmp_name"] != ""){
	$fileName = $_FILES["photo"]["name"];
    $fileTmpLoc = $_FILES["photo"]["tmp_name"];
	$fileType = $_FILES["photo"]["type"];
	$fileSize = $_FILES["photo"]["size"];
	$fileErrorMsg = $_FILES["photo"]["error"];
	$kaboom = explode(".", $fileName);
	$fileExt = end($kaboom);
	list($width, $height) = getimagesize($fileTmpLoc);
	if($width < 10 || $height < 10){
		header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();	
	}
	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
	if($fileSize > 1048576) {
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
	img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
	$fileLocation = 'uploads/'.$db_file_name;
	
	//FIXME: Make this a transaction
	
	//insert photo into database
	$sql = "insert into photo_files (uploadname, uploaddate, caption, filelocation) VALUES ('$db_file_name', now(), '$photoCaption', '$fileLocation')";
	$query = mysqli_query($db_conx, $sql); 
	$newPhotoId = mysqli_insert_id($db_conx);
	
	//make connection between user and photo
	$sql = "insert into photo_user_links (user_id, photo_id) VALUES ('$log_id' ,'$newPhotoId')";
	$query = mysqli_query($db_conx, $sql); 
	$uid = mysqli_insert_id($db_conx);
	mysqli_close($db_conx);
	//header("location: ../user.php?u=$log_username");
	exit();
}
?>