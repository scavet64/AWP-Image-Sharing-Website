<?php
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if($user_ok == true) {
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php">Log Out</a>';
}
?>

<div id="pageTop">
  <div id="pageTopWrap">
    <div id="pageTopRest">
      <div id="menu1" class="TopMenu" style="float: right">
        <div>
          <?php echo $loginLink; ?>
        </div>
      </div>
      <div id="menu2" style="float:left">
        <div>
          <a href="home.php">
            <img id="home" style="width:35px;height:35px;margin-top:0px;" src="images/home-icon.png" alt="home" title="Home">
          </a>
        </div>
      </div>
      <div class="searchHolder" style="margin: auto;width: 500px;height: 40px;min-width: 500px;">
          <input id="searchBar" type="text" class="form-control searchBar" name="search"/>
          <button class="formButton searchButton" onclick="SearchHashtags()">Search</button>
      </div>
    </div>
  </div>
</div>