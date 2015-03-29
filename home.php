<?php
	// initialize variables
	$text; $following_sql; $followed_sql; $result; $num_rows; $row; $following_num;
	$db; $uid; $sql; $stmt; $uname; $fname; $lname; $cheeps; $date; $words; $word;
?>
<style>
#header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
}
#content {
    position: fixed;
    top: 150px;
    bottom: 100px;
    left: 0;
    right: 0;
	width: 90%;
	padding-right: 30px; 
    overflow: auto;
	font-family:'Comic Sans MS';
	font-size: 16px;
}
</style>
<?php
	session_start();
	echo "<title>Chirper Home</title>";
	echo "<body background='bg.gif'>";
	if (!isset($_SESSION['valid_name'])) {
		// user is not logged in, prompt login page
		echo "<br/ ><center>Hey, you must log in to discover the secrets!</center><br/ >";
		echo "<center><a href='login.php'>Log In</a></center><br/ >";
	}
	else {
?>
		<div id='header'>
		<h1 style="margin-left:30px; margin-top:20px; color:#56A0D3; font-family:
		'Segoe Script'; font-size:60px;">Chirper</h1>
		<div id='header' style="margin-right:30px; font-family:'Nyala'; font-size:18px;">
<?php
		echo "<br/ ><p align='right'>Welcome, <b>" . $_SESSION['firstname'] . "</b>&nbsp;";
		echo "<a href='logout.php'>(Log Out)</a><br/ ><br/ >";
		// if user is an admin, display admin page
		if ($_SESSION['user_type'] == 'admin') {
			echo "<a href='admin.php'>Admin Only!</a><br/ ><br/ >";
		}
		// connect to db
		require"./dongdong_p3_dbconnect.php";
		// count the number of users the current user follows, using username from session
		$following_sql = "select count(*) from follows where user_id = ( select user_id 
		from users where username = '" . $_SESSION['valid_name'] . "')";
		// count the number of users who follow the current user
		$followed_sql = "select count(*) from follows where follows_id = ( select user_id 
		from users where username = '" . $_SESSION['valid_name'] . "')";
		if ($result = mysqli_query($db,$following_sql)) {
			$num_rows = mysqli_num_rows($result);
			if ($num_rows > 0) {
				$row = mysqli_fetch_row($result);
				$following_num = $row[0];
			}
		}
		if ($result = mysqli_query($db,$followed_sql)) {
			$num_rows = mysqli_num_rows($result);
			if ($num_rows > 0) {
				$row = mysqli_fetch_row($result);
				$followed_num = $row[0];
			}
		}
		echo "Following " . $following_num . " / ";
		echo "Followed by " . $followed_num . "</p>";
?>
		</div></div>
		<div id='content'><div id='content'>
		<!-- the form for posting cheeps -->
		<form method="post" action="post_cheep.php" style="margin-left:40px;">
			<input type="text" name="cheep" maxlength="141" 
			placeholder="Type a new cheep here" style="width:700px;height:35px;">
			<input type="submit" value="Post" style="width:60px;height:30px;">
		</form>	
		<br/ ><br/ >
		<!-- the form for searching cheeps -->
		<form method="get" action="home.php" style="margin-left:40px;">
			Matching Text:
			<p><input type="text" name="match_text" style="width:400px;height:35px;"
<?php		
			// fill the box with previous criteria
			if (!empty($_GET['match_text'])) {
				echo " value='" . $_GET['match_text'] . "'";
			} 
			else { echo " placeholder='Search cheeps'";}
?>
			><p><input type="radio" name="match_radio" value="all_users" 
<?php
			// save previous criteria for radio button
			if ($_GET['match_radio'] == "all_users") {
				echo "checked";
			}
?>
			>All users</p>
			<p><input type="radio" name="match_radio" value="only_users" 
<?php
			// save previous criteria for radio button
			if ($_GET['match_radio'] == "only_users") {
				echo "checked";
			}
?>
			>Only users I follow
			</p><input type="submit" value="Search" style="width:60px;height:30px;">
		</form>	
		</div>
		<div id='content' style="margin-left:650px; margin-top:80px;
		height:350px; width:450px;">
<?php
		require"./check_magic_quotes.php";
		$uid = $_SESSION['user_id'];
		if (isset($_GET['match_text'])) {
			// sanitize user input
			$text = check_magic_quotes($db,$_GET['match_text']);
		}
		// construct SQL statement for searching cheeps
		// if search for all users' cheeps
		if ($_GET['match_radio'] == "all_users" || !isset($_GET['match_radio'])) {
			// if search for specific texts
			if (!empty($text)) {
				$sql = "select a.username, a.firstname, a.lastname, b.cheep_text, 
				b.created_date from cheeps b inner join users a on b.user_id = a.user_id 
				where match b.cheep_text against ('$text') order by created_date 
				desc limit 10";
			}
			else {
				$sql = "select a.username, a.firstname, a.lastname, b.cheep_text, 
				b.created_date from cheeps b inner join users a on b.user_id = a.user_id 
				order by created_date desc limit 10";
			}
		}
		else {
			if (!empty($text)) {
				$sql = "select a.username, a.firstname, a.lastname, b.cheep_text, 
				b.created_date from cheeps b inner join users a on b.user_id = a.user_id 
				where b.user_id in (select follows_id from follows where user_id = '$uid')
				and match b.cheep_text against ('$text') order by created_date desc limit 10";
			}
			else {
				$sql = "select a.username, a.firstname, a.lastname, b.cheep_text, b.created_date 
				from cheeps b inner join users a on b.user_id = a.user_id where b.user_id in 
				(select follows_id from follows where user_id = '$uid') order by created_date 
				desc limit 10";
			}
		}
		if ($stmt = mysqli_prepare($db, $sql)) {
			// execute query and bind results to variables
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$uname,$fname,$lname,$cheeps,$date);
			while (mysqli_stmt_fetch($stmt)) {
				// format date
				$date = date("Y F d g:i a", strtotime($date));
				echo "<p><b>" . $fname . " " . $lname . "</b> @" . $uname . " - " . $date;
				echo "<br/ >";
				$words = explode(" ",$cheeps);
                foreach($words as $word){
					// if a word starts with a hashtag
					if (substr($word,0,1) == '#'){
						// make hashtags clickable and use the word as the search keyword
						echo "<a href='home.php?match_text=" . substr($word,1) . "'>$word</a>";
					}
					else {
						echo $word . " ";
					}
				}
				echo "<br/ ><br/ >";
			}
			// if there is no matching record
			if (empty($fname)) {
				echo "<script>alert('Oops! No Records.');</script>";
			}
		}
		else {
			echo "<script>alert('Oops! Failed to load.');</script>";
		}
		
	mysql_close($db);
	}
	echo "</div></div></body>";
?>
