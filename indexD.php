<html>
    <head>
        <link rel="stylesheet" href="index.css">
    </head>
    <body class = "bg">
    <div align="center"> <b style="font-size=30px;" align = "center">Delivery Service</b></div> 
    <br> <br> 
     <div class="box" align="center">
    <form action="login.php" method="post">
      Username: <br>
      <input name = "username"type="text"/> <br>
      Password:
      <input name = "password" class="user_field" type="text"/> <br> <br> 
      <input type="submit" value="Login">
    </form>
      <!-- <button id="loginType" type="button"
        onclick = "myFunction()"
        >Not a Customer? Click Here to Login as a Delivery Driver.</button>
     </div> -->
      <?php include('db_connection.php');?>
    <!--   <script type = "text/javascript" src="index.js"></script> -->
    </body>
</html>
