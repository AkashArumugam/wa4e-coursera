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

	if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["headline"]) && isset($_POST["summary"]) && isset($_POST["profile_id"])) {

		if(strlen($_POST["first_name"]) > 0 && strlen($_POST["last_name"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["headline"]) > 0 && strlen($_POST["summary"]) > 0) {

			if(strpos($_POST['email'], '@') == false) {
            	$_SESSION["error"] = 'Email address must contain @';
            	header("Location: edit.php?profile_id=".$_GET["profile_id"]);
            	return;
        	}

        	$stmt = $pdo->prepare('UPDATE profile SET user_id = :uid, first_name = :fn, last_name = :ln, email = :em, headline = :hd, summary = :su WHERE profile_id = :pid');

		    $stmt->execute(array(
		        ':uid' => $_SESSION['user_id'],
		        ':fn' => $_POST['first_name'],
		        ':ln' => $_POST['last_name'],
		        ':em' => $_POST['email'],
		        ':hd' => $_POST['headline'],
		        ':su' => $_POST['summary'],
		    	':pid' => $_POST['profile_id'])
		    );
		    $_SESSION["success"] = "Profile updated";
		    header("Location: index.php");
		    return;
		}
		else {

			$_SESSION["error"] = "All fields are required";
			header("Location: edit.php?profile_id=".$_GET["profile_id"]);
			return;
		}
	}

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
	<h1>Editing Profile for <?=htmlentities($_SESSION["name"])?></h1>
	
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
		<input type="text" name="first_name" value="<?= htmlentities($row['first_name']) ?>" size="60"/></p>
		
		<p>Last Name:
		<input type="text" name="last_name" value="<?= htmlentities($row['last_name']) ?>" size="60"/></p>
		
		<p>Email:
		<input type="text" name="email" value="<?= htmlentities($row['email']) ?>" size="30"/></p>
		
		<p>Headline:<br/>
		<input type="text" name="headline" value="<?= htmlentities($row['headline']) ?>" size="80"/></p>
		
		<p>Summary:<br/>
		<textarea name="summary" rows="8" cols="80"><?= htmlentities($row['summary']) ?></textarea>
		<p>
		
		<input type="hidden" name="profile_id"
		value="<?= htmlentities($_GET["profile_id"]) ?>"
		/>
		<input type="submit" value="Save">
		<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
	</div>
</body>
</html>