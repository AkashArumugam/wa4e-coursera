<?php
	session_start();
	
	if ( ! isset($_SESSION['account']) ) {
  		die('Not logged in');
	}
?>


<html><head>
<title>AKASH ARUMUGAM B S</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['account'])?></h1>
<?php // line added to turn on color syntax highlight

if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}
?>
<h2>Automobiles</h2>
			<ul>
				<?php
					if(isset($_SESSION["rows"])) {
						
						foreach ($_SESSION["rows"] as $row) {
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
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>


</body></html>