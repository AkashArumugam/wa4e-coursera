<?php
	session_start();
	require_once "pdo.php";

	$stmt = $pdo->query("SELECT * FROM profile ORDER BY profile_id");

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
	    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
	    crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
	    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
	    crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h1>Resume Registry</h1>
		
		<?php

		if(isset($_SESSION["success"])) {
			echo "<p style='color: green;'>".$_SESSION["success"]."</p>";
			unset($_SESSION["success"]);
		}
		if(isset($_SESSION["error"])) {
			echo "<p style='color: red;'>".$_SESSION["error"]."</p>";
			unset($_SESSION["error"]);
		}

		if(!isset($_SESSION["name"])) {
			echo '<p><a href="login.php">Please log in</a></p>';
			if($rows != false) {
						
				echo "<table border='1'>
				<tr>
					<th>Name</th>
					<th>Headline</th>
				</tr>";

				foreach ($rows as $row) {
				 	echo "<tr>";
				 	echo "<td>".htmlentities($row["first_name"]).htmlentities($row["last_name"])."</td>";
				 	echo "<td>".htmlentities($row["headline"])."</td>";
				 	echo "</tr>";
				}
				echo "</table>";
			}
		}
		else {

			echo '<p><a href="logout.php">Logout</a></p>';

			if($rows != false) {
						
				echo "<table border='1'>
				<tr>
					<th>Name</th>
					<th>Headline</th>
					<th>Action</th>
				</tr>";

				foreach ($rows as $row) {
				 	echo "<tr>";
				 	echo "<td>".htmlentities($row["first_name"]).htmlentities($row["last_name"])."</td>";
				 	echo "<td>".htmlentities($row["headline"])."</td>";
				 	echo "<td>".'<a href="edit.php?profile_id='.$row["profile_id"].'">Edit</a> / <a href="delete.php?profile_id='.$row["profile_id"].'">Delete</a>'."</td>";
				 	echo "</tr>";
				}
				echo "</table>";
			}
			print "<p><a href='add.php'>Add New Entry</a></p>";
		}
		?>
	</div>
</body>
</html>