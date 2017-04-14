<?php
$db_conx = mysqli_connect("localhost","scavet64","scavet64","scavet64");
// evaluate the connection
if(mysqli_connect_errno()){
    echo 'we have an error';
    echo mysqli_connect_error();
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>