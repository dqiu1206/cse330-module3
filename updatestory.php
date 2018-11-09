<!--updates the stories table after user submits changes-->
<?php
session_start();
require 'database.php';
//get information of the new edits
$token = $_SESSION['token'];
$newtitle = (string) $_POST['title'];
$newcontent = (string) $_POST['content'];
$link = (string) $_POST['link'];
$story_id = $_POST['story_id'];
//checks token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//https://stackoverflow.com/questions/8591623/checking-if-a-url-has-http-at-the-beginning-inserting-if-not
//checks if link has https:// in the beginning of the link submitted, adds it if it doesn't
if ($link != null) {
    $parsed = parse_url($link);
    if (empty($parsed['scheme'])) {
        $link = 'https://' . ltrim($link, '/'); 
    }
}
//updates the story in the stories table to the new content
$stmt = $mysqli->prepare("update stories set title=?, link=?, content = ? where story_id = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('sssi', $newtitle,$link, $newcontent, $story_id);
$stmt->execute();
$stmt->close();
//directs back the user account page
header ('Location: myaccount.php');
?>