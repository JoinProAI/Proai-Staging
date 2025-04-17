<?php
include "includes/config.php";

$max_plan=5;

try {
	$auth0->exchange(SITE_URL."/callback");
} catch (Exception $e) {
	unset($_SESSION['PRO_USER_ID']);
	unset($_SESSION['PRO_USER_NM']);
	unset($_SESSION['PRO_USER_EMAIL']);
	unset($_SESSION['PRO_PLAN_ID']);
	unset($_SESSION['MODAL']);
	unset($_SESSION['modal']);
	unset($_SESSION['plan']);

	unset($_SESSION['CHECKOUT_LOGIN']);
	unset($_SESSION['PAYMENT_URL']);
	session_destroy();
	header("Location: ".$auth0->logout(SITE_URL."/login"));
	exit;
}

$session = $auth0->getCredentials();
$name = $session->user['nickname'];
$email = $session->user['email'];
$first_name = $session->user['family_name'];
$last_name = $session->user['given_name'];

$login_type_arr = array();
$login_type_arr = explode("|",$session->user["sub"]);
$login_type = $login_type_arr[0];
	
	$db->where("user_email",$email);
	$res = $db->getOne("user_master");

	if($db->count) {
	
		$_SESSION['PRO_USER_ID']= $res['user_id'];
		$_SESSION['PRO_USER_NM']= $res['full_name'];
		$_SESSION['PRO_USER_EMAIL']=$res['user_email'];
		$_SESSION['PRO_MAX_PLN'] = isset($res['max_plan']) ? $res['max_plan'] : $max_plan; 

		$db->where("user_id",$_SESSION['PRO_USER_ID']);
		$db->where("status","Y");
		$rr = $db->getOne("company_master");
		if($db->count){
			$_SESSION['PRO_COMPANY_ID']= $rr['company_mast_id'];
			$_SESSION['PRO_COMPANY_NM'] = $rr['company_name'];
		}
		else {
			$db->where("user_id", $_SESSION['PRO_USER_ID']);
			$db->orderBy("company_mast_id","desc");
			$rr = $db->getOne("company_master");
			if($db->count){
				$_SESSION['PRO_COMPANY_ID']=$rr['company_mast_id'];
				$_SESSION['PRO_COMPANY_NM']=$rr['company_name'];
			}
		}

			$_SESSION['PRO_PLAN_ID'] =NOPLANID;

			$db->where("company_id",$_SESSION['PRO_COMPANY_ID']);
			$r1 =$db->getOne("plan_master");

			if($db->count){				
				$_SESSION['PRO_PLAN_ID']=$r1['plan_id'];
			}

		$data = array(				
			"login_type"=>$login_type
		);				
		$db->where("user_email",$res['user_email']);			
		$db->update("user_master", $data);

	} else {
		$insert = array(
		"full_name" => $name,
		"user_email" => $email,
		"first_name"=>$first_name,
		"last_name"=>$last_name,
		"login_type"=>$login_type,
		);		

		if($db->insert("user_master",$insert))
		{
			$user_id = $db->getInsertId();

			$_SESSION['PRO_USER_ID']=$user_id;
			$_SESSION['PRO_USER_NM']=$name;
			$_SESSION['PRO_USER_EMAIL']=$email;
			$_SESSION['PRO_MAX_PLN'] = $max_plan;		
			
			if($_SESSION['modal']) {
			  $curl = curl_init();
			  curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "PUT",
			  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"1cc6924f-d844-4b38-9a40-9ea18b7eab12\"],\"contacts\":[{\"email\":\"". $email ."\"}]}",
			  CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".SENDGRID_API_KEY,
				"content-type: application/json"
				  ),
				));

				$response1 = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
			}
		}
		else {
			header("Location: ".SITE_URL."/login_error");
			exit;
		}
	}

if(isset($_SESSION['modal']) && $_SESSION['modal'] === true) {

	$db->where("md5(plan_id)", $_SESSION['plan']);
	$db->update("plan_master",array("user_id"=>$_SESSION['PRO_USER_ID']));

	if(!isset($_SESSION['company_id'])){

		$db->where("md5(plan_id)", $_SESSION['plan']);
		$r = $db->getOne("plan_master");

		$_SESSION['company_id']=$r['company_id'];
	}
	$db->where("company_mast_id", $_SESSION['company_id']);
	$db->update("company_master",array("user_id"=>$_SESSION['PRO_USER_ID']));	
	
	unset($_SESSION['modal']);
?>
<script src="<?php echo SITE_URL; ?>/assets/js/jquery.js"></script>
<script>
opener.window.location.href="<?php echo SITE_URL; ?>/modify_plan?plan=<?php echo $_SESSION['plan']; ?>#executive-summary";
window.close();
</script>
<?php
}
else
{
	if(isset($_SESSION['CHECKOUT_LOGIN']) && $_SESSION['CHECKOUT_LOGIN'] === true) {

		$payment_url = $_SESSION['PAYMENT_URL'];

		unset($_SESSION['PAYMENT_URL']);
		unset($_SESSION['CHECKOUT_LOGIN']);
?>
<script>
opener.window.location.href="<?php echo $payment_url; ?>";
window.close();
</script>
<?php
	exit;
	}
  
	header("Location: ".$_ENV['AUTH0_BASE_URL']);
	exit;
}

?>