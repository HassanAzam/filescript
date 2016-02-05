
<?php

include "db.php";
/*** begin our session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
	header("Location: login.php");
	die();
}
elseif($_SESSION['user_id']!=1)
{
	echo "Only admin can view this page";
	die();
}
/*** set a form token ***/
if(!isset($_POST["form_token"])){
$form_token = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;
}
else{
	$form_token=$_POST["form_token"];
}
?>

<html>
<head>
<title>Add files</title>
</head>
<body>
<h2>Add file</h2>
<form action="addfile.php" method="post">
<fieldset>
<p>
<label for="username">File Name</label>
<input type="text" id="filename" name="filename" value="" maxlength="20" />
</p>

<p>
<label for="filetype">File Type</label>
<select name="filetype">
    <option value="pdf">PDF</option>
    <option value="png">PNG</option>
    <option value="jpg">JPEG</option>
    <option value="docx">Word Document</option>
    <option value="txt">Text Document</option>
</select>
</p>
<p>
<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
<input type="submit" name="submit_button" value="Add" />
</p>
</fieldset>

<?php if(isset($_POST['submit_button'])): 
/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_POST['filename'], $_POST['filetype'], $_POST['form_token']))
{
    $message = 'Please enter a valid Filename and Filetype';
}
/*** check the form token is valid ***/
elseif( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Invalid form submission';
	echo $_SESSION['form_token'].'!='.$_POST['form_token'];
}
/*** check the username is the correct length ***/
elseif (strlen( $_POST['filename']) > 20 || strlen($_POST['filename']) < 4)
{
    $message = 'Incorrect Length for File name';
}

/*** check the username has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['filename']) != true)
{
    /*** if there is no match ***/
    $message = "File name must be alpha numeric";
}
/*** check the password has only alpha numeric characters ***/

else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $filename = filter_var($_POST['filename'], FILTER_SANITIZE_STRING);
    $filetype = filter_var($_POST['filetype'], FILTER_SANITIZE_STRING);

    
    
    /*** connect to database ***/
    

    try
    {
        

        /*** prepare the insert ***/
        $stmt = $dbh->prepare("INSERT INTO users (username, password ) VALUES (:username, :password )");

        /*** bind the parameters ***/
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR, 40);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** unset the form token session variable ***/
        /***unset( $_SESSION['form_token'] );***/

        /*** if all is done, say thanks ***/
        $message = 'New user added';
    }
    catch(Exception $e)
    {
        /*** check if the username already exists ***/
        if( $e->getCode() == 23000)
        {
            $message = 'Username already exists';
        }
        else
        {
            /*** if we are here, something has gone wrong with the database ***/
            $message = 'We are unable to process your request. Please try again later';
        }
    }
} 
echo $message;
endif;
?>
</form>
<a href="index.php">Go back</a>
</body>
</html>