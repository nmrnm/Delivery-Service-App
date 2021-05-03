<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>

	<body class = "bg">
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
       $customer = $_POST["username"];
       $password = $_POST["password"];

       $sql = "SELECT COUNT(DISTINCT UserID) AS count FROM Customer WHERE UserID = '$customer' AND password = '$password'";
       $result = $conn->query($sql) or die(mysqli_error());
       $count = mysqli_fetch_assoc($result)['count'];

       if($count != 1){
           echo "<p> Invalid Login Information. Please hit back button on your browser
               to go back to the login screen. </p>";
       }else{
            $query = "SELECT Name FROM Customer WHERE UserID = '$customer'";
            $result = $conn->query($query) or die(mysqli_error());
            $name = mysqli_fetch_assoc($result)['Name'];
            echo "Welcome $name. ";
            echo "These are your orders: ";
            /* this button will jump to the order page when clicked */
            echo "<button id='customerOrder' type='button' style='align: right; position: absolute;
            top: 5; right: 5;' >
                    Create New Order
                </button>";
            $sql = "SELECT * FROM MyOrder WHERE UserID = '$customer'";
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
