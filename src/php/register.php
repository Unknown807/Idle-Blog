<?php
	session_start();

	require "twig_init.php";
	$twig = init();

	if (isset($_SESSION["uid"])) {
		header("Location: index.php");
		exit;
	}

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		session_destroy();
		echo $twig->render("register.html.twig");
		exit;
	}
	
	require "connect.php";
	require "validation.php";
	
	$username = sanitise($_POST["username"]);	
	$email = sanitise($_POST["email"]);
	$password = sanitise($_POST["password"]);
	$confirmPassword = sanitise($_POST["confirmPassword"]);

	$dbhandle = getConnection();
	
	$umsg = validateUsername($dbhandle, $username);
	$emsg = validateEmail($email);
	$pmsg = validatePassword($password, $confirmPassword);

	if ($umsg || $emsg || $pmsg) {
		session_destroy();
		echo $twig->render("register.html.twig", [
			"username" => $username,
			"username_error_msg" => $umsg ? $umsg : "",
			
			"email" => $email,
			"email_error_msg" => $emsg ? $emsg : "",
			
			"password_error_msg" => $pmsg ? $pmsg : "",
		]);
		exit;
	}
	
	$passhash = password_hash($password, PASSWORD_BCRYPT);
	$params = ["username" => $username,
			"email" => $email,
			"passhash" => $passhash];
	
	$sql = "INSERT INTO users
			(username, email, passhash) VALUES
			(:username, :email, :passhash)";
	
	$query = $dbhandle->prepare($sql);
	$query->execute($params);
	
	header("Location: login.php");

?>