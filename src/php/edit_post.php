<?php
	session_start();
	
	if (!isset($_SESSION["uid"]) || !isset($_SESSION["currently_viewed_blog"])) {
		header("Location: index.php");
		exit;
	}
	
	require "connect.php";
	require "twig_init.php";
	require "validation.php";
	require "general_post.php";
	
	$twig = init();
	$dbhandle = getConnection();
	
	$sql = "SELECT * FROM posts WHERE title = :title";
	$query = $dbhandle->prepare($sql);
	$query->execute(["title" => $_SESSION["currently_viewed_blog"]]);
	$result = $query->fetch();
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		echo $twig->render("post_edit.html.twig", [
			"pfp_path" => $_SESSION["pfp"],
			"username" => $_SESSION["username"],
			
			"blog_img" => $result["image"],
			"blog_title" => $result["title"],
			"blog_content" => $result["content"],
		]);
		exit;
	}
	
	$blogTitle = sanitise($_POST["blogTitle"]);
	$blogContent = sanitise($_POST["blogContent"]);
	
	createOrEditBlog(true, $dbhandle, $twig, $blogTitle, $blogContent, $result);

?>