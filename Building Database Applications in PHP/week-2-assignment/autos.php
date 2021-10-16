<?php
	require_once "pdo.php";
	
	// Demand a GET parameter
	if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
	    die('Name parameter missing');
	}

	// If the user requested logout go back to index.php
	if ( isset($_POST['logout']) ) {
	    header('Location: index.php');
	    return;
	}
	$warning = "";
	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

		if(strlen($_POST['make']) > 1  && is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
			
			$sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
		        ':make' => $_POST['make'],
		        ':year' => $_POST['year'],
		        ':mileage' => $_POST['mileage']));

			$stmt = $pdo->query("SELECT year, make, mileage FROM autos ORDER BY auto_id");
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			if(strlen($_POST['make']) < 1) {
				$warning = "Make is required";
			}
			else {
				$warning = "Mileage and year must be numeric";
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
			<h1>Tracking Autos for <?php print $_GET['name']?></h1>
			<p style="color: red">
				<?php
					echo $warning;
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
				<input type="submit" name="logout" value="Logout">
			</form>

			<h2>Automobiles</h2>
			<ul>
				<?php
					if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && strlen($_POST['make']) > 1  && strlen($_POST['year']) > 1 && strlen($_POST['mileage']) > 1) {
						
						foreach ($rows as $row) {
						 	echo "<li>";
						 	echo htmlentities($row['year'].' ');
						 	echo htmlentities($row['make']);
						 	echo " / ";
						 	echo htmlentities($row['mileage']);
						 	echo "</li>";
						}
					} 
				?>
			</ul>
		</div>
	</body>
</html>