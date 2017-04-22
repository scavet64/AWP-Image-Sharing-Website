<?php
include_once("php_includes/check_login_status.php");
include_once("comment_controller.php");
?>
<?php 
// //if (isset($_POST["show"]) && $_POST["show"] == "all"){
// 	$picstring = "";
// // 	$gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
// // 	$user = preg_replace('#[^a-z0-9]#i', '', $_POST["user"]);
// 	$sql = "SELECT * FROM photo_files ORDER BY uploaddate ASC";
// 	$query = mysqli_query($db_conx, $sql);
// 	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
// 		$id = $row["id"];
// 		$filename = $row["filename"];
// 		$description = $row["description"];
// 		$uploaddate = $row["uploaddate"];
// 		$picstring .= "$id|$filename|$description|$uploaddate|||";
//     }
// 	mysqli_close($db_conx);
// 	$picstring = trim($picstring, "|||");
// 	echo $picstring;
// 	exit();
// //}
?>
<?php 
//if (isset($_GET["show"]) && $_GET["show"] == "all"){
	$picstring = "";
	$containerString = "";
// 	$gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
// 	$user = preg_replace('#[^a-z0-9]#i', '', $_POST["user"]);
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
        '.genComment($log_username,'you stink!!!').'
    </div>
    <div>
        <input id="inputOnPhoto'.$id.'" type="text" name="firstname">
        <button type="button" onclick="postComment('.$id.')" class="">Comment</button>
    </div>
</div>';
		
		//$picstring .= "$id|$filename|$description|$uploaddate|||";
    }
	mysqli_close($db_conx);
	//$picstring = trim($picstring, "|||");
	echo $containerString;
	exit();
//}
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