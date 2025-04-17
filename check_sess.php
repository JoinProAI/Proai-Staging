<?php
include_once "includes/config.php";

if(!isset($_SESSION['PRO_USER_ID']) || intval($_SESSION['PRO_USER_ID'])== 0) {
	echo "window.location='".SITE_URL."/logout.php';";
}
else  {

echo "1==1";
}
?>