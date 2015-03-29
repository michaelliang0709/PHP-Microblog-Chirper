<?php
	$db; $stmt;
	// if the magic quote is on, do not escape data
	function check_magic_quotes($db,$stmt) {
	if (get_magic_quotes_gpc()) {
		$stmt = stripslashes($stmt);
	}
	// escape string for use in SQL statement
	$stmt = mysqli_real_escape_string($db,$stmt);
	return $stmt;
}
?>
