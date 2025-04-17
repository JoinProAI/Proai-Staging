<?php
	error_reporting(E_ALL & ~E_WARNING);
	include_once "includes/config.php";
	extract($_REQUEST);
	$db->where("md5(plan_id)",$plan);
	$res = $db->getOne("plan_master");

	if($db->count){

		$doc_id = $res[''];

		exit;
	}

	echo "1==1";

?>