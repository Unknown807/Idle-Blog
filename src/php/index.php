<?php

	session_start();
	require 'twig_init.php';
	$twig = init();

	if (!isset($_SESSION["uid"])) {
		session_destroy();
		echo $twig->render("index.html.twig");
		exit;
	}

	echo $twig->render("index.html.twig", [
		"pfp_path" => $_SESSION["pfp"],
		"username" => $_SESSION["username"],
	]);
	
?>