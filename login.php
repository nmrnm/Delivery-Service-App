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
   	if($_SERVER['REQUEST_METHOD'] == "POST"){
       	$username = $_POST["username"];
       	$password = $_POST["password"];

		$type = $_SESSION["userType"];
		$idName = "UserID";
		if($type == "Delivery_Driver")
			$idName = "DriverID";

       	$sql = "SELECT COUNT(DISTINCT Username) AS count FROM " . $type . " WHERE Username = '$username' AND Password = '$password'";
       	$result = $conn->query($sql) or die(mysqli_error());
       	$count = mysqli_fetch_assoc($result)['count'];

       	if($count != 1){
           	echo "<br><p> That username/password does not match. </p>
           	<br> <br>
  			<button class=\"bigButton\" onclick=\"location.href='index".substr($type,0,1).".php'\">Back</button>";
       	}else{
            $query = "SELECT Name, " . $idName . " FROM " . $type . " WHERE Username = '$username'";
            $result = $conn->query($query) or die(mysqli_error());
            $data =  mysqli_fetch_assoc($result);
            $name = $data['Name'];
            $myid = $data[$idName];

            echo "Welcome $name. ";
            echo "These are your orders: ";
            /* this button will jump to the order page when clicked */
            if($type == "Customer")
            	echo "<button id='customerOrder' type='button' style='align: right; position: absolute;
            top: 5; right: 5;' >
                    Create New Order
                </button>";
                
            
            $sql = "SELECT * FROM MyOrder WHERE UserID = '$myid'";
            if($type == "Delivery_Driver")
            	$sql = "SELECT * FROM MyOrder WHERE DriverID = '$myid'";
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
        echo "</table>";*/
    }
    $conn->close()
    ?> 
    <script type = "text/javascript">
      document.getElementById("customerOrder").onclick = function() {
              location.href = "customerOrder.php";
      }
    </script>
	</body>
<html>
