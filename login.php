<?php
	// initialize variables
	$username; $sha1_pwd; $sql; $result; $db; $num_rows; $row;
	require"./check_magic_quotes.php";
	// if the user has typed the username and password
	if (isset($_POST['username']) && isset($_POST['password'])) {
		require"./dongdong_p3_dbconnect.php";
		// try to log in with username and password
		$username = check_magic_quotes($db,$_POST['username']);
		// use sha1 encryption to encrypt password
		$sha1_pwd = sha1($_POST['password']);
		// validate username and password
		$sql = "select usertype, firstname, lastname, user_id from users where 
		username = '$username' and password = '$sha1_pwd'";
		if ($result = mysqli_query($db,$sql)) {
			$num_rows = mysqli_num_rows($result);
			if ($num_rows > 0) {
				session_start();
				$row = mysqli_fetch_row($result);
				// if the user is valid, store some variables in session
				$_SESSION['valid_name'] = $username;
				$_SESSION['user_type'] = $row[0];
				$_SESSION['firstname'] = $row[1];
				$_SESSION['lastname'] = $row[2];
				$_SESSION['user_id'] = $row[3];
			}
		}
	mysql_close($db);
	}
	if (isset($_SESSION['valid_name'])) {
		echo "<title>Chirper</title>";
		echo "<body background='bg.gif'>";
		// user is logged in
		echo "<br/ ><center>Welcome, " . $_SESSION['firstname'] . "</center><br/ >";
		echo "<center><a href='home.php'>Home Page</a></center><br/ >";
		if ($_SESSION['user_type'] == 'admin') {
			// if the user is an admin, display admin page
			echo "<br/ ><center>Wow, enjoy deleting!</center>";
			echo "<br/ ><center><a href='admin.php'>Admin Only!</a></center>";
		}
		echo "</body>";
	}
	else {
		// user is not logged in
		if (isset($username)) {
			// tried to login, but failed
			echo "<center><b><font color='red'>Problem logging in.</font></b></center>";
		} 
?>
	<head>
		<title>Chirper</title>
	</head>
	<body background="bg.gif">
		<br/ >
		<center><h1 style="color: white; font-family: 'Monotype Corsiva'; 
		font-size: 50px;">Welcome to Chirper</h1></center>
		<!-- the form for logging in -->
		<form method="post" action="login.php" style="text-align:center">
			<b>Username:</b>&nbsp;<input type="text" name="username">
			<p>
			<b>Password:</b>&nbsp;<input type="password" name="password">
			<p>
			<input type="submit" value="Log in">
		</form>
	</body>
<?php
	}
?>
