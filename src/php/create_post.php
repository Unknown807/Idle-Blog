<?php
	session_start();
	
	require "twig_init.php";
	$twig = init();
	
	if (!isset($_SESSION["uid"])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("post_creation.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
		]);
		exit;
	}

?>