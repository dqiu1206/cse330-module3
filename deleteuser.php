<!--deletes the user and all of its stories, comments and upvotes-->
<?php
session_start();
require 'database.php';
$token = $_SESSION['token'];
$story_id = $_POST['story_id'];
$user = $_SESSION['user'];
//check the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//store all stories created by the user into an array
$stmt = $mysqli->prepare("select story_id from stories where user=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$user);
$stmt->execute();
$stmt->bind_result($comment_id);
$storyid_array = array();
while($stmt->fetch()){
	array_push($storyid_array,$comment_id);
}
$stmt->close();
//push all comments from the stories the user created into an array
$commentid_array = array();
for($j=0;$j<count($storyid_array);$j++){
	$stmt = $mysqli->prepare("select comment_id from comments where story_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$storyid_array[$j]);
	$stmt->execute();
	$stmt->bind_result($comment_id);
	while($stmt->fetch()){
		array_push($commentid_array,$comment_id);
	}
	$stmt->close();
}
//delete all the upvotes for each comment in the array
for($i=0;$i<count($commentid_array);$i++){
	$stmt = $mysqli->prepare("delete from upvotes where comment_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$commentid_array[$i]);
	$stmt->execute();
	$stmt->close();
}
//delete all the comments for the stories the user created
for($i=0;$i<count($commentid_array);$i++){
	$stmt = $mysqli->prepare("delete from comments where comment_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$commentid_array[$i]);
	$stmt->execute();
	$stmt->close();
}
//delete the stories the user created
for($i=0;$i<count($storyid_array);$i++){
	$stmt = $mysqli->prepare("delete from stories where story_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$storyid_array[$i]);
	$stmt->execute();
	$stmt->close();
}
//delete all the upvotes that this user has created from all comments
$stmt = $mysqli->prepare("delete from upvotes where user=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$user);
$stmt->execute();
$stmt->close();
//push all comments that the user has created in all stories into an array
$othercomments_array = array();
$stmt = $mysqli->prepare("select comment_id from comments where user=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$user);
$stmt->execute();
$stmt->bind_result($otherid);
$storyid_array = array();
while($stmt->fetch()){
	array_push($othercomments_array,$otherid);
}
$stmt->close();
//delete all the upvotes from each comment that the user has created
for($i=0;$i<count($othercomments_array);$i++){
	$stmt = $mysqli->prepare("delete from upvotes where comment_id=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$othercomments_array[$i]);
	$stmt->execute();
	$stmt->close();
}
//delete all comments that the user has created in each story
for($i=0;$i<count($othercomments_array);$i++){
	$stmt = $mysqli->prepare("delete from comments where comment_id=?");

	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('i',$othercomments_array[$i]);
	$stmt->execute();
	$stmt->close();
}
//remove the user from the user_info table, which stores each user and password
$stmt = $mysqli->prepare("delete from user_info where username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s',$user);
$stmt->execute();
$stmt->close();
//destroys the session and directs to the start page
session_destroy();
header("Location: start.php");
?>