<?php
	session_start();
	$_SESSION["username"] = "";
	$_SESSION["password"] = "";
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
            header("Location: orders.php");
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
