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
    $type = NULL; // need this declared here
   	if($_SERVER['REQUEST_METHOD'] == "POST"){
       	$username = $_POST["username"];
       	$password = $_POST["password"];
		if(!empty($username) and !empty($password)){
			$_SESSION["username"] = $username;
			$_SESSION["password"] = $password;
		}
       	$username = $_SESSION["username"];
       	$password = $_SESSION["password"];

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
            $_SESSION['UID'] = $myid;

            /* this button will jump to the order page when clicked */
            if($type == "Customer"){
                    echo "Welcome $name. ";
                    echo "Here are your orders.";
                    
                   	echo "<button class='bigButton' id='customerOrder' type='button' style='align: right; position: absolute;
                    top: 5; right: 5;' >
                            Create New Order
                        </button>";
                        
                    //Created
                    echo "<br><br><span class='header'>Pending Orders</span>";
                    $sql = "SELECT * FROM MyOrder WHERE UserID = '$myid' AND DriverID IS NULL";
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
                    
                    
                    //Accepted
                    echo "<br><br><span class='header'>Ongoing Orders</span>";
                    $sql = "SELECT * FROM MyOrder WHERE UserID = '$myid' AND DriverID IS NOT NULL AND Time_End IS NULL";
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
                    
                    
                    //Finished
                    echo "<br><br><span class='header'>Completed Orders</span>";
                    
                    $sql = "SELECT * FROM MyOrder WHERE UserID = '$myid' AND Time_End IS NOT NULL";
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
                    
            }elseif($type == "Delivery_Driver"){

                echo "Welcome $name. ";
                echo "These are your accepted orders which are still running: ";
            	$sql = "SELECT * FROM MyOrder WHERE DriverID = '$myid' AND Time_End IS NULL";
                $result = $conn->query($sql) or die(mysql_error());
                
                echo "<div align='center'><table class='styled-table box'><tr>
                    <form action='login.php' method='post'>";
                for($i = 0; $i < mysqli_num_fields($result); $i++) {
                        $field_info = mysqli_fetch_field($result);
                        echo "<th>$field_info->name</th>";
                }
                echo "<th>Select Item</th>";
                echo "</tr>";
                while($line = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    echo "<tr>";
                    foreach($line as $col_value){
                        echo "<td>$col_value</td>";
                    }
                    $val = $line["OrderID"];
                    echo "<td align='center'><input type='checkbox' 
                                name='checkAcceptedDelivery[]' value='$val' ></td>";
                    echo "</tr>";
                }
                echo "</table>
                     <input type='submit' value='Add Checked items'>
                    </form>
                    </div>";

               $checkAccDeliv = $_POST['checkAcceptedDelivery'];
               if (isset($_POST['checkAcceptedDelivery'])) {
                   foreach ($checkAccDeliv as $ID){
                        $cur_time = strval(time());
                        echo $cur_time;
                        $sql = "UPDATE MyOrder SET Time_End = $cur_time
                         WHERE OrderID = $ID";
                        $worked = $conn->query($sql);
                        //header("Location:login.php");
                        //exit;
                        
                        echo "<script src=/text/javascript>location.reload()</script>";
                        if($worked === TRUE){
                            echo "WORKED " ;
                        }else{
                            echo "NOT WORKED " ;
                        }

                   }
               }

                echo "<br> <br> These are pending orders that you can accept: ";
                echo "<br> <br> Select a checkbox and hit the button
                at the bottom of the page to accept a pending order.";
                $sql = "SELECT * FROM MyOrder WHERE DriverID IS NULL";
                $result = $conn->query($sql);
                echo "<div align='center'>
                    <form action='login.php' method='post'> 
                    <table class='styled-table box'><tr>";
                for($i = 0; $i < mysqli_num_fields($result); $i++) {
                        $field_info = mysqli_fetch_field($result);
                        echo "<th>$field_info->name</th>";
                }
                echo "<th>Select Item</th>";
                echo "</tr>";
                while($line = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    echo "<tr>";
                    foreach($line as $col_value){
                        echo "<td>$col_value</td>";
                    }
                    $val = $line["OrderID"];
                    echo "<td align='center'><input type='checkbox' 
                                name='check[]' value='$val'></td>";
                    echo "</tr>";
                }
                echo "</table>
                     <input type='submit' value='Add Checked items'>
                     </form>
                    </div>";
               $check = $_POST['check'];
               if (isset($_POST['check'])) {
                   foreach ($check as $ID){
                        $sql = "UPDATE MyOrder SET DriverID = $myid
                         WHERE OrderID = $ID ";
                        $worked = $conn->query($sql);
                        //header("Location:login.php");
                        //exit;
                        
                        echo "<script src=/text/javascript>location.reload()</script>";
                        /*if($worked === TRUE){
                            echo "WORKED " ;
                        }else{
                            echo "NOT WORKED " ;
                        }*/

                   }
               }

                echo "<br> <br> These are finished orders that you have already completed: ";
               $sql = "SELECT * FROM MyOrder WHERE DriverID IS NOT NULL AND Time_End IS NOT 
                   NULL";
                $result = $conn->query($sql);
                echo "<div align='center'>
                    <table class='styled-table box'><tr>";
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
                echo "</table>
                    </div>";
            }
       }
    }
    $conn->close()
    ?> 
    <script type = "text/javascript">
      document.getElementById("customerOrder").onclick = function() {
              location.href = "customerOrder.php";
      }
    </script>
    <br><br>
	<button class="bigButton" onclick="location.href='index.php'">Back to Home</button>
	</body>
<html>
