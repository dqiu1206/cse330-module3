<!--Start page for the news site-->
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="newssite.css"> 
		<!--allows the site to scale-->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>News Sharing Site</title>
	</head>
	<body class="home">
        <?php
        //first destroys any existing session
        session_start();
		session_destroy();
		session_start();
		//creates unique token for this log in
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        ?>
        <h1 class="homepage">David & Zach's News Sharing Site</h1> 
        <!--login and password information-->
		<div class="box">
			<form action="login.php" method="post"> <!--link to our main stories page -->
                <input type="text" name="username" placeholder = "Username" maxlength = '30' required> 
                <input type="password" name="password" placeholder = "Password" required> <!--Password-->
                <input type = 'hidden' name = 'token' value = '<?php echo $_SESSION['token']; ?>'>
                <input type="submit" value="Log-in" > <!--Log-in button-->
			</form>
		</div> 
		<!--link to sign up page-->
		<p>
			New user? Click <a href="signup.html">here</a> to sign up.
		</p>
		<!--link for users who don't want to log in-->
		<p>
			Don't want to sign in? Click <a href="homepage.php">here</a> to view news.
		</p>
	</body>
</html>

