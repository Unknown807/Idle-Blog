<?php
	session_start();
	
	if (!isset($_SESSION["uid"]) || !isset($_SESSION["currently_viewed_blog"])) {
		header("Location: index.php");
		exit;
	}
	
	require "connect.php";
	
	$dbhandle = getConnection();
	
	$sql = "DELETE FROM posts WHERE title = :title AND uid = :uid";
	$query = $dbhandle->prepare($sql);
	$params = [
		"title" => $_SESSION["currently_viewed_blog"],
		"uid" => $_SESSION["uid"],
	];
	$query->execute($params);
	
	unset($_SESSION["currently_viewed_blog"]);
	
	header("Location: profile.php?username=".$_SESSION["username"]);

?>