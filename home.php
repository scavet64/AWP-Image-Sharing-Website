<?php
include_once("php_includes/check_login_status.php");
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
    <div class="photoUploadButtonWrapper">
        <button class="uploadbutton mainUploadButton" id="uploadButton" onclick="toggleElement('uploadModal')" style="float:none; width:100%">Upload</button>
    </div>

<!-- The Upload Modal -->
<div id="uploadModal" class="modal">
  <span onclick="toggleElement('uploadModal')" 
    class="close" title="Close Modal">&times;</span>

  <!-- Modal Content -->
  <form class="modal-content animate uploadForm" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">

    <div class="modalContainer">
      <label><b>Please select the photo to upload</b></label>
      <input type="file" name="photo" required>

        <label class="btn btn-default btn-file">
    Browse <input type="file" style="display: none;">
</label>
      <label><b>Description</b></label>
      <input type="text" placeholder="Enter Description" name="description" required maxlength="255">

      
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