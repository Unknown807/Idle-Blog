<?php
	
	function getLatestBlog($dbhandle, $uid = false) {
		$sql = "";
		$params = [];
		if ($uid == false) {
			$sql = "SELECT * FROM posts ORDER BY date_last_modified DESC LIMIT 1";
		} else {
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


?>