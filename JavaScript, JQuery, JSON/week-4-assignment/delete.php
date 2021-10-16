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

if(isset($_POST["delete"]) && isset($_POST["profile_id"])) {

	$sql = "DELETE FROM profile WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
    	':profile_id' => $_POST["profile_id"]
    ));
    $_SESSION['success'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
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

	<?php require_once 'head.php'; ?>
</head>
<body>
	<div class="container">
	<h1>Deleteing Profile</h1>
	
	<form method="post" action="delete.php">
		<p>First Name:
		<?= htmlentities($row["first_name"]) ?></p>
		
		<p>Last Name:
		<?= htmlentities($row["last_name"]) ?></p>
		
		<input type="hidden" name="profile_id"
		value="<?= htmlentities($_GET["profile_id"]) ?>"
		/>
		
		<input type="submit" name="delete" value="Delete">
		<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
	</div>
</body>
</html>