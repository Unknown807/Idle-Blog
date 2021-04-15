<?php

	function getConnection() {
		try {
			$dbhandle = new PDO("mysql:host=localhost;dbname=sitedb", "root");
			return $dbhandle;
		} catch (PDOException $e) {
			die("DB Connection Error: ".$e->getMessage());
		}
	}

?>