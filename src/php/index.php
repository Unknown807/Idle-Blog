<?php

	session_start();
	require 'twig_init.php';
	$twig = init();
	
	$loggedIn = isset($_SESSION["uid"]);

	if (!$loggedIn) {
		session_destroy();
	}
	
	require "connect.php";
	require "blog_utils.php";
	
	$dbhandle = getConnection();
	$latest = getLatestBlog($dbhandle);
	$formatted_blog_content = formatBlogContent($latest["content"]);
	
	$sql = "SELECT username FROM users WHERE uid = :uid";
	$params = ["uid" => $latest["uid"]];
	$query = $dbhandle->prepare($sql);
	$query->execute($params);
	$blog_username = $query->fetch()["username"];

	echo $twig->render("index.html.twig", [
		"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
		"username" => $loggedIn ? $_SESSION["username"] : "",
		
		"other_username" => $blog_username,
		"blog_title" => $latest["title"],
		"blog_content" => $formatted_blog_content,
		"blog_img" => $latest["image"],
		"blog_last_edit_date" => $latest["date_last_modified"],
	]);
	
?>