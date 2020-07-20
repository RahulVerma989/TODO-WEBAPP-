<?php
session_start();

  //connect to data base (server,username,password,db name)
  $mysqli = NEW MySQLi('localhost','root','','register'); 
  $result = $mysqli->query("SELECT verifykey,verified FROM registrationdetails WHERE verified=1 AND verifykey='$verify'");
  if($result->num_rows == 1)
  {  //email for resetting user password
     $to = $email;
     $subject = "RSNT:Reset Password";
     $message = "<html><body><p style='text-align:justify;'>Hi $user!<br/>Please click the bellow button to reset your password.<br/><a style='text-decoration:none; color:white; border:1px solid #037ef3; overflow:hidden; padding:5px 10px; border-radius:5px; display:inline-block;
     position:relative; margin:20px auto; background-color:#037ef3;' href='http://192.168.1.6/Html%20Practice/login%20and%20register%20page/resetpass.php?verify=$verify' target='_top'>Reset your password</a></p></body></html>";
     $headers = "From: RSNT GROUP. \r\n";
     $headers .= "Reply-To: some email id \r\n";
     $headers .= "MIME-VERSION: 1.0 \r\n";
     $headers .= "Content-Type: text/html; charset=UTF-8 \r\n";
     header('location:thankyou.php'); //after clicking on register user will be taken to thankyou for registering page to tell that an email has been sent to his/her email id.
     if(mail($to,$subject,$message,$headers))
     { echo '<p style="display:block; position:relative; word-wrap:break-word; text-align:center; margin:50px auto; color:#037ef3; width:80%;">A mail has been sent on your mail id to reset your password.</p>';}
     else
     { echo '<p style="display:block; position:relative; word-wrap:break-word; text-align:center; margin:50px auto; color:#037ef3; width:80%;">Cannot send mail send!</p>';}
  }
  else
  { echo $mysqli->error;}

?>
<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="Login_register.css">

<title>Forget Password</title>
</head>
<body>
<form action="login.php" method="POST" target="_top">
<h1>Reset Password</h1>
   <div class="inputbox">
     <input type="text" name="email" required>
	   <label>Email</label>
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
<input style="background-color:var(--blue); color:white; letter-spacing:1px; font-size:14px; padding:5px 10px; border-radius:5px; border:1px solid transparent; overflow:none; cursor:pointer;" type="submit" value="Reset password" name="submit">
<a style="text-decoration:none; color:#2ECC71; font-size:11px; cursor:pointer;" href='forgetpass.php' target="_TOP">Forget Password?</a>
</form>
</body>
</html>