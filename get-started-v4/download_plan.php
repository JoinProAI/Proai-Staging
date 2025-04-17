<?php
include_once "includes/config.php";
extract($_REQUEST);
  
$paypal_payment_tbl = "payments";
$stripe_payment_tbl = "stripe_transactions";

	$user_id = $_SESSION["PRO_USER_ID"];
	$db->where("user_id",$user_id);
	$res1 = $db->get("payments");
	$paid = false;
	if($db->count) {
		
		foreach($res1 as $res)
		{
			if($res['payment_status']=='approved'){
				$paid=true;
				break;
			}
		}
	}
	else {

		$db->where("user_id", $user_id);
		$res1 = $db->get("stripe_transactions");

		
		if($db->count){
			foreach($res1 as $res) {
				if($res['payment_status']=='succeeded') {
					$paid = true;
					break;
				}
			}
		}
	}

$res  = $db->get("openai_prompt");
$prompt_count = $db->count;
$db->where("md5(plan_id)",$plan);
$res1 = $db->get("openai_output");
$output_count = $db->count;

if($paid){

	if($output_count == $prompt_count){
		$db->where("md5(plan_id)",$plan);
		$res = $db->getOne("plan_master");
		$google_doc_id = $res['google_doc_id'];

		$google_doc = "https://docs.google.com/document/d/".$google_doc_id."/edit";
		header("Location: ".$google_doc);
		exit;
	}
	else
	{
		$modify_url = SITE_URL."/modify_plan?plan=".$plan;
		include_once "header.php";
?>
	<div class="message_block">
		<div class="message_content">
			<img src="assets/image/chat-gpt-smile-sad.png" alt="">
			<div class="message_right">			
				<h4>ERROR</h4>
				<p>Your plan is not ready. Please completed all require <br> input to generate and ready your plan.</p>
				<p>You can continue your plan from <a href="<?php echo $modify_url; ?>">here</a></p>
			</div>		
		</div>
	</div>
	<?php
		include_once "footer.php";
	}
}
else
{
	$modify_url = SITE_URL."/modify_plan.php?plan=".$plan;
include_once "header.php";
if($output_count != $prompt_count) {
?>
<div class="message_block">
	<div class="message_content">
	<img src="assets/image/chat-gpt-smile-sad.png" alt="">
		<div class="message_right">		
			<h4>ERROR</h4>
			<p>Your plan is not ready. Please completed all require <br> input to generate and ready your plan.</p>
				<p>You can continue your plan from <a href="<?php echo $modify_url; ?>">here</a></p>
		</div>
</div>
</div>
<?php
}
else {
$checkout_url = SITE_URL."/../subscribe-2.php?plan=".$plan;
?>
<div class="message_block">
	<div class="message_content">
		<img src="assets/image/chat-gpt-smile-sad.png" alt="">
		<div class="message_right">		
			<h4>ERROR</h4>
			<p>You haven't paid yet. Please Complete <br> <a href="<?php echo $checkout_url; ?>">Checkout</a> to download Plan.</p>
		</div>	
	</div>
</div>
<?php
}
include_once "footer.php";
}
?>