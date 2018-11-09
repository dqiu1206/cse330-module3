<!--inserts new user into the database-->
<!DOCTYPE html>	
<html>
<head>
	<link rel="stylesheet" type="text/css" href="newssite.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Story</title>	
</head>
<body>
<?php
require 'database.php';
//obtain new user information from the sign up page
$username = (string) $_POST['new_user'];
$password = (string) $_POST['pass'];
//hash the password
$hashed_pass = password_hash($password, PASSWORD_BCRYPT);
//checks if the username already exists
$stmt = $mysqli->prepare("select username from user_info where username = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($user);
$stmt->fetch();
$stmt->close();
//prints if the user already exists
if ($user != null) {
      echo "Username already exists<br>";
	echo "Click <a href = 'signup.html'>here</a> to return to signup page";
} 
//inserts new user into the database if it doesn't exists
else {
	$stmt = $mysqli->prepare("insert into user_info (username, hashed_pass) values (?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
}
$stmt->bind_param('ss', $username, $hashed_pass);
$stmt->execute();
$stmt->close();
//directs back to start page
header( 'Location: start.php' ) ;
}
?>
</body>
</html>
