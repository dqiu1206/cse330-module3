<!--deletes the story and all of its upvotes, comments and content-->
<?php
session_start();
require 'database.php';
$token = $_SESSION['token'];
$story_id = $_POST['story_id'];
//check the token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//store all the comments related to this story into an array
$stmt = $mysqli->prepare("select comment_id from comments where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$story_id);
$stmt->execute();
$stmt->bind_result($comment_id);
$commentid_array = array();
while($stmt->fetch()){
	array_push($commentid_array,$comment_id);
}
$stmt->close();
//delete the upvotes for each comment in the story from the table
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
//delete all the comments of the story from the table
$stmt = $mysqli->prepare("delete from comments where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$story_id);
$stmt->execute();
$stmt->close();
//delete the story itself
$stmt = $mysqli->prepare("delete from stories where story_id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('i',$story_id);
$stmt->execute();
$stmt->close();
//go back to user account page
header("Location: myaccount.php");
?>