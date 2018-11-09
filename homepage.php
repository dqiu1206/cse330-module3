<!--page to display every story as well as buttons to account page, and logout button-->
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="newssite.css"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> News Site</title>
    </head>
    <body>
    <h1> News Feed</h1>  
<?php
session_start();
require 'database.php';
$token = $_SESSION['token'];
//button to account page only if user is logged in
if($_SESSION['user'] != null) {
echo " <form action='myaccount.php' method='POST'>
        <input type='submit' value='My Account' >
        
    </form>";
}
//get the information of each story in the table
$stmt = $mysqli->prepare("select user, title, content, link,story_id from stories");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->execute();
$stmt->bind_result($user, $title, $content, $link, $story_id);
//print out the title, author and links to content and/or link of each story
while($stmt->fetch()){
	$eouser=htmlentities($user);
	$eotitle=htmlentities($title);
	$eocontent=htmlentities($content);
	$eolink=htmlentities($link);
	echo "<h4>$eotitle</h4> "; 
    echo "Author: $eouser<br> ";
    if ($eolink != null) {
        echo "<a href='$eolink'>Link</a><br>";
    }
	echo "<form action = 'content.php' method = 'POST'>
			<input type = 'hidden' name = 'story_id' value = '$story_id'>
			<input type = 'submit' value = 'Go to Story'>
        </form> <br>";
}
$stmt->close();
//allows you to logout if you are signed in, or provides a link to the start page if you aren't logged in
if ($_SESSION['user'] != null){
    echo "<form action='logout.php' method='POST'>
        <input type='submit' value= 'Log out'>
        <input type = 'hidden' name = 'token' value = '$token'>
        </form>";
} else {
    echo "<p>Click <a href = 'start.php'>here</a> to go to log-in page. </p>";
}
?>
</body>
</html>