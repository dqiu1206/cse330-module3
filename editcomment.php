<!--provies area for the user to edit his comment-->
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="newssite.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Comment</title>
</head>

<body>
<?php
session_start();
require 'database.php';
$token = $_SESSION['token'];
$story_id = $_POST['story_id'];
$comment_id = $_POST['comment_id'];
//check the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//get information of the comment to be edited
$stmt = $mysqli->prepare("select comment from comments where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$comment_id);
$stmt->execute();
$stmt->bind_result($comment);
$stmt->fetch();
//places content of the comment in the text box to allow editing once 'Save Changes' is pressed
echo "<form action = 'updatecomment.php' method='POST'>
    <textarea name = 'newcomment' placeholder = 'Enter your comment here' maxlength='1000' rows='5' cols='20'>$comment</textarea><br>
    <input type = 'hidden' name = 'token' value = '$token'>
    <input type = 'hidden' name = 'story_id' value = '$story_id'>
    <input type = 'hidden' name = 'comment_id' value = '$comment_id'>
    <input type = 'submit' value = 'Save changes'>
</form>";
$stmt->close();
?>

</body>
</html>