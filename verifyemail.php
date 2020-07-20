<?php
session_start();
if(isset($_GET['verify']))
{   //verification 
    $verify = $_GET['verify'];
    //connect to data base (server,username,password,db name)
  $mysqli = NEW MySQLi('localhost','root','','register'); 
  $result = $mysqli->query("SELECT verifykey,verified,username FROM registrationdetails WHERE verified=0 AND verifykey='$verify'");
  $data_from_query = mysqli_fetch_assoc($result);
  $user = $data_from_query['username'];
  if($result->num_rows == 1)
  {  //velidate the email
    $update =$mysqli->query("UPDATE registrationdetails SET verified = 1 WHERE verifykey ='$verify' LIMIT 1");
    if($update)
    {      $result = $mysqli->query("CREATE DATABASE IF NOT EXISTS userstodolist");//create user database for todo list after verify 
           $result = $mysqli->select_db("userstodolist"); //selecting the db
           $result = $mysqli->query("CREATE TABLE $user (id int(255) unsigned auto_increment primary key, tasks varchar(255) not null, create_date datetime(6) not null)");
            echo "<p style='width:80%; margin:30px auto; text-align:center; position:relative;'>Your account has been verified you may now login.<br/><a style='text-decoration:none; color:white; border:1px solid #037ef3; overflow:hidden; padding:5px 10px; border-radius:5px; display:inline-block;
            position:relative; margin:20px auto; background-color:#037ef3; ' href='login.php' target='_TOP'>Login</a></p>";
        
    }  
    else
    {
        echo $mysqli->error;
    }
  }
  else
  {
      echo "<p style='width:80%; margin:30px auto; text-align:center; position:relative;'>This account is invalid or already verified.</p>";
  }
}
else
{
    die("<h2 style='display:block; position:relative; margin:40px auto; color:#DB0121;'>Something Went Wrong!</h2>");
}
?>
<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="Login_register.css">

<title>Verifying account</title>
</head>
<body>

</body>
</html>