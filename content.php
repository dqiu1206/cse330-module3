<!--prints out the title, author, content and comments section for the story viewed-->
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
require 'database.php';
$story_id = $_POST['story_id'];
//we query the stories table to get information on the story
$stmt = $mysqli->prepare("select user, title, content, link from stories where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$story_id);
$stmt->execute();
$stmt->bind_result($user, $title, $content, $link);

//print out the title, author, content and/or link for this story
while($stmt->fetch()){
	$eotitle = htmlentities($title);
	$eouser = htmlentities($user);
	$eocontent = htmlentities($content);
	$eolink = htmlentities($link);
	echo "<h1>$eotitle</h1>"; 
    echo "Author:$eouser <br><br>";
    if ($eocontent != null) {
        echo "$eocontent<br><br><br>";
    }	
	if ($link != null) {
        echo "<a href='$eolink'>Link</a>";
    }
}
$stmt->close();
//button and textarea for creating a new comment
if ($_SESSION['user'] != null) {
	echo "
		<form action='createcomment.php' method='POST'>
			<textarea name = 'comment' placeholder= 'Enter your comment here' maxlength='1000' rows='5' cols='30' required></textarea><br>
			<input type ='hidden' name = 'story_id' value = '$story_id'>
			<input type = 'hidden' name = 'token' value = '$token'>
			<input type = 'submit' value = 'Post Comment'>
		</form>";
}
//query comments database to pull up every comment associated with this story
$stmt = $mysqli->prepare("select * from comments where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$story_id);
$stmt->execute();
$stmt->bind_result($comment_author, $storyid, $comment_content, $comment_id);
echo "<h3>Comment Section</h3>";
//push information of each comment into an array
$comment_authorarray = array();
$storyid_array = array();
$comment_array = array();
$commentid_array = array();
while($stmt->fetch()){
array_push($comment_authorarray,$comment_author);
array_push($storyid_array,$storyid);
array_push($comment_array,$comment_content);
array_push($commentid_array,$comment_id);
}
$stmt->close();
//iterate through the array of comments to print its author, content and upvotes
for($i=0;$i<count($commentid_array);$i++){
	$eoauthor = htmlentities($comment_authorarray[$i]);
	$eocomment = htmlentities($comment_array[$i]);
	$sum_upvote=0;
	//query the upvotes table to get the sum of all the upvotes for each comment
	$stmt = $mysqli->prepare("select sum(upvotes) from upvotes where comment_id=?");
		if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
		}
	$stmt->bind_param('i',$commentid_array[$i]);
	$stmt->execute();
	$stmt->bind_result($sum_upvote);
	$stmt->fetch();
	$stmt->close();
	//print out each comment's author, content and upvote score
	echo "Posted by: $eoauthor <br>";
	echo "Comment: $eocomment<br>";
	if ($sum_upvote != null) {
		echo "Comment Score: $sum_upvote<br>";
		} else {
			echo "Comment Score: 0<br>";
		}
	//if there is a user logged in, allow him to upvote, reset or downvote the comment
	if ($_SESSION['user'] != null) {
		echo "<form action = 'upvote.php' method = 'POST' >
			<button type = 'submit' name = 'like' value = '1'>Upvote</button>
			<button type = 'submit' name = 'like' value = '0'>Reset</button>
			<button type = 'submit' name = 'like' value = '-1'>Downvote</button>
			<input type = 'hidden' name = 'token' value = '$token'>
			<input type = 'hidden' name = 'story_id' value = '$storyid_array[$i]'>
			<input type = 'hidden' name = 'comment_id' value = '$commentid_array[$i]'>
			</form> ";
	}
	//if the user made the comment, allow the user to edit or delete the comment
	if ($_SESSION['user'] == $comment_authorarray[$i]) {
		echo
		"<form action='editcomment.php' method='POST'>
			<input type = 'submit' value = 'Edit comment'>
			<input type = 'hidden' name = 'token' value = '$token'>
			<input type = 'hidden' name = 'comment_id' value = '$commentid_array[$i]'>
			<input type = 'hidden' name = 'story_id' value = '$storyid_array[$i]'>
		</form>";
		echo
			"<form action='deletecomment.php' method='POST'>
				<input type = 'submit' value = 'Delete comment'>
				<input type = 'hidden' name = 'token' value = '$token'>
				<input type = 'hidden' name = 'comment_id' value = '$commentid_array[$i]'>
				<input type = 'hidden' name = 'story_id' value = '$storyid_array[$i]'>
			</form><br>";
	} echo "<br>";
}
//if there is a user logged in, allow user to go to his account page or to the general news feed
if ($_SESSION['user'] != null) {
	echo "
    <form action='myaccount.php' method='POST'>
    <input type = 'submit' value = 'My Account'>
    <input type = 'hidden' name = 'token' value = '$token'>
    
    </form>";
}
echo "<form action='homepage.php' method='POST'>
    	<input type = 'submit' value = 'News Feed'>
  		<input type = 'hidden' name = 'token' value = '$token'>
    </form>";
?>
</body>
</html>