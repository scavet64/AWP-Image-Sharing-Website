<?php
include_once("php_includes/check_login_status.php");
include_once("comment_controller.php");
include_once("php_includes/date_conversion.php");
include_once("php_parsers/user_tagging_parser.php");
include_once("php_parsers/hashtag_parser.php");
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
        $containerString .= generatePhotoDisplay($row, $db_conx);
    }
	mysqli_close($db_conx);
	echo $containerString;
	exit();
?>

<?php
function generatePhotoDisplay($row, $db_conx){
    	$id = $row["photo_id"];
		$filename = $row["filename"];
		$description = $row["caption"];
		$uploaddate = $row["uploaddate"];
		$filelocation = $row["filelocation"];
		$photoOwner = $row["username"];
		
        $displayDate = convertDate($uploaddate, 'America/New_York');
        
        $description = parseTextForUsername($description);
        $description = parseTextForHashtag($description);
        $matches = getHashtagArray($description);
		
		$containerString = 
		'<div class="photoContainer">
    		<div style="margin:auto;">
        		<img src="'.$filelocation.'" id="photoID'.$id.'" class="displayImages" ></img>
        	</div>
            <div class="imageInfo">
                <p id="ownerTag'.$id.'" class="pictureOwner">by: 
                	<a class="linkToUser" href=user.php?u='.$photoOwner.'>'.$photoOwner.'</a>
                </p>
                <p id="uploadDate'.$id.'" class="uploadDate">Uploaded on: '.$displayDate.'</p>
            </div>
            <p id="description'.$id.'" class="descriptionText">'.$description.'</p>
            <div id=commentsForPhoto'.$id.'>
                '.genComments($id, $db_conx).'
            </div>
            <div>
                <input class="form-control commentBox" id="inputOnPhoto'.$id.'" type="text" name="firstname">
                <button class="formButton commentButton" type="button" onclick="postComment('.$id.')" class="">Comment</button>
            </div>
        </div>';
        return $containerString;
}
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
		$date = $rowComment["comment_date"];
    
        $displayDate = convertDate($date, 'America/New_York');
        
        $commentText = parseTextForUsername($commentText);
        $commentText = parseTextForHashtag($commentText);
    
        $commentArrayOfDivs .= genComment($commenter, $commentText, $displayDate);
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