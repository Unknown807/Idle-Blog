<?php

	if (isset($_COOKIE["currentlyViewedUser"])) {
		echo "Cookie is set";
	} else {
		echo "Not Set";
	}

	// this scripts receives blotTitle GET var and will use
	// currentlyViewedUser cookie to search the user's blog's
	// for the matching blogTitle (after sanitising it)
?>