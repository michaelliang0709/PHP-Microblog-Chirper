<h1 style="text-align:center">Project 2</h1>
<style>
.num {
	font-size: 17px;
}
.page {
	font-size: 17px;
	color: blue;
}
</style>
<table border="1" cellpadding="2" style="text-align:center">
<tbody> 
<?php
	// construct table
	// use <a href> and "sortby" to get which heading is clicked
	echo "<tr><td><a href='dongdong_p2_browse.php?sortby=authors'>Authors</a></td>";
	echo "<td><a href='dongdong_p2_browse.php?sortby=title'>Title</a></td>";
	echo "<td><a href='dongdong_p2_browse.php?sortby=publication'>Publication</a></td>";
	echo "<td><a href='dongdong_p2_browse.php?sortby=year'>Year</a></td>";
	echo "<td><a href='dongdong_p2_browse.php?sortby=type'>Type</a></td></tr>";
	// log into the database
	require"./dongdong_p2_dbconnect.php";
	// search for records and order by the heading clicked by user
	if ($_GET['sortby'] != "") {
		if ($_GET['page'] != "") {
			// display 25 records from itemnum [25*(page-1)+1]
			$record_sql = "select authors,title,publication,year,type,url from p2records
			order by " . $_GET['sortby'] . " limit " . 25*($_GET['page']-1) . ", 25";
		}
		else {
			$record_sql = "select authors,title,publication,year,type,url from p2records
			order by " . $_GET['sortby'] . " limit 25";
		}
	}
	// by default, search for records and order by itemnum
	else {
		if ($_GET['page'] != "") {
			$record_sql = "select authors,title,publication,year,type,url from p2records
			order by itemnum limit " . 25*($_GET['page']-1) . ", 25";
		}
		else {
			$record_sql = "select authors,title,publication,year,type,url from p2records
			order by itemnum limit 25";
		}
	}
	// prepare SQL statement
	if ($stmt = mysqli_prepare($mysqli, $record_sql)) {
		// execute query and bind results to variables
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$authors,$title,$publication,$year,$type,$url);
		while (mysqli_stmt_fetch($stmt)) {
			// set the title as a hyperlink to its URL
			echo "<tr><td>" . $authors . "</td><td><a href='" . $url . "'>" . $title;
			echo "</a></td><td>" . $publication . "</td><td>" . $year . "</td><td>";
			echo $type. "</td></tr>"; 
		}
	}
	else {
		echo "prepare failed";
	}
	$page_sql = "select count(*) from p2records";
	if ($stmt = mysqli_prepare($mysqli, $page_sql)) {
		// get the number of total records
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$num_of_records);
		while (mysqli_stmt_fetch($stmt)) {
			// calculate the number of pages
			$num_of_page = $num_of_records / 25;
			// if it needs an extra page
			if ($num_of_records % 25 != 0) {
				$num_of_page++;
			}
		}
	}
	else {
		echo "prepare failed";
	}
	mysqli_stmt_close(); 
	mysqli_close();
?> 
</tbody> 
</table>

<?php
	// construct paging
	echo "<div class = 'num' align='center'>";
	$sortby = $_GET['sortby'];
	// dispaly all page number choices
	for ($i = 1; $i <= $num_of_page; $i++) {
		echo "<b><a href='dongdong_p2_browse.php?sortby=$sortby&page=" . $i . "'>" . $i . "</a></b>  ";
	}
	$page_num = $_GET['page'];
	// initial value of page number should be 1
	if ($page_num == "") {
		$page_num = 1;
	}
	// display current page number
	echo "</div><div class = 'page' align='center'>Page: $page_num</div>";
	echo "<p> </p>"
?>
