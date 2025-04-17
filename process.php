<?php
include_once "includes/config.php";
$redirectStr = '';

file_put_contents(__DIR__."/Paypal_process.txt", serialize($_REQUEST)."\r\n\r\n", FILE_APPEND);

if(!empty($_GET['paymentID']) && !empty($_GET['token']) && !empty($_GET['payerID']) && !empty($_GET['plan']) ){
    // Include and initialize database class

    // Include and initialize paypal class
    include_once 'PaypalExpress.class.php';
    $paypal = new PaypalExpress;
    
    // Get payment info from URL
    $paymentID = isset($_GET['paymentID']) ? $_GET['paymentID'] : "";
    $token = isset($_GET['token']) ? $_GET['token'] : "";
    $payerID = isset($_GET['payerID']) ? $_GET['payerID'] : "";
    $productID = isset($_GET['pid']) ? $_GET['pid'] : "";
    $keytxt = isset($_GET['key']) ? $_GET['key'] : "";

    $marstatus = isset($_GET['marstatus']) ? $_GET['reptype'] : "";
    $reptype = isset($_GET["reptype"]) ? $_GET['reptype'] : "";
    $paymenttable = "";
    $marstatusurl = "";
    if($marstatus == "mar"){
        $paymenttable = "payments_mar";
        $marstatusurl = "&marstatus=mar";
        $marstatusurl .="&reptype=".$reptype;
    }    
    $coupon_id = 0;
    if(isset($_GET['coupon']) && $_GET["coupon"]!="");
    {
        $coupon_id=$_GET["coupon"];
    }
    
    // Validate transaction via PayPal API
    $paymentCheck = $paypal->validate($paymentID, $token, $payerID, $productID);
    
  file_put_contents(__DIR__."/Paypal_process.txt", serialize($paymentCheck)."\r\n\r\n", FILE_APPEND);
	
	// If the payment is valid and approved
    if($paymentCheck && $paymentCheck->state == 'approved') {

        $id = $paymentCheck->id;
        $state = $paymentCheck->state;
        $payerFirstName = $paymentCheck->payer->payer_info->first_name;
        $payerLastName = $paymentCheck->payer->payer_info->last_name;
        $payerName = $payerFirstName.' '.$payerLastName;
        $payerEmail = $paymentCheck->payer->payer_info->email;
        $payerID = $paymentCheck->payer->payer_info->payer_id;
        $payerCountryCode = $paymentCheck->payer->payer_info->country_code;
        $paidAmount = $paymentCheck->transactions[0]->amount->details->subtotal;
        $currency = $paymentCheck->transactions[0]->amount->currency;
        
        $db->where("md5(plan_id)",$_REQUEST['plan']);
		$productData = $db->getOne('plan_master');
        
        // If payment price is valid
            $productID = $productData['plan_id'];
            $data = array(
                'product_id' => $productID,
                'txn_id' => $id,
                'payment_gross' => $paidAmount,
                'currency_code' => $currency,
                'payer_id' => $payerID,
                'payer_name' => $payerName,
                'payer_email' => $payerEmail,
                'payer_country' => $payerCountryCode,
                'payment_status' => $state,
                'keytxt' => $keytxt,
				'user_id'=> intval($_SESSION['PRO_USER_ID']),
                'coupon_id'=>$coupon_id
            );
            if($marstatus == "mar"){                
                $insert = $db->insert('payments_mar', $data);
            }
            else{
                $insert = $db->insert('payments', $data);
            }            
					removeSendGrid("fa29d743-18c5-43eb-bf7c-c1b0754b2325",$payerEmail);
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
					  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"4c40889f-e8b4-4cf9-8729-69de4955a8e8\"],\"contacts\":[{\"email\":\"".$payerEmail."\"}]}",
					  CURLOPT_HTTPHEADER => array(
						"authorization: Bearer ".SENDGRID_API_KEY,
						"content-type: application/json"
						  ),
						));
					  //f960ef44-7473-4047-b05b-27b114c7ca52

					$response1 = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);
            
            // Add insert id to the URL
			$redirectStr = '?pid='.md5($insert)."&plan=".md5($productID)."&via=Paypal".$marstatusurl;
		header("Location:".SITE_URL."/../checkoutv4".$redirectStr);
    }
	else {
        $id = $paymentCheck->id;
        $state = $paymentCheck->state;
        $payerFirstName = $paymentCheck->payer->payer_info->first_name;
        $payerLastName = $paymentCheck->payer->payer_info->last_name;
        $payerName = $payerFirstName.' '.$payerLastName;
        $payerEmail = $paymentCheck->payer->payer_info->email;
        $payerID = $paymentCheck->payer->payer_info->payer_id;
        $payerCountryCode = $paymentCheck->payer->payer_info->country_code;
        $paidAmount = $paymentCheck->transactions[0]->amount->details->subtotal;
        $currency = $paymentCheck->transactions[0]->amount->currency;
        
        $db->where("md5(plan_id)",$_REQUEST['plan']);
  		  $productData = $db->getOne('plan_master');
        
        // If payment price is valid
            $productID = $productData['plan_id'];
            $data = array(
                'product_id' => $productID,
                'txn_id' => $id,
                'payment_gross' => $paidAmount,
                'currency_code' => $currency,
                'payer_id' => $payerID,
                'payer_name' => $payerName,
                'payer_email' => $payerEmail,
                'payer_country' => $payerCountryCode,
                'payment_status' => $state,
                'keytxt' => $keytxt,
				'user_id'=>intval($_SESSION['PRO_USER_ID']),
                'coupon_id'=>$coupon_id
            );
            if($marstatus == "mar"){                
                $insert = $db->insert('payments_mar_failed', $data);
				$data['payment_status']='approved';
				$insert = $db->insert('payments_mar', $data);
            }
            else{
                $insert = $db->insert('payments_failed', $data);
				$data['payment_status']='approved';
				$insert = $db->insert('payments', $data);
            }            

					removeSendGrid("fa29d743-18c5-43eb-bf7c-c1b0754b2325",$payerEmail);
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
					  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"4c40889f-e8b4-4cf9-8729-69de4955a8e8\"],\"contacts\":[{\"email\":\"".$payerEmail."\"}]}",
					  CURLOPT_HTTPHEADER => array(
						"authorization: Bearer ".SENDGRID_API_KEY,
						"content-type: application/json"
						  ),
						));
					  //f960ef44-7473-4047-b05b-27b114c7ca52

					$response1 = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);

            // Add insert id to the URL
			$redirectStr = '?pid='.md5($insert)."&plan=".md5($productID)."&via=Paypal".$marstatusurl;
	echo "Payment Failed";
    }
    // Redirect to payment status page
}else{
    // Redirect to the home page
    header("Location:index");
}
?>