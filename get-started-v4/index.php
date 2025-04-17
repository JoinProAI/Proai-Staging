<?php
include_once "includes/config.php";
$MODE = "plan_create";
if(isset($_SESSION['PRO_USER_ID']) && intval($_SESSION['PRO_USER_ID']) > 0) {
	header("Location: ".SITE_URL."/dashboard/");
	exit;
}
else {
	include_once __DIR__."/header-nologin.php";
	$header = ob_get_clean();
	$bodyjs ="";
	$header = str_replace("<!--BODYJS-->",$bodyjs,$header);	
	echo $header;
	include "dashboard-nologin.php";
	include_once __DIR__."/footer-nologin.php";
}

?>
