<?php
session_start();
if(isset($_SESSION['user']))
{
    header('location:home.php');
}
$GLOBALS["error"] = $GLOBALS["success"] = NULL;           //set globally error to null
    //connect to data base (server,username,password,db name)
    $mysqli = NEW MySQLi('localhost','root','','register');
      //echo "Connected to db.<br/>";
      
?>
<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="Login_register.css">

<title>Forget Account</title>
</head>
<body>
<?php

if(isset($_POST['submit']))
{

  $email = $_POST['email'];

  $result = $mysqli->query("SELECT username,email,verifykey,userface,verified FROM registrationdetails WHERE (email ='$email' AND verified =1)");
     $data_from_query = mysqli_fetch_assoc($result);
  
 // echo $result->num_rows;
  if($result->num_rows == 1)
  {
    $user = $data_from_query['username'];  //get username from db associated with provided email.
    //echo $user;
    $userface = $data_from_query['userface'];
    $success = "Account found!";

    //send password resetting mail
    $email = $_POST['email'];
    $verify = $data_from_query['verifykey'];

    $to = $email;
    $subject = "RSNT:Reset Password";
    $message = "<html><body><p style='text-align:justify;'>Hi $user!<br/>Please click the bellow button to reset password of your account.<br/><a style='text-decoration:none; color:white; border:1px solid #037ef3; overflow:hidden; padding:5px 10px; border-radius:5px; display:inline-block;
    position:relative; margin:20px auto; background-color:#037ef3;' href='http://192.168.0.101/Html%20Practice/login%20and%20register%20page/resetpass.php?key=$verify' target='_top'>Reset your password</a></p></body></html>";
    $headers = "From: RSNT GROUP. \r\n";
    $headers .= "Reply-To: some email id \r\n";
    $headers .= "MIME-VERSION: 1.0 \r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8 \r\n";
    if(mail($to,$subject,$message,$headers))
    {  
        $success ="An email has been send on your email id to recover your account."; 

    }
    else
    {$error = "Cannot send mail for resetting your account password.";}

  }
  else
  {
      $error = "No account is registered with this email id.";
  }
}
?>
<form method="POST" target="_top">
<h1>Search Account</h1>
<p style="position:relative; font-size:11px; font-weight:600; width:80%; word-wrap:break-word; margin:20px auto;">Enter your email address to search for your account.</p>

<?php
if(isset($success))  //display user image and user name if email found in database
{
    echo "<div class='user_preview_box'>
            <div class='user_image'>
            <img src='http://192.168.0.101/Html%20Practice/login%20and%20register%20page/user_images/$userface'> 
            </div>
            <p>$user</p>
          </div>";

}
?>  

<div class="inputbox">
     <input type="text" name="email" required>
	   <label>Email</label>
    </div>

<?php                // for error box above submit button
if(isset($_POST['submit']))
{echo "<div class='error_box'>$error</div>";
 echo "<div class='success_box'>$success</div>";
} 
?>

<input style="background-color:var(--blue); color:white; letter-spacing:1px; font-size:14px; padding:5px 10px; border-radius:5px; border:1px solid transparent; overflow:none; cursor:pointer;" type="submit" value="Send Mail" name="submit">
<a style="display:block; position:relative; margin:20px auto; width:80%; text-decoration:none; color:var(--lightgreen); font-size:10px;" href="login.php">Back to login page?</a>
</form>
</body>
</html>