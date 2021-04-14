<?php
	require_once 'twig_init.php';
	$twig = init();

	echo $twig->render("index.html.twig");

	//echo $twig->render("index.html.twig",
	//	["first_page_path" => "first.php"]);
?>