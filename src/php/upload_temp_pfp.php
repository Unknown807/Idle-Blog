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
	
	echo attemptImageUpload("pfp", 100, 100);

?>