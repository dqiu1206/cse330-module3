<!--updates the comments table after the user submits the new comment-->
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
//get information on which comment was edited
$token = $_SESSION['token'];
$newcomment = (string) $_POST['newcomment'];
$comment_id = $_POST['comment_id'];
$story_id = $_POST['story_id'];
//updates the comment in the comments table with the new comment that the user submitted
$stmt = $mysqli->prepare("update comments set comment=? where comment_id = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('si', $newcomment, $comment_id);
$stmt->execute();
$stmt->close();
//confirmation and button back to the content page
echo "Comment successfully changed";
echo "
    <form action='content.php' method='POST'>
        <input type='hidden' name = 'story_id' value = '$story_id'>
        <input type = 'submit' value = 'Return to story'>
    </form> ";
?>
</body>
</html>