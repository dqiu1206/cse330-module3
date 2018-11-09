<!--inserts the comment created by the user into the comments table-->
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
//checks token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
$comment = (string) $_POST['comment'];
$user = $_SESSION['user'];
$story_id = $_POST['story_id'];
//insert comment into database
$stmt = $mysqli->prepare("insert into comments (user, story_id, comment) values (?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('sis',$user, $story_id, $comment);
$stmt->execute();
$stmt->close();
//create back button to go back to the story page
echo "Comment added";
echo "
    <form action='content.php' method='POST'>
        <input type='hidden' name = 'story_id' value = '$story_id'>
        <input type = 'submit' value = 'Return to story'>
    </form> ";
?>
</body>
</html>