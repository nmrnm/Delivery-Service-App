<?php
	session_start();
?>

<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>

	<body class = "bg">
	<?php
    include('passwords.php');
    global $USERNAME;
    global $PASSWORD;
    global $CURRENT_UID;

    $servername = "mysql.eecs.ku.edu";
    $username = $USERNAME;
    $password = $PASSWORD;
    $dbname = "$USERNAME";

    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

	if($conn->connect_error){
		echo "Connection Failed:" . $conn->connect_error;
		die("FAILED TO CONNECT");
	}
	
    //$data =  mysqli_fetch_assoc($result);
    //$name = $data['Name'];
	$myid = $_SESSION["UID"];
	
    //Locations visited
    echo "<br><br><span class='header'>Stores Purchased From</span>";
    $sql = "SELECT DISTINCT Location.Name FROM Location, (
    	SELECT LocationID FROM Order_Has_Item, MyOrder WHERE UserID = '$myid' AND MyOrder.OrderID = Order_Has_Item.OrderID) AS L
    	WHERE Location.LocationID = L.LocationID";
    $result = $conn->query($sql) or die(mysql_error());
    
    echo "<div align='center'><table class='styled-table box'><tr>";
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
    echo "</table></div>";

   	$conn->close()
	?>
    <br><br>
	<button class="bigButton" onclick="location.href='orders.php'">Back</button>
	</body>
    <br><br>
	<button class="bigButton" onclick="location.href='index.php'">Back to Home</button>
	</body>
</html>
