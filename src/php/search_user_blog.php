<?php
	session_start();

	require "validation.php";
	require "blog_utils.php";
	require "twig_init.php";
	
	$twig = init();

	if (!isset($_SESSION["currently_viewed_user"])) {
		header("Location: index.php");
		exit;
	}
	
	$current_uid = $_SESSION["currently_viewed_user"];
	$title = sanitise($_GET["blogTitle"]);
	
	if (str_contains($title, "@")) {
		$user = str_replace("@", "", $title);
		header("Location: profile.php?username=".$user);
		exit;
	}
	
	require "connect.php";
	
	$dbhandle = getConnection();
	
	$sql = "SELECT * FROM users WHERE uid = :uid";
	$params = ["uid" => $current_uid];
	$query = $dbhandle->prepare($sql);
	$query->execute($params);
	$result = $query->fetch();
	
	if (!isset($_GET["blogTitle"]) || empty(str_replace(['"',"'"], "", $_GET["blogTitle"]))) {
		$render_options = formatRenderOptions($dbhandle, $current_uid, $result);
		$render_options["title_error_msg"] = "Blank blog title";
		echo $twig->render($_SESSION["returning_template"].".html.twig", $render_options);
		exit;
	}
	
	$loggedIn = isset($_SESSION["uid"]);

	$userBlogs = getBlogs($dbhandle, $title, $current_uid);
	$render_options = formatRenderOptions($dbhandle, $current_uid, $result);
	
	if (empty($userBlogs)) {
		$render_options["no_blogs"] = true;
	} else {
		$render_options["found_blogs"] = true;
		$blog_list = "";
		foreach($userBlogs as $blog) {
			$section = "<a href='displayBlog.php?blog=".$blog["pid"]."' ";
			$section .= "class='list-group-item list-group-item-action flex-column align-items-start'>";
			$section .= "<div class='d-flex w-100 justify-content-between'>";
			$section .= "<h5 class='mb-1'>".$blog["title"]."</h5>";
			$section .= "<small>".$blog["date_last_modified"]."</small></div></a>";
			
			$blog_list .= $section;
		}
		
		$render_options["blog_list"] = $blog_list;
	}
	
	echo $twig->render($_SESSION["returning_template"].".html.twig", $render_options);
	
?>