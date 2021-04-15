<?php

	session_start();
	require 'twig_init.php';
	$twig = init();

	if (!isset($_GET["username"]) || empty(str_replace(['"',"'"], "", $_GET["username"]))) { // No username to search for
		
		if (!isset($_SESSION["uid"])) {
			session_destroy();
			echo $twig->render("profile_error.html.twig", [
				"username_msg" => "Blank Username",
			]);
		} else {		
			echo $twig->render("profile_error.html.twig", [
				"pfp_path" => $_SESSION["pfp"],
				"username" => $_SESSION["username"],
				"username_msg" => "Blank Username",
			]);
		}
		exit;
	}
	
	$username = $_GET["username"];
	$username = str_replace(['"',"'"], "", $username);
	
	if (isset($_SESSION["uid"]) && ($_SESSION["username"] == $username)) {
		// username to search is the same as logged in username (your profile)
		echo $twig->render("profile_personal.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
			"username_msg" => "Personal Profile",
		]);
	} else {
		// else the user is either not logged in or its a strangers profile
		if (isset($_SESSION["uid"])) {
			// if logged in then you have to render the pfp + other things
			echo $twig->render("profile_other.html.twig", [
				"pfp_path" => $_SESSION["pfp"],
				"username" => $_SESSION["username"],
				"username_msg" => "Stranger's Profile",
			]);
		} else {
			session_destroy();
			// otherwise don't render them in
			echo $twig->render("profile_other.html.twig", [
				"username_msg" => "Stranger's Profile",
			]);
		}
		
	}

?>