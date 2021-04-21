<?php
	session_start();

	require "connect.php";
	require "validation.php";
	require "twig_init.php";
	require "search_blogs.php";
	
	$twig = init();

	if (!isset($_SESSION["currently_viewed_user"])) {
		header("Location: index.php");
		exit;
	}
	
	$title = sanitise($_GET["blogTitle"]);
	$dbhandle = getConnection();
	
	searchAndDisplayBlogs(false, $dbhandle, $twig, $title);
	
?>