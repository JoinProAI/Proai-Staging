<?php
	include_once __DIR__."/includes/config.php";

	extract($_REQUEST);

	$header  = "MIME-Version: 1.0\r\n";
	$header .= "From: ProAI Admin <proai@business-plans.com>\r\n";
	$header .= "Content-type: text/html; charset: utf8\r\n";

	$user_id=0;
	$response = array();
	$err = false;
	$msg = array();






	$val = new Validation();

switch($step) {

	case 'storeFundingdata':
			$insert_data = array(
				"user_id"=>intval(@$_SESSION['PRO_USER_ID']),
				"plan_id"=>intval(@$_REQUEST['plan_id']),
				"jsondata"=>json_encode($_REQUEST)
			);
	
	
			$db->where("md5(auto_id)", $_REQUEST['pid']);
			$db->getOne("business_funding_master");
	
		if($db->count > 0)
		{
			$db->where("md5(auto_id)",$pid);
			unset($_REQUEST['pid']);
	
			if($db->update("business_funding_master", $insert_data)) {
				$response  = array(
					"err"      => false,
					"redirect" => "loadInvestorList('" . md5($pid) . "');",
					"msg"      => "",
					"data"     => array()
				);
			}
			else {
					$response =	array(
					'err'=>true,
					'redirect'=>"1==1",
					'msg'=>"Something wrong with Database Storage",
					"data"=>array()
					);
	
			}
		}
		else
		{
			if($db->insert("business_funding_master", $insert_data)) {
	
				$pid = md5($db->getInsertId());
			
				$response  = array(
					"err"      => false,
					"redirect" => "loadInvestorList('" . $pid. "');",
					"msg"      => "",
					"data"     => array("pid"=>$pid)
				);
	
			}
			else {
				
				$response =	array(
				'err'=>true,
				'redirect'=>"1==1",
				'msg'=>"Something wrong with Database Storage",
				"data"=>array()
				);
	
			}
		}
	


		break;
	
}
echo json_encode($response);


function validateInput($input){


	return true;

}

function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}


?>