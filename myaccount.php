<!--User Account page that allows the user to view, edit, delete his stories, and delete the user itself-->
<!DOCTYPE html>	
<html>
<head>
	<link rel="stylesheet" type="text/css" href="newssite.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Story</title>	
</head>
<body>
<h1>My Stories</h1>
<?php
session_start();
require 'database.php';
$token = $_SESSION['token'];
//queries stories table to get information on each story the user has created
$stmt = $mysqli->prepare("select title, content, link,story_id from stories where user=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$_SESSION['user']);
$stmt->execute();
$stmt->bind_result($title, $content, $link, $story_id);
//prints out title and links to view, edit and delete each story
while($stmt->fetch()){
	$eotitle = htmlentities($title);
	$eocontent = htmlentities($content);
	$eolink = htmlentities($link);
	echo "$eotitle "; 
	//prints out link if there is one
    if ($eolink != null) {
        echo "<a href='$eolink'>Link</a><br>";
    }
        echo "
            <form action = 'content.php' method = 'POST'>
				<input type = 'hidden' name = 'story_id' value = '$story_id'>
				<input type = 'submit' value = 'Go to Story'>
            </form> ";
            
        echo "
			<form action = 'editstory.php' method = 'POST'>
			   <input type = 'submit' value = 'Edit'>
			    <input type = 'hidden' name = 'token' value = '$token'>
			   <input type = 'hidden' name = 'story_id' value = '$story_id'>
			</form>";

		echo "
			<form action = 'deletestory.php' method = 'POST'>
				<input type = 'submit' value = 'Delete Story'>
			    <input type = 'hidden' name = 'token' value = '$token'>
			    <input type = 'hidden' name = 'story_id' value = '$story_id'>
			</form> <br>";
    
    
	
}
echo "</ul>\n";
$stmt->close();
?>
<!--button for user to create story-->
<form action = 'storyform.php' method='POST'>
	<input type = 'hidden' name = 'token' value = '<?php echo $_SESSION['token']; ?>'>
    <input type ='submit' value = 'Create Story'>
</form>
<!--button for user to go to News Feed-->
<form action = 'homepage.php' method='POST'>
    <input type = 'submit' value = 'News Feed'>
</form>
<!--button for user to Log out-->
<form action='logout.php' method='POST'>
    <input type='submit' value= 'Log out'>
    <input type = 'hidden' name = 'token' value = '<?php echo $_SESSION['token']; ?>'>
</form>
<!--button for user to delete the user-->
<form action = 'deleteuser.php' method='POST'>
	<input type='submit' value='Delete User'>
	 <input type = 'hidden' name = 'token' value = '<?php echo $_SESSION['token']; ?>'>
</form>
</body>
</html>