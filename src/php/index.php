<?php
	require 'twig_init.php';
	$twig = init();

	echo $twig->render("index.html.twig");
?>