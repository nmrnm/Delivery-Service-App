<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>

    <body class = "bg">


    <div align="center"> <b><big style="font-size=50px;" align = "center">Create New Order
</big></b></div> 
    <br> <br>
    <div align="left">
            
           Current items in cart: <br><br> 
           Current number of items in cart: <br><br> 
           Total price of items in cart: <br><br> 
           Flat order fee: $5.00  <br> <br> 
           <b> Order Subtotal:  </b> <br> <br> 
           Enter amount willing to pay for delivery:  <br><br> 
<i>(Note that if you make it too low,
           a delivery driver probably won't accept your order):</i> <br> <br>
          <!--input type="submit" class="bigButton" value="Confirm Order"-->    </div>

    <br> <br>
    <div align="center"> <b><big style="font-size=50px;" align = "center">Add Items To Order:
</big></b></div> 
    <br> <br>
    
    <div align="center">
       <form method="post">
          Search by Item Name:  <br>
          <input name="search" type="text"/>
          <input type="submit" value="Search">
        </form>
    </div>

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
    session_start();

	if($conn->connect_error){
		echo "Connection Failed:" . $conn->connect_error;
		die("FAILED TO CONNECT");
	}	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		//echo "DEBUG POST<br>";
		$search = $_POST["search"];
		//echo $search;

		$query = "SELECT L.Name AS LocationName, L.LocationID, 
		    L.Street_Address, L.City, L.State, I.Name AS ItemName, 
		    I.ItemID, I.Price FROM Item I, Location L WHERE I.LocationID = L.LocationID 
		    AND I.Name LIKE '%$search%'";
		//echo $query;
		$result = $conn->query($query) or die(mysqli_error());
		if(empty($result->num_rows)){
		    echo "<div align='center'> The query returned no items. Please search again. </div> "; 
		}else {
		    echo "<div align='center'> The query returned the following items. "; 
		    echo "Please select which items you would like to add to your order and <b>
		       select the button at the bottom of the page to add the items to your order.</b>
		        </div> "; 
		echo "<div align='center'>
        <form action='customerOrder.php' method='get'> 
        <table class='styled-table'><tr>";
        for($i = 0; $i < mysqli_num_fields($result); $i++) {
                $field_info = mysqli_fetch_field($result);
                echo "<th>$field_info->name</th>";
        }
        echo "<th>Select Item</th><th>Quantity</th>";
        echo "</tr>";
		$i = 0;
        while($line = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            echo "<tr>";
            foreach($line as $col_value){
                echo "<td>$col_value</td>";
            }
            $val1 = $line["LocationID"];
            $val2 = $line["ItemID"];
			$val3 = strval($val1) . "_" . strval($val2) . "_" . strval($i);
            echo "<td align='center'><input type='checkbox' 
                name='check[]' value='$val3'></td>";
            echo"<td align='center'>
                <input type='number' min='1' value='1' name='quan[]'></td>
                </tr>";
			$i = $i + 1;
        }
        echo "</table>
            <input type='submit' value='Add Checked items'>
            </form>
            </div>";
        }


	}
   	
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
			//echo "DEBUG GET<br>";
		if (isset($_GET['check'])) {
		    
		    $query = "SELECT MAX(OrderID) AS maxVal FROM MyOrder";
		    $result = $conn->query($query);
		    $data_array = $result->fetch_assoc();
		    
		    $orderID = $data_array['maxVal'] + 1;
		    $check = $_GET['check'];
			$quan = $_GET['quan'];

		    $curTime = time();
		    $dateNum = date("Ymd");
		    $userID = $_SESSION['UID'];
		    //echo "THIS IS YOUR UID: " . strval($userID);
		    //echo strval($dateNum);
		    //echo "<br>" ;
		    //echo strval($curTime);
		    $sql = "INSERT INTO MyOrder(OrderID, DriverID, UserID, Start_Date, Time_Start, Time_End, Delivery_Cost)
		    VALUES($orderID, NULL, $userID, $dateNum, $curTime, NULL, NULL)";

		    $worked = $conn->query($sql);
		    /*if($worked === TRUE){
		        echo "WORKED ";
		    }else{
		        echo "NOT WORKED ";
		    }*/

		
			foreach ($check as $box){
				$vars = explode("_", $box); // split string 
				$locID = $vars[0];
				$itemID = $vars[1];
				$rowNum = $vars[2];
                $quantity = $quan[$rowNum];
 		        $sql = "INSERT INTO Order_Has_Item(OrderID, ItemID, LocationID, Quantity)
         		   VALUES($orderID, $itemID, $locID, $quantity)";
                $worked = $conn->query($sql);

                /*if($worked === TRUE){
                    echo "SUCCESSFUL INSERT";
                }else {
                    echo "NOT SUCCESSFUL INSERT";
                }*/
			}
		} else {
			echo "You did not choose any items.";
		}
	}  
    
    $conn->close()
    ?>
    <br><br>
    <button align="left" class="bigButton" onclick="location.href='orders.php'">Back</button>
    <br>
    <button align="left" class="bigButton" onclick="location.href='index.php'">Back to Home</button>

    </body>
</html>

