<?php

	require "twig_init.php";
	$twig = init();

	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("login.html.twig");
		exit;
	}

	echo $twig->render("login.html.twig", [
		"username_input" => "is-invalid",
		"username_error_msg" => "Wrong username",
	]);

?>