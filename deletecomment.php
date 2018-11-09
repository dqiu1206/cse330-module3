<!--deletes the comment and all of its upvotes from the database-->
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
$comment_id = $_POST['comment_id'];
$story_id = $_POST['story_id'];
//check the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//remove all the upvotes related to this comment
$stmt = $mysqli->prepare("delete from upvotes where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$comment_id);
$stmt->execute();
$stmt->close();
//delete the comment itself from the table after deleting the upvotes
$stmt = $mysqli->prepare("delete from comments where comment_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$comment_id);
$stmt->execute();
$stmt->close();
//create button back to the story page
echo "Comment successfully deleted";
echo "
    <form action='content.php' method='POST'>
        <input type='hidden' name = 'story_id' value = '$story_id'>
        <input type = 'submit' value = 'Return to story'>
    </form> ";
?>
</body>
</html>