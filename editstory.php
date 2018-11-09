<!--provides area for the user to edit his story-->
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
$token = $_SESSION['token'];
$story_id = $_POST['story_id'];
//checks the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//query the stories table to get information on the story to be edited
$stmt = $mysqli->prepare("select title, link, content from stories where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$story_id);
$stmt->execute();
$stmt->bind_result($title, $link, $content);
$stmt->fetch();
//places title, link and content into text boxes that edit the story once 'Save Changes' is pressed
echo "<form action = 'updatestory.php' method='POST'>
    <input type = 'text' name = 'title' placeholder = 'Title' value = '$title' maxlength='50' required><br>
	<input type = 'text' name = 'link' placeholder = 'Enter Link' value = '$link' maxlength= '1000'><br>
    <textarea name = 'content' placeholder = 'Enter your story here' maxlength='65535' rows='20' cols='50'>$content</textarea><br>
    <input type = 'hidden' name = 'token' value = '$token'>
    <input type = 'hidden' name = 'story_id' value = '$story_id'>
    <input type = 'submit' value = 'Save changes'>
</form>";
$stmt->close();
?>
</body>
</html>