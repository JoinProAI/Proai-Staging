<?php
	include_once "includes/config.php";
	include_once "includes/dbConnect.php";

	extract($_REQUEST);
	$MODE = "thankyou";
	$payment_id = $statusMsg = '';
	$status = 'error';

// Check whether stripe checkout session is not empty
if(!empty($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $marstatus = isset($_GET['marstatus']) ? $_GET['marstatus'] : "";
    $paymenttable = "";
    $marstatusurl = "";
    if($marstatus == "mar"){
        $paymenttable = "stripe_transactions_mar";
        $marstatusurl = "&marstatus=mar";
        $marstatusurl .="&reptype=".$_GET["reptype"];
    }
    else{
        $paymenttable = "stripe_transactions";
    }
	$coupon_id= 0;
    if($_GET["coupon"]!=""){
        $coupon_id = $_GET["coupon"];
    }
    
    $sqlQ = "SELECT * FROM $paymenttable WHERE stripe_checkout_session_id = ?";    
	$stmt = $db1->prepare($sqlQ); 
	$stmt->bind_param("s", $db_session_id);
	$db_session_id = $session_id;
	$stmt->execute();
	$result = $stmt->get_result();

    if($result->num_rows > 0){
		$transData = $result->fetch_assoc();
        $payment_id = $transData['id'];
		$transactionID = $transData['txn_id'];
		$paidAmount = $transData['paid_amount'];
		$paidCurrency = $transData['paid_amount_currency'];
		$payment_status = $transData['payment_status'];		
		$customer_name = $transData['customer_name'];
		$customer_email = $transData['customer_email'];		
		$status = 'success';
		$statusMsg = 'Your Payment has been Successful!';
    }else{
        require_once 'stripe-php/init.php';
        
        $STRIPE_API_KEY = STRIPE_API_KEY;

        $stripe = new \Stripe\StripeClient($STRIPE_API_KEY);       
		$checkout_session = false;
        try {
			$checkout_session = $stripe->checkout->sessions->retrieve($_REQUEST['session_id']);
        } catch(Exception $e) { 
            $api_error = $e->getMessage(); 
        }
			try {
                $invoice = $stripe->invoices->retrieve($checkout_session->invoice);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $api_error = $e->getMessage();
            }

        if(empty($api_error) && $checkout_session) {
            $customer_details = $checkout_session->customer_details;			
            try {
                $paymentIntent = $stripe->paymentIntents->retrieve($invoice->payment_intent);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $api_error = $e->getMessage();
            }
          
			$productName = (isset($checkout_session->metadata->name)) ? $checkout_session->metadata->name : "";
			$productID = (isset($checkout_session->metadata->plan_id)) ? $checkout_session->metadata->plan_id : "";
            $key = (isset($checkout_session->metadata->key)) ? $checkout_session->metadata->key : "";
            
            if(empty($api_error) && $paymentIntent){
                if(!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
                    $transactionID = $paymentIntent->id;
                    $paidAmount = $paymentIntent->amount;
                    $paidAmount = ($paidAmount/100);
                    $paidCurrency = $paymentIntent->currency;
                    $payment_status = $paymentIntent->status;                    
                    $customer_name = $customer_email = '';
                    if(!empty($customer_details)){
                        $customer_name = !empty($customer_details->name)?$customer_details->name:'';
                        $customer_email = !empty($customer_details->email)?$customer_details->email:'';
                    }
		try {  
			$subscriptionData = $stripe->subscriptions->retrieve($checkout_session->subscription); 
		}catch(Exception $e) {  

			$api_error = $e->getMessage();  
		}

        $r = explode("--", $_REQUEST['key']);
        $_REQUEST['plan'] = $r[0];
        $plan = $_REQUEST['plan'];
        $plan_type = $r[1];
        $key_text = $plan_type;
        $plan_type = decryptText($plan_type);
        $sub = getSubscription($plan_type);
		$paidAmount = $paymentIntent->amount;
		$paidAmount = ($paidAmount/100);
		$paidCurrency = $paymentIntent->currency;
		$plan_obj = $subscriptionData->plan;
		$plan_price_id = $plan_obj->id;
		$plan_interval = $plan_obj->interval;
		$plan_interval_count = $plan_obj->interval_count;
		$current_period_start = $current_period_end = '';
		if(!empty($subscriptionData)){
			$created = date("Y-m-d H:i:s", $subscriptionData->created);
			$current_period_start = date("Y-m-d H:i:s", $subscriptionData->current_period_start);
			$current_period_end = date("Y-m-d H:i:s", $subscriptionData->current_period_end);
		}

			$sqlQ = "INSERT INTO user_subscriptions_stripe (
			user_id,
			plan_id,
			stripe_customer_id,
			stripe_plan_price_id,
			stripe_payment_intent_id,
			stripe_subscription_id,
			default_payment_method,
			default_source,
			paid_amount,
			paid_amount_currency,
			plan_interval,
			plan_interval_count,
			customer_name,
			customer_email,
			plan_period_start,
			plan_period_end,
			created,
			status,
			keystr
			) VALUES (
			'".$_SESSION['PRO_USER_ID']."',
			'".$db->escape($sub['subcription_id'])."',
			'".$db->escape($checkout_session->customer)."',
			'".$db->escape($sub['stripe_price_id'])."',
			'".$db->escape($checkout_session->invoice)."',
			'".$db->escape($checkout_session->subscription)."',
			'".$db->escape($subscriptionData->default_payment_method)."',
			'".$db->escape($subscriptionData->default_source)."',
			'".$db->escape($paidAmount)."',
			'".$db->escape($paidCurrency)."',
			'".$db->escape($plan_interval)."',
			'".$db->escape($plan_interval_count)."',
			'".$db->escape($customer_name)."',
			'".$db->escape($customer_email)."',
			'".$db->escape($current_period_start)."',
			'".$db->escape($current_period_end)."',
			now(),
			'".$payment_status."',
			'".$db->escape($key)."')";

			$db->query($sqlQ);
                    $sqlQ = "SELECT id FROM $paymenttable WHERE txn_id = ?";
                    $stmt = $db1->prepare($sqlQ); 
                    $stmt->bind_param("s", $transactionID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $prevRow = $result->fetch_assoc();

                    if(!empty($prevRow)){
                        $payment_id = $prevRow['id'];
                    }else{
                        if($marstatus == "mar"){
                            $sqlQ = "INSERT INTO stripe_transactions_mar (customer_name,customer_email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,stripe_checkout_session_id,created,modified,keytxt,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW(),?,'".intval(@$_SESSION['PRO_USER_ID'])."')"; //n1 change table name
                        }
                        else{
                            $sqlQ = "INSERT INTO stripe_transactions (customer_name,customer_email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,stripe_checkout_session_id,created,modified,keytxt,coupon_id,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW(),?,'".$coupon_id."','".intval(@$_SESSION['PRO_USER_ID'])."')"; //n1 change table name
                        }

                    $productPrice = $paidAmount;
                    $currency = $paidCurrency;

                    if($productName==""){
                        if($marstatus == "mar" && (isset($_GET["reptype"]) && $_GET["reptype"] !="")){
                            $productName = "Market Research report - ".$_GET["reptype"];
                        }
                        else{
                            $productName = "AI Generated Plan";
                        }
                    }
                    else{
                        if($marstatus == "mar" && (isset($_GET["reptype"]) && $_GET["reptype"] !="")){
                            $productName = "Market Research report - ".$_GET["reptype"];
                        }   
                    }
					removeSendGrid("fa29d743-18c5-43eb-bf7c-c1b0754b2325",$customer_email);

					if(isset($_SESSION['PRO_USER_EMAIL'])){
						removeSendGrid("fa29d743-18c5-43eb-bf7c-c1b0754b2325",$_SESSION['PRO_USER_EMAIL']);
					}
					  $curl = curl_init();
					  curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "PUT",
					  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"4c40889f-e8b4-4cf9-8729-69de4955a8e8\"],\"contacts\":[{\"email\":\"". $customer_email ."\"}]}",
					  CURLOPT_HTTPHEADER => array(
						"authorization: Bearer ".SENDGRID_API_KEY,
						"content-type: application/json"
						  ),
						));
					  //f960ef44-7473-4047-b05b-27b114c7ca52

					$response1 = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);

                        $stmt = $db1->prepare($sqlQ);
                        $stmt->bind_param("ssssdsdsssss", $customer_name, $customer_email, $productName, $productID, $productPrice, $currency, $paidAmount, $paidCurrency, $transactionID, $payment_status, $session_id, $key);                   
                        $insert = $stmt->execute();

                        if($insert){
                            $payment_id = $stmt->insert_id;

							if(!(intval($_SESSION['PRO_USER_ID'])>0)){

                                if($checkout_session->metadata->create){

                                                $plan = $productID;
                                                $txtemail = $checkout_session->metadata->email;
                                                $txtregpwd = $checkout_session->metadata->pwd;

                                                    $db->where('user_email', $txtemail);
                                                    $r = $db->getOne("user_master");

                                                    if($db->count) {
                                                            $user_id = $r['user_id'];
                                                            $db->where("md5(plan_id)",$plan);
                                                            $db->update("plan_master",array("user_id"=>$user_id));
                                                    }else {
                                                        $input = array(
                                                            'user_email'=>$txtemail,
                                                            'password'=>$txtregpwd,
                                                            'status'=>'Y',
                                                        );
                                                        if($db->insert('user_master',$input)) {
                                                            $user_id= $db->getInsertId();
                                                            $db->where("md5(plan_id)",$plan);
                                                            $db->update("plan_master", array("user_id"=>$user_id));
                                                        }
                                                        else
                                                        {
                                                            $response=array(
                                                            'error'=>true,
                                                            'msg'=>'Some Server occured. Please try after sometimes',
                                                            'redirect'=>'1=1'
                                                            );
                                                        }
                                                    }
                                }
							}
                        }
						else {
							echo $stmt->error;
						}
                    }
                    
					$redirectStr = '?pid='.md5($payment_id)."&plan=".$productID."&via=Stripe".$marstatusurl;
                    $status = 'success';
                    $statusMsg = 'Your Payment has been Successful!';
					header("Location: ".SITE_URL."/../checkoutv4".$redirectStr);
					exit;
                }else{
                    $statusMsg = "Transaction has been failed!";
                }
            }else {
                $statusMsg = "Unable to fetch the transaction details! $api_error"; 
            }
        }else{
            $statusMsg = "Invalid Transaction! $api_error"; 
        }
    }
}else{
	$statusMsg = "Invalid Request!";
}
file_put_contents("STRIPE_PAYMENT.txt", $payment_id."---".$session_id."--".$statusMsg."---".serialize($_REQUEST)."\r\n",FILE_APPEND);
?>

<!DOCTYPE HTML>
<html>
<head prefix="og: http://ogp.me/ns#">
    <meta content=IE=edge http-equiv=X-UA-Compatible>
    <meta charset="utf-8">
    <meta content="noindex,nofollow" name=robots>
    <meta content=yes name=apple-mobile-web-app-capable>
    <meta content=black name=apple-mobile-web-app-status-bar-style>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" name="viewport">
    <link href="<?php echo SITE_URL; ?>/assets/image/favicon.png" rel=icon type="image/x-icon" />
    <title>Pro AI | Pro Business Plans</title>
    <meta content="Voted the number one pitch deck experts; work with a consultant your can trust." name="description">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">
    <meta content="#ffffff" name="msapplication-TileColor">
    <meta content="en_US" property="og:locale">
    <meta content="Voted the number one pitch deck experts; work with a consultant your can trust." property="og:description">
    <meta content="og-logo.png" property="og:image">
    <meta content="article" property="og:type">
    <meta content="Pro AI | Pro Business Plans" property="og:site_name">
    <meta content="telephone_no" name="format-detection">
    <meta content="address_no" name="format-detection">
	<link href="<?php echo SITE_URL; ?>/assets/css/extra.css?<?php strtotime('now'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/bootnavbar.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/nice-select.css">
    <link href="<?php echo SITE_URL; ?>/assets/css/planbuilder_style.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/assets/css/sweetalert.css" rel="stylesheet" />
  	<link href="<?php echo SITE_URL; ?>/assets/css/thankyou.css?<?php strtotime('now'); ?>" rel="stylesheet">
    <script src="https://www.googleoptimize.com/optimize.js?id=OPT-56DQ57W"></script>

  <!-- Hotjar Tracking Code for PROAI -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3439309,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<!-- Google tag (gtag.js) -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WV9XB6G');</script>
<!-- End Google Tag Manager -->

<script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o), m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-46044477-1', 'auto');
    ga('send', 'pageview');
</script>

</head>

<body class=no-js>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WV9XB6G"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!--BODYJS-->
    <div class="dashboard_body">

        <section class="header_section">
            <div class="container justify-content-center">
                <div class="logo_box">
                    <a href="./"><img src="<?php echo SITE_URL; ?>/assets/image/logo-proai.png" alt=""></a>
                </div>                
            </div>
        </section>

        <section class="thankyou_wrapper">
            <div class="thankyou_block">
                <h1>Thank You</h1>
                <p>Your downloads are being generated. For support related questions, please email <a href="mailto:proai@business-plans.com">proai@business-plans.com</a>, or join our slack channel <here> <a href="https://probusinessplans.slack.com/archives/C0551QYFP28" target="_blank">probusinessplans.slack.com/archives/C0551QYFP28</a> </p>
            </div>
        </section>

        <section class="footer">
            <div class="container">
                <div class="footer_main">
                    <div class="footer_right">
                        <p>&copy; <?php echo date("Y"); ?> Pro Business Plans&#8482;| ProAI. All Rights Reserved.</p>
                    </div>
                    <div class="footer_navmenu">
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/contact-us">Contact Us</a></li>
                            <li><a href="#">Help</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script src="<?php echo SITE_URL; ?>/assets/js/sweetalert.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery_form.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/bootnavbar.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jqueryui.js"></script>    

</body>
</html>