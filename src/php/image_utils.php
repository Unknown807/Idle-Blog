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

	function validateExtension($filename) {
		$imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
		$imageFileType = strtolower($imageFileType);
		
		$valid_extensions = array("jpg", "jpeg", "png");
		if (in_array(strtolower($imageFileType), $valid_extensions)) {
			return $imageFileType;
		}
		return false;
	}

	function uploadImage($path, $str_infix, $nwidth, $nheight) {
		
		$filename = $_SESSION["uid"].$str_infix."_".mt_rand().".".pathinfo($_FILES["userImg"]["name"], PATHINFO_EXTENSION);
		
		$ext = validateExtension($filename);
		
		if ($ext) {
			resizeImage($_FILES["userImg"]["tmp_name"], $path, $filename, $ext, $nwidth, $nheight);
			return $path.$filename;
		}
		
		return false;
		
	}

	function removeLastImage($path) {
		$files = glob($path);
		if (count($files) > 0) {
			unlink($files[0]);
		}
	}
	
	function attemptImageUpload($str_infix, $nwidth, $nheight) {
		if ($_FILES["userImg"]["error"] == UPLOAD_ERR_OK) {
			removeLastImage("../resources/temp_images/".$_SESSION["uid"].$str_infix."_*.*");
			$response = uploadImage("../resources/temp_images/", $str_infix, $nwidth, $nheight);
		} else {
			$response = false;
		}
		
		return $response;
	}

?>