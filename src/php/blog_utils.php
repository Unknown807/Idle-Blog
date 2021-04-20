<?php
	
	function getLatestBlog($dbhandle, $uid = false) {
		$sql = "SELECT * FROM posts ORDER BY date_last_modified DESC LIMIT 1";
		$params = [];
		if ($uid) {
			$sql = "SELECT * FROM posts WHERE uid = :uid ORDER BY date_last_modified DESC LIMIT 1";
			$params = ["uid" => $uid];
		}
		
		$query = $dbhandle->prepare($sql);
		$query->execute($params);
		$result = $query->fetch();
		
		return $result;
	}
	
	function formatBlogContent($content) {
		
		$new_content = "";
		
		$lines = explode("\n", $content);

		foreach ($lines as $line) {

			if (str_contains($line, "TITLE_START(") && str_contains($line, ")TITLE_END")) {
				
				$temp = str_replace("TITLE_START(", "<h5>", $line);
				$temp = str_replace(")TITLE_END", "</h5>", $temp);
				
				$new_content .= $temp;
				
			} else if (!empty(trim($line))) {
			
				$new_content .= "<p>".$line."</p>";
				
			}
		}

		return $new_content;
		
	}
	
	function getBlogs($dbhandle, $blogTitle, $uid = false) {
		
		$sql = "SELECT pid, title, date_last_modified FROM posts WHERE title LIKE :title LIMIT 10";
		$params = ["title" => "%".$blogTitle."%"];
		
		if ($uid) {
			$sql = "SELECT pid, title, date_last_modified 
					FROM posts WHERE title LIKE :title AND uid = :uid LIMIT 10";
			
			$params = ["title" => "%".$blogTitle."%", "uid" => $uid,];
		}
		
		$query = $dbhandle->prepare($sql);
		$query->execute($params);
		$results = $query->fetchAll();
		
		return $results;
	}
	
	function formatRenderOptions($dbhandle, $uid, $userInfo = false) {
		$loggedIn = isset($_SESSION["uid"]);
		$latest = getLatestBlog($dbhandle, $uid);
		
		$err = false;
		if (empty($latest)) {
			$err = true;
		} else {
			$formatted_blog_content = formatBlogContent($latest["content"]);
		}
		
		return [
			"search_script" => "search_user_blog.php",
			"pfp_path" => $loggedIn ? $_SESSION["pfp"] : "",
			"username" => $loggedIn ? $_SESSION["username"] : "",
			"joined" => $loggedIn ? $_SESSION["joined"] : "",
			"email" => $loggedIn ? $_SESSION["email"] : "",
			
			"other_joined" => $userInfo ? $userInfo["joined"] : "",
			"other_pfp_path" => $userInfo ? $userInfo["pfp"] : "",
			
			"other_username" => $userInfo ? $userInfo["username"] : "",
			"blog_img" => $err ? "" : $latest["image"],
			"blog_title" => $err ? "" : $latest["title"],
			"blog_content" => $err ? "" : $formatted_blog_content,
			"blog_last_edit_date" => $err ? "" : $latest["date_last_modified"],
			
			"error" => $err,
		];
	}


?>