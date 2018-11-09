<!--allows user to type in title, link and/or content of the story he wishes to create-->
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
$token = $_SESSION['token'];
//checks token
 if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//creates text boxes for user to input and links to page that updates the database
echo "<form action = 'makestory.php' method='POST'>
    <input type = 'text' name = 'title' placeholder ='Title' maxlength='50' required><br>
    <input type = 'text' name = 'link' placeholder = 'Link (optional)' maxlength='1000'><br>
    <textarea name = 'content' placeholder= 'Enter your story here (optional)' maxlength='65535' rows='20' cols='50'></textarea><br>
    <input type = 'hidden' name = 'token' value = '$token'>
    <input type = 'submit' value = 'Create'>
</form>";
?>
</body>
</html>
