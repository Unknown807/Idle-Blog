<?php

	require "blog_utils.php";
	
	function searchAndDisplayBlogs($searchAll, $dbhandle, $twig, $title) {
		
		if (str_contains($title, "@")) {
			$user = str_replace("@", "", $title);
			header("Location: profile.php?username=".$user);
			exit;
		}
		
		if ($searchAll) {
			$render_options = formatRenderOptions($dbhandle, false);
			$render_options["search_script"] = "search_all_blogs.php";
			$params = ["uid" => $render_options["other_username"]];
			$template = "index";
		} else {
			$current_uid = $_SESSION["currently_viewed_user"];
			$params = ["uid" => $current_uid];
			$template = $_SESSION["returning_template"];
		}
		
		$sql = "SELECT * FROM users WHERE uid = :uid";
		$query = $dbhandle->prepare($sql);
		$query->execute($params);
		$result = $query->fetch();
		
		if(!$searchAll) {
			$render_options = formatRenderOptions($dbhandle, $current_uid, $result);
		}
		
		if (empty(str_replace(['"', "'"], "", $title))) {
			if ($searchAll) {
				$render_options["other_username"] = $result["username"];
				$render_options["title_error_msg"] = "Blank blog title";
			} else {
				$render_options["title_error_msg"] = "Blank blog title";
			}

			echo $twig->render($template.".html.twig", $render_options);
			exit;
		}
		
		if ($searchAll) {
			$returnedBlogs = getBlogs($dbhandle, $title);
		} else {
			$returnedBlogs = getBlogs($dbhandle, $title, $current_uid);
		}
		
		if (empty($returnedBlogs)) {
			$render_options["no_blogs"] = true;
		} else {
			$render_options["found_blogs"] = true;
			$blog_list = "";
			foreach($returnedBlogs as $blog) {
				$section = "<a href='display_blog.php?blog=".$blog["pid"]."' ";
				$section .= "class='list-group-item list-group-item-action flex-column align-items-start'>";
				$section .= "<div class='d-flex w-100 justify-content-between'>";
				$section .= "<h5 class='mb-1'>".$blog["title"]."</h5>";
				$section .= "<small>".$blog["date_last_modified"]."</small></div>";
				
				$content = str_replace(["TITLE_START(", ")TITLE_END"], "", $blog["content"]);
				$content = substr($content, 0, 200);
				
				$section .= "<p class='mb-1'>".$content."...</p>";
				
				if ($searchAll) {
					$sql = "SELECT username FROM users WHERE uid = :uid";
					$query = $dbhandle->prepare($sql);
					$query->execute(["uid" => $blog["uid"]]);
					$username = $query->fetch()["username"];
					
					$section .= "<small>By ".$username."</small>";
				}
				
				$section .= "</a>";
				
				$blog_list .= $section;
			}
			
			$render_options["blog_list"] = $blog_list;
		}
		
		echo $twig->render($template.".html.twig", $render_options);
		
	}


?>























































































