<!--inserts the story the user created into the stories table-->
<?php
session_start();
require 'database.php';
//checks token
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
//get post data of title, link and/or content of the story user wants to create
$title = (string) $_POST['title'];
$link = (string) $_POST['link'];
$content = (string) $_POST['content'];
$user = $_SESSION['user'];

//From stackoverflow
//checks to see if https:// exists in the link given by user, adds it if it doesn't
if ($link != null) {
    $parsed = parse_url($link);
    if (empty($parsed['scheme'])) {
        $link = 'https://' . ltrim($link, '/'); 
    }
}
//inserts story into the database
$stmt = $mysqli->prepare("insert into stories (user, title, content, link) values (?,?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ssss',$user, $title, $content, $link);
$stmt->execute();
$stmt->close();
//directs to user account page
header ('Location: myaccount.php');
?>