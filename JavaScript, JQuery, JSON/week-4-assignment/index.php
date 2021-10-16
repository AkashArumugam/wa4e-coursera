<?php
	session_start();
	require_once 'pdo.php';
	require_once 'util.php';

	$stmt = $pdo->query("SELECT * FROM profile ORDER BY profile_id");

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>

	<?php require_once 'head.php'; ?>
</head>
<body>
	<div class="container">
		<h1>Resume Registry</h1>
		
		<?php

		flashMessages();

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
				 	echo '<tr>';
				 	echo '<td>'.'<a href="view.php?profile_id='.$row["profile_id"].'">'.htmlentities($row['first_name']).htmlentities($row['last_name']).'</a>'.'</td>';
				 	echo '<td>'.htmlentities($row["headline"]).'</td>';
				 	echo '<td>'.'<a href="edit.php?profile_id='.$row["profile_id"].'">Edit</a> / <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>'.'</td>';
				 	echo "</tr>";
				}
				echo "</table>";
			}
			echo "<p><a href='add.php'>Add New Entry</a></p>";
		}
		?>
	</div>
</body>
</html>