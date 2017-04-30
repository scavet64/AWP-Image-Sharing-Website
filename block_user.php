<?php
if(isset($_POST["user_id"])){
	include_once("php_includes/check_login_status.php");
	
	$user_id = preg_replace('#[^0-9]#i', '', $_POST['user_id']);
	
	if($user_id !== $log_id){
	    //safe to block
	    
	    //insert block into database
		$sql = "insert into blockedusers (blocker, blockee, blockdate) 
		        VALUES ('$log_id', '$user_id', now())";
		$query = mysqli_query($db_conx, $sql); 
		$newBlocked_id = mysqli_insert_id($db_conx);
	    
	    //header("location: index.php");
	    echo("Successfully blocked.");
	} else {
	    echo("error");
	    //header("location: message.php?msg=Cant_Delete_Another_User_You_Silly");
	}
}
?>