<?php
//logs user out
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}
session_destroy();
//directs back to start page
header ('Location: start.php');

?>