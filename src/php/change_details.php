<?php
	session_start();
	
	require "twig_init.php";
	$twig = init();
	
	function refreshPage($twig) {
		echo $twig->render("profile_personal.html.twig", [
			"search_script" => "search_user_blog.php",
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
			"joined" => $_SESSION["joined"],
			"email" => $_SESSION["email"],
		]);
		exit;
	}
	
	if (!isset($_SESSION["uid"])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		refreshPage($twig);
	}
	
	require "connect.php";
	require "validation.php";
	
	$uid = $_SESSION["uid"];
	
	$username = sanitise($_POST["username"]);	
	$email = sanitise($_POST["email"]);
	$password = sanitise($_POST["password"]);
	$confirmPassword = sanitise($_POST["confirmPassword"]);
	
	$dbhandle = getConnection();
	
	$umsg = false;
	if ($username != $_SESSION["username"]) {
		$umsg = validateUsername($dbhandle, $username);
	}
	
	$pmsg = false;
	if ($password != $confirmPassword) {
		$pmsg = "Password's have to be the same";
	}
	
	$emsg = validateEmail($email);
	
	if ($umsg || $emsg || $pmsg) {
		echo $twig->render("profile_personal.html.twig", [
			"username" => $username,
			"username_error_msg" => $umsg ? $umsg : "",
			
			"email" => $email,
			"email_error_msg" => $emsg ? $emsg : "",
			
			"password_error_msg" => $pmsg ? $pmsg : "",
			
			"pfp_path" => $_SESSION["pfp"],
			"joined" => $_SESSION["joined"],
		]);
		exit;
	}
	
	require "image_utils.php";
	
	if ($_FILES["userImg"]["error"] == UPLOAD_ERR_OK) {
	
		$pattern = $uid."pfp_*.*";
		
		removeLastImage("../resources/temp_images/".$pattern);
		removeLastImage("../resources/user_pfps/".$pattern);
		
		$newpfp_path = uploadImage("../resources/user_pfps/", "pfp", 100, 100);
		$_SESSION["pfp"] = $newpfp_path;
		
		$sql = "UPDATE users SET pfp = :newpfp_path WHERE uid = :uid";
		$query = $dbhandle->prepare($sql);
		$params = ["newpfp_path" => $newpfp_path, "uid" => $uid];
		$query->execute($params);
		
	}
	
	$sql = "UPDATE users SET username = :username, email = :email WHERE uid = :uid";
	$query = $dbhandle->prepare($sql);
	$params = [
		"username" => $username,
		"email" => $email,
		"uid" => $uid,
	];
	$query->execute($params);
	
	$_SESSION["username"] = $username;
	$_SESSION["email"] = $email;
	
	if (!empty($password) && !empty($confirmPassword)) {
		$passhash = password_hash($password, PASSWORD_BCRYPT);
		
		$sql = "UPDATE users SET passhash = :passhash WHERE uid = :uid";
		$query = $dbhandle->prepare($sql);
		$params = ["passhash" => $passhash, "uid" => $uid];
		$query->execute($params);
	}
	
	refreshPage($twig);

?>