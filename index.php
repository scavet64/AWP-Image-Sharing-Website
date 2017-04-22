<?php
include_once("php_includes/check_login_status.php");
?>
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
  <button class="uploadbutton" id="uploadButton" onclick="toggleElement('uploadModal')">Upload</button> 

<!-- The Modal -->
<div id="uploadModal" class="modal">
  <span onclick="document.getElementById('uploadModal').style.display='none'" 
    class="close" title="Close Modal">&times;</span>

  <!-- Modal Content -->
  <form class="modal-content animate uploadForm" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">

    <div class="modalContainer">
      <label><b>Please select the photo to upload</b></label>
      <input type="file" name="photo" required>

      <label><b>Description</b></label>
      <input type="text" placeholder="Enter Description" name="description" required>

      
    </div>

    <div class="modalContainer" style="background-color:#f1f1f1">
        <div style="height:50px;">
            <button type="button" onclick="document.getElementById('uploadModal').style.display='none'" class="uploadbutton cancelbtn sbsLeftButton">Cancel</button>
            <button type="submit" class="uploadbutton sbsRightButton">Upload</button>
        </div>
    </div>
  </form>
</div>
  
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>