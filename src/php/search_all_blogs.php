<?php
	session_start();
	
	require "connect.php";
	require "validation.php";
	require "twig_init.php";
	require "search_blogs.php";
	
	$twig = init();
	$title = sanitise($_GET["blogTitle"]);
	$dbhandle = getConnection();
	
	searchAndDisplayBlogs(true, $dbhandle, $twig, $title);

?>