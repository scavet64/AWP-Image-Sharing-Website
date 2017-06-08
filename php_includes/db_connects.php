<?php
$db_conx = mysqli_connect("shareddb1a.hosting.stackcp.net","scavet64","scavet64","scavet64-348306");
// evaluate the connection
if(mysqli_connect_errno()){
    echo 'we have an error!!';
    echo mysqli_connect_error();
    exit();
}

// Check connection
if ($db_conx->connect_error) {
    die("Connection failed: " . $db_conx->connect_error);
} 
?>