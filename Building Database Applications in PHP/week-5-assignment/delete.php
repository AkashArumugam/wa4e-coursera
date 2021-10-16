<?php
	require_once "pdo.php";
	session_start();

	if ( ! isset($_SESSION['account']) ) {
  		die('ACCESS DENIED');
	}

	if(!isset($_GET["autos_id"])) {
		$_SESSION["error"] = "Bad value for id";
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :autos_id");
	$stmt->execute(array(":autos_id" => $_GET["autos_id"]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row === false) {
		$_SESSION["error"] = "Bad value for id";
		header("Location: index.php");
		return;
	}

	if(isset($_POST["delete"]) && isset($_POST["autos_id"])) {

		$sql = "DELETE FROM autos WHERE autos_id = :autos_id";
	    $stmt = $pdo->prepare($sql);
	    $stmt->execute(array(
	    	':autos_id' => $_POST["autos_id"]
	    ));
	    $_SESSION['success'] = 'Record deleted';
	    header( 'Location: index.php' ) ;
	    return;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>AKASH ARUMUGAM B S</title>
</head>
<body>
	<div class="container">
		<p>
			Confirm: Deleting <?= htmlentities($row["make"])?>
		</p>
		<form method="post">
			<input type="hidden" name="autos_id" value="<?=htmlentities($_GET["autos_id"])?>">
			<input type="submit" value="Delete" name="delete"><a href="index.php">Cancel</a>
		</form>
	</div>
</body>
</html>