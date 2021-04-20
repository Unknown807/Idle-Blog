<?php

	session_start();
	require 'twig_init.php';
	$twig = init();
	
	$loggedIn = isset($_SESSION["uid"]);
	

	if (!isset($_GET["username"]) || empty(str_replace(['"',"'"], "", $_GET["username"]))) { // No username to search for
		
		echo $twig->render("profile_error.html.twig", [
				"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
				"username" => $loggedIn ? $_SESSION["username"] : "",
				"error_msg" => "Blank Username",
		]);
		exit;
	}
	
	require "validation.php";
	
	$username = $_GET["username"];
	$username = sanitise(str_replace(['"',"'"], "", $username));
	
	// Check if username actually exists before, if not then render error page
	
	require "connect.php";
	require "blog_utils.php";
	
	$dbhandle = getConnection();
	
	$sql = "SELECT * FROM users WHERE username = :username";
	$query = $dbhandle->prepare($sql);
	$params = ["username" => $username,];
	$query->execute($params);
	$result = $query->fetch();
	if (!$result) {
		echo $twig->render("profile_error.html.twig", [
			"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
			"username" => $loggedIn ? $_SESSION["username"] : "",
			"error_msg" => "User Doesn't Exist",
		]);
		exit;
	}
	
	$_SESSION["currently_viewed_user"] = $result["uid"];
	
	// get latest blog post by the user
	
	$latest = getLatestBlog($dbhandle, $result["uid"]);
	
	$err = false;
	if (empty($latest)) {
		$err = true;
	} else {
		$formatted_blog_content = formatBlogContent($latest["content"]);	
	}
	
	if ($loggedIn && ($_SESSION["username"] == $username)) {
		// username to search is the same as logged in username (your profile)
		$_SESSION["returning_template"] = "profile_personal";
	} else {
		$_SESSION["returning_template"] = "profile_other";
	}
	
	echo $twig->render($_SESSION["returning_template"].".html.twig", [
		"search_script" => "search_user_blog.php",
		"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
		"username" => $loggedIn ? $_SESSION["username"] : "",
		"joined" => $_SESSION["joined"],
		"email" => $_SESSION["email"],
		
		"other_joined" => $result["joined"],
		"other_pfp_path" => $result["pfp"],
		
		"other_username" => $result["username"],
		"blog_title" => $err ? "" : $latest["title"],
		"blog_content" => $err ? "" : $formatted_blog_content,
		"blog_img" => $err ? "" : $latest["image"],
		"blog_last_edit_date" => $err ? "" : $latest["date_last_modified"],
		
		"error" => $err,
		
	]);

?>