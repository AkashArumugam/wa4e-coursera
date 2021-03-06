<?php
	
session_start();
if (isset($_POST["email"]) && isset($_POST["pass"])) {

	if ( isset($_POST['cancel'] ) ) {
	    header("Location: index.php");
	    return;
	}

	$salt = 'XyZzy12*_';
	$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';


    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION["error"] = "User name and password are required";
        header( 'Location: login.php' );
        return;
    }
    else {
        $check = hash('md5', $salt.$_POST['pass']);
        
        if(strpos($_POST['email'], '@') == false) {
            $_SESSION["error"] = 'Email must have an at-sign (@)';
            header( 'Location: login.php' );
            return;
        }
        elseif ( $check == $stored_hash ) {
        	$_SESSION["account"] = $_POST["email"];
        	$_SESSION["success"] = "Logged in.";
            header("Location: view.php");
            return;
        }
        else {
            $_SESSION["error"] = "Incorrect password";
            header( 'Location: login.php' );
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>AKASH ARUMUGAM B S</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the 
programming language used in this class (all lower case) 
followed by 123. -->
</p>
</div>
</body>
