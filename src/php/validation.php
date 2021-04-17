<?php

	function sanitise($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	
	function checkUsernameExists($dbhandle, $username) {
		$sql = "SELECT uid FROM users WHERE username = :username";
		$query = $dbhandle->prepare($sql);
		$params = ["username" => $username,];
		$query->execute($params);
		$result = $query->fetch();
		if ($result) {
			return "Username already exists";
		}
		return false;
	}
	
	function validateUsername($dbhandle, $username) {
		$umsg = false;
		
		if (empty($username)) {
			$umsg = "Username cannot be left blank";
		} else {
			$umsg = checkUsernameExists($dbhandle, $username);
		}
		
		return $umsg;
	}
	
	function validateEmail($email) {
		$emsg = false;
		
		if (empty($email)) {
			$emsg = "Email cannot be left blank";
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emsg = "Invalid email format";
		}
		
		return $emsg;
	}
	
	function validatePassword($password, $confirmPassword) {
		$pmsg = false;
		
		if (empty($password) || empty($confirmPassword)) {
			$pmsg = "Password cannot be left blank";
		} else if ($password != $confirmPassword) {
			$pmsg = "Password's have to be the same";
		}
		
		return $pmsg;
	}
	
?>









