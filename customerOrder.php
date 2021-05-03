<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>

    <body class = "bg">
    <div align="center"> <b style="font-size=30px;" align = "center">Create New Order</b></div> 
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


	if($conn->connect_error){
		echo "Connection Failed:" . $conn->connect_error;
		die("FAILED TO CONNECT");
	}	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
	$search = $_POST["search"];
    //echo $search;

    $query = "SELECT L.Name AS LocationName, L.Street_Address, L.City, L.State, I.Name AS ItemName, I.Price FROM Item I, Location L WHERE I.LocationID = L.LocationID AND I.Name LIKE '%$search%'";
    //echo $query;
    $result = $conn->query($query) or die(mysqli_error());
    if(empty($result->num_rows)){
        echo "The query returned no items. "; 
    }else {
        echo "The query returned the following items. "; 
        echo "Please select which items you would like to add to your order. "; 
        echo "<div align='center'><table class='styled-table'><tr>";
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

	}
   
    $conn->close()
    ?>
    </body>
</html>

