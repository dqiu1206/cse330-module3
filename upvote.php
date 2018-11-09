<!--inserts or updates the upvote value for the comment-->
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
//get information on the comment, user and value of the upvote
$value = $_POST['like'];
$user = $_SESSION['user'];
$comment_id = $_POST['comment_id'];
$story_id = $_POST['story_id'];
//checks upvotes table to see if duplicate upvote exists
$stmt = $mysqli->prepare("select upvotes from upvotes where user = ? and comment_id = ?");
    if(!$stmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
}
$stmt->bind_param('si',$user, $comment_id);
$stmt->execute();
$stmt->bind_result($upvote);
$stmt->fetch();
$stmt->close();
//if there is no existing upvote, insert the upvote into the database
if($upvote===null){
    if($value!=0){
    $stmt = $mysqli->prepare("insert into upvotes (user,comment_id,upvotes) values (?,?,?)");
    if(!$stmt){
       	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
}
        $stmt->bind_param('sii',$user, $comment_id,$value);
        $stmt->execute();
        $stmt->close();
       
    }
}
//if there is an existing upvote, update the upvote in the upvotes table
else{
    if($upvote!==$value){
        $stmt = $mysqli->prepare("update upvotes set upvotes=? where user=? and comment_id=? ");
        if(!$stmt){
        	printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('isi',$value, $user,$comment_id);
        $stmt->execute();
        $stmt->close();  
    }
}
//back button to the content page
echo "<form action = 'content.php' method = 'POST'>
        <input type = 'hidden' name = 'story_id' value = '$story_id'>
        <input type = 'submit' value = 'Go back'>
        </form>";
?>
</body>
</html>