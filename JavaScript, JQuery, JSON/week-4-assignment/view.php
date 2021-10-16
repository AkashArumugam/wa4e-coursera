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

$stmt = $pdo->prepare("SELECT * FROM education WHERE profile_id = :profile_id ORDER BY rank");
$stmt->execute(array(":profile_id" => $_GET["profile_id"]));
$educations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>

	<?php require_once 'head.php'; ?>
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
	
	<p>Education</p>
	<ul>
	<?php
		if($educations != false) {
			
			foreach ($educations as $edu) {
				$stmt = $pdo->prepare("SELECT name FROM institution WHERE institution_id = :iid");
				$stmt->execute(array(":iid" => $edu['institution_id']));
				$schl = ($stmt->fetch(PDO::FETCH_ASSOC))['name'];
				echo '<li>'.htmlentities($edu['year']).': '.htmlentities($schl).'</li>';
			}
		} 
	?>
	</ul>
	
	<p>Position</p>
	<ul>
	<?php
		if($positions != false) {

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