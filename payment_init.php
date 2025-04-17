<?php

require_once 'includes/config.php';
$plan_prices=array(
	"BPLAN"=> ItemPrice("BPLAN"),
	"IPLAN"=> ItemPrice("IPLAN"),
	"MPLAN"=> ItemPrice("MPLAN"),
	"MARKETRESEARCH"=>0.5,
	"BUSINESSREGISTRATION"=>0,
	);

require_once 'stripe-php/init.php';

$r = explode("--", $_REQUEST['key']);

$key = $_REQUEST['key'];

$plan_id = $r[0];
$plan_type = $r[1];

$plan_type = decryptText($plan_type);

$db->where("stripe_price_id", $_REQUEST['priceid']);

$sub = $db->getOne("subscription_plans");

$product_price = $plan_prices[$plan_type];

$plan_title_text = $plan_nm[$plan_type];
$STRIPE_SUCCESS_URL = STRIPE_SUCCESS_URL;
$STRIPE_CANCEL_URL  = STRIPE_CANCEL_URL;

$STRIPE_SUCCESS_URL = SITE_URL.'/thank-you-stripe';

$stripe_api_key = STRIPE_PUBLISHABLE_KEY;
$stripe_api_secret=STRIPE_API_KEY;

$stripe = new \Stripe\StripeClient($stripe_api_secret);

$response = array(
    'status' => 0,
    'error' => array(
        'message' => 'Invalid Request!'   
    )
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$input = file_get_contents('php://input');
	$request = json_decode($input);	
}

if (json_last_error() !== JSON_ERROR_NONE) {
	http_response_code(400);
	echo json_encode($response);
	exit;
}
$reptype = "";
$reptype= $_GET["reptype"];
$marstatus = "";

if($_GET["marstatus"] == "mar"){
    $marstatus = "&marstatus=mar";
    $marstatus .="&reptype=".$reptype;
    $product_items = array(
        1=>$product_price,
        2=>295,
        3=>0
    );
}
else{
    if($_GET["coupon"]!= ""){ //coupon_code apply so take if/else condition
        $db->where("coupon_id",$_GET["coupon"]);
        $coupondetail = $db->getOne("coupon_master");
        $coupon_value = $coupondetail["value"];
        $marstatus ="&coupon=".$_GET["coupon"];
        $original_price = $product_price;

        $discount_price = ($original_price * $coupon_value);
        $totalpay_amount = ($original_price - $discount_price);

        $product_items = array(
            1=>$totalpay_amount,
            2=>295,
            3=>0
        );
    }
    else{
        $product_items = array(
            1=>$product_price,
            2=>295,
            3=>0
        );    
    }
}

$product_titles= array(
1=>$plan_title_text,
2=> "Market Research Plan",
3=> ""
);

	$subscr_plan_id = !empty($sub['subcription_id'])?$sub['subcription_id']:'';
	$name = !empty($_SESSION['PRO_USER_NM'])?$_SESSION['PRO_USER_NM']:'';
	$email = !empty($_SESSION['PRO_USER_EMAIL'])?$_SESSION['PRO_USER_EMAIL']:'';

	$planPrice = $sub['price'];

	$planPriceCents = round($planPrice*100);
	
    try {  
        $customer = $stripe->customers->create([
            'name' => $name, 
            'email' => $email
        ]); 
    }catch(Exception $e) {  
        $api_error = $e->getMessage();  
    }
		if(empty($api_error) && $sub) {
			try {
					$checkout_session = $stripe->checkout->sessions->create([
						'payment_method_types' => ['card'],
						'line_items' => [[
							'price' => $sub['stripe_price_id'],
							'quantity' => 1
						]],
						
						'mode' => 'subscription',
						'success_url' => $STRIPE_SUCCESS_URL.'?session_id={CHECKOUT_SESSION_ID}'.$marstatus,
						'cancel_url' => $STRIPE_CANCEL_URL,
						'customer_email' => $email,
						'phone_number_collection' => ['enabled' => true],
						'metadata' => [
							"plan_id" => $_REQUEST['plan'],
							'email' => $email,
							'name' => $productName,
							'key' => $key
						],
					]);

			}catch(Exception $e) {
				$api_error = $e->getMessage();
			}
			
			if(empty($api_error) && $checkout_session){
			
		 $response = array(
            'status' => 1,
            'message' => 'Checkout Session created successfully!',
            'sessionId' => $checkout_session->id
        );
				echo json_encode($response);
				exit;
			}else{
				$response = array(
					'status' => 0,
					'error' => array(
						'message' => 'Checkout Session creation failed! '.$api_error   
					)
				);
			}
	}else{
		$response = array(
            'status' => 0,
            'error' => array(
                'message' => 'Checkout Session creation failed! '.$api_error   
            )
        );
	}

echo json_encode($response);
