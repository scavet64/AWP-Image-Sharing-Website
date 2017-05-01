<?php
include_once("php_includes/check_login_status.php");
$uploadButton = "";
if($user_ok){
    $uploadButton = '
    <div class="photoUploadButtonWrapper">
        <button class="uploadbutton mainUploadButton" id="uploadButton" onclick="toggleElement(\'uploadModal\')" style="float:none; width:100%">Upload</button>
    </div>';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>AWP: Image sharing website</title>
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
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

<?php echo $uploadButton ?>

<!-- The Upload Modal -->
<div id="uploadModal" class="modal">

  <!-- Modal Content -->
  <form class="modal-content animate uploadForm" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">

    <div class="modalContainer">
        <label class="uploadHeading"><b>Please select the photo to upload</b></label>
        <!--<input type="file" name="photo" required>-->
      
            <div class="input-group" style="margin:15px;">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Browse&hellip; <input type="file" name="photo" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control" readonly>
            </div>
        <label class="uploadHeading"><b>Enter a brief description</b></label>
        <input type="text" class="form-control" placeholder="Enter Description" name="description" required maxlength="255">
    </div>

    <div class="modalContainer" style="background-color:#f1f1f1">
        <div style="height:50px;">
            <button type="button" onclick="toggleElement('uploadModal')" class="uploadbutton cancelbtn sbsLeftButton">Cancel</button>
            <button type="submit" class="uploadbutton sbsRightButton">Upload</button>
        </div>
    </div>
  </form>
</div>

<!--Test This doesnt work yet-->
<?php
    require_once('php_gens/delete_confirm.php');
?>
<!--Test -->

    <!--Place where we display photos-->
    <div id="photoDisplayContainer" class="photoDisplayContainer">
    </div>
  
</div>
<script>
// Get the modal
var modal = document.getElementById('uploadModal');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

loadImages(0);
var offset = 10;
	$(window).scroll(function(){
        if ($(window).scrollTop() == $(document).height()-$(window).height()){
            console.log("hey bottom of the page here");
            
            loadImages(offset);
            //add to our offset
            offset += 10;
        }
    });
</script>
</body>

</html>