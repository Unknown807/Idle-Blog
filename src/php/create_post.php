<?php
	session_start();
	
	require "twig_init.php";
	$twig = init();
	
	if (!isset($_SESSION["uid"])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("post_creation.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
		]);
		exit;
	}
	
	// validate post details
	// Then insert post data to DB
	// 
	
	require "connect.php";
	require "validation.php";
	
	$blogTitle = sanitise($_POST["blogTitle"]);
	$blogContent = sanitise($_POST["blogContent"]);
	
	$dbhandle = getConnection();
	
	$title_msg = false;
	if (empty($blogTitle)) {
		$title_msg="Blog title cannot be left empty";
	} else {
		$sql = "SELECT pid FROM posts WHERE title = :title";
		$query = $dbhandle->prepare($sql);
		$params = ["title" => $blogTitle];
		$query->execute($params);
		$result = $query->fetch();
		if ($result) {
			$title_msg = "One of your blogs already has that title";
		}
	}
	
	$content_msg = false;
	if (empty($blogContent)) {
		$content_msg = "Blog content cannot be left empty";
	} else if (strlen($blogContent) < 100) {
		$content_msg = "You need to write a little more than that";
	}
	
	if ($title_msg || $content_msg) {
		echo $twig->render("post_creation.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
			
			"blog_title" => $blogTitle,
			"blog_title_error_msg" => $title_msg ? $title_msg : "",
			
			"blog_content" => $blogContent,
			"blog_content_error_msg" => $content_msg ? $content_msg : "",
			
		]);
		exit;
	}
	
	
	
	

?>