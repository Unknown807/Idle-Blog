<?php
	require_once 'twig_init.php';
	$twig = init();
	
	$words = ['sky', 'mountain', 'falcon', 'forest', 'rock', 'blue'];
	$sentence = 'today is a windy day';

	echo $twig->render('first.html', 
		['words' => $words, 'sentence' => $sentence]);
?>