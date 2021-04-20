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
	
	require "connect.php";
	
	$dbhandle = getConnection();
	
	$sql = "SELECT * FROM users WHERE uid = :uid";
	$params = ["uid" => $current_uid];
	$query = $dbhandle->prepare($sql);
	$query->execute($params);
	$result = $query->fetch();
	
	if (!isset($_GET["blogTitle"]) || empty(str_replace(['"',"'"], "", $_GET["blogTitle"]))) {
		//header("Location: profile.php?username=".$result["username"]);
		$render_options = refreshPage($dbhandle, $current_uid, $result);
		$render_options["title_error_msg"] = "Blank blog title";
		echo $twig->render($_SESSION["returning_template"].".html.twig", $render_options);
		exit;
	}
	
	$loggedIn = isset($_SESSION["uid"]);

	$userBlogs = getBlogs($dbhandle, sanitise($_GET["blogTitle"]), $current_uid);
	
	if (empty($userBlogs)) {
		
	} else {
	
	}
	

	// this scripts receives blogTitle GET var and will use
	// currentlyViewedUser session var to search the user's blog's
	// for the matching blogTitle (after sanitising it)
?>