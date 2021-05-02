<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>

	<body>
	HELLO THERE
	<?php echo "something";?>
	<?php
    include('passwords.php');
    global $USERNAME;
    global $PASSWORD;

    $servername = "mysql.eecs.ku.edu";
    $username = $USERNAME;
    $password = $PASSWORD;
    $dbname = "$USERNAME";
	echo " before connection.";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
		
	echo $username;

	if($conn->connect_error){
		echo "Connection Failed:"  + $conn->connect_error;
		die("FAILED TO CONNECT");
	}	
    /*
        //useful select all query for table
        $sql = "SELECT * FROM Customer";
    
	$result = mysqli_query($conn, $sql) or die(mysql_error());
    echo "<table class='styled-table'><tr>";
    for($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
            echo "<th>$field_info->name</th>";
    }
    echo "</tr>";
    while($line = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        echo "<tr>";
        foreach($line as $col_value){
            echo "<td>$col_value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    ?> */
	</body>
<html>
