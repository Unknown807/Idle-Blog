<?php
	
	require "image_utils.php";
	
	function createOrEditBlog($edit, $dbhandle, $twig, $blogTitle, $blogContent, $blogInfo=false) {
		
		$template = $edit? "post_edit" : "post_creation";
		
		$title_msg = false;
		if (empty($blogTitle)) {
			$title_msg = "Blog title cannot be left empty";
		} else {
			$sql = "SELECT pid FROM posts WHERE title = :title";
			$query = $dbhandle->prepare($sql);
			$params = ["title" => $edit ? $_SESSION["currently_viewed_blog"] : $blogTitle];
			$query->execute($params);
			$result = $query->fetch();
			
			if ($result){
				if ($edit) {
					if ($result["pid"] != $blogInfo["pid"]) {
						$title_msg = "One of your blogs already has that title";
					}
				} else {
					$title_msg = "One of your blogs already has that title";
				}
			}
		}
		
		$content_msg = false;
		if (empty($blogContent)) {
			$content_msg = "Blog cannot be left empty";
		} else if (strlen($blogContent) < 100) {
			$content_msg = "You need to write a little more than that";
		}
		
		if ($title_msg || $content_msg) {
			echo $twig->render($template.".html.twig", [
				"pfp_path" => $_SESSION["pfp"],
				"username" => $_SESSION["username"],
				
				"blog_title" => $blogTitle,
				"blog_title_error_msg" => $title_msg ? $title_msg : "",
				
				"blog_content" => $blogContent,
				"blog_content_error_msg" => $content_msg ? $content_msg : "",
			]);
			exit;
		}
		
		$image = $edit ? $blogInfo["image"] : "../resources/blog_images/default.jpg";
		if ($_FILES["userImg"]["error"] == UPLOAD_ERR_OK) {
			
			removeLastImage("../resources/temp_images/".$_SESSION["uid"]."blog_image_*.*");
			$image = uploadImage("../resources/blog_images/", $blogTitle, 1000, 450);
		}
	
		$params = [
			"title" => $blogTitle,
			"content" => $blogContent,
			"image" => $image,
		];
		
		if ($edit) {
			$sql = "UPDATE posts
			SET title = :title, content = :content, image = :image, 
			date_last_modified = :date 
			WHERE pid = :pid";
			
			$params["pid"] = $blogInfo["pid"];
			
			$time_now = new DateTime("now");
			$time_now->setTimeZone(new DateTimeZone("Europe/London"));
			$params["date"] = $time_now->format("Y-m-d h:m:s");
			
		} else {
			$sql = "INSERT INTO posts (uid, title, content, image) VALUES
					(:uid, :title, :content, :image)";
					
			$params["uid"] = $_SESSION["uid"];
		}
		
		$query = $dbhandle->prepare($sql);
		$query->execute($params);
		
		header("Location: profile.php?username=".$_SESSION["username"]);
	}

?>