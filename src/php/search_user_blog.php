<?php
	session_start();

	if (!isset($_SESSION["currently_viewed_user"])) {
		
	}

	

	// this scripts receives blotTitle GET var and will use
	// currentlyViewedUser session var to search the user's blog's
	// for the matching blogTitle (after sanitising it)
?>