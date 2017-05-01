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
	    echo("Success");
	} else {
	    echo("error");
	    //header("location: message.php?msg=Cant_Delete_Another_User_You_Silly");
	}
}
?>

<?php
if(isset($_POST["blocker"]) && isset($_POST["blockee"])){
	include_once("php_includes/check_login_status.php");
	
	$blocker = preg_replace('#[^0-9]#i', '', $_POST['blocker']);
	$blockee = preg_replace('#[^0-9]#i', '', $_POST['blockee']);
	
	if($blocker === $log_id){
	    //safe to unblock
	    
		$sql = "DELETE FROM blockedusers WHERE blocker='$blocker' AND blockee='$blockee'";
		$query = mysqli_query($db_conx, $sql); 
		if($query){
			echo("Success");
		} else {
			echo("error: Please try again later.");
		}
	} else {
	    echo("error");
	}
}
?>

<?php
function isUserBlocked($blockee, $Blocker, $db_conx){
	$sql = "SELECT * FROM blockedusers WHERE blocker='$Blocker' AND blockee='$blockee'";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows !== null && $numrows > 0){
		return true;
	} else {
		return false;
	}
}

?>