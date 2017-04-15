<?php
if (isset($_GET['id']) && isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p'])) {
	// Connect to database and sanitize incoming $_GET variables
    include_once("php_includes/db_connects.php");
    $id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$e = urldecode($_GET['e']);
	$p = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);		//using sha1 this will work. if we ever encrypt maybe need to reconsider.
	// Evaluate the lengths of the incoming $_GET variable
	if($id == "" || strlen($u) < 3 || strlen($e) < 5 || $p == ""){
		// Log this issue into a text file and email details to yourself
		header("location: message.php?msg=activation_string_length_issues".$id.$u.$e.$p);
    	exit(); 
	}
	// Check their credentials against the database
	$sql = "SELECT * FROM photo_users WHERE user_id='$id' AND username='$u' AND email='$e' AND password='$p' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	// Evaluate for a match in the system (0 = no match, 1 = match)
	if($numrows === 0){
		// Log this potential hack attempt to text file and email details to yourself
		echo $sql;
		//header("location: message.php?msg=Your credentials are not matching anything in our system".$id.$u.$e.$p.$_GET['e']);
    	exit();
	}
	// Match was found, you can activate them
	$sql = "UPDATE photo_users SET activated='1' WHERE user_id='$id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	// Optional double check to see if activated in fact now = 1
	$sql = "SELECT * FROM photo_users WHERE user_id='$id' AND activated='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	// Evaluate the double check
    if($numrows === 0){
		// Log this issue of no switch of activation field to 1
        header("location: message.php?msg=activation_failure");
    	exit();
    } else if($numrows == 1) {
		// Great everything went fine with activation!
        header("location: message.php?msg=activation_success");
    	exit();
    }
} else {
	// Log this issue of missing initial $_GET variables
	header("location: message.php?msg=missing_GET_variables");
    exit(); 
}
?>