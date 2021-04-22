<?php
	session_start();

	if (isset($_SESSION["uid"])) {
		header("Location: index.php");
		exit;
	}
	
	session_destroy();
	
	require "twig_init.php";
	$twig = init();
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("email_reset_form.html.twig");
		exit;
	}
	
	require "connect.php";
	require "validation.php";

	$dbhandle = getConnection();
	$result = $umsg = false;
	
	$email = sanitise($_POST["email"]);
	$emsg = validateEmail($email);
	$username = sanitise($_POST["username"]);
	
	if (empty($username)) {
		$umsg = "Username cannot be left empty";
	} else {
		$username_exists = checkUsernameExists($dbhandle, $username);
		if ($username_exists) {
			if (!$emsg) {
				
				$sql = "SELECT * FROM users WHERE username = :username AND email = :email";
				$query = $dbhandle->prepare($sql);
				$params = [
					"username" => $username,
					"email" => $email,
				];
				
				$query->execute($params);
				$result = $query->fetch();
				
				if (!$result) {
					$emsg = "Email is incorrect for that user";
				}
			}
		} else {
			$umsg = "That user does not exist";
		}
	}
	
	if ($umsg || $emsg) {
		echo $twig->render("email_reset_form.html.twig", [
			"username" => $username,
			"username_error_msg" => $umsg,
			
			"email" => $email,
			"email_error_msg" => $emsg
		]);
		exit;
	}
	
	$gen_link_token = md5($username.$email.$result["uid"]);
	// Instead of localhost you would have your domain
	$link = "localhost/BlogProject/src/php/reset_password.php?token=".$gen_link_token;
	
	$sql = "INSERT INTO password_resets (pr_uid, username, email, link_token)
			VALUES (:uid, :username, :email, :token)";
	$query = $dbhandle->prepare($sql);
	
	
	$params = [
		"uid" => $result["uid"],
		"username" => $username,
		"email" => $email,
		"token" => $gen_link_token,
	];
	$query->execute($params);
	
	$mail_msg = wordwrap("Here is the link to reset your account's password:\n\n".$link, 70);
	
	mail($email, 
		"Idleblog Password Reset",
		$mail_msg,
		"From: no-reply@idleblog.com"."\r\n".
		"Content-Type: text/plain; charset=utf-8");
	
	echo $twig->render("email_reset_form.html.twig", [
		"success" => true,
	]);


?>