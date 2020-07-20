<?php
session_start();
$GLOBALS['message'] = NULL;
$user = $_SESSION['user']; //get username form login page using session
if(!isset($_SESSION['user']))
{   echo "You are logged out!";
    header('location:login.php');
}
//connect to data base (server,username,password,db name)
$mysqli = NEW MySQLi('localhost','root','','register');
$result = $mysqli->query("SELECT userface FROM registrationdetails WHERE username='$user'");
$data = mysqli_fetch_assoc($result);
$userface = $data['userface'];
$result = $mysqli->select_db("userstodolist"); //selecting the db
//$mysqli = NEW MySQLi('localhost','root','','userstodolist');
$user = strtolower($user);
$result = $mysqli->query("SELECT * FROM $user");
//echo $result->num_rows; // show number of rows in table
echo $mysqli->error;   //show errors
if(isset($_POST['submit']))
{
    $task = $_POST['task'];
    $task = $mysqli->real_escape_string($task);
    date_default_timezone_set("Asia/Kolkata");
    $date = date("y-m-d h:i:s",time());
    $check = $mysqli->query("SELECT tasks FROM $user WHERE tasks = '$task'");
    if($check->num_rows == 1) //if task is already created then will not create same task
        {
            $message = "Task is already created.";
        }
        else
        {
            $insert = $mysqli->query("INSERT INTO $user(id, tasks, create_date) VALUES('','$task','$date')");
            if($insert)
            {
                  $message = "Task Created";
                  header('location:home.php');
            }
            else
            {
                $message = "Unable to create task.";
            }
        }
}
if(isset($_GET['del_task']))
{
    $del_task = $_GET['del_task'];
    $check = $mysqli->query("SELECT id FROM $user WHERE id='$del_task'");
    if($check->num_rows == 1)
    {
        $delete = $mysqli->query("DELETE FROM $user WHERE id='$del_task'");
        if($delete)
        {
            $message = "Task deleted";
            header('location:home.php');
        }
        else
        {
            $message = "Unable to delete task";
        }
    }
}
?>
<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width , height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- For 'Poppins' -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="home.css?v=<?php echo time(); ?>">

<title><?php echo "Hi ".$user; ?></title>
</head>
<body>
    <header>
       <div class="heading">
         <h1><?php echo "Welcome ".$user." this is your TODO list." ?></h1>
       </div>
       
       <div class="profile_icon">
         <?php echo '<img  src="user_images/'.$userface.'">'; ?>
         <a href="logout.php" target="_TOP">Logout</a>
       </div>
    </header>

    <div class="todo">
     <form method="POST">
       <input type="text" name="task" placeholder="New task" required>
       <input type="submit" name="submit" value="Create">
       <?php if(isset($GLOBALS['message'])){ echo '<h5 id="message">'.$message.'</h5>';} ?>
     </form> 
       <div class="tablecontent">
          <div class="columns">
            <div class="fields">id</div>
            <div class="fields">Tasks</div>
            <div class="fields">Creation Date</div>
          </div>
         <?php
         echo '<table>';
         while($data_from_query = mysqli_fetch_assoc($result))
         {
            echo '<tr> 
                  <td>'.$data_from_query['id'].'</td>
                  <td>'.$data_from_query['tasks'].'</td>
                  <td>'.$data_from_query['create_date'].'<a href="home.php?del_task='.$data_from_query['id'].'">DELETE</a></td>
                  </tr>';
         }
         echo '</table>';
         ?> 
      </div>
         <div class="fotter">
         Created By <a href="https://www.linkedin.com/in/rahul-verma-b769b8147/">Rahul Verma</a>
         </div>
    </div>
</body>
</html>