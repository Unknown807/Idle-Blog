<?php

	require "twig_init.php";
	$twig = init();

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("register.html.twig");
		exit;
	}
	
	require "connect.php";

	function sanitise($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	
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
		// Check if username already exists
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
	
	

?>