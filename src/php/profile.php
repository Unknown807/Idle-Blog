<?php

	session_start();
	require 'twig_init.php';
	$twig = init();
	
	$loggedIn = isset($_SESSION["uid"]);
	if (!$loggedIn) {
		session_destroy();
	}
	

	if (!isset($_GET["username"]) || empty(str_replace(['"',"'"], "", $_GET["username"]))) { // No username to search for
		
		echo $twig->render("profile_error.html.twig", [
				"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
				"username" => $loggedIn ? $_SESSION["username"] : "",
				"error_msg" => "Blank Username",
		]);
		exit;
	}
	
	require "validation.php";
	
	$username = $_GET["username"];
	$username = sanitise(str_replace(['"',"'"], "", $username));
	
	if ($loggedIn && ($_SESSION["username"] == $username)) {
		// username to search is the same as logged in username (your profile)
		setcookie("currentlyViewedUser", $username, time() + 86400, "/");
		
		echo $twig->render("profile_personal.html.twig", [
			"search_script" => "search_user_blog.php",
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
			"joined" => $_SESSION["joined"],
			"email" => $_SESSION["email"],
		]);
	} else {
		// Check if username actually exists before, if not then render error page
		
		require "connect.php";
		
		$dbhandle = getConnection();
		
		$sql = "SELECT * FROM users WHERE username = :username";
		$query = $dbhandle->prepare($sql);
		$params = ["username" => $username,];
		$query->execute($params);
		$result = $query->fetch();
		if (!$result) {
			echo $twig->render("profile_error.html.twig", [
				"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
				"username" => $loggedIn ? $_SESSION["username"] : "",
				"error_msg" => "User Doesn't Exist",
			]);
			exit;
		}
		
		// Render stranger's page since they do exist and set cookie for the user you are
		// looking at
		
		setcookie("currentlyViewedUser", $username, time() + 86400, "/");
		
		echo $twig->render("profile_other.html.twig", [
			"search_script" => "search_user_blog.php",
			"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
			"username" => $loggedIn ? $_SESSION["username"] : "",
			"other_username" => $result["username"],
			"other_joined" => $result["joined"],
			"other_pfp_path" => $result["pfp"],
		]);
		
	}

?>