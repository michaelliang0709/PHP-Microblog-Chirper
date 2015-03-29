<?php
	$cid; $sql; $stmt; $db;
?>
<title>Chirper Delete</title>
<body background='bg.gif'>
<?php
	session_start();
	if (!isset($_SESSION['valid_name']) && ($_SESSION['user_type'] == 'admin')) {
		// only admin can see admin page
		echo "<br/ >";
		echo "<center><a href='home.php'>Be a good citizen and go home!</a></center>";
	}
	else {
		require"./dongdong_p3_dbconnect.php";
		// a cheep_id can not be empty
		if (!empty($_GET['cid'])) {
			$cid = $_GET['cid'];
			// delete cheeps by cheep_ids
			$sql = "delete from cheeps where cheep_id = '$cid'";
			if ($stmt = mysqli_prepare($db, $sql)) {
				mysqli_stmt_execute($stmt);
				echo "<script>alert('Cheep Deleted!');</script>";
			}
			else {
				echo "<script>alert('Oops! Failed to delete.');</script>";
			}
		}
		echo "<br/ ><center><a href='admin.php'>Admin Page</a></center>";
		mysql_close($db);
	}
?>
</body>
