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
   	$username = $_SESSION["username"];
   	$password = $_SESSION["password"];

	$type = $_SESSION["userType"];
	$idName = "UserID";
	if($type == "Delivery_Driver")
		$idName = "DriverID";
   	
    $query = "SELECT Name, " . $idName . " FROM " . $type . " WHERE Username = '$username'";
    $result = $conn->query($query) or die(mysqli_error());
    $data =  mysqli_fetch_assoc($result);
    $name = $data['Name'];
    $myid = $data[$idName];
    $_SESSION['UID'] = $myid;

    if($type == "Customer")
    {
	    echo "Welcome $name. ";
	    echo "Here are your orders.";
	    
	   	echo "<button class='bigButton' id='customerOrder' type='button' style='align: right; position: absolute;
	    top: 5; right: 5;' >
	            Create New Order
	        </button> <br><br>";
	        
	    echo "<button class='bigButton' id='logisticsC' type='button' style='align: right; position: absolute;
	    top: 80; right: 5;' >
	            Logistics
	        </button>";
	        
	    //Created
	    echo "<br><br><span class='header'>Pending Orders</span>";
	    $sql = "SELECT OrderID, Start_Date, Time_Start FROM MyOrder WHERE UserID = '$myid' AND DriverID IS NULL";
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
	    $sql = "SELECT OrderID, Username, Start_Date, Time_Start FROM MyOrder, Delivery_Driver WHERE UserID = '$myid' AND Time_End IS NULL AND Delivery_Driver.DriverID = MyOrder.DriverID";
	    $result = $conn->query($sql) or die(mysql_error());
	    
	    echo "<div align='center'><table class='styled-table box'><tr>";
	    for($i = 0; $i < mysqli_num_fields($result); $i++) {
	    	$field_info = mysqli_fetch_field($result);
	    	if($i == 1)
	    		echo "<th>Driver</th>";
	    	else
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
	    
	    $sql = "SELECT OrderID, Username, Start_Date, Time_Start, Time_End FROM MyOrder, Delivery_Driver WHERE UserID = '$myid' AND Time_End IS NOT NULL AND Delivery_Driver.DriverID = MyOrder.DriverID";
	    $result = $conn->query($sql) or die(mysql_error());
	    
	    echo "<div align='center'><table class='styled-table box'><tr>";
	    for($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
	    	if($i == 1)
	    		echo "<th>Driver</th>";
	    	else
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
    elseif($type == "Delivery_Driver")
    {
        echo "Welcome $name. ";
        
	   	echo "<button class='bigButton' id='logisticsD' type='button' style='align: right; position: absolute;
	    top: 5; right: 5;' >
	            Logistics
	        </button>";
            
        //Pending
     	echo "<br><br><span class='header'>Pending Orders</span>";
        echo "<br> <br> These are pending orders that you can accept: ";
        echo "<br> <br> Select a checkbox and hit the button
        at the bottom of the page to accept a pending order.";
        $sql = "SELECT OrderID, Username, Start_Date, Time_Start FROM MyOrder, Customer WHERE DriverID IS NULL AND Customer.UserID = MyOrder.UserID";
        $result = $conn->query($sql) or die(mysql_error());
        
         echo "<div align='center'><table class='styled-table box'><tr>
            <form action='orders.php' method='post'>";
        for($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
        	if($i == 1)
        		echo "<th>Customer</th>";
        	else
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
                        name='checks[]' value='$val' ></td>";
            echo "</tr>";
        }
        echo "</table>
             <input type='submit' value='Add Checked Items' class='bigButton'>
            </form>
            </div>";
            
       if (isset($_POST['checks'])) {
       		echo "yes";
          $checks = $_POST['checks'];
         foreach ($checks as $ID){
            $sql = "UPDATE MyOrder SET DriverID = $myid
                 WHERE OrderID = $ID";

            $worked = $conn->query($sql);
                
            //echo "<script src=/text/javascript>location.reload()</script>";
            /*if($worked === TRUE){
                echo "WORKED " ;
            }else{
                echo "NOT WORKED " ;
            }*/
   		}
           	//header("Location:orders.php");
            //exit;
       }
            
        //Accepted
        echo "<br><br><span class='header'>Accepted Orders</span>";
        echo "<br><br>These are your accepted orders which are still running: ";
    	$sql = "SELECT OrderID, Username, Start_Date, Time_Start FROM MyOrder, Customer WHERE DriverID = '$myid' AND Time_End IS NULL AND Customer.UserID = MyOrder.UserID";
        $result = $conn->query($sql) or die(mysql_error());
        
        echo "<div align='center'><table class='styled-table box'><tr>
            <form action='orders.php' method='post'>";
        for($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
        	if($i == 1)
        		echo "<th>Customer</th>";
        	else
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
             <input type='submit' value='Add Checked Items' class='bigButton'>
            </form>
            </div>";

       if (isset($_POST['checkAcceptedDelivery'])) {
       		echo "no";
          $checkAccDeliv = $_POST['checkAcceptedDelivery'];
           foreach ($checkAccDeliv as $ID){
                $cur_time = strval(time());
                //echo $cur_time;
                $sql = "UPDATE MyOrder SET Time_End = $cur_time
                 WHERE OrderID = $ID";
                $worked = $conn->query($sql);
                
                //echo "<script src=/text/javascript>location.reload()</script>";
                /*if($worked === TRUE){
                    echo "WORKED " ;
                }else{
                    echo "NOT WORKED " ;
                }*/
           }
           	//header("Location:orders.php");
            //exit;
       }
        if(isset($_POST['checks']) or isset($_POST['checkAcceptedDelivery']) ){
           	header("Location:orders.php");
        }
   		//Finished
   		echo "<br><br><span class='header'>Finished Orders</span>";
        echo "<br> <br> These are finished orders that you have already completed: ";
       $sql = "SELECT OrderID, Username, Start_Date, Time_Start, Time_End FROM MyOrder, Customer WHERE DriverID = $myid AND Time_End IS NOT NULL AND Customer.UserID = MyOrder.UserID";
        $result = $conn->query($sql);
        
        echo "<div align='center'>
            <table class='styled-table box'><tr>";
        for($i = 0; $i < mysqli_num_fields($result); $i++) {
			$field_info = mysqli_fetch_field($result);
        	if($i == 1)
        		echo "<th>Customer</th>";
        	else
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
    $conn->close()
    ?> 
    <script type = "text/javascript">
      document.getElementById("customerOrder").onclick = function() {
              location.href = "customerOrder.php";
      }
    </script>
    <script type = "text/javascript">
      document.getElementById("logisticsD").onclick = function() {
              location.href = "logisticsD.php";
      }
    </script>
    <script type = "text/javascript">
      document.getElementById("logisticsC").onclick = function() {
              location.href = "logisticsC.php";
      }
    </script>
    <br><br>
	<button class="bigButton" onclick="location.href='index.php'">Back to Home</button>
	</body>
<html>
