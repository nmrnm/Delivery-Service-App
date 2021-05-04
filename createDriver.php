<html>
    <head>
        <link rel="stylesheet" href="index.css">
        <div class="header"> <b>Create New Driver Account</b></div>
    </head>
    <body class = "bg">
    <?php
    //define variables
    $msg = $uname = $pswd = $name = $phone = $license = $state = $model = $year = $make = $did = "";

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
       		
		if(empty($_POST["license"]))
       		$license = "";
       	else
       		$license = $_POST["license"];
       		
		if(empty($_POST["state"]))
       		$state = "";
       	else
       		$state = $_POST["state"];
       		
		if(empty($_POST["model"]))
       		$model = "";
       	else
       		$model = $_POST["model"];
       		
		if(empty($_POST["year"]))
       		$year = "";
       	else
       		$year = $_POST["year"];
		
		if(empty($_POST["make"]))
       		$make = "";
       	else
       		$make = $_POST["make"];
	
		//Check for any blanks
		if(empty($_POST["uname"]) || empty($_POST["pswd"])
		|| empty($_POST["name"]) || empty($_POST["phone"])
		|| empty($_POST["license"]) || empty($_POST["state"])
		|| empty($_POST["model"]) || empty($_POST["year"])
		|| empty($_POST["make"]))
			$msg = "Unable to create user; all fields are required";
		else
		{
       		$sql = "SELECT COUNT(DISTINCT Username) AS count FROM Delivery_Driver WHERE Username = '$uname'";
       		$result = $conn->query($sql) or die(mysqli_error());
       		$count = mysqli_fetch_assoc($result)['count'];
       		if($count > 0)
       		{
       			$msg = "Unable to create user; this username is already taken";
       		}
       		else
       		{
       			$sql = "SELECT MAX(DriverID) AS max FROM Delivery_Driver";
       			$result = $conn->query($sql) or die(mysqli_error());
       			$did = mysqli_fetch_assoc($result)['max'];
       			$did++;
       		
       			$sql = "INSERT INTO Delivery_Driver VALUES('$did','$pswd','$name','NULL','$phone','$license','$state','$model','$year','$make','$uname');";
       			$result = $conn->query($sql) or die(mysqli_error());
       			
       			header("Location: indexD.php");
       		}
		}
	}
   
    $conn->close()
    ?>
    
    <div align="left">
       	<form method="post">
       		<span align="center" class="header">User Information</span><br><br>
       		Username: 
      		<input name = "uname" type="text" value="<?php echo $uname;?>"/> <br><br>
			Password: 
      		<input name = "pswd" type="text" value="<?php echo $pswd;?>"/> <br><br>
      		Name: 
      		<input name = "name" type="text" value="<?php echo $name;?>"/> <br><br>
      		Phone Number: 
      		<input name = "phone" type="text" value="<?php echo $phone;?>"/> <br><br><br><br>
      		<span align="center" class="header">Car Information</span><br><br>
      		License Number:
      		<input name = "license" type="text" value="<?php echo $license;?>"/> <br><br>
      		State Registered:
      		<input name = "state" type="text" value="<?php echo $state;?>"/> <br><br>
      		Model:
      		<input name = "model" type="text" value="<?php echo $model;?>"/> <br><br>
      		Year:
      		<input name = "year" type="text" value="<?php echo $year;?>"/> <br><br>
      		Make:
      		<input name = "make" type="text" value="<?php echo $make;?>"/> <br><br>
          	<br>
      		<input type="submit" name="selectType" value="Create Account" class="bigButton">
        </form>
    </div>
	<button class="bigButton" onclick="location.href='index.php'">Back</button>
	<br><br>
    <span><?php echo $msg;?></span>
    
    </body>
</html>
