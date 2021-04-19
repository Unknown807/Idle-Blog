<?php
	session_start();
	
	if (!isset($_SESSION["uid"])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	
	if ($_SERVER["REQUEST_METHOD"] != "POST") {
		header("Location: index.php");
		exit;
	}
	
	require "image_utils.php";
	
	echo attemptImageUpload("blog_image", 1000, 450);

?>