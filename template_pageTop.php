<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
//$envelope = '<img src="images/note_dead.jpg" width="22" height="12" alt="Notes" title="This envelope is for logged in members">';
$loginLink = '<a href="login.php">Log In</a> &nbsp; | &nbsp; <a href="signup.php">Sign Up</a>';
if($user_ok == true) {
  // $sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
  // $query = mysqli_query($db_conx, $sql);
  // $row = mysqli_fetch_row($query);
  // $notescheck = $row[0];
  // $sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
  // $query = mysqli_query($db_conx, $sql);
  // $numrows = mysqli_num_rows($query);
  //   if ($numrows == 0) {
  //   //$envelope = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/note_still.jpg" width="22" height="12" alt="Notes"></a>';
  //   } else {
  //   //$envelope = '<a href="notifications.php" title="You have new notifications"><img src="images/note_flash.gif" width="22" height="12" alt="Notes"></a>';
  // }
    $loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php">Log Out</a>';
}
?>

<div id="pageTop">
  <div id="pageTopWrap">
    <!--<div id="pageTopLogo">-->
    <!--  <a href="home.php">-->
    <!--    <img src="images/logo.jpg" style="width:40px;height:40px;margin-top: 0px;" alt="logo" title="AWP">-->
    <!--  </a>-->
    <!--</div>-->
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
          <!--<input id="searchBar" type="text" class="form-control searchBar" name="search"/>-->
          <!--<button class="formButton searchButton" onclick="SearchHashtags()">Search</button>-->
        </div>
      </div>
      <div class="searchHolder" style="margin: auto;width: 500px;height: 40px;min-width: 500px;">
          <input id="searchBar" type="text" class="form-control searchBar" name="search"/>
          <button class="formButton searchButton" onclick="SearchHashtags()">Search</button>
      </div>
    </div>
  </div>
</div>