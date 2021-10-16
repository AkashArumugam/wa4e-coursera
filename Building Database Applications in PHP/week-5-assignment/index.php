<?php
	require_once "pdo.php";
	session_start();

	$stmt = $pdo->query("SELECT * FROM autos ORDER BY autos_id");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>
	<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
	    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
	    crossorigin="anonymous">

	<link rel="stylesheet" 
	    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

	<script
	  src="https://code.jquery.com/jquery-3.2.1.js"
	  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
	  crossorigin="anonymous"></script>

	<script
	  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
	  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
	  crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
	<h2>Welcome to the Automobiles Database</h2>
	<?php
		if(!isset($_SESSION["account"])) {
			print "<p><a href='login.php'>Please log in</a></p>
			<p>Attempt to <a href='add.php'>add data</a> without logging in</p>";
		}
		else {
			if(isset($_SESSION["success"])) {
				echo "<p style='color: green;'>".$_SESSION["success"]."</p>";
				unset($_SESSION["success"]);

			}
			if(isset($_SESSION["error"])) {
				echo "<p style='color: red;'>".$_SESSION["error"]."</p>";
				unset($_SESSION["error"]);

			}
			if($rows != false) {
						
				echo "<table border='1'>
				<thead><tr>
				<th>Make</th>
				<th>Model</th>
				<th>Year</th>
				<th>Mileage</th>
				<th>Action</th>
				</tr></thead>";

				foreach ($rows as $row) {
				 	echo "<tr>";
				 	echo "<td>".htmlentities($row["make"])."</td>";
				 	echo "<td>".htmlentities($row["model"])."</td>";
				 	echo "<td>".htmlentities($row["year"])."</td>";
				 	echo "<td>".htmlentities($row["mileage"])."</td>";
				 	echo "<td>".'<a href="edit.php?autos_id='.$row["autos_id"].'">Edit</a> / <a href="delete.php?autos_id='.$row["autos_id"].'">Delete</a>'."</td>";
				 	echo "</tr>";
				}
				echo "</table>";
			}
			else {
				echo "<p>No rows found</p>";
			}

			print "<p><a href='add.php'>Add New Entry</a></p>
			<p><a href='logout.php'>Logout</a></p>";
		}
	?>
</body>
</html>