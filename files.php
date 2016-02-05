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
<title>View Files</title>
</head>
<body>
<h5><?php echo $message; ?></h5>
<br>
<?php if($access): ?>
<div id="filesArea">
<p>Files Area</p>

<a href="index.php">Go back</a>
</div>
<?php else: ?>
<p>Sorry, you must be login to view files</p>
<a href="login.php">Login</a>
<?php endif; ?>
</body>
</html>