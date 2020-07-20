<?php
session_start();
if(isset($_SESSION['user']))
{
    header('location:home.php');
}
$GLOBALS['success'] = $GLOBALS['error'] = NULL;

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="Login_register.css">
<title>Update password</title>
</head>
<body>
<?php
if(isset($_POST['submit']))
{
    if(isset($_GET['key']))
{
    $verify = $_GET['key'];
      //checking if key is correct or not
      $mysqli = NEW MYSQLi("localhost","root","","register");
      $result = $mysqli->query("select email from registrationdetails where verifykey ='$verify'");
      if($result->num_rows == 0)
      {
        die("<h1 style='display:block; position:relative; width:80%; margin:80px auto; color:#DB0121; text-align:center; font-size:50px;'>Key is incorrect.</h1>");
      }

      $pass = $_POST['password'];
      $cpass = $_POST['cpassword'];
      $pass = $mysqli->real_escape_string($pass);
      $cpass = $mysqli->real_escape_string($cpass);
      //password validation
      if($pass === $cpass)
      {
         if(strlen($pass) > 9)
         {
             $pass = password_hash($pass,PASSWORD_BCRYPT);
             $result = $mysqli->query("update registrationdetails set password='$pass', cpassword='$pass' where verifykey='$verify'");
             if($result)
             {
                 $_SESSION['message'] = "Password has been Updated!";
                 header('location:login.php');
             }
             else
             {
                 echo $mysql->error;
             }
         }
         else
         {
             $error = "Password length should be greater than 9 characters.";
         }
      }
      else
      {
         $error = "Password donot match!";
      }
}
else
{
    //die("<h1  style='display:block; position:relative; width:80%; margin:80px auto; color:#DB0121; text-align:center; font-size:50px;'>No key Found.</h1>");
    $_SESSION['message']="Key Not Found!";  
    header('location:login.php');
}
    
}
?>
<form method="POST">
    <h1>Update Password</h1>
    <div class="inputbox">
       <input type="password" name="password" required>
       <label>New Password</label>
    </div>
    <div class="inputbox">
       <input type="password" name="cpassword" required>
       <label>Confirm password</label>
    </div>
<?php
if(isset($_POST['submit']))
{
    echo "<div class='error_box'>$error</div>";
    echo "<div class='success_box'>$success</div>";
}
?>
    <input type="submit" value="Update Password" name="submit" style="background-color:var(--blue); color:white; letter-spacing:1px; font-size:14px; padding:5px 10px; border-radius:5px; border:1px solid transparent; overflow:none; cursor:pointer;"/>
</form>
</body>
</html>