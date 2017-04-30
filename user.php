<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: index.php");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM photo_users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();	
}
// Check to see if the viewer is the account owner
// using the variables in the check_login_status.php
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$deleteButton = 
	'<button class="btn btn-xs btn-danger deleteCommentButton" 
			onclick="deleteUser(\''.$log_username.'\')" type="button">
		<i class="glyphicon glyphicon-trash"></i>
	</button>';
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["user_id"];
	$signup = $row["joindate"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
</head>
<body class="mainBody">
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
	<div id="userWrapper" class="userWrapper">
		<h3><?php echo $u; ?></h3>
		<p>Is the viewer the page owner, logged in and verified? <b><?php echo $isOwner; ?></b></p>
		<p>Join Date: <?php echo $joindate; ?></p>
		<p>Last Session: <?php echo $lastsession; ?></p>
		<?php echo $deleteButton?>
	</div>
</div>
</body>
</html>