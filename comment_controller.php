<?php
function genComment($userWhoLeftComment, $comment, $commentDate, $canDelete, $comment_id) {
	
	$deleteButton ='';
	
	if($canDelete){
		//let them delete this comment
		$deleteButton = '<button class="btn btn-xs btn-danger deleteCommentButton" 
							onclick="deleteComment('.$comment_id.')" type="button">
							<i class="glyphicon glyphicon-trash"></i>
						</button>';
		
		//maybe get this working one day
		// $deleteButton = 
		// '<div method="POST" action="comment_controller.php" accept-charset="UTF-8" style="display:inline">
		//     <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" 
		//     data-target="#confirmDelete" data-title="Delete comment" 
		//     data-message="Are you sure you want to delete this comment ?"
		//     onclick="deleteComment('.$comment_id.')">
		//         <i class="glyphicon glyphicon-trash"></i>
		//     </button>
		    
		// </div>';
		// //<input name="comment_id" value="'.$comment_id.'">
	}
	
	if($userWhoLeftComment === '[deleted]'){
		$LinkToUser = '<a class="deletedUser">'.$userWhoLeftComment.':</a>';
	} else {
		$LinkToUser = '<a class="linkToUser" href=user.php?u='.$userWhoLeftComment.'>'.$userWhoLeftComment.':</a>';
	}
	
    $commentHTML = 
    '<div id="comment'.$comment_id.'" class="commentContainer">
        '.$LinkToUser.'
        <p class="commentDate">'.$commentDate.'</p>
        <p class="comment">'.$comment.'</p>
        '.$deleteButton.'
    </div>';
    return $commentHTML;
}
?>

<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["comment_id"])){
	include_once("php_includes/check_login_status.php");
	
	if($user_ok){
		$comment_id = preg_replace('#[^a-z0-9]#i', '', $_POST['comment_id']);
		
		//delete comment from the database
		$sql = "DELETE FROM photo_comments 
		        WHERE comment_id = '$comment_id'";
		$query = mysqli_query($db_conx, $sql); 
		$newPhotoId = mysqli_insert_id($db_conx);
	} else {
		echo "error";
	}
	exit();
}
?>

<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["comment"]) && isset($_POST["photo_id"])){
	include_once("php_includes/check_login_status.php");
	include_once("php_parsers/user_tagging_parser.php");
	include_once("php_parsers/hashtag_parser.php");
	
	if($user_ok){
		$comment = mysqli_real_escape_string($db_conx, $_POST['comment']);
		$photo_id = preg_replace('#[^a-z0-9]#i', '', $_POST['photo_id']);
		
		//get user who owns this photo
		$sql = "SELECT user_id FROM photo_files 
	        	JOIN photo_users USING(user_id)
	        	WHERE photo_id='".$photo_id."'";
		$query = mysqli_query($db_conx, $sql);
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		$photoOwnerID = $row["user_id"];
		
		
		//check and see if he is on the block list
		$sql = "SELECT blocker FROM blockedusers 
	        	WHERE blockee='".$log_id."'
	        	AND blocker='".$photoOwnerID."'";
		$query = mysqli_query($db_conx, $sql);
		$numrows = mysqli_num_rows($query);
		if($numrows >= 1){
			//blocked user from commenting
			echo "error: This user blocked you :^)";
		    exit();	
		}
		
		//insert comment into database
		$sql = "insert into photo_comments (user_id, photo_id, comment_text, comment_date) 
		        VALUES ('$log_id', '$photo_id', '$comment', now())";
		$query = mysqli_query($db_conx, $sql); 
		$newComment_id= mysqli_insert_id($db_conx);
		
		$comment = parseTextForUsername($comment);
		$comment = parseTextForHashtag($comment);
		
		echo genComment($log_username, $comment, 'Just Now', True, $newComment_id);
	} else {
		//user not logged in.
		echo "error: You cannot post a comment if you are not logged in.";
	}
}
?>