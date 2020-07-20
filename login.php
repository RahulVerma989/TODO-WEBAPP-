<?php
session_start();
if(isset($_SESSION['message']))
{
$session_message = $_SESSION['message'];
}
if(isset($_SESSION['user'])) //getback to home page when user try to go back to some other page ultil user is logged out 
{
    header('location:home.php');
}

?>
<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="Login_register.css">

<title>login</title>
</head>

<?php

if(isset($_POST['submit']))
{
     //connect to data base (server,username,password,db name)
    $mysqli = NEW MySQLi('localhost','root','','register');
     $GLOBALS["error"] = $GLOBALS["success"] = NULL;           //set globally error to null
     $user=$_POST["username"];	
     $pass=$_POST["password"];	
     //sanitize the data 
     $user = $mysqli->real_escape_string($user);
     $pass = $mysqli->real_escape_string($pass);
   

  $result = $mysqli->query("SELECT username,password,verified FROM registrationdetails WHERE (username ='$user' AND verified ='1') ");
  //echo $result->num_rows;
  
 if($result->num_rows == 1)// && $pass_verify)
 { // if username found and account is verified 
     
     $data_from_query = mysqli_fetch_assoc($result);
     $dbpass = $data_from_query['password'];
     $pass_verify = password_verify($pass,$dbpass); // returns true in passwords match.
   if($pass_verify)
   {
    //create a session veriable user which can be accessed on home.php page only.
    $_SESSION['user'] = $data_from_query['username'];  
    
    $success = "Success! Logging you in.." ;
    
    //date and time at the time of registration
    date_default_timezone_set("Asia/Kolkata");
    $date = date("y-m-d h:i:s");// day-month-year hour:minute:seconds
   
    $previouslogin = $mysqli->query("SELECT username FROM logindetails WHERE username='$user'");   
    if($previouslogin->num_rows == 0)
    {
    $insert = $mysqli->query("INSERT INTO logindetails(id,username,LastTime) VALUES('','$user','$date')"); //insert time in db at wich user logged in.
    }
    else
    {
     $update =$mysqli->query("UPDATE logindetails SET LastTime='$date' WHERE username='$user' LIMIT 1");
    }

   header('location:home.php'); // taking to user home page
    }
    else
    {
        $error ="Password is incorrect.";
    }
 }
 else
 {
    $error ="Username is incorrect! or account has not been verified yet!";
 }

}
?>
<body>
<form action="login.php" method="POST" target="_top">
<h1>Login</h1>
<?php if(isset($_SESSION['message'])){echo "<div class='user_preview_box'><p>$session_message</p></div>";} ?>
   <div class="inputbox">
     <input type="text" name="username" required>
	   <label>Username</label>
    </div>
   <div class="inputbox">
     <input type="password" name="password" required>
     <label>Password</label>
   </div> 
 <?php                // for error box above submit button
if(isset($_POST['submit']))
{echo "<div class='error_box'>$error</div>";
 echo "<div class='success_box'>$success</div>";} 
?>
<input style="background-color:var(--blue); color:white; letter-spacing:1px; font-size:14px; padding:5px 10px; border-radius:5px; border:1px solid transparent; overflow:none; cursor:pointer;" type="submit" value="Submit" name="submit">
<a style="font-size:10px; display:block;
margin:10px auto; text-align:center; color:var(--blue);" href="register.php" >Create an account?</a>
<a style="text-decoration:none; color:#2ECC71; font-size:11px; cursor:pointer;" href="forgetaccount.php" target="_TOP">Forget account ?</a>
</form>
</body>
</html>
