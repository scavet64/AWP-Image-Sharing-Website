<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$sql = "SELECT user_id, email FROM photo_users WHERE username='$u' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["user_id"];
			$e = $row["email"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = sha1($tempPass);
		$sql = "UPDATE photo_users SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);

		$websiteURL = "http://elvis.rowan.edu/~scavet64/awp/awp/";
		$mySiteName = "Super Cool Image Site";
		$UrlEncodedEmail = urlencode($e);

		$to = "$e";
		$from = "scavet64@students.rowan.edu";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject = $mySiteName." Temporary Password";

		$forgotPassURL = $websiteURL.'forgot_pass.php?u='.$u.'&p='.$hashTempPass;
		
		$msg = '<h2>Hello '.$u.'</h2><p>This is an automated message from '.$mySiteName.'. If you did not recently initiate 
		the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password.
		We can generate a temporary password for you to log in with, then once logged in you can change your password to
		anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b>
		</p><p><a href="'.$forgotPassURL.'">Click here now to apply the 
		temporary password shown below to your account</a></p><p>If you do not click the link in this email, no changes will 
		be made to your account. In order to set your login password to the temporary password you must click the link above.</p>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT user_id FROM photo_users WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows === 0){
		header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
    	exit();
	} else {
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE photo_users SET password='$temppasshash' WHERE user_id='$id' AND username='$u' LIMIT 1";
		echo $sql.'\n';
	    $query = mysqli_query($db_conx, $sql);
	    echo $query.'\n';
		$sql = "UPDATE photo_users SET temp_pass='' WHERE username='$u' LIMIT 1";
		echo $sql.'\n';
	    $query = mysqli_query($db_conx, $sql);
	    echo $query.'\n';

	    header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="style/style.css">
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function forgotpass(){
	var u = _("username").value;
	if(u == ""){
		_("status").innerHTML = "Type in your username";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "forgot_pass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Sorry that username is not in our system";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
				} else {
					_("status").innerHTML = "An unknown error occurred";
				}
	        }
        }
        ajax.send("u="+u);
	}
}
</script>
</head>
<body class="mainBody">
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
<div class="formWrapper loginFormWrapper">
  <h3 style="margin-bottom: 30px;">Generate a temporary login password</h3>
  <form id="forgotpassform" onsubmit="return false;">
    <div>Step 1: Enter Your username</div>
    <input id="username" class="form-control inputForm" type="text" onfocus="_('status').innerHTML='';" maxlength="255">
    <button id="forgotpassbtn" class="formButton" onclick="forgotpass()">Generate Temporary Log In Password</button> 
    <p id="status"></p>
  </form>
</div>
</body>
</html>