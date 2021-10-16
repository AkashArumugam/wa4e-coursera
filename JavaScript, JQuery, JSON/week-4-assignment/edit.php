<?php

session_start();
require_once "pdo.php";
require_once 'util.php';

if(!isset($_SESSION["name"]) || !isset($_SESSION['user_id'])) {
	die('ACCESS DENIED');
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

if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if(isset($_POST['save'])) {

	if(validatePos() === true && validateProfile() === true && validateEdu() === true) {

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

	    $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id = :pid');
	    $stmt->execute(array(':pid' => $_POST['profile_id']));
		$rank = 1;
		for($i=1; $i<=9; $i++) {
			if(!isset($_POST['year'.$i])) continue;
			if(!isset($_POST['desc'.$i])) continue;
			
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];

			$stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
			$stmt->execute(array(
				':pid' => $_POST['profile_id'],
				':rank' => $rank,
				':year' => $year,
				':desc' => $desc)
			);
			$rank++;
		}

		$stmt = $pdo->prepare('DELETE FROM education WHERE profile_id = :pid');
	    $stmt->execute(array(':pid' => $_POST['profile_id']));
		$rank = 1;
		for($i=1; $i<=9; $i++) {
			if(!isset($_POST['edu_year'.$i])) continue;
			if(!isset($_POST['edu_school'.$i])) continue;
			
			$year = $_POST['edu_year'.$i];
			$schl = $_POST['edu_school'.$i];

			$stmt = $pdo->prepare('SELECT * FROM institution WHERE name = :schl');
			$stmt->execute(array(':schl' => $schl));
			$schl_row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($schl_row === false) {
				$stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:schl)');
				$stmt->execute(array(':schl' => $schl));
			}

			$stmt = $pdo->prepare('SELECT institution_id FROM institution WHERE name = :schl');
			$stmt->execute(array(':schl' => $schl));
			$schl_id = $stmt->fetch(PDO::FETCH_ASSOC)['institution_id'];

			$stmt = $pdo->prepare('INSERT INTO education (profile_id, rank, year, institution_id) VALUES (:pid, :rank, :year, :iid)');
			$stmt->execute(array(
				':pid' => $_POST['profile_id'],
				':rank' => $rank,
				':year' => $year,
				':iid' => $schl_id)
			);
			$rank++;
		}

		$_SESSION['success'] = 'Profile added';
		header('Location: index.php');
		return;
	}
	else{
		header('Location: edit.php?profile_id='.$_GET['profile_id']);
		return;
	}
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
	<h1>Editing Profile for <?=htmlentities($_SESSION["name"])?></h1>
	
	<?php flashMessages(); ?>

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

		<p>Education: <input type="submit" id="addEdu" value="+">
		<div id="edu_fields">
		<?php

		$stmt = $pdo->prepare("SELECT * FROM education WHERE profile_id = :profile_id ORDER BY rank");
		$stmt->execute(array(":profile_id" => $_GET["profile_id"]));
		$educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$countEdu = 0;
		//print($educations);
		foreach ($educations as $edu) {
			$countEdu++;
			$stmt = $pdo->prepare("SELECT name FROM institution WHERE institution_id = :iid");
			$stmt->execute(array(":iid" => $edu['institution_id']));
			$schl = ($stmt->fetch(PDO::FETCH_ASSOC))['name'];
			echo '<div id="edu'.$countEdu.'">
		            <p>Year: <input type="text" name="edu_year'.$countEdu.'" value="'.$edu['year'].'" />
		            <input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();return false;"></p>
		            <p>School: <input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.$schl.'" /></p>
	            </div>';
		}

		?>
		</div>
		</p>
		
		<p>
		Position: <input type="submit" id="addPos" value="+">
		<div id="position_fields">
		<?php

		$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id = :profile_id ORDER BY rank");
		$stmt->execute(array(":profile_id" => $_GET["profile_id"]));
		$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$countPos = 0;

		foreach ($positions as $pos) {
			$countPos++;
			echo '<div id="position'.$countPos.'">
		            <p>Year: <input type="text" name="year'.$countPos.'" value="'.$pos['year'].'" />

		            <input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();return false;"></p>
		            <textarea name="desc'.$countPos.'" rows="8" cols="80">'.htmlentities($pos['description']).'</textarea>
	            </div>';
		}

		?>
		</div>
		</p>

		<input type="hidden" name="profile_id" value="<?= htmlentities($_GET["profile_id"]) ?>" />

		<input type="submit" name="save" value="Save">
		<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>

	<script>
	countPos = <?= $countPos ?>;
	countEdu = <?= $countEdu ?>;

	// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
	$(document).ready(function(){
	    window.console && console.log('Document ready called');

	    $('#addPos').click(function(event){
	        // http://api.jquery.com/event.preventdefault/
	        event.preventDefault();
	        if ( countPos >= 9 ) {
	            alert("Maximum of nine position entries exceeded");
	            return;
	        }
	        countPos++;
	        window.console && console.log("Adding position "+countPos);
	        $('#position_fields').append(
	            '<div id="position'+countPos+'"> \
	            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
	            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
	            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
	            </div>');
	    });

	    $('#addEdu').click(function(event){
	        event.preventDefault();
	        if ( countEdu >= 9 ) {
	            alert("Maximum of nine education entries exceeded");
	            return;
	        }
	        countEdu++;
	        window.console && console.log("Adding education "+countEdu);

	        $('#edu_fields').append(
	            '<div id="edu'+countEdu+'"> \
	            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
	            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
	            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
	            </p></div>'
	        );

	        $('.school').autocomplete({
	            source: "school.php"
	        });

	    });

	});
	</script>
	</div>
</body>
</html>