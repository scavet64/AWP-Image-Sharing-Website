<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>AWP: Image sharing website</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<link rel="stylesheet" href="style/style.css">
</head>
<body class="mainBody">
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">

    <!--Place where we display photos-->
    <div id="photoDisplayContainer" class="photoDisplayContainer">
        <?php
            //if they click a hashtag link
            if(isset($_GET["tag"])){
                include_once("php_includes/check_login_status.php");
                include_once("php_gens/photo_display_gen.php");
                
                $tag = preg_replace('#[^a-z0-9]#i', '', $_GET['tag']);
                $containerString = "";
                
                $sql = "SELECT * FROM photo_files 
            	        JOIN photo_users USING(user_id) 
            	        JOIN photos_hashtags USING(photo_id) 
            	        JOIN hashtags USING(hashtag_id) 
            	        WHERE hashtag_value = '$tag'
            	        ORDER BY uploaddate DESC LIMIT 10";
            	        
            	$query = mysqli_query($db_conx, $sql);
            	$numrows = mysqli_num_rows($query);
		        if($numrows < 1){
		            //show no hashtags found!
		            $containerString = '<p class="noHashTags">No photos with that hashtag!</p>';
		        } else {
		            $containerString = '<p>Photos with #'.$tag.'</p>';
                	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $containerString .= generatePhotoDisplay($row, $db_conx, $log_username);
                    }
		        }
                echo $containerString;
            }
            
            //if they search multiple things
            if(isset($_GET["q"])){
                include_once("php_includes/check_login_status.php");
                include_once("php_gens/photo_display_gen.php");
                include_once("php_parsers/hashtag_parser.php");
                
                //get the hashtag array from the search
                $matches = getHashtagArray($_GET["q"])[1];
                
                $sql = "SELECT * FROM photo_files 
            	        JOIN photo_users USING(user_id) 
            	        JOIN photos_hashtags USING(photo_id) 
            	        JOIN hashtags USING(hashtag_id) 
            	        WHERE hashtag_value =".$matches[0];
                
                for($i = 1; $i < count($matches); $i++){
                    $sql .="UNION
                            SELECT * FROM photo_files 
                	        JOIN photo_users USING(user_id) 
                	        JOIN photos_hashtags USING(photo_id) 
                	        JOIN hashtags USING(hashtag_id) 
                	        WHERE hashtag_value =".$matches[$i];
                }
                $sql .="ORDER BY uploaddate DESC LIMIT 10";
                
                $containerString = "";
            	        
            	$query = mysqli_query($db_conx, $sql);
            	$numrows = mysqli_num_rows($query);
		        if($numrows < 1){
		            //show no hashtags found!
		            $containerString = '<p class="noHashTags">No photos with that hashtag!</p>';
		        } else {
		            $containerString = '<p>Photos with #'.$tag.'</p>';
                	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $containerString .= generatePhotoDisplay($row, $db_conx, $log_username);
                    }
		        }
                echo $containerString;
            }
        ?>
    </div>
  
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>

</html>