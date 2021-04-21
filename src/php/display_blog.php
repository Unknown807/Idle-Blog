<?php
	session_start();
	
	require "connect.php";
	require "twig_init.php";
	require "validation.php";
	require "blog_utils.php";
	
	if (!isset($_GET["blog"]) || 
		empty(str_replace(['"', "'"], "", $_GET["blog"])) ||
		!is_numeric($_GET["blog"])) {
			
		header("Location: index.php");
		exit;
	}
	
	$pid = (int) sanitise($_GET["blog"]);
	$dbhandle = getConnection();
	$twig = init();
	$loggedIn = isset($_SESSION["uid"]);
	
	$sql = "SELECT * FROM users u JOIN posts p ON p.pid = :pid AND p.uid = u.uid; ";
	$query = $dbhandle->prepare($sql);
	$params = ["pid" => $pid];
	$query->execute($params);
	$result = $query->fetch();
	
	if (!$result) {
		echo $twig->render("profile_error.html.twig", [
			"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
			"username" => $loggedIn ? $_SESSION["username"] : "",
			"error_msg" => "That blog doesn't exist",
		]);
		exit;
	}
	
	$joined = $opfp = $ousername = $ojoined = $email = "";
	$template = "profile_other";
	$formatted_blog_content = formatBlogContent($result["content"]);
	
	$_SESSION["currently_viewed_user"] = $result["uid"];
	
	if ($loggedIn && ($_SESSION["uid"] == $result["uid"])) {
		
		$_SESSION["currently_viewed_blog"] = $result["title"];
		
		$template = "profile_personal";
		$ousername = $_SESSION["username"];
		$joined = $_SESSION["joined"];
		$email = $_SESSION["email"];
	} else {
		$opfp = $result["pfp"];
		$ousername = $result["username"];
		$ojoined = $result["joined"];
	}
	
	echo $twig->render($template.".html.twig", [
		"search_script" => "search_user_blog.php",
		"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
		"username" => $loggedIn ? $_SESSION["username"] : "",
		"joined" => $joined,
		"email" => $email,
		
		"other_joined" => $ojoined,
		"other_pfp_path" => $opfp,
		"other_username" => $ousername,
		
		"blog_img" => $result["image"],
		"blog_title" => $result["title"],
		"blog_content" => $formatted_blog_content,
		"blog_last_edit_date" => $result["date_last_modified"],
		
	]);
	
?>