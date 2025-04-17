<?php
	include_once "includes/config.php";
	
	
	unset($_SESSION['PRO_USER_ID']);
	unset($_SESSION['PRO_USER_NM']);
	unset($_SESSION['PRO_USER_EMAIL']);
	unset($_SESSION['MODAL']);
	unset($_SESSION['modal']);
	unset($_SESSION['plan']);

	unset($_SESSION['CHECKOUT_LOGIN']);
	unset($_SESSION['PAYMENT_URL']);
	session_destroy();

	
	header("Location: ".$auth0->logout($_ENV['AUTH0_BASE_URL']));
	exit;


?>