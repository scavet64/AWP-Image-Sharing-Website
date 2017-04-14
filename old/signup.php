
<?php
if ( isset($_POST['email'] ) && isset($_POST['psw']) && isset($_POST['psw-repeat']) ) {
    
    $users_email = $_POST['email'];
    $users_psw = $_POST['psw'];
    $users_pswR = $_POST['psw-repeat'];
    $date = date('Y-m-d H:i:s');
    $dbname = "awp";
    
    if($users_psw !== $users_pswR){
        echo "not a match";
        return;
    }
    
    $servername = "localhost";
    $username = "vstro24";
    $password = "";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    echo "<br>Connected successfully";
    
    $query = "INSERT INTO photo_users  values (default, '$date', '$users_email', '$users_psw', null);";
    echo "<br>" . $query;
    $result = $conn->query($query);
    
    if ($result === TRUE) {
        // output data of each row
        echo "<br>New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
    
} else {
    echo "nope";
}
?>