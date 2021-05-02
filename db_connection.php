<?php
    include('passwords.php');
    global $USERNAME;
    global $PASSWORD;

    $servername = "mysql.eecs.ku.edu";
    $username = $USERNAME;
    $password = $PASSWORD;
    $dbname = "$USERNAME";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    //if ($conn->connect_error) {
    //  die("Connection failed: " . $conn->connect_error);
    //}

}
?> 
