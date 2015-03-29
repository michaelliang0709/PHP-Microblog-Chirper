<?php
	$old_user;
	session_start();
	echo "<title>Logout</title>";
	echo "<body background='bg.gif'>";
	// destroy the session completely
	$old_user = $_SESSION['valid_name'];
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy();
	if (!empty($old_user)) {
		echo "<br/ ><center>See you next time!</center><br/ >";
	} 
	else {
		echo "<center>You were not logged in.</center><br/ >";
	}
?>
	<center><a href="home.php">Home Page</a></center>
	</body>
