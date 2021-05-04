<?php
	session_start();
	$_SESSION["userType"] = "Delivery_Driver";
?>

<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>
    <body class="bg">
    <div class="header" align="center"> <b style="font-size=30px;" align = "center">Driver Login</b></div> 
    <br> <br> 
     <div class="box" align="center">
    <form action="login.php" method="post">
      Username: <br>
      <input name = "username"type="text"/> <br><br>
      Password:
      <input name = "password" class="user_field" type="text"/> <br> <br> 
        <input type="submit" value="Login" class="bigButton">
    </form>
      	<br><br>
  		<button class="bigButton" onclick="location.href='index.php'">Back to Home</button>
      <!-- <button id="loginType" type="button"
        onclick = "myFunction()"
        >Not a Customer? Click Here to Login as a Delivery Driver.</button>
     </div> -->
      <?php include('db_connection.php');?>
    <!--   <script type = "text/javascript" src="index.js"></script> -->
    </body>
</html>
