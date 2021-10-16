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

	if ( isset($_POST['cancel']) ) {
	    header('Location: index.php');
	    return;
	}

	if(isset($_POST['make'])  && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {

		if(strlen($_POST['make']) >= 1  && strlen($_POST['model']) >= 1 && is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
			
			$sql = "UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE autos_id = :autos_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
		        ':make' => $_POST['make'],
		        ':model' => $_POST['model'],
		        ':year' => $_POST['year'],
		        ':mileage' => $_POST['mileage'],
		        ':autos_id' => $_GET['autos_id']
		    ));

			$_SESSION["success"] = "Record edited.";
			header("Location: index.php");
			return;
		}
		else {
			if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
				$_SESSION["error"] = "All fields are required";
				header("Location: edit.php");
				return;
			}
			elseif(!is_numeric($_POST['year'])){
				$_SESSION["error"] = "Year must be numeric";
				header("Location: edit.php");
				return;
			}
			elseif(!is_numeric($_POST['mileage'])){
				$_SESSION["error"] = "Mileage must be numeric";
				header("Location: edit.php");
				return;
			}
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

	<link rel="stylesheet" 
	    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
	    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
	    crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h1>Editing Automobiles for <?= htmlentities($_SESSION["account"]) ?></h1>
		<p style="color: red">
			<?php
				if(isset($_SESSION["error"])) {
				echo $_SESSION["error"];
				unset($_SESSION["error"]);
				}
			?>
		</p>
		<form method="post">
			<p>Make:

			<input type="text" name="make" size="40" value="<?= htmlentities($row["make"])?>"/></p>
			<p>Model:

			<input type="text" name="model" size="40" value="<?= htmlentities($row["model"])?>"/></p>
			<p>Year:

			<input type="text" name="year" size="10" value="<?= htmlentities($row["year"])?>"/></p>
			<p>Mileage:

			<input type="text" name="mileage" size="10" value="<?= htmlentities($row["mileage"])?>"/></p>
			<input type="submit" name='edit' value="Save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>