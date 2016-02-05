<?php
include "db.php";
/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
}
else
{
    try
    {
        

        /*** prepare the insert ***/
        $stmt = $dbh->prepare("SELECT username FROM users 
        WHERE user_id = :user_id");

        /*** bind the parameters ***/
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $username = $stmt->fetchColumn();

        /*** if we have no something is wrong ***/
        if($username == false)
        {
            $message = 'Access Error';
			die();
        }
        else
        {
            $message = 'Welcome '.$username;
            $access = true;
        }
    }
    catch (Exception $e)
    {
        /*** if we are here, something is wrong in the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }
}

?>

<html>
<head>
<title>Members Only Page</title>
</head>
<body>
<h5><?php echo $message; ?></h5>
<br>
<?php if($access): ?>
<ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="files.php">View Files</a></li>
    <?php if($_SESSION['user_id'] == 1){ echo '<li><a href="adduser.php">Add user</a></li>';} ?>
    <?php if($_SESSION['user_id'] == 1){ echo '<li><a href="addfile.php">Add file</a></li>';} ?>
    <li><a href="login.php">Logout</a></li>
</ul>
<?php endif; ?>
</body>
</html>