<?php
	session_start();

	if (isset($_SESSION["uid"])) {
		header("Location: index.php");
		exit;
	}
	
	require "connect.php";
	require "twig_init.php";
	require "validation.php";

	$dbhandle = getConnection();
	$twig = init();
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {

		$link_token = sanitise($_GET["token"]);
		
		$sql = "SELECT * FROM password_resets WHERE link_token = :token";
		$query = $dbhandle->prepare($sql);
		$query->execute(["token" => $link_token]);
		$result = $query->fetch();
		
		if ($result) {
			$_SESSION["pr_uid"] = $result["pr_uid"];
		}
		
		echo $twig->render("password_reset_form.html.twig", [
			"token_error" => !$result,
		]);
		exit;	
	}
	
	$password = sanitise($_POST["password"]);
	$confirmPassword = sanitise($_POST["confirmPassword"]);
	$pmsg = validatePassword($password, $confirmPassword);
	
	if ($pmsg) {
		echo $twig->render("password_reset_form.html.twig", [
			"password_error_msg" => $pmsg,
		]);
		exit;
	}
	
	$passhash = password_hash($password, PASSWORD_BCRYPT);
	$params = [
		"passhash" => $passhash,
		"uid" => $_SESSION["pr_uid"],
	];
	
	$sql = "UPDATE users SET passhash = :passhash WHERE uid = :uid";
	$query = $dbhandle->prepare($sql);
	$query->execute($params);
	
	$sql = "DELETE FROM password_resets WHERE pr_uid = :uid";
	$query = $dbhandle->prepare($sql);
	$query->execute(["uid" => $_SESSION["pr_uid"]]);
	
	header("Location: login.php");
	
?>