<html>
    <head>
        <link rel="stylesheet" href="index.css">
        <div class="header"> <b>Create New Account</b></div>
    </head>
    <body class = "bg">
        <br> Select an account type: <br>
    <div align="left">
       	<form method="post">
       		Username: 
      		<input name = "username" type="text"/> <br>
			Password: 
      		<input name = "password" type="text"/> <br>
      		Name: 
      		<input name = "name" type="text"/> <br>
      		Phone Number: 
      		<input name = "phone" type="text"/> <br>
      		Address:
      		<input name = "address" type="text"/> <br>
      		City:
      		<input name = "city" type="text"/> <br>
      		State:
      		<input name = "state" type="text"/> <br>
          	<br>
      		<input type="submit" name="selectType" value="Confirm Selection" class="bigButton">
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
		//Check for any blanks
		if(empty($_POST["username"]) || empty($_POST["password"])
		|| empty($_POST["name"]) || empty($_POST["phone"])
		|| empty($_POST["address"]) || empty($_POST["city"])
		|| empty($_POST["state"]))
			echo "Unable to create user; all fields are required";
		else
		{
	       	$username = $_POST["username"];

       		$sql = "SELECT COUNT(DISTINCT UserID) AS count FROM Customer WHERE UserID = '$customer'";
       		$result = $conn->query($sql) or die(mysqli_error());
       		$count = mysqli_fetch_assoc($result)['count'];
       		if($count > 0)
       		{
       			echo "Unable to create user; this username is already taken";
       		}
       		else
       		{
       			$password = $_POST["password"];
       			$name = $_POST["name"];
       			$phone = $_POST["phone"];
       			$address = $_POST["address"];
       			$city = $_POST["city"];
       			$state = $_POST["state"];
       			
       			$sql = "INSERT INTO Customer VALUES('$username','$password','$name','123456789','$phone','$address','$city','$state');";
       		$result = $conn->query($sql) or die(mysqli_error());
       			
       			echo "User successfully created";
       		}
		}
	}
   
    $conn->close()
    ?>
    </body>
</html>
