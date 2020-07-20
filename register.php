<?php
session_start();

if(isset($_SESSION['user']))
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

<title>Register</title>
</head>

<?php

if(isset($_POST['submit']))
{
$GLOBALS["error"] = $GLOBALS["success"] = NULL;   //set globally error and success to null
$user=$_POST["username"];	
$email=$_POST["email"];	
$pass=$_POST["password"];	
$cpass=$_POST["cpassword"];

//connect to data base (server,username,password,db name)
$mysqli = NEW MySQLi('localhost','root','','');
if($mysqli->query("CREATE DATABASE IF NOT EXISTS register"))
{
  $mysqli->select_db("register"); //selecting the db
  $result = $mysqli->query("CREATE TABLE registrationdetails(id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL,password VARCHAR(255) NOT NULL,cpassword VARCHAR(255) NOT NULL,verifykey VARCHAR(255) NOT NULL,verified INT(1) DEFAULT 0,userface VARCHAR(255) NOT NULL DEFAULT 'default_user.png',createdate datetime(6) NOT NULL)");
  $result = $mysqli->query("CREATE TABLE logindetails(id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL,LastTime datetime(6) NOT NULL)");
}
  
$email_search = $mysqli->query("SELECT email FROM registrationdetails WHERE email='$email'");
$user_search = $mysqli->query("SELECT username FROM registrationdetails WHERE username='$user'");

//echo $email_search->num_rows;
if($email_search->num_rows > 0)
{
  $error ="Account using this email id is already registered!";
}
else
if(strlen($user) < 5)
{ $error ="Username should be greater than 5 characters.<br/>"; }
else
if(preg_match("/\s/", $user))  // if username containg spaces.
{
  $error .= "Username cannot contain spaces.<br/> ";
}
else
if($user_search->num_rows > 0 )
{
  $error ="Sorry! Username already taken.";
}
else
if($pass!=$cpass)     //check if both pass and cpass do not match error is assigned
{
   $error .="Passwords do not match.<br/>";
} 
else
if(strlen($pass) < 10) // if passsword is short
{
  $error .="Password is too short! Enter a strong password having length greater than 9 characters.";
}
else 
{
  
     //sanitize from data so that there is no error when passing sql queries
     
     $user = $mysqli->real_escape_string($user);
     $email = $mysqli->real_escape_string($email);
     $pass = $mysqli->real_escape_string($pass);
     $cpass = $mysqli->real_escape_string($cpass);
 
     //generate Verification key which will be send on user email id to verify
     $verify = password_hash(time().$email,PASSWORD_BCRYPT);
     //encripting password
     $pass = $cpass = password_hash($pass,PASSWORD_BCRYPT);
      //date and time at the time of registration
      $date = date("y-m-d h:i:s");// day-month-year hour:minute:seconds
      
      //if todo list table of some user exhist but is not registered then delete table from database
        
     //entering into data base
     $insert = $mysqli->query("INSERT INTO registrationdetails(id,username,email,password,cpassword,verifykey,verified,createdate) VALUES('','$user','$email','$pass','$cpass','$verify','','$date')");
     if($insert)
     { //send verification mail
     $to = $email;
     $subject = "TODO App:Email Verification";
     $message = "<html><body><p style='text-align:justify;'>Hi $user!<br/>Welcome to TODO App. Please click the bellow button to verify the email address that you provided.<br/><a style='text-decoration:none; color:white; border:1px solid #037ef3; overflow:hidden; padding:5px 10px; border-radius:5px; display:inline-block;
     position:relative; margin:20px auto; background-color:#037ef3;' href='http://192.168.1.3/Html%20Practice/login%20and%20register%20page/verifyemail.php?verify=$verify' target='_top'>Verify your account</a></p></body></html>";
     $headers = "From: TODO App. \r\n";
     $headers .= "Reply-To: some email id \r\n";
     $headers .= "MIME-VERSION: 1.0 \r\n";
     $headers .= "Content-Type: text/html; charset=UTF-8 \r\n";
     header('location:thankyou.php'); //after clicking on register user will be taken to thankyou for registering page to tell that an email has been sent to his/her email id.
     if(mail($to,$subject,$message,$headers))
     {$success = "A verification mail has been sent on your mail id.";}
     else
     {$error = "Cannot send verification mail send!";}
     }
     else
     { echo $mysqli->error;}
  
}

}
?>
<body>
<form action="register.php" method="POST" target="_top">
<h1>Register</h1>
  <div class="inputbox">
   <input type="text" name="username" required>
   <label>Username</label>
  </div>
  <div class="inputbox">
     <input type="email" name="email" required>
	   <label>Email</label>
	 </div>
   <div class="inputbox">
     <input type="password" name="password" required>
     <label>Password</label>
   </div> 
   <div class="inputbox">
      <input type="password" name="cpassword" required>
      <label>Confirm Password</label>
    </div>
<?php if(isset($_POST['submit'])){echo "<div class='error_box'>$error</div>";} $error=NULL;?>
<?php if(isset($_POST['submit'])){echo "<div class='success_box'>$success</div>";} $success=NULL;?>
<input style="background-color:var(--blue); color:white; letter-spacing:1px; font-size:14px; padding:5px 10px; border-radius:5px; border:1px solid transparent; overflow:none; cursor:pointer;" type="submit" value="Submit" name="submit">
<a style="font-size:10px; display:block; margin:10px auto; text-align:center; color:var(--blue);" href="login.php" >Already have an account?</a>
</form>
</body>
</html>