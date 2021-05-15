<html>
    <head>
        <link rel="stylesheet" type="text/css"href="index.css"/> 
        <link rel="stylesheet" type="text/css" href="popup.css"/>
        <link rel="stylesheet" type="text/css" href="tablescroll.css"/>
<!-- https://stackoverflow.com/questions/62051806/how-to-use-div-to-layout-a-nav-bar-two-column-div-and-a-bottom-div -->
       <style>
       body {
          display: grid;
          grid-template-columns: 1fr 1fr;
          grid-template-rows: auto 1fr auto;
          grid-template-areas: "nav nav" "column1 column2" "bottom-row bottom-row";
          width: 100%;
          height: 100%;
          margin: 0;
        }

        nav {
          grid-area: nav;
          align-self: center;
        }

        .column1 {
          grid-area: column1;
          /*width: 75%;*/
        }

        .column2 {
          grid-area: column2;
          
          /*width: 50%;*/
        }

        .bottom-row {
          grid-area: bottom-row;
          justify-self: center;
        }
        </style>
    </head>

    <body class = "bg">

    <div style="min-width: 800px;padding: 2em;">
       <form method="get">
          Search by Item Name:  <br> <br>
          <input name="search" type="text"/>
          <input type="submit" value="Search">
        </form>

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
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		//echo "DEBUG POST<br>";
		$search = $_GET["search"];
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
            echo "Please select which items you would like to add to your order by scrolling
                horizontally (if necessary) and checking the 
               selected items and selecting quantities and <b>
		       select the button at the bottom of the page to add the items to your order.</b>
		        </div> "; 
		echo "<div align='center'>
        <form action='customerOrder.php' method='post'> 
       <div class='search-table-outter' >
        <table class='styled-table'><tr>";
        for($i = 0; $i < mysqli_num_fields($result); $i++) {
                $field_info = mysqli_fetch_field($result);
                echo "<th>$field_info->name</th>";
        }
        echo "<th>Select Item</th><th>Quantity</th>";
        echo "</tr>";
		$i = 0;
        
                //<!-- <span class='popuptext' id='myPopup'>A Simple Popup!</span> -->
        while($line = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            //"<div style='z-index: -1;' class='popup' onmouseover='popMoreDetails()'>
            //class='popup' onmouseover='popMoreDetails()'
             echo "<tr >
            ";

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
                </tr>
                </div>";
			$i = $i + 1;
        }
        echo "</table>
            </div>
            <input type='submit' value='Add Checked items'>
            </form>
            </div>";
        }


	}
   	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
			//echo "DEBUG GET<br>";
		if (isset($_POST['check'])) {
		    
		    $query = "SELECT MAX(OrderID) AS maxVal FROM MyOrder";
		    $result = $conn->query($query);
		    $data_array = $result->fetch_assoc();

            if(empty($data_array['maxVal'])){
		        $orderID = 1;
            }else {
		        $orderID = $data_array['maxVal'] + 1;
            }
		    $check = $_POST['check'];
			$quan = $_POST['quan'];

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
			//echo "You did not choose any items.";
		}
	}  
    //unset($_SESSION['stuffChecked']);
    $conn->close()
    ?>
    </div>
        <div class="search-table-outter" style="min-width: 800px;padding: 2em;"> 
            <b>
                 <big style="font-size=50px;">Current Items In Order</big>
            </b>
            <?php
            include('passwords.php');
            global $USERNAME;
            global $PASSWORD;

            $servername = "mysql.eecs.ku.edu";
            $username = $USERNAME;
            $password = $PASSWORD;
            $dbname = "$USERNAME";
            session_start();
            $conn = new mysqli($servername, $username, $password, $dbname);
            //echo $_SESSION['UID'];

                $query = "SELECT MAX(OrderID) AS maxVal FROM MyOrder";
                $result = $conn->query($query);
                $data_array = $result->fetch_assoc();
                $orderID = $data_array['maxVal'];

                $query = "SELECT ItemID, LocationID, Quantity FROM Order_Has_Item WHERE OrderID = $orderID";
                $result = $conn->query($query);

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
		    $result = $conn->query($query) or die(mysqli_error());

            $conn->close()

        /*session_start();*/
        ?>

        <form method='get'>
          <div align='left' class='checkboxes'>
            <label><input style='width: 10px;' type='checkbox'> 
    <span><i> &nbsp;&nbsp;
            I understand that I cannot delete the order after submitting. </i></span></label>
          </div> <br>
          <input type="submit" value="Submit Order">
        </form>
    </div> 



    <div class="bottom-row">
        <br><br>
        <button align="left" class="bigButton" onclick="location.href='orders.php'">Back</button>
        <br> <br> 
        <button align="left" class="bigButton" onclick="location.href='index.php'">Back to Home</button>
    </div>
        <script>
         function popMoreDetails() {
             console.log("DEBUG popmoredetails");
             var popup = document.getElementById("myPopup");
             popup.classList.toggle("show");
         }
        </script>
    </body>
</html>

