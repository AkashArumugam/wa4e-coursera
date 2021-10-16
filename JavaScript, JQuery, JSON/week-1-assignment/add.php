<?php
	session_start();
	require_once "pdo.php";

	if(!isset($_SESSION["name"]) || !isset($_SESSION['user_id'])) {
		die('ACCESS DENIED');
	}

	if ( isset($_POST['cancel']) ) {
	    header('Location: index.php');
	    return;
	}

	if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["headline"]) && isset($_POST["summary"])) {

		if(strlen($_POST["first_name"]) > 0 && strlen($_POST["last_name"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["headline"]) > 0 && strlen($_POST["summary"]) > 0) {

			if(strpos($_POST['email'], '@') == false) {
            	$_SESSION["error"] = 'Email address must contain @';
            	header( 'Location: add.php' );
            	return;
        	}

        	$stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');
		    $stmt->execute(array(
		        ':uid' => $_SESSION['user_id'],
		        ':fn' => $_POST['first_name'],
		        ':ln' => $_POST['last_name'],
		        ':em' => $_POST['email'],
		        ':he' => $_POST['headline'],
		        ':su' => $_POST['summary'])
		    );
		    $_SESSION["success"] = "Profile added";
		    header("Location: index.php");
		    return;
		}
		else {

			$_SESSION["error"] = "All fields are required";
			header("Location: add.php");
			return;
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>
	
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
	<h1>Adding Profile for <?=htmlentities($_SESSION["name"])?></h1>
	
	<?php

	if(isset($_SESSION["success"])) {
		echo "<p style='color: green;'>".$_SESSION["success"]."</p>";
		unset($_SESSION["success"]);
	}
	if(isset($_SESSION["error"])) {
		echo "<p style='color: red;'>".$_SESSION["error"]."</p>";
		unset($_SESSION["error"]);
	}

	?>

	<form method="post">
		<p>First Name:
		<input type="text" name="first_name" size="60"/></p>
		
		<p>Last Name:
		<input type="text" name="last_name" size="60"/></p>
		
		<p>Email:
		<input type="text" name="email" size="30"/></p>
		
		<p>Headline:<br/>
		<input type="text" name="headline" size="80"/></p>
		
		<p>Summary:<br/>
		<textarea name="summary" rows="8" cols="80"></textarea>
		
		<p>
		<input type="submit" value="Add">
		<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
	</div>
</body>
</html>