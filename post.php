<?php
	$cheep; $db; $date; $uid; $sql; $stmt;
	//set the timezone as EDT
	date_default_timezone_set('EST5EDT');
	session_start();
	echo "<title>Post Cheeps</title>";
	echo "<body background='bg.gif'>";
	if (!isset($_SESSION['valid_name'])) {
		// user is not logged in
		echo "<br/ ><center>Hey, you must log in to post cheeps!</center><br/ >";
		echo "<center><a href='home.php'>Home Page</a></center><br/ >";
	}
	else {
		require"./check_magic_quotes.php";
		require"./dongdong_p3_dbconnect.php";
		// sanitize user input
		$cheep = htmlspecialchars(strip_tags($_POST['cheep']));
		$cheep = check_magic_quotes($db,$cheep);
		// a cheep can not be empty
		if ($cheep != '') {
			$date = date_format(date_create(), 'Y-m-d H:i:s');
			$uid = $_SESSION['user_id'];
			// insert cheeps to db
			$sql = "insert into cheeps (cheep_text,created_date,user_id) values 
			('$cheep','$date','$uid')";
			if ($stmt = mysqli_prepare($db, $sql)) {
				mysqli_stmt_execute($stmt);
				echo "<script>alert('Cheep Posted!');</script>";
				echo "<center><a href='home.php'>Home Page</a></center><br/ >";
			}
			else {
				echo "<script>alert('Oops! Failed to post.');</script>";
				echo "<center><a href='home.php'>Home Page</a></center><br/ >";
			}
		}
		else {
			echo "<script>alert('Oops! Failed to post.');</script>";
			echo "<center><a href='home.php'>Home Page</a></center><br/ >";
		}
	mysql_close($db);
	}
	echo "</body>";
?>
