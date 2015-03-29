<?php
	$sql; $db; $stmt; $uid; $uname; $date; $cheeps; $cid;
?>
<title>Chirper Admin</title>
<body background='bg.gif'>
<table border="1" cellpadding="2" style="text-align:center;font-family:
'Palatino Linotype';" align="center">
	<tbody>
<?php
	session_start();
	if (!isset($_SESSION['valid_name']) && ($_SESSION['user_type'] == 'admin')) {
		// only admins have access to this page
		echo "<br/ >";
		echo "<center><a href='home.php'>Be a good citizen and go home!</a></center>";
	}
	else {
		echo "<br/ ><center><a href='home.php'>Home Page</a></center>";
		// construct table
		echo "<tr><td><b>User_id</b></td>";
		echo "<td><b>Username</b></td>";
		echo "<td><b>Date/Time</b></td>";
		echo "<td><b>Cheep</b></td>";
		echo "<td><b>Delete</b></td></tr>";
		require"./dongdong_p3_dbconnect.php";
		// display all info of cheeps
		$sql = "select a.user_id, a.username, b.created_date, b.cheep_text, b.cheep_id
		from cheeps b inner join users a on b.user_id = a.user_id order by b.cheep_id";
		if ($stmt = mysqli_prepare($db, $sql)) {
			// execute query and bind results to variables
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$uid,$uname,$date,$cheeps,$cid);
			while (mysqli_stmt_fetch($stmt)) {
				$date = date("Y F d g:i a", strtotime($date));
				echo "<tr><td>" . $uid . "</td><td>" . $uname . "</td><td>";
				echo $date . "</td><td>" . $cheeps . "</td><td>";
				// use cheep_id for deleting a cheep
				echo "<a href='delete.php?cid=". $cid . "'>Delete</a></td></tr>";
			}
		}
		else {
			echo "<script>alert('Oops! Failed to load.');</script>";
		}
		mysql_close($db);
	}
?>
	</tbody> 
</table>
</body>
