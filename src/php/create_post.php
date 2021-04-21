<?php
	session_start();
	
	if (!isset($_SESSION["uid"])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	require "twig_init.php";
	require "connect.php";
	require "validation.php";
	require "general_post.php";
	
	$twig = init();
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("post_creation.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
		]);
		exit;
	}
	
	$blogTitle = sanitise($_POST["blogTitle"]);
	$blogContent = sanitise($_POST["blogContent"]);
	
	$dbhandle = getConnection();
	
	createOrEditBlog(false, $dbhandle, $twig, $blogTitle, $blogContent);
	
?>