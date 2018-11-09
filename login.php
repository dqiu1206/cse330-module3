<!--verifies the login information submitted-->
<!DOCTYPE html>	
<html>
<head>
	<link rel="stylesheet" type="text/css" href="newssite.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Story</title>	
</head>
<body>
<?php
session_start();
require 'database.php';
//checks the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//get the username and password from the form
$user = (string)$_POST['username'];
$_SESSION['user'] = $user;
$pwd_guess =(string) $_POST['password'];
//get the username and hashed password that is stored in the table for the username that was submitted
$stmt = $mysqli->prepare("SELECT COUNT(*), username, hashed_pass FROM user_info WHERE username=?");
$stmt->bind_param('s', $user);
$stmt->execute();
$stmt->bind_result($cnt, $user_id, $pwd_hash);
$stmt->fetch();
// Compare the submitted password to the actual password hash
if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	//directs to the new feed homepage if login succeeded
	$_SESSION['user_id'] = $user_id;
	header( 'Location: homepage.php' ) ;
} else{
	//prints out error message and back button if username/password doesn't match
	echo "Username password combination not recognized";
	echo " <form action = 'start.php' method='POST'>
			<input type='submit' value='Go Back'/>
			</form>";
}
?>
</body>
</html>