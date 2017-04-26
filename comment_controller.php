<?php
function genComment($userWhoLeftComment, $comment, $commentDate) {
	
    $commentHTML = 
    '<div class="commentContainer">
        <a class="linkToUser" href=user.php?u='.$userWhoLeftComment.'>'.$userWhoLeftComment.':</a>
        <p class="commentDate">'.$commentDate.'</p>
        <p class="comment">'.$comment.'</p>
    </div>';
    return $commentHTML;
}
?>


<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["comment"]) && isset($_POST["photo_id"])){
	include_once("php_includes/check_login_status.php");
	include_once("php_parsers/user_tagging_parser.php");
	include_once("php_parsers/hashtag_parser.php");
	
	$comment = mysqli_real_escape_string($db_conx, $_POST['comment']);
	$photo_id = preg_replace('#[^a-z0-9]#i', '', $_POST['photo_id']);
	
	//insert comment into database
	$sql = "insert into photo_comments (user_id, photo_id, comment_text, comment_date) 
	        VALUES ('$log_id', '$photo_id', '$comment', now())";
	$query = mysqli_query($db_conx, $sql); 
	$newPhotoId = mysqli_insert_id($db_conx);
	
	$comment = parseTextForUsername($comment);
	$comment = parseTextForHashtag($comment);
	
	echo genComment($log_username, $comment, 'Just Now');
}
?>