<?php

	session_start();
	require 'twig_init.php';
	$twig = init();

	if (!isset($_SESSION["uid"])) {
		session_destroy();
		echo $twig->render("welcome.html.twig");
		exit;
	}

	echo $twig->render("index.html.twig");
	
?>