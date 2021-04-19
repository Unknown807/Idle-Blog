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
	
	$response = false;
	
	require "image_utils.php";
	
	if (!($_FILES["userImg"]["error"] > UPLOAD_ERR_OK)) {
		removeLastImage("../resources/temp_images/".$_SESSION["uid"]."blog_image_*.*");
		$response = uploadImage("../resources/temp_images/", "blog_image", 1000, 450);
	}
	
	echo $response;

?>