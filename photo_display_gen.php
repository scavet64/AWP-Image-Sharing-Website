<?php
include_once("php_includes/check_login_status.php");
include_once("comment_controller.php");
?>
<?php 
//if (isset($_GET["show"]) && $_GET["show"] == "all"){
	$picstring = "";
	$containerString = "";
	$sql = "SELECT * FROM photo_files 
	        JOIN photo_users USING(user_id)
	        ORDER BY uploaddate ASC LIMIT 10";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$id = $row["photo_id"];
		$filename = $row["filename"];
		$description = $row["description"];
		$uploaddate = $row["uploaddate"];
		$filelocation = $row["filelocation"];
		$photoOwner = $row["username"];
		
		$containerString .= '<div class="photoContainer">
    <img src="'.$filelocation.'" id="photoID'.$id.'" class="displayImages" ></img>
    <div style="height:30px;">
        <p id="description" class="pictureOwner">'.$photoOwner.'</p>
        <p id="description" class="uploadDate">'.$uploaddate.'</p>
    </div>
    <p id="description" class="descriptionText">'.$description.'</p>
    <div id=commentsForPhoto'.$id.'>
        '.genComments($id, $db_conx).'
    </div>
    <div>
        <input id="inputOnPhoto'.$id.'" type="text" name="firstname">
        <button type="button" onclick="postComment('.$id.')" class="">Comment</button>
    </div>
</div>';
		
    }
	mysqli_close($db_conx);
	echo $containerString;
	exit();
?>

<?php
function genComments($id, $db_conx) {
    
    $commentArrayOfDivs = "";
    
    $sqlComment = "SELECT * FROM photo_comments
            JOIN photo_users USING(user_id)
	        WHERE photo_id = ".$id." LIMIT 10";
	$queryComments = mysqli_query($db_conx, $sqlComment);
    
    while ($rowComment = mysqli_fetch_array($queryComments, MYSQLI_ASSOC)) {
		$commentText = $rowComment["comment_text"];
		$commenter = $rowComment["username"];
    
        $commentArrayOfDivs .= genComment($commenter, $commentText);
    }
    
    return $commentArrayOfDivs;
}
?>

<!--<div id="photo1" class="photoContainer">-->
<!--    <img src="uploads/999526020558.png" class="displayImages" ></img>-->
<!--    <div style="height:30px;">-->
<!--        <p id="description" class="pictureOwner">PictureOwner</p>-->
<!--        <p id="description" class="uploadDate">Upload date</p>-->
<!--    </div>-->
<!--    <p id="description" class="descriptionText">This is a description</p>-->
<!--    <div id=comments>-->
<!--        <p id="comment1" class="comment">This is a comment</p>-->
<!--        <p id="comment2" class="comment">This is another comment</p>-->
<!--    </div>-->
<!--</div>-->