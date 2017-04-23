<?php
function genComment($userWhoLeftComment, $comment) {
    $commentHTML = 
    '<div>
        <p id="user" class="commenter">'.$userWhoLeftComment.'</p>
        <p id="comment1" class="comment">'.$comment.'</p>
    </div>';
    return $commentHTML;
}
?>


<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["comment"]) && isset($_POST["photo_id"])){
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	include_once("php_includes/check_login_status.php");
	$comment = preg_replace('#[^a-z0-9]#i', '', $_POST['comment']);
	$photo_id = preg_replace('#[^a-z0-9]#i', '', $_POST['photo_id']);
	
	//insert comment into database
	$sql = "insert into photo_comments (user_id, photo_id, comment_text) 
	        VALUES ('$log_id', '$photo_id', '$comment')";
	$query = mysqli_query($db_conx, $sql); 
	$newPhotoId = mysqli_insert_id($db_conx);
	
	echo genComment($log_username, $comment);
}
?>