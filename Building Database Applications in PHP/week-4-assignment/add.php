<?php
	require_once "pdo.php";
	session_start();
	
	if ( ! isset($_SESSION['account']) ) {
  		die('Not logged in');
	}

	if ( isset($_POST['cancel']) ) {
	    header('Location: view.php');
	    return;
	}

	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

		if(strlen($_POST['make']) > 1  && is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
			
			$sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
		        ':make' => $_POST['make'],
		        ':year' => $_POST['year'],
		        ':mileage' => $_POST['mileage']));

			$stmt = $pdo->query("SELECT year, make, mileage FROM autos ORDER BY auto_id");
			$_SESSION["rows"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$_SESSION["success"] = "Record inserted";
			header("Location: view.php");
			return;
		}
		else {
			if(strlen($_POST['make']) < 1) {
				$_SESSION["error"] = "Make is required";
				header("Location: add.php");
				return;
			}
			else {
				$_SESSION["error"] = "Mileage and year must be numeric";
				header("Location: add.php");
				return;
			}
		}

	}
?>

<html>
	<head>
	<title>AKASH ARUMUGAM B S</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	</head>
	<body>
		<div class="container">
			<h1>Tracking Autos for <?php print htmlentities($_SESSION['account'])?></h1>
			<p style="color: red">
				<?php
					if(isset($_SESSION["error"])) {
					echo htmlentities($_SESSION["error"]);
				}
				?>
			</p>
			<form method="post">
				<p>Make:
				<input type="text" name="make" size="60"/></p>
				<p>Year:
				<input type="text" name="year"/></p>
				<p>Mileage:
				<input type="text" name="mileage"/></p>
				<input type="submit" value="Add">
				<input type="submit" name="cancel" value="Cancel">
			</form>
		</div>
	</body>
</html>