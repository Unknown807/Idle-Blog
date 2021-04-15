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
		echo $twig->render("login.html.twig");
		exit;
	}
	
	require "connect.php";
	require "validation.php";
	
	$dbhandle = getConnection();
	
	$uflag = $pflag = false;
	$umsg = $pmsg = $retrieved_hash = "";
	
	$username = sanitise($_POST["username"]);
	$password = sanitise($_POST["password"]);
	
	if (empty($password)) {
		$pflag = true;
		$pmsg = "Password cannot be left blank";
	}
	
	if (empty($username)) {
		$uflag = true;
		$umsg = "Username cannot be left blank";
	} else {
		$sql = "SELECT username, passhash FROM users WHERE username = :username";
		$query = $dbhandle->prepare($sql);
		$params = ["username" => $username,];
		$query->execute($params);
		$result = $query->fetch();
		if ($result) {
			$retrieved_hash = $result["passhash"];
			if (!password_verify($password, $retrieved_hash)) {
				$pflag = true;
				$pmsg = "Wrong password";
			}
		} else {
			$uflag = true;
			$umsg = "That username doesn't exist";
		}
	}

	if ($uflag || $pflag) {
		session_destroy();
		echo $twig->render("login.html.twig", [
			"username" => $username,
			"username_valid" => $uflag ? "is-invalid" : "",
			"username_error_msg" => $uflag ? $umsg : "",
			
			"password_valid" => $pflag ? "is-invalid" : "",
			"password_error_msg" => $pflag ? $pmsg : "",
		]);
		exit;
	}
	
	$sql = "SELECT * FROM users WHERE username = :username AND passhash = :passhash";
	$query = $dbhandle->prepare($sql);
	$params = ["username" => $username, "passhash" => $retrieved_hash];
	$query->execute($params);
	$result = $query->fetch();
	
	$_SESSION["uid"] = $result["uid"];
	$_SESSION["username"] = $result["username"];
	$_SESSION["email"] = $result["email"];
	
	header("Location: index.php");
	

?>