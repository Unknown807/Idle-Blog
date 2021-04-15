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
	
	$uflag = $eflag = $pflag = false;
	$umsg = $emsg = $pmsg = "";
	
	$username = sanitise($_POST["username"]);	
	$email = sanitise($_POST["email"]);
	$password = sanitise($_POST["password"]);
	$confirmPassword = sanitise($_POST["confirmPassword"]);

	$dbhandle = getConnection();
	
	if (empty($username)) {
		$uflag = true;
		$umsg = "Username cannot be left blank";
	} else {
		$query = $dbhandle->prepare("SELECT uid FROM users WHERE username = :username");
		$params = ["username" => $username,];
		$query->execute($params);
		$result = $query->fetch();
		if ($result) {
			$uflag = true;
			$umsg = "Username already exists";
		}
	}		
	
	if (empty($email)) {
		$eflag = true;
		$emsg = "Email cannot be left blank";
	} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$eflag = true;
		$emsg = "Invalid email format";
	}
	
	if (empty($password) || empty($confirmPassword)) {
		$pflag = true;
		$pmsg = "Password cannot be left blank";
	} else if ($password != $confirmPassword) {
		$pflag = true;
		$pmsg = "Password's have to be the same";
	}

	if ($uflag || $eflag || $pflag) {
		session_destroy();
		echo $twig->render("register.html.twig", [
			"username" => $username,
			"username_valid" => $uflag ? "is-invalid" : "",
			"username_error_msg" => $uflag ? $umsg : "",
			
			"email" => $email,
			"email_valid" => $eflag ? "is-invalid" : "",
			"email_error_msg" => $eflag ? $emsg : "",
			
			"password_valid" => $pflag ? "is-invalid" : "",
			"password_error_msg" => $pflag ? $pmsg : "",
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