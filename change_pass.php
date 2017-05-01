<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["user_id"]) && isset($_POST["password1"]) && isset($_POST["password2"])){
	include_once("php_includes/check_login_status.php");
	
	$user_id = preg_replace('#[^0-9]#i', '', $_POST['user_id']);
	$newPass = sha1($_POST['password1']);
	$newPass2 = sha1($_POST['password2']);
	
	if($newPass !== $newPass2){
	    echo "passwords do not match";
	    exit();
	}
	
	if($user_id == $log_id){
	    //safe to change password
	    
	    $sql = "UPDATE photo_users SET password='$newPass' WHERE user_id='$log_id'";
        $query = mysqli_query($db_conx, $sql); 
    	if($query){
    	    //failed to update
    	    echo "success";
    	    
    	    //update the session and cookie
    	    $_SESSION['password'] = $newPass;
    	    setcookie("pass", $newPass, strtotime( '+30 days' ), "/", "", "", TRUE);
    	    
    	    exit();
    	} else {
    	    echo "internal server error, please try again later";
    	    exit();
    	}

	} else {
	    header("location: message.php?msg=Cant_Change_Another_User_You_Silly");
	}
}
?>