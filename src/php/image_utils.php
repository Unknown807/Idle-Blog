<?php

	function resizeImage($source, $dest, $image_name, $ext, $nwidth, $nheight) {
		$image = null;
		if ($ext == "png") {
			$image = imagecreatefrompng($source);
			$image = imagescale($image, $nwidth, $nheight);
			imagepng($image, $dest.$image_name);
		} else {
			$image = imagecreatefromjpeg($source);
			$image = imagescale($image, $nwidth, $nheight);
			imagejpeg($image, $dest.$image_name);
		}
		
	}

	function uploadImage($path, $nwidth, $nheight) {
		
		$filename = $_SESSION["uid"]."pfp_".mt_rand().".".pathinfo($_FILES["userImg"]["name"], PATHINFO_EXTENSION);
		
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
		$imageFileType = strtolower($imageFileType);
		
		$valid_extensions = array("jpg", "jpeg", "png");
		if (in_array(strtolower($imageFileType), $valid_extensions)) {
			resizeImage($_FILES["userImg"]["tmp_name"], $path, $filename, $imageFileType, $nwidth, $nheight);
		}
		
		return $path.$filename;
	}

	function removeLastImage($path) {
		$files = glob($path);
		if (count($files) > 0) {
			unlink($files[0]);
		}
	}

?>