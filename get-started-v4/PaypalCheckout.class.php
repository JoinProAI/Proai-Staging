<?php 
/** 
 * 
 * This PayPal Checkout API handler class is a custom PHP library to handle the PayPal REST API requests. 
 * 
 * @class   PaypalCheckout 
 * @author  CodexWorld 
 * @link    https://www.codexworld.com 
 * @version 1.0 
 */ 

// Include the configuration file
include_once 'config.php';

class PaypalCheckout{ 
    public $paypalAuthAPI    = PAYPAL_SANDBOX?'https://api-m.sandbox.paypal.com/v1/oauth2/token':'https://api-m.paypal.com/v1/oauth2/token';
    public $paypalProductAPI = PAYPAL_SANDBOX?'https://api-m.sandbox.paypal.com/v1/catalogs/products':'https://api-m.paypal.com/v1/catalogs/products';
    public $paypalBillingAPI = PAYPAL_SANDBOX?'https://api-m.sandbox.paypal.com/v1/billing':'https://api-m.paypal.com/v1/billing';
    public $paypalClientID   = PAYPAL_API_CLIENT_ID; 
    private $paypalSecret    = PAYPAL_API_SECRET; 

    public function generateAccessToken(){
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $this->paypalAuthAPI);  
        curl_setopt($ch, CURLOPT_HEADER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_POST, true);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_USERPWD, $this->paypalClientID.":".$this->paypalSecret);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");  
        $auth_response = json_decode(curl_exec($ch)); 
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);  
 
        if ($http_code != 200 && !empty($auth_response->error)) {  
            throw new Exception('Failed to generate Access Token: '.$auth_response->error.' >>> '.$auth_response->error_description);  
        } 
          
        if(!empty($auth_response)){ 
            return $auth_response->access_token;  
        }else{ 
            return false; 
        } 
    }

    public function createPlan($planInfo){ 
        $accessToken = $this->generateAccessToken(); 
        if(empty($accessToken)){ 
            return false;  
        }else{ 
            $postParams = array(
                "name" => $planInfo['name'],
                "type" => "DIGITAL",
                "category" => "SOFTWARE"
            ); 
 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->paypalProductAPI); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $accessToken));  
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
            $api_resp = curl_exec($ch); 
            $pro_api_data = json_decode($api_resp); 
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
            curl_close($ch); 
 
            if ($http_code != 200 && $http_code != 201) {  
                throw new Exception('Failed to create Product ('.$http_code.'): '.$api_resp);  
            } 
            
            if(!empty($pro_api_data->id)){
                $postParams = array( 
                    "product_id" => $pro_api_data->id,
                    "name" => $planInfo['name'],
                    "billing_cycles" => array( 
                        array( 
                            "frequency" => array( 
                                "interval_unit" => $planInfo['interval'], 
                                "interval_count" => $planInfo['interval_count'] 
                            ),
                            "tenure_type" => "REGULAR",
                            "sequence" => 1,
                            "total_cycles" => 999,
                            "pricing_scheme" => array( 
                                "fixed_price" => array(                                    
                                    "value" => $planInfo['price'],
                                    "currency_code" => 'usd'
                                )
                            ),
                        ) 
                    ),
                    "payment_preferences" => array( 
                        "auto_bill_outstanding" => true
                    )
                ); 
     
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, $this->paypalBillingAPI.'/plans'); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $accessToken));  
                curl_setopt($ch, CURLOPT_POST, true); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));  
                $api_resp = curl_exec($ch); 
                $plan_api_data = json_decode($api_resp, true); 
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
                curl_close($ch); 
     
                if ($http_code != 200 && $http_code != 201) {  
                    throw new Exception('Failed to create Product ('.$http_code.'): '.$api_resp);  
                } 
                
                return !empty($plan_api_data)?$plan_api_data:false;
            }else{
                return false;
            }
        } 
    }

	    public function createProduct($planInfo){ 
        $accessToken = $this->generateAccessToken(); 
        if(empty($accessToken)){ 
            return false;  
        }else{            
			$postParams = array(
                "name" => $planInfo['name'],
                "type" => "DIGITAL",
                "category" => "SOFTWARE"
            ); 
 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $this->paypalProductAPI); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $accessToken));  
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
            $api_resp = curl_exec($ch); 
            $pro_api_data = json_decode($api_resp); 
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
            curl_close($ch); 
 
            if ($http_code != 200 && $http_code != 201) {  
                throw new Exception('Failed to create Product ('.$http_code.'): '.$api_resp);  
            } 
            return $pro_api_data->id;
        } 
    }
    
  public function createSubPlan($postArray){ 
        $accessToken = $this->generateAccessToken(); 

	    https://api-m.sandbox.paypal.com/v1/billing/plans
                $ch = curl_init(); 
                curl_setopt($ch, CURLOPT_URL, $this->paypalBillingAPI.'/plans'); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $accessToken));  
                curl_setopt($ch, CURLOPT_POST, true); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postArray));  
                $api_resp = curl_exec($ch); 
                $plan_api_data = json_decode($api_resp, true); 
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
                curl_close($ch); 
     
                if ($http_code != 200 && $http_code != 201) {  
                    throw new Exception('Failed to create Product ('.$http_code.'): '.$api_resp);  
                }                 
                return !empty($plan_api_data)?$plan_api_data:false;
        } 
    
    public function getSubscription($subscription_id){
        $accessToken = $this->generateAccessToken(); 
        if(empty($accessToken)){ 
            return false;  
        }else{ 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->paypalBillingAPI.'/subscriptions/'.$subscription_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '. $accessToken)); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $api_data = json_decode(curl_exec($ch), true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
            curl_close($ch);

            if ($http_code != 200 && !empty($api_data['error'])) { 
                throw new Exception('Error '.$api_data['error'].': '.$api_data['error_description']); 
            }
            return !empty($api_data) && $http_code == 200?$api_data:false;
        }
    }
}