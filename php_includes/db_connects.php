<?php
$db_conx = mysqli_connect("localhost","vstro24","","photosite");
// evaluate the connection
if(mysqli_connect_errno()){
    echo mysqli_connect_error();
    exit();
}
?>