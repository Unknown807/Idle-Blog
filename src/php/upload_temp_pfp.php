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
		removeLastImage("../resources/temp_user_pfps/".$_SESSION["uid"]."pfp_*.*");
		$response = uploadImage("../resources/temp_user_pfps/", 100, 100);
	}
	
	echo $response;

?>