<?php

//Validation of user details for registration, if theres an error then send redirect to register page
//with entered details as the values (to avoid user having to retype things, except for pass)
//if wrong then it renders the register page, but with certain classes for errored inputs to
//display

	require 'twig_init.php';
	$twig = init();

	// example of turning some fields border red to indicate invalid data was submitted
	echo $twig->render("register.html.twig", [
		"username_input" => "is-invalid",
		"email_input" => "is-invalid",
	]);

?>