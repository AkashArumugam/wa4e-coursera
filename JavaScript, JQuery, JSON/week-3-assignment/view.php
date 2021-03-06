<?php

	session_start();
	require_once "pdo.php";

	if(!isset($_GET["profile_id"])) {
		$_SESSION["error"] = "Missing profile_id";
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
	$stmt->execute(array(":profile_id" => $_GET["profile_id"]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row === false) {
		$_SESSION["error"] = "Could not load profile";
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id ORDER BY rank");
	$stmt->execute(array(":profile_id" => $_GET["profile_id"]));
	$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

	<script
	  src="https://code.jquery.com/jquery-3.2.1.js"
	  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
	  crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
	<h1>Profile information</h1>
	
	<p>First Name:
	<?= htmlentities($row['first_name']) ?></p>
	
	<p>Last Name:
	<?= htmlentities($row['last_name']) ?></p>
	
	<p>Email:
		<?= htmlentities($row['email']) ?>
	</p>
	
	<p>Headline:<br/>
	<?= htmlentities($row['headline']) ?></p>
	
	<p>Summary:<br/>
	<?= htmlentities($row['summary']) ?></p>
	
	<p>Position</p>
	<ul>
	<?php
		if($positions != false) {
			//print_r($positions);
			foreach ($positions as $pos) {
				echo '<li>'.htmlentities($pos['year']).': '.htmlentities($pos['description']).'</li>';
			}
		} 
	?>
	</ul>

	<p><a href="index.php">Done</a></p>
	</div>
</body>
</html>