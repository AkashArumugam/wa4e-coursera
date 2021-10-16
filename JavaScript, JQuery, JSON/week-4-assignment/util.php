<?php

function flashMessages() {
	if(isset($_SESSION["success"])) {
		echo "<p style='color: green;'>".$_SESSION["success"]."</p>";
		unset($_SESSION["success"]);
	}
	if(isset($_SESSION["error"])) {
		echo "<p style='color: red;'>".$_SESSION["error"]."</p>";
		unset($_SESSION["error"]);
	}
}

function validatePos() {

	for($i=1; $i<=9; $i++) {
		if(!isset($_POST['year'.$i])) continue;
		if(!isset($_POST['desc'.$i])) continue;
		
		$year = $_POST['year'.$i];
		$desc = $_POST['desc'.$i];

		if(strlen($year) == 0 || strlen($desc) == 0) {
			$_SESSION["error"] = "All fields are required";
			//header("Location: add.php");
			return false;
		}

		if(!is_numeric($year)) {
			$_SESSION["error"] = "Position year must be numeric";
			//header("Location: add.php");
			return false;
		}
	}
	return true;
}

function validateEdu() {
	
	for($i=1; $i<=9; $i++) {

		if(!isset($_POST['edu_year'.$i])) continue;
		if(!isset($_POST['edu_school'.$i])) continue;

		$year = $_POST['edu_year'.$i];
		$schl = $_POST['edu_school'.$i];

		if(strlen($year) == 0 || strlen($schl) == 0) {
			$_SESSION["error"] = "All fields are required";
			//header("Location: add.php");
			return false;
		}

		if(!is_numeric($year)) {
			$_SESSION["error"] = "Year must be numeric";
			//header("Location: add.php");
			return false;
		}
	}
	return true;
}

function validateProfile() {

	if(strlen($_POST["first_name"]) > 0 && strlen($_POST["last_name"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["headline"]) > 0 && strlen($_POST["summary"]) > 0) {

		if(strpos($_POST['email'], '@') == false) {
        	$_SESSION['error'] = 'Email address must contain @';
        	//header( 'Location: add.php' );
        	return false;
    	}
		//header("Location: index.php");
		return true;
	}
	else {

		$_SESSION['error'] = "All fields are required";
		//header("Location: add.php");
		return false;
	}
}