<?php
	require_once 'twig_init.php';
	$twig = init();

	echo $twig->render("register.html.twig");
?>