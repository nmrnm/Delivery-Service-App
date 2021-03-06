<html>
    <head>
        <link rel="stylesheet" href="index.css">
        <div class="header"> <b>Create New Customer Account</b></div>
    </head>
    <body class = "bg">
    <?php
    //define variables
    $msg = $uname = $pswd = $name = $phone = $address = $city = $state = $uid = "";

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
		//Update variables
		if(empty($_POST["uname"]))
       		$uname = "";
       	else
       		$uname = $_POST["uname"];
       	
       	if(empty($_POST["pswd"]))
       		$pswd = "";
       	else
       		$pswd = $_POST["pswd"];
       		
		if(empty($_POST["name"]))
       		$name = "";
       	else
       		$name = $_POST["name"];

		if(empty($_POST["phone"]))
       		$phone = "";
       	else
       		$phone = $_POST["phone"];
       		
		if(empty($_POST["address"]))
       		$address = "";
       	else
       		$address = $_POST["address"];
       		
		if(empty($_POST["city"]))
       		$city = "";
       	else
       		$city = $_POST["city"];
       		
		if(empty($_POST["state"]))
       		$state = "";
       	else
       		$state = $_POST["state"];
	
		//Check for any blanks
		if(empty($_POST["uname"]) || empty($_POST["pswd"])
		|| empty($_POST["name"]) || empty($_POST["phone"])
		|| empty($_POST["address"]) || empty($_POST["city"])
		|| empty($_POST["state"]))
			$msg = "Unable to create user; all fields are required";
		else
		{
       		$sql = "SELECT COUNT(DISTINCT Username) AS count FROM Customer WHERE Username = '$uname'";
       		$result = $conn->query($sql) or die(mysqli_error());
       		$count = mysqli_fetch_assoc($result)['count'];
       		if($count > 0)
       		{
       			$msg = "Unable to create user; this username is already taken";
       		}
       		else
       		{
       			$sql = "SELECT MAX(UserID) AS max FROM Customer";
       			$result = $conn->query($sql) or die(mysqli_error());
       			$uid = mysqli_fetch_assoc($result)['max'];
       			$uid++;
       			
       			$sql = "INSERT INTO Customer VALUES('$uid','$pswd','$name','NULL','$phone','$address','$city','$state','$uname');";
       			$result = $conn->query($sql) or die(mysqli_error());
       			
       			header("Location: indexC.php");
       		}
		}
	}
   
    $conn->close()
    ?>
    
    <div align="left">
       	<form method="post">
       		Username: 
      		<input name = "uname" type="text" value="<?php echo $uname;?>"/> <br><br>
			Password: 
      		<input name = "pswd" type="text" value="<?php echo $pswd;?>"/> <br><br>
      		Name: 
      		<input name = "name" type="text" value="<?php echo $name;?>"/> <br><br>
      		Phone Number: 
      		<input name = "phone" type="text" value="<?php echo $phone;?>"/> <br><br>
      		Address:
      		<input name = "address" type="text" value="<?php echo $address;?>"/> <br><br>
      		City:
      		<input name = "city" type="text" value="<?php echo $city;?>"/> <br><br>
      		State:
      		<input name = "state" type="text" value="<?php echo $state;?>"/> <br><br>
          	<br>
      		<input type="submit" name="selectType" value="Create Account" class="bigButton">
        </form>
    </div>
	<button class="bigButton" onclick="location.href='index.php'">Back</button>
	<br><br>
    <span><?php echo $msg;?></span>
    
    </body>
</html>
