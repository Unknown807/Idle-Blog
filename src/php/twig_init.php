<?php

	require __DIR__ . '/../vendor/autoload.php';

	use Twig\Environment;
	use Twig\Loader\FilesystemLoader;

	function init() {
		$loader = new FilesystemLoader(__DIR__ . '/../templates');
		$twig = new Environment($loader);
		
		return $twig;
	}

?>