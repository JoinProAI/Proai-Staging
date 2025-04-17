<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');
include_once __DIR__."/includes/config.php";
extract($_REQUEST);

$user_id=0;
$response = array();
$err = false;
$msg = array();

$val = new Validation();
switch($step) {
	

case "popregister":
		$db->where('user_email', $txtemail);
		$r = $db->getOne("user_master");

		if($db->count){
			$response=array(
				'error'=>true,
				'msg'=>'Email address already exists',
				'redirect'=>'1=1'
			);
		}
		else {


			$db->where('user_email', $txtemail);
			$r = $db->getOne("user_master");
			
			if($db->count) {
						$response=array(
							'error'=>true,
							'msg'=>'Email address already exists.',
							'redirect'=>'1=1'
							);
			}else
			{
				$txtregpwd = hashKey(8);
				$input = array(
					'user_email'=>$txtemail,
					'password'=>md5($txtregpwd),
					'status'=>'Y',
				);
				
				if($db->insert('user_master',$input)) {

					$user_id= $db->getInsertId();
					@session_destroy();
					@session_start();

					$_SESSION['PRO_USER_ID']=$user_id;
					$_SESSION['PRO_USER_NM']=$txtemail;



					$db->where("md5(plan_id)",$plan);
					$db->update("plan_master",array("user_id"=>$user_id));

					$response=array(
					'error'=>false,
					'msg'=>'An Password email has been sent to your Email Address "'.$txtemail.'"',
					'redirect'=>'window.location="modify_plan?plan='.$plan.'#executive-summary";',
					);

					$login_url = SITE_URL."/login";
					$msg = "Hello, <br><BR>
					Please find your Password to Access ProAI Account.<br><br>
					<b>".$txtregpwd."</b><BR><BR>
					You can login from <a href='".$login_url."' target='_blank'>here</a><BR><BR>
					<a href='".$login_url."' target='_blank'>".$login_url."</a><BR><BR>
					Thank you,
					Regards
					";
					sendmail($txtemail,"ProAI Account Access",$msg);




					  $curl = curl_init();
					  curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "PUT",
					  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"fa29d743-18c5-43eb-bf7c-c1b0754b2325\"],\"contacts\":[{\"email\":\"". $txtemail ."\"}]}",
					  CURLOPT_HTTPHEADER => array(
						"authorization: Bearer ".SENDGRID_API_KEY,
						"content-type: application/json"
						  ),
						));

					  //f960ef44-7473-4047-b05b-27b114c7ca52

					$response1 = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);




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
	break;
	case 'ver2plancreate':



	$val->name("Company Name")->value($company)->required();
	$val->name("Company Stage")->value($company_type)->required();
	$val->name("City")->value($city)->required();
	$val->name("State/Country")->value($state)->required();
	$val->name("Describe your company")->value($industry);

	$company_url = isset($company_url) ? $company_url : "";


	$seeking_fund='';
	
	if($val->isSuccess()){
			$db->where("user_id",intval($_SESSION['PRO_USER_ID']));
			$no = $db->get("plan_master");

			$plan_title = "My Bussiness Plan ".($db->count +1);

			$company_id =  $db->insert("company_master", array(
				"company_name" => $company,
				"company_city"=>$city,
				"company_state_country"=>$state,
				"status"=>"Y",
				"payment_type"=>"paid"
			));


			$_SESSION['company_id'] = $company_id;
			$_SESSION['company_name'] = $company;



			
			$md5 =md5(json_encode(array(
			"plan_title" => $plan_title,
			"existing_company" => $company_type,
			"industry_operate"=>$industry,
			"seeking_fund"=>$seeking_fund,
			)));
			
			
			$insert = array(
			'plan_title'=> $plan_title,
			'user_id'=>intval($_SESSION['PRO_USER_ID']),
			'existing_company'=>$company_type,
			'industry_operate'=>$industry,
			'seeking_fund'=>$seeking_fund,
			'current_md5'=>$db->escape($md5),
			'last_md5'=>$db->escape($md5),
			"company_id" => $company_id,
			"refferer"=>$_SERVER['HTTP_REFERER'],
			"ip"=>$_SERVER['REMOTE_ADDR']."---".$_SERVER['HTTP_CF_CONNECTING_IP'],
			"user_agent"=>$_SERVER['HTTP_USER_AGENT'],
			"extradata" => serialize($_SERVER),
			"company_url"=>$company_url,
			);




			if($db->insert("plan_master",$insert)) {
				$plan_id= $db->getInsertId();
				$response=array(
					'error'=>false,
					'msg'=> 'Plan created successfully.',
					'redirect'=> 'jQuery("#generateDocument").attr("data-plan","'.md5($plan_id).'");jQuery("#generateDocument").modal("show")',
				);


				$cover_data = array("plan_id" => $plan_id, "design_template" => 1);


				$db->insert("cover_design_master", $cover_data);


				$name = $title =  $email = $Address = $code = "";


				$update_data = array(
				'company_name'=>$company,
				'full_name'=>$name,
				'title'=>$title,
				'email'=>$email,
				'street_address'=>$Address,
				'city'=>$city,
				'state'=>$state,
				'zipcode'=>$code,
				);
			
				$md5= md5(json_encode($update_data));
				$update_data['plan_id']=$plan_id;
				$update_data['current_md5']=$md5;
				$update_data['last_md5']=$md5;


				$db->insert("cover_page_information_master",$update_data);


			}
			else {
				$err = true;
				$response=array(
				'error'=>true,
				'msg'=>$db->getLastError()
				);
			}



	}
	else {
				$response=array(
				'error'=>true,
				'msg'=>$val->displayErrors()
				);
	}
	break;
	
	
	case 'overviewdata':
		// print_r($_REQUEST);
		// exit();
		$plan = $_REQUEST['plan'];

		if(!isset($_REQUEST['company_type'])) {
			$company_type='';
		}
	
		$val->name("New or Existing")->value($company_type)->required();
			if($company_type !='N'){
				$val->name("Industry do you operate")->value($industry)->required();
				$val->name("Funding for business plan")->value($seeking_fund)->required();
			}	
	
		if($val->isSuccess()) {

			$db->where("user_id",$_SESSION['PRO_USER_ID']);
			$no = $db->get("plan_master");
			
			$insert = array(
			'user_id'=>$_SESSION['PRO_USER_ID'],
			'existing_company'=>$company_type,
			'industry_operate'=>$industry,
			'seeking_fund'=>$seeking_fund,
			'goal_business_plan'=>$_REQUEST["goal_business_plan"],
			'language'=>$language,
            'currency'=>$_REQUEST["currency"]
			);
			
			$md5 = md5(json_encode($insert));
			$insert['current_md5']= $md5;
			$insert['last_md5']= $md5;

			unset($update_data['last_md5']);
			$db->query("update plan_master set last_md5=current_md5 where md5(plan_id)='".$plan."'");

			$db->where("md5(plan_id)",$plan);
			if($db->update("plan_master",$insert)) {
				$plan_id= $db->getInsertId();
				$response=array(
					'error'=>false,
					'msg'=> 'Plan updated successfully.',
					'redirect'=> "loadTab('#cover-page-information')",
				);
				$db->insert("cover_design_master", array("plan_id" => $plan_id, "design_template" => 1));
			}
			else {
				$err = true;
				$response=array( 'error'=>true, 'msg'=>$db->getLastError());
			}
			
		}else {
				$response=array( 'error'=>true, 'msg'=>$val->displayErrors() );
		}
	break;
	
	case 'company_overview':
		$plan = $_REQUEST['plan'];
		$db->where('md5(plan_id)',$plan);
		$r =$db->getOne("plan_master");
		$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

		$date = $_REQUEST['formation_year']."-".$_REQUEST['formation_month']."-".$_REQUEST['formation_date'];
		$business_optional = isset($_REQUEST['business_operational']) ? $_REQUEST['business_operational']:"N";
		$legal_structure = isset($_REQUEST['legal_structure']) ? $_REQUEST['legal_structure']: "";
		$accomplishment=array(); 
		$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';
		$accoplishment_arr = $db->get("master_accomplishment");




		foreach($accoplishment_arr as $k => $v){
			$val ="";
			$check ='N';
			if(isset($_REQUEST["accomplishment"][$v['slug']])) {
				$val = $_REQUEST[$v['slug']."_val"];
				$check = 'Y';
			}
			$accomplishment['check'][$v['slug']]=$check;
			$accomplishment['val'][$v['slug']] = $val;
		}
		



			$update_data= array(
				'plan_id'=>$plan_id,
				'formation_date'=>$date,
				'business_oprational'=>$business_optional,
				'legal_structure'=>$legal_structure,
				'accomplishment'=>json_encode($accomplishment),
				'print_omit'=>$print_omit
			);

			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("company_overview");
			
			$md5= md5(json_encode($update_data));
			$update_data['last_md5']=$md5;
			$update_data['current_md5']=$md5;


			if(!($db->count)){
				if($db->insert("company_overview",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
//							'redirect'=> "loadTab('#executive-summary')",
							'redirect'=> '1==1',

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


				unset($update_data['last_md5']);
				$db->query("update company_overview set last_md5=current_md5 where md5(plan_id)='".$plan."'");
				$db->where("md5(plan_id)",$plan);



				$db->where("md5(plan_id)",$plan);
				if($db->update("company_overview",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#executive-summary')",
							'redirect'=> '1==1',


						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;

	case 'cover-page-information':
		//print_r($_POST);exit();
		$plan = $_REQUEST['plan'];
		$db->where('md5(plan_id)',$plan);
		$r =$db->getOne("plan_master");
		$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;



		$update_data = array(
			'company_name'=>$company,
			'full_name'=>$name,
			'title'=>$title,
			'email'=>$email,
			'street_address'=>$Address,
			'city'=>$city,
			'state'=>$state,
			'zipcode'=>$code,
			'plan_id'=>$plan_id,
		);
			$current_md5 = md5(json_encode($update_data));

			$update_data['current_md5']=$current_md5;
			$update_data['last_md5']= $current_md5;

			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("cover_page_information_master");

			if(!($db->count)){
				if($db->insert("cover_page_information_master",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#executive-summary')",
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {
					
					unset($update_data['last_md5']);
					$db->query("update cover_page_information_master set last_md5=current_md5 where md5(plan_id)='".$plan."'");
					$db->where("md5(plan_id)",$plan);

					if($db->update("cover_page_information_master",$update_data)) {
							$response=array(
								'error'=>false,
								'msg'=>"Record updated successfully",
								'redirect'=> "loadTab('#executive-summary')",
								'qry'=>"update cover_page_information_master set last_md5=current_md5 where md5(plan_id)='".$plan."'"

							);
					}
					else
					{
						$response=array(
							'error'=>true,
							'msg'=>$db->getLastError()
							);
					}
			}

			$db->where("md5(plan_id)",$plan);
			$list = $db->getOne("cover_page_information_master");
			
			$json_data=array();

			if($db->count){
				$json_data[]=array('name'=>'Company name', "val" => $list['company_name']);
				$json_data[]=array('name'=>'Full name', "val"=>$list['full_name']);
				$json_data[]=array('name'=>'Email',"val"=>$list['email']);
				$json_data[]=array('name'=>'City',"val"=>$list['city']);
				$json_data[]=array('name'=>'State', "val"=>$list['state']); 
			}

			$response['json_data'] = json_encode($json_data,JSON_HEX_QUOT | JSON_HEX_APOS);

	break;
	case 'executive-summary-business-overview':
		$plan = $_REQUEST['plan'];
		$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';
		$overview = isset($_REQUEST['overview']) ? $_REQUEST['overview'] :""; 

			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;


			$update_data= array(
				'plan_id'=>$plan_id,
				'overview'=>$overview,
				'print_omit'=>$print_omit
			);

			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;



			$db->where('plan_id', $plan_id);
			$rs = $db->get("executive_overview_summary");

			if(!($db->count)){
				if($db->insert("executive_overview_summary",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update executive_overview_summary set last_md5=current_md5 where plan_id='".$plan_id."'");
					$db->where("md5(plan_id)",$plan);


				$db->where("plan_id",$plan_id);

				if($db->update("executive_overview_summary",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}


	break;
	
	case 'executive-summary-product-service-offerings':	
		$plan = $_REQUEST['plan'];
		$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';
		$product_service_offering  = isset($_REQUEST['product_service_offering']) ? $_REQUEST['product_service_offering'] :""; 
		$db->where('md5(plan_id)',$plan);
		$r =$db->getOne("plan_master");
		$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;
		
			$update_data= array(
				'plan_id'=>$plan_id,
				'product_service_offering'=>$product_service_offering ,
				'print_omit'=>$print_omit
			);



			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('plan_id', $plan_id );
			$rs = $db->get("executive_service_offering");

			if(!($db->count)){
				if($db->insert("executive_service_offering",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully", 
							'redirect'=> "loadTab('#target-customers')",
							'redirect'=> '1==1',

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update executive_service_offering set last_md5=current_md5 where plan_id='".$plan_id ."'");

				$db->where("plan_id",$plan_id );

				if($db->update("executive_service_offering",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#target-customers')",
							'redirect'=> '1==1',

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
	break;
	case 'executive-summary-product-success-factors':
			//print_r($_POST);exit();
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$success_factors=array(); 
			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';
			$db->where("status", "Y");
			$success_factors_arr = $db->get("master_success_factors");

			foreach($success_factors_arr as $k => $v){
				$value ="";
				$check ='N';
				if(isset($_REQUEST["success_factors"][$v['slug']])) {
					$value = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
					$val->name('Success Factors')->value($value)->min(50)->required();
				}
				$success_factors['check'][$v['slug']]=$check;
				$success_factors['val'][$v['slug']] = $value;
			}
			if($val->isSuccess()) {
			$update_data= array(
				'plan_id'=>$plan_id,
				'success_factor'=>json_encode($success_factors),
				'print_omit'=>$print_omit
			);
			
			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;



			$db->where('plan_id', $plan_id);
			$rs = $db->get("executive_success_factores");
			


			if(!($db->count)){
				if($db->insert("executive_success_factores",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "1==1",
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update executive_service_offering set last_md5=current_md5 where plan_id='".$plan_id."'");
				
				$db->where("plan_id",$plan_id);

				if($db->update("executive_success_factores",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "1==1",
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}



			$db->where('plan_id',$plan_id);
			$list = $db->getOne('executive_success_factores');
			$rcount2=$db->count;

			$db->where("status","Y");
			$success_factors_arr = $db->get("master_success_factors");
			$success_factores_val_arr = isset($list['success_factor']) ? json_decode($list['success_factor'],true): array();



			foreach($success_factors_arr as $key => $itm) {
				$chk = '';
				if(isset($success_factores_val_arr['check'][$itm['slug']]) && $success_factores_val_arr['check'][$itm['slug']]=='Y'){
			
						$val = $success_factores_val_arr['val'][$itm['slug']];
			
						if($val=='') { $val = 'N/A'; }
						$json_data2[]=array('name'=>$itm['name'],'val'=>$val);
				}
				else {
			
					if($rcount2){
						$json_data2[]=array('name'=> $itm['name'],'val'=>"N/A");
					}
				}
			}


			$response['json_data'] = json_encode($json_data2,JSON_HEX_QUOT | JSON_HEX_APOS);

			}
			else {

				$response=array(
				'error'=>true,
				'msg'=>$val->displayErrors(),
				'redirect'=> "selTab('executive-summary');loadTab('#executive-summary')",
				);
			}


	break;
	case 'industry-overview':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;
			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';

			$update_data= array(
				'plan_id'=>$plan_id,
				'industry_name'=>isset($_REQUEST['industry_name']) ? $_REQUEST['industry_name']: '',
				'last_year_sale'=>isset($_REQUEST['last_year_sale']) ? $_REQUEST['last_year_sale']: '',
				'last_year_operators'=>isset($_REQUEST['last_year_operators']) ? $_REQUEST['last_year_operators']: '',
				'service_segments'=>isset($_REQUEST['service_segments']) ? $_REQUEST['service_segments']: '',
				'industry_trends'=>isset($_REQUEST['industry_trends']) ? $_REQUEST['industry_trends']: '',
				'industry_market_statistics'=>isset($_REQUEST['industry_market_statistics']) ? $_REQUEST['industry_market_statistics']: '',
				'print_omit'=>$print_omit
			);



			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;



			$db->where('plan_id', $plan_id);
			$rs = $db->get("industry_overview");

			if(!($db->count)) {
				if($db->insert("industry_overview",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#target-customers')",
							'redirect'=> '1==1',

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update industry_overview set last_md5=current_md5 where md5(plan_id)='".$plan."'");
					$db->where("md5(plan_id)",$plan);




				$db->where("plan_id",$plan_id);
				if($db->update("industry_overview",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#target-customers')",
							'redirect'=> '1==1',

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;
	case 'industry-relavant-market-size':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';

			$update_data= array(
				'plan_id'=>$plan_id,
				'customers_per_year'=>isset($_REQUEST['customers_per_year']) ? $_REQUEST['customers_per_year']: '',
				'spend_per_year'=>isset($_REQUEST['spend_per_year']) ? $_REQUEST['spend_per_year']: '',
				'relevant_market_size'=>isset($_REQUEST['relevant_market_size']) ? $_REQUEST['relevant_market_size']: '',
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('plan_id', $plan_id);
			$rs = $db->get("industry_market_size");

			if(!($db->count)) {
				if($db->insert("industry_market_size",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update industry_market_size set last_md5=current_md5 where plan_id='".$plan_id."'");

				$db->where("plan_id",$plan_id);
				if($db->update("industry_market_size",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
	break;
	case 'customers-target':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';




			$targest_customers=array();
			$targest_customers_arr = $db->get("master_target_customers");
			foreach($targest_customers_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["target_customers"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$targest_customers['check'][$v['slug']]=$check;
				$targest_customers['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($targest_customers),
				'print_omit'=>$print_omit
			);



			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('plan_id', $plan_id);
			$rs = $db->get("target_customers");

			if(!($db->count)) {
				if($db->insert("target_customers",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#direct-competitors')",
							'redirect'=> '1==1',

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update target_customers set last_md5=current_md5 where plan_id='".$plan_id."'");

				$db->where("plan_id",$plan_id);
				if($db->update("target_customers",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#direct-competitors')",
							'redirect'=> '1==1',

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}


	break;
	case 'customers-needs':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;


			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';
			$customers_needs=array();
			$customers_needs_arr = $db->get("master_customers_needs");
			foreach($customers_needs_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["customers_need"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$customers_needs['check'][$v['slug']]=$check;
				$customers_needs['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($customers_needs),
				'print_omit'=>$print_omit
			);

			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('plan_id', $plan_id);
			$rs = $db->get("customers_need");

			if(!($db->count)) {
				if($db->insert("customers_need",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update customers_need set last_md5=current_md5 where plan_id='".$plan_id."'");



				$db->where("plan_id",$plan_id);
				if($db->update("customers_need",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;
	case 'competition-direct':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

		$compititor_output = array();

		
		if(is_array($_REQUEST['compititor_name'])) {
			foreach($_REQUEST['compititor_name'] as $key=> $val) {
					$compititor_output[]=array(
						'compititor_name'=>$_REQUEST['compititor_name'][$key],	
						'compititor_overview'=>$_REQUEST['compititor_overview'][$key],	
						'compititor_product_services'=>$_REQUEST['compititor_product_services'][$key],	
						'compititor_pricing'=>$_REQUEST['compititor_pricing'][$key],	
						'compititor_revenues'=>$_REQUEST['compititor_revenues'][$key],	
						'compititor_location'=>$_REQUEST['compititor_location'][$key],	
						'compititor_segments'=>$_REQUEST['compititor_segments'][$key],	
						'compititor_strengths'=>$_REQUEST['compititor_strengths'][$key],	
						'compititor_weaknesses'=>$_REQUEST['compititor_weaknesses'][$key],	
					);
			}
		}
	
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($compititor_output),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('plan_id', $plan_id);
			$rs = $db->get("compitition_directs");

			if(!($db->count)) {
				if($db->insert("compitition_directs",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "selTab('direct-competitors'); loadTab('#management-team-members')",
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update compitition_directs set last_md5=current_md5 where plan_id='".$plan_id."'");
					


				$db->where("plan_id",$plan_id);
				if($db->update("compitition_directs",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "selTab('direct-competitors'); loadTab('#management-team-members')",
							//'redirect'=> '1==1',
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}


			$db->where('plan_id',$plan_id);
			$list = $db->getOne('compitition_directs');
			$rcount = $db->count;
			
			
			$direct_competitors_val_arr = isset($list['details']) ? json_decode($list['details'],true): array();
			$json_data=array();
			$print_omit =' checked ';
			
				$json_data1 = array();
				$json_data2 = array();
				$json_data3 = array();
				foreach($direct_competitors_val_arr as $key=>$itm){
					$json_data1[] = array("name"=>"Direct Competitor #".($key+1),"val"=>"");
					$json_data1[] = array("name"=>"Competitor's Name","val"=>$itm['compititor_name']);
					$json_data1[] = array("name"=>"Location","val"=>$itm['compititor_location']);
					$json_data1[] = array("name"=>"&nbsp;","val"=>"&nbsp;");
				}
			$response['json_data']=json_encode($json_data1,JSON_HEX_QUOT | JSON_HEX_APOS);

			$db->where("plan_id",$plan_id);
			$rs = $db->getOne("compitition_directs");
			if($db->count){

				$last_md5 = $rs['last_md5'];
				$current_md5= $rs['current_md5'];

				if($last_md5 != $current_md5) {
					generate_api_output_chunk($plan,array("competitors"));
				}
			}


	break;
	case 'competition-indirect':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

		$compititor_output = array();

		
		if(is_array($_REQUEST['compititor_name'])) {
			foreach($_REQUEST['compititor_name'] as $key=> $val) {
					$compititor_output[]=array(
						'compititor_name'=>$_REQUEST['compititor_name'][$key],	
						'compititor_overview'=>$_REQUEST['compititor_overview'][$key],	
						'compititor_product_services'=>$_REQUEST['compititor_product_services'][$key],	
						'compititor_pricing'=>$_REQUEST['compititor_pricing'][$key],	
						'compititor_revenues'=>$_REQUEST['compititor_revenues'][$key],	
						'compititor_location'=>$_REQUEST['compititor_location'][$key],	
						'compititor_segments'=>$_REQUEST['compititor_segments'][$key],	
						'compititor_strengths'=>$_REQUEST['compititor_strengths'][$key],	
						'compititor_weaknesses'=>$_REQUEST['compititor_weaknesses'][$key],	
					);
			}
		}
	
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($compititor_output),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('plan_id', $plan_id);
			$rs = $db->get("compitition_indirect");

			if(!($db->count)) {
				if($db->insert("compitition_indirect",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update compitition_indirect set last_md5=current_md5 where plan_id='".$plan_id."'");
					


				$db->where("plan_id",$plan_id);
				if($db->update("compitition_indirect",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}


	break;
	case 'competition-competitive-advantages':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';




			$targest_customers=array();
			$targest_customers_arr = $db->get("master_compitition_analysis");
			foreach($targest_customers_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["target_customers"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$targest_customers['check'][$v['slug']]=$check;
				$targest_customers['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($targest_customers),
				'print_omit'=>$print_omit
			);

			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('plan_id', $plan_id);
			$rs = $db->get("compitition_analysis");

			if(!($db->count)) {
				if($db->insert("compitition_analysis",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#management-team-members')",
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update compitition_analysis set last_md5=current_md5 where plan_id='".$plan_id."'");
					



				$db->where("plan_id",$plan_id);
				if($db->update("compitition_analysis",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#management-team-members')",
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}


	break;
	case 'marketing-products-and-pricing':
		$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

		$compititor_output = array();
		if(is_array($_REQUEST['service_name'])) {
			foreach($_REQUEST['service_name'] as $key=> $val) {
					$compititor_output[]=array(
						'service_name'=>$_REQUEST['service_name'][$key],	
						'service_description'=>$_REQUEST['service_description'][$key],	
						'service_price'=>$_REQUEST['service_price'][$key],	
					);
			}
		}
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($compititor_output),
				'print_omit'=>$print_omit
			);

			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('plan_id', $plan_id);
			$rs = $db->get("marketing_products_pricing");

			if(!($db->count)) {
				if($db->insert("marketing_products_pricing",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "selTab('executive-summary');loadTab('#direct-competitors')",

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {
					unset($update_data['last_md5']);
					$db->query("update marketing_products_pricing set last_md5=current_md5 where plan_id='".$plan_id."'");
					

				$db->where("plan_id",$plan_id);
				if($db->update("marketing_products_pricing",$update_data)) {
						$response=array(
							'error'=>false,
								'msg'=>"Record updated successfully",
								'redirect'=> "selTab('executive-summary');loadTab('#direct-competitors')",
	
							);
					}
					else
					{
						$response=array(
							'error'=>true,
							'msg'=>$db->getLastError()
							);
				}
			}
		
		
			$db->where('plan_id',$plan_id);
			$list = $db->getOne('marketing_products_pricing');
			$product_services_val_arr = isset($list['details']) ? json_decode($list['details'],true): array();
			$json_data=array();
			$print_omit ='';
			
				$json_data1=array();
			foreach($product_services_val_arr as $key=>$itm){
				$json_data1[] = array("name"=>"Product/Service #".($key+1),"val"=>"");
				$json_data1[] = array("name"=>"Product/Service Name","val"=>$itm['service_name']);
				$json_data1[] = array("name"=>"Product/Service Description/Benefits","val"=>$itm['service_description']);
				$json_data1[] = array("name"=>"Product/Service Price","val"=>$itm['service_price']);
				$json_data1[] = array("name"=>"&nbsp;","val"=>"&nbsp;");
			}
			$response['json_data'] = json_encode($json_data1,JSON_HEX_QUOT | JSON_HEX_APOS);



			$db->where("plan_id",$plan_id);
			$rs = $db->getOne("marketing_products_pricing");

			if($db->count){

				$last_md5 = $rs['last_md5'];
				$current_md5= $rs['current_md5'];

				if($last_md5 != $current_md5) {
					generate_api_output_chunk($plan,array("services"));
				}
			}

	break;
	case 'marketing-promotions-plan':

			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';

			$promotions_plans=array();
			$promotions_plans_arr = $db->get("master_marketing_promotions_plan");
			foreach($promotions_plans_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["promotions_plans"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$promotions_plans['check'][$v['slug']]=$check;
				$promotions_plans['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($promotions_plans),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('plan_id', $plan_id);		
			$rs = $db->get("marketing_promotions_plan");

			if(!($db->count)) {
				if($db->insert("marketing_promotions_plan",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update marketing_promotions_plan set last_md5=current_md5 where plan_id='".$plan_id."'");



				$db->where("plan_id",$plan_id);
				if($db->update("marketing_promotions_plan",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;
	case 'marketing-distribution-plan':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';




			$promotions_plans=array();
			$promotions_plans_arr = $db->get("master_marketing_distribution_plan");
			foreach($promotions_plans_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["distribution_plan"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$promotions_plans['check'][$v['slug']]=$check;
				$promotions_plans['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($promotions_plans),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('plan_id', $plan_id);
			$rs = $db->get("marketing_distribution_plan");

			if(!($db->count)) {
				if($db->insert("marketing_distribution_plan",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {


					unset($update_data['last_md5']);
					$db->query("update marketing_distribution_plan set last_md5=current_md5 where plan_id='".$plan_id."'");
					


				$db->where("plan_id",$plan_id);
				if($db->update("marketing_distribution_plan",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
	break;
	case 'operations-key-operational-processes':
			$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';




			$promotions_plans=array();
			$promotions_plans_arr = $db->get("master_key_operational_processes");
			foreach($promotions_plans_arr as $k => $v){
				$val ="";
				$check ='N';
				if(isset($_REQUEST["target_customers"][$v['slug']])) {
					$val = $_REQUEST[$v['slug']."_val"];
					$check = 'Y';
				}
				$promotions_plans['check'][$v['slug']]=$check;
				$promotions_plans['val'][$v['slug']] = $val;
			}

			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($promotions_plans),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("operational_processes");

			if(!($db->count)) {
				if($db->insert("operational_processes",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#management-team-members')",
							'redirect'=> '1==1',

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

					unset($update_data['last_md5']);
					$db->query("update operational_processes set last_md5=current_md5 where md5(plan_id)='".$plan."'");
					$db->where("md5(plan_id)",$plan);


				$db->where("md5(plan_id)",$plan);
				if($db->update("operational_processes",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#management-team-members')",
							'redirect'=> '1==1',

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
	break;
	case 'operations-milestones':
				$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';


			$key_data = array();
			if(isset($_REQUEST['key_accomplishment_dd']) && is_array($_REQUEST['key_accomplishment_mm'])){

				foreach($_REQUEST['key_accomplishment_dd'] as $k => $v) {
					
					$key_date = $_REQUEST['key_accomplishment_yy'][$k]."-".$_REQUEST['key_accomplishment_mm'][$k]."-".$_REQUEST['key_accomplishment_dd'][$k];
					$accomp_1=$_REQUEST['detail_accomplishment_date_1'][$k];
					$accomp_2=$_REQUEST['detail_accomplishment_date_2'][$k];
					$accomp_3=$_REQUEST['detail_accomplishment_date_3'][$k];


					$key_data[]= array(
						'key_accomplishment_date'=>$key_date,
						'key_yy'=>$_REQUEST['key_accomplishment_yy'][$k],
						'key_mm'=>$_REQUEST['key_accomplishment_mm'][$k],
						'key_dd'=>$_REQUEST['key_accomplishment_dd'][$k],
						'accomplishment_1'=>$accomp_1,
						'accomplishment_2'=>$accomp_2,
						'accomplishment_3'=>$accomp_3,
						);
				}
			}

			
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($key_data),
				'print_omit'=>$print_omit
			);



			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;



			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("operational_milestones");

			if(!($db->count)) {
				if($db->insert("operational_milestones",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {



					unset($update_data['last_md5']);
					$db->query("update operational_milestones set last_md5=current_md5 where md5(plan_id)='".$plan."'");
					$db->where("md5(plan_id)",$plan);


				$db->where("md5(plan_id)",$plan);
				if($db->update("operational_milestones",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully"
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	
	
	
	break;
	case 'management-team-members':
		//print_R($_POST);exit();
		$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';

			$validation_err= false;
			$team_member = array();
			if(isset($_REQUEST['team_name']) && is_array($_REQUEST['team_name'])){

				$nm=1;
				foreach($_REQUEST['team_name'] as $k => $v) {
 
					if($_REQUEST['team_name'][$k] !="" && trim($_REQUEST['team_background'][$k])=="") {
						$validation_err= true;
						$err[]= "please enter background for Team Member ".$nm." (".$_REQUEST['team_name'][$k].")";
					}
					$team_member[]= array(
						'name'=>$_REQUEST['team_name'][$k],
						'title'=>$_REQUEST['team_title'][$k],
						'background'=>$_REQUEST['team_background'][$k],
						);
					$nm++;
				}
			}


			if($validation_err){
					$response=array(
						'error'=>true,
						'msg'=>implode("<BR>",$err)
						);

			}
			else
			{
			
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($team_member),
				'print_omit'=>$print_omit
			);


			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("team_members");

			if(!($db->count)) {
				if($db->insert("team_members",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							//'redirect'=> "loadTab('#funding-requirements-funds')",
							'redirect'=> "selTab('management-team-members');loadTab('#financial-assumptions')",
						//	'redirect'=> '1==1',

						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {



				unset($update_data['last_md5']);
				$db->query("update team_members set last_md5=current_md5 where md5(plan_id)='".$plan."'");
				$db->where("md5(plan_id)",$plan);



				$db->where("md5(plan_id)",$plan);
				if($db->update("team_members",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							//'redirect'=> "loadTab('#funding-requirements-funds')",
						//	'redirect'=> '1==1',
							'redirect'=> "selTab('management-team-members');loadTab('#financial-assumptions')",

						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}



			$db->where('md5(plan_id)',$plan);
			$list = $db->getOne('team_members');
			$rcount1 = $db->count;
			$team_members_arr = isset($list['details']) ? json_decode($list['details'],true): array();
			$json_data1=$json_data2=$json_data3=array();
			
			
			
			
			if($rcount1) {
				foreach($team_members_arr as $key=>$itm){
					$json_data1[] = array("name"=>"Members #".($key+1),"val"=>"");
					$json_data1[] = array("name"=>"Name","val"=>$itm['name']);
					$json_data1[] = array("name"=>"Title","val"=>$itm['title']);
					$json_data1[] = array("name"=>"Background","val"=>$itm['background']);
					$json_data1[] = array("name"=>"","val"=>"&nbsp;");
				}
			}



			$response['json_data']= json_encode($json_data1,JSON_HEX_QUOT | JSON_HEX_APOS);



			

			$db->where("md5(plan_id)",$plan);
			$rs = $db->getOne("team_members");

			if($db->count){

				$last_md5 = $rs['last_md5'];
				$current_md5= $rs['current_md5'];

				if($last_md5 != $current_md5) {
					generate_api_output_chunk($plan,array("management_team"));
				}
			}

}





	break;
	
	case 'board_member':
				$db->where('md5(plan_id)',$plan);
			$r =$db->getOne("plan_master");
			$plan_id = isset($r['plan_id']) ? $r['plan_id']: 0;

			$print_omit = isset($_REQUEST['print_omit']) ? 'Y':'N';


			$team_member = array();
			if(isset($_REQUEST['board_name']) && is_array($_REQUEST['board_name'])){
				foreach($_REQUEST['board_name'] as $k => $v) {
					$team_member[]= array(
						'board_name'=>$_REQUEST['board_name'][$k],
						'past_position'=>$_REQUEST['past_position'][$k],
						);
				}
			}

			
			$update_data= array(
				'plan_id'=>$plan_id,
				'details'=>json_encode($team_member),
				'print_omit'=>$print_omit
			);

			$md5 = md5(json_encode($update_data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;


			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("team_board_members");

			if(!($db->count)) {
				if($db->insert("team_board_members",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
	'redirect'=> "loadTab('#financial-assumptions')",
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {

				unset($update_data['last_md5']);
				$db->query("update team_board_members set last_md5=current_md5 where md5(plan_id)='".$plan."'");
				$db->where("md5(plan_id)",$plan);



				$db->where("md5(plan_id)",$plan);
				if($db->update("team_board_members",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#financial-assumptions')",
						);
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;
	
	case 'financial-assumptions':
		//print_R($_POST);exit();
		$db->where("md5(plan_id)",$_REQUEST['plan']);
		$r = $db->getOne("plan_master");
		$plan_id= $r['plan_id'];
		
		$update_data = array(
		"plan_id"=>$plan_id,
		"raising_funding"=>$_REQUEST['raising_funding'],
		"seeking_bank_loan"=>$_REQUEST['seeking_bank_loan'],
		"details"=>$_REQUEST['financial-assumptions'],
		"capital_requirement"=>$_REQUEST['capital_requirement'],
		"operating_expenses"=>$_REQUEST['operating_expenses'],
		'revenue_validated'=>$_REQUEST['revenue_validated'],
		);

		$callPlan = trim($_REQUEST['revenue_validated']) == "" ? false : true;

			$md5 = md5(json_encode($data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			$db->where('md5(plan_id)', $plan);
			$rs = $db->get("financial_assumptions");

			if(!($db->count)) {
				if($db->insert("financial_assumptions",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#generateDocument')",							
							'redirect'=>"selTab('financial-assumptions');loadTab('#revenue-assumptions')",
						);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {
				unset($update_data['last_md5']);
				$db->query("update financial_assumptions set last_md5=current_md5 where md5(plan_id)='".$plan."'");
				$db->where("md5(plan_id)",$plan);

				$db->where("md5(plan_id)",$plan);
				if($db->update("financial_assumptions",$update_data)) {
						$response=array(
							'error'=>false,
							'msg'=>"Record updated successfully",
							'redirect'=> "loadTab('#generateDocument')",
							'redirect'=>"selTab('financial-assumptions');loadTab('#revenue-assumptions')",
						);
						if(!$callPlan){
			//					$response['redirect']='';
						}
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}

	break;
	
	case 'unsubscribeContact':

	$db->where("user_id", intval($_SESSION['PRO_USER_ID']));
	$udata = $db->getOne("user_master");
	$user_email =$udata['user_email'];


		$curl = curl_init();

	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "PUT",
	  CURLOPT_POSTFIELDS => "{\"list_ids\":[\"78713ddc-07ed-4e00-9b89-35f38b31e1e6\"],\"contacts\":[{\"email\":\"". $user_email ."\"}]}",
	  CURLOPT_HTTPHEADER => array(
		"authorization: Bearer ".SENDGRID_API_KEY,
		"content-type: application/json"
	  ),
	));

	$response = curl_exec($curl);

//		file_put_contents("aa.txt", serialize($response)."\r\n", FILE_APPEND);

	$err = curl_error($curl);

	curl_close($curl);

	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts/search",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//		CURLOPT_CUSTOMREQUEST => "PUT",
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"query\": \"email like '".$user_email."' \"}",
	CURLOPT_HTTPHEADER => array(
	"authorization: Bearer ".SENDGRID_API_KEY,
	"content-type: application/json"
	  ),
	));

	//f960ef44-7473-4047-b05b-27b114c7ca52

	$response1 = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);


//		file_put_contents("aa.txt", serialize($response1)."\r\n",  FILE_APPEND);

	$result = json_decode($response1,true);

	$id=0;
	if(isset($result['result'])){
		$id = $result['result'][0]['id'];


$jsonData = json_encode(array("contact_ids" => array($id)));

//print_r($jsonData);

//f960ef44-7473-4047-b05b-27b114c7ca52
		$curl = curl_init();
			curl_setopt_array($curl, array(
				//			https://api.sendgrid.com/v3/marketing/lists/f960ef44-7473-4047-b05b-27b114c7ca52/contacts/delete
			CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/f960ef44-7473-4047-b05b-27b114c7ca52/contacts",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "DELETE",
//			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $jsonData,
			//"{\"contact_ids\": \"".$id."\"}",
			CURLOPT_HTTPHEADER => array(
			"authorization: Bearer ".SENDGRID_API_KEY,
			"content-type: application/json"
			  ),
			));

	//f960ef44-7473-4047-b05b-27b114c7ca52

	$response1 = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

//	file_put_contents("aa.txt", serialize($response1)."\r\n", FILE_APPEND);

	}

	break;
	case 'unsubscribeContact1':
	$db->where("user_id", $_REQUEST['user']);
	$udata = $db->getOne("user_master");
	$user_email =$udata['user_email'];




	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts/search",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//		CURLOPT_CUSTOMREQUEST => "PUT",
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"query\": \"email like '".$user_email."' \"}",
	CURLOPT_HTTPHEADER => array(
	"authorization: Bearer ".SENDGRID_API_KEY,
	"content-type: application/json"
	  ),
	));

	//f960ef44-7473-4047-b05b-27b114c7ca52

	$response1 = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	$result = json_decode($response1,true);

	$id=0;
	if(isset($result['result'])){
		$id = $result['result'][0]['id'];


//f960ef44-7473-4047-b05b-27b114c7ca52
		$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/f960ef44-7473-4047-b05b-27b114c7ca52/contacts",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "DELETE",
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => "{\"contact_ids\": \"".$id."\"}",
	CURLOPT_HTTPHEADER => array(
	"authorization: Bearer ".SENDGRID_API_KEY,
	"content-type: application/json"
	  ),
	));

	$response1 = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	}
	break;
	
	case 'financial_assumptions_edited':
		
		//print_r($_POST);exit();
		$db->where("md5(plan_id)",$_REQUEST['plan_id']);
		$r = $db->getOne("plan_master");
		$plan_id= $r['plan_id'];
		$plan = $r['plan_id'];
		
		$update_data = array(
			"plan_id"=>$plan_id,			
			"capital_requirement"=>$_REQUEST['capital_expes'],
			"operating_expenses"=>$_REQUEST['operating_expes'],			
		);
		

			$md5 = md5(json_encode($data));
			$update_data['current_md5']= $md5;
			$update_data['last_md5']= $md5;

			//$db->where('md5(plan_id)', $plan);
			$db->where('plan_id', $plan);
			$rs = $db->get("financial_assumptions");
			
			if(!($db->count)) {
				if($db->insert("financial_assumptions",$update_data)) {
					
					$response=array(
						'error'=>false,
						'msg'=>"Record insert successfully",
						'redirect'=> "loadTab('#generateDocument')",							
						'redirect'=>"loadTab('#revenue-assumptions')",
					);
				}
				else {
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
						);
				}
			}
			else {
				unset($update_data['last_md5']);
				$db->query("update financial_assumptions set last_md5=current_md5 where md5(plan_id)='".$plan."'");
				//$db->where("md5(plan_id)",$plan);

				//$db->where("md5(plan_id)",$plan);
				$db->where("plan_id",$plan);
				if($db->update("financial_assumptions",$update_data)) {
					
					$response=array(
						'error'=>false,
						'msg'=>"Record updated successfully",
						'redirect'=> "loadTab('#generateDocument')",
						'redirect'=>"loadTab('#revenue-assumptions')",
					);						
				}
				else
				{
					$response=array(
						'error'=>true,
						'msg'=>$db->getLastError()
					);
				}
			}

	break;


	//n1 code start for revenue Assumptions


	


	case 'revenue_assumptions_detail':
		
		

		$plan = $_POST["plan_id"];
		$db->where("md5(plan_id)", $plan);
		$res = $db->getOne("financial_assumptions");
		
		
	
		$capital_requirement = $res['capital_requirement'];
		$operating_expenses = $res['operating_expenses'];
		$revenue_validated = $res['revenue_validated'];

		
    	

		/*echo "<pre>";
    	print_r($res);exit();*/


		// Example usage
		//$result = processFinancialData($capital_requirement, $operating_expenses, $revenue_validated);
		$result = processFinancialData_New($capital_requirement, $operating_expenses, $revenue_validated);




		$individual_revenues = $result;



		$dhtml .='<fieldset>';
		ob_start();
		?>
			<section class="faq-section pb-5 border-bottom">
				<div class="edit_assumption">
					<div class="edit_title"><span style="text-decoration: blink;">💡</span> Want to edit the assumptions?</div>
					<div class="edit_desc">Once you subscribe, you will be able to edit them using the financial<br>editor within ProAI, or directly in the financial model once downloaded.</div>
				</div>

                <div class="row revenue_faq">
                    <!-- ***** FAQ Start ***** -->
                    <div class="col-12">
                        <div class="faq" id="accordion">						
							<?php
							$row=0;

							foreach($individual_revenues['revenue_line_items'] as $individual)
							{
								
							$row++;
							?>
								<div class="card ">
									<div class="card-header" id="faqHeading-<?php echo $row; ?>">
										<div class="mb-0">
											<h5 class="faq-title d-flex justify-content-between align-items-center m-0" data-toggle="collapse" data-target="#faqCollapse-<?php echo $row; ?>" data-aria-expanded="true" data-aria-controls="faqCollapse-<?php echo $row; ?>">Revenue Line Item - <?php echo $individual["name"]; ?> <!--p class="text-muted"><?php echo $individual["type"]; ?></p -->
 											<span><i class="fa fa-angle-down" aria-hidden="true"></i></span></h5>
										</div>
									</div>
									<div id="faqCollapse-<?php echo $row; ?>" class="collapse" aria-labelledby="faqHeading-<?php echo $row; ?>" data-parent="#accordion">
										<div class="card-body">
											<div class="revenue_blocks_wrapper">
												<div class="revenue_blocks_skils">
													<h4 class="py-2-5 pl-6 mb-0 color-011627 text-sm font-semibold ">Annual Monthly Sales Units</h4>
													<?php
														$monthly_sales = $individual["annual_monthly_sales_units"];														
														$i=1;
														$row1 = '';
														$row2 = '';
														foreach($monthly_sales as $monthsales){
															$row1 .='<td style="width:20%;text-align:center;">FY'.$i.'</td>';
															//$row2 .="<td style='width:20%;text-align:center;'><strong>".$monthsales."</strong></td>";
                                                          	$row2 .="<td style='width:20%;text-align:center;'><strong>".number_format(round(floatval($monthsales)),0)."</strong></td>";
															$i++;
														}
													?>
													<table>
                                                    	<tr class="color-364D61 text-sm font-medium">
														<?php echo $row1; ?>
														</tr>
														<tr class="color-011627 text-sm font-semibold">
															<?php echo $row2; ?>
														</tr>
													</table>
												</div>
												<div class="revenue_blocks_skils">
													<h4 class="py-2-5 pl-6 mb-0 color-011627 text-sm font-semibold ">Cost Per Units</h4>
														<?php
														$cost_units = $individual["cost_per_units"];
														$j=1;
														$row3 = '';
														$row4 = '';
														foreach($cost_units as $unitscost){

															$row3 .='<td style="width:20%;text-align:center;">FY'.$j.'</td>';
															//$row4 .="<td style='width:20%;text-align:center;'><strong>".round($unitscost)."</strong></td>";
                                                          	$row4 .="<td style='width:20%;text-align:center;'><strong>".number_format(round(floatval($unitscost)),2)."</strong></td>";
															$j++;
														}
														?>									
													<table>
														<tr class="color-364D61 text-sm font-medium">
														<?php echo $row3; ?>
														</tr>
														<tr class="color-011627 text-sm font-semibold">
															<?php echo $row4; ?>
														</tr>
													</table>
												</div>
												<div class="revenue_blocks_skils">
													<h4 class="py-2-5 pl-6 mb-0 color-011627 text-sm font-semibold ">Growth Rate</h4>
								
													<?php
														$growthrate = $individual["growth_rate"];														
														$k=1;
														$row5 = '';
														$row6 = '';
														foreach($growthrate as $g_rate){
															$row5 .='<td style="width:20%;text-align:center;">FY'.$k.'</td>';
															$row6 .="<td style='width:20%;text-align:center;'><strong>".number_format((float)$g_rate, 2, '.', '')."%</strong></td>";
															$k++;
														}
													?>													
													<table>
														<tr class="color-364D61 text-sm font-medium">
														<?php echo $row5; ?>
														</tr>
														<tr class="color-011627 text-sm font-semibold">
															<?php echo $row6; ?>
														</tr>
													</table>
												</div>
												<h5 class="mb-0 py-3-5 pl-6 color-011627 text-sm font-semibold">Price per unit : <?php echo $individual["price_per_units"]; ?></h5>								
											</div>
										</div>
									</div>
								</div>
							<?php
							} 
							?>
				    	</div>
				    </div>
				</div>
			</section>
			<div class="revenu_new_wrap">
				<?php

						

				if(isset($individual_revenues['annual_cogs']) && is_array($individual_revenues['annual_cogs']))
				{
					$row1=$row2='';
					$i=1;

					


					foreach($individual_revenues['annual_cogs'] as $itm){
						$row1 .= '<td>FY'.$i++.'</td>';
						$row2 .= '<td><strong>'.number_format(round(floatval($itm)),2).'</strong></td>';
					}
					?>
					<div class="revenue_blocks_skils">
						<h4 class="pt-50 pb-2-5 mb-0 color-011627 md-text-lg text-base font-semibold ">Annual COGS</h4>
						<div class="table-responsive">
							<table>
								<tr class="color-364D61 text-sm font-medium">
									<?php echo $row1; ?>
								</tr>
								<tr class="color-011627 text-sm font-semibold">
									<?php echo $row2; ?>
								</tr>
							</table>
						</div>
					</div>
				<?php
				}
				?>
				<?php
				if($individual_revenues['annual_ebitda'])
				{
					$row1=$row2='';
					$i=1;
					foreach($individual_revenues['annual_ebitda'] as $itm){
						$row1 .= '<td>FY'.$i++.'</td>';
						$row2 .= '<td><strong>'.number_format(round(floatval($itm)),2).'</strong></td>';
					}
				?>
					<div class="revenue_blocks_skils">
						<h4 class="pt-8 pb-2-5 mb-0 color-011627 md-text-lg text-base font-semibold ">Annual EBITDA</h4>
						<div class="table-responsive">
							<table>
								<tr class="color-364D61 text-sm font-medium">
									<?php echo $row1; ?>
								</tr>
								<tr class="color-011627 text-sm font-semibold">
									<?php echo $row2; ?>
								</tr>
							</table>	
						</div>					
					</div>
				<?php
				}
				?>					
				<?php
				if($individual_revenues['annual_revenues'])
				{
					$row1=$row2='';
					$i=1;
					foreach($individual_revenues['annual_revenues'] as $itm){
						$row1 .= '<td>FY'.$i++.'</td>';
						$row2 .= '<td><strong>'.number_format(round(floatval($itm)),2).'</strong></td>';
					}
				?>
					<div class="revenue_blocks_skils">
						<h4 class="pt-8 pb-2-5 mb-0 color-011627 md-text-lg text-base font-semibold ">Annual REVENUES</h4>
						<div class="table-responsive">
							<table>
								<tr class="color-364D61 text-sm font-medium">
									<?php echo $row1; ?>
								</tr>
								<tr class="color-011627 text-sm font-semibold">
									<?php echo $row2; ?>
								</tr>
							</table>
						</div>						
					</div>
				<?php
				}
				?>
			</div>		
			<!-- <div style="margin:15px 0px; text-align:center;">
				<button class="themebtn_new theme_btn" onclick="loadTab('#generateDocument');">
				Generate My Business Plan
				<img class="loading_icon" src="<?php //echo SITE_URL; ?>/assets/image/spinner.svg">
            	</button>
			</div> -->
			<div class="generate_business_plan_btn_wrapper pt-50">
                <button class="generate_business_plan_btn font-poppins" style="border: 0;" onclick="showModal('#generateDocument');">🚀 Generate Business Plan</button>
            </div>

					    
			</fieldset>
				<script>
			//	jQuery(".revenueassumption_wrapper").accordion();
				/*jQuery(".revenueassumption_wrapper").accordion({
			                    header: "> div > h3",
			                    active: false,
			                    collapsible: true,
							    heightStyle: "content",
								navigation:true,
			                })
			                .sortable({
			                    axis: "y",
			                    handle: "h3",
			                    stop: function(event, ui) {
			                        // IE doesn't register the blur when sorting
			                        // so trigger focusout handlers to remove .ui-state-focus
			                        ui.item.children("h3").triggerHandler("focusout");

			                        // Refresh accordion to handle new order
			                        jQuery(this).accordion("refresh");
			                    }
			                });*/






				// jQuery("#itemhead1").trigger("click");
				//jQuery("#faqCollapse-1").trigger('click');
				//jQuery("#faqCollapse-1").show();
			    jQuery("#faqCollapse-1").addClass("show");
				</script>


				<?php
					$dhtml = ob_get_clean();


		$response=array(
			'id'=>1,
			'error'=>FALSE,
			'content'=>$dhtml,
			'msg'=> "Revenue Assumptions Generate"
		);
		//print_r($response);exit();
	break;

	

	// Review modal start
	case 'validatetp_review':

		//print_r($_POST);exit();
		   $db->where("md5(plan_id)",$_REQUEST['plan']);
		   $r= $db->getOne("plan_master");
		   $plan_id =$r['plan_id'];
		   
		   $data=array(
			   "email" =>$_REQUEST['txtemail'],
			   "fullname" =>$_REQUEST['txtfullname'],
			   "plan" =>md5($plan_id),
			   "user_id" =>$_SESSION['PRO_USER_ID'],

		   );
		   $db->insert("validatetp_review",$data);
		   //echo $query = $db->getLastQuery();exit();
		   $curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "PUT",
				CURLOPT_POSTFIELDS => "{\"list_ids\":[\"f960ef44-7473-4047-b05b-27b114c7ca52\"],\"contacts\":[{\"email\":\"". $txtemail ."\"}]}",
				CURLOPT_HTTPHEADER => array(
				"authorization: Bearer ".SENDGRID_API_KEY,
				"content-type: application/json"
				),
			));			
			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

		   $doc_id = $r['google_doc_id'];
		   echo 'loadDoc("'.$doc_id.'");';
		   /*$response = array(
                'error' => false,
                'id'=>"1",
                'msg' => "Review form detail added successfully",
                'redirect' => 'loadDoc("'.$doc_id.'")',
            );*/	
		   exit;
   break;
   //  Review modal end
	//n1 start code for dynamic Questionnari third part
	case 'dynamic_questionnaire_third':
		
		$plan_id = getPlanId();
		$credt = date('Y-m-d H:i:s');
		$user_id = $_SESSION["PRO_USER_ID"];
		$purpos_opt = $_POST["purpos_opt"];
		$purpos_specify_opt = $_POST["purpos_specify_opt"];
		$employee_opt = $_POST["employee_opt"];
		$role_opt = $_POST["role_opt"];


		if(((isset($purpos_opt) && $purpos_opt!="") || (isset($purpos_specify_opt) && $purpos_specify_opt!="")) && isset($employee_opt) && $employee_opt!="" && isset($role_opt) && $role_opt!="")
		{
			$db->where('plan_id',md5($plan_id));
			$db->where('user_id',$user_id);		
			$que_master = $db->getOne("dynamic_questionnaire_third");
			//echo $query = $db->getLastQuery();exit();
			if($db->count > 0){
				$update = array(
					'plan_id'=>md5($plan_id),
					'user_id'=>$user_id,
					'purpose'=>$purpos_opt,
					'specific_purpose'=>$purpos_specify_opt,
					'employee'=>$employee_opt,
					'role_company'=>$role_opt,
					'created_on'=>$credt
				);
				$db->where("user_id", $user_id);
				$db->where("plan_id", md5($plan_id));			
				$db->update("dynamic_questionnaire_third", $update);
				$que_id = $que_master["id"];
			}
			else{
				
				$input = array(
					'plan_id'=>md5($plan_id),
					'user_id'=>$user_id,
					'purpose'=>$purpos_opt,
					'specific_purpose'=>$purpos_specify_opt,
					'employee'=>$employee_opt,
					'role_company'=>$role_opt,
					'created_on'=>$credt
				);
				$db->insert('dynamic_questionnaire_third',$input);			
				$que_id = $db->getInsertId();
			}
			$response=array(
					'error'=>false,
					'id'=>'1',
					'que_id'=>$que_id,
					'msg'=> "Your Request submitted successfully"
				);
		}
		else {
			$response=array(
				'error'=>true,
				'id'=>'0',
				'msg'=>$db->getLastError()
				);
		}


	break;
	//n1 end code for dynamic Questionnari third part
	//n1 13-02-2024 existing company code start
	case 'existing_numbers1':

		$db->where("md5(plan_id)",$_REQUEST['plan_id']);
		//$db->where("md5(plan_id)",$_REQUEST['plan']);
		$r = $db->getOne("plan_master");
		$plan_id= $r['plan_id'];
		
		$update_data = array(
			"plan_id"=>$plan_id,
			"existing_number"=>$_REQUEST['existing_number'],			
		);
		$db->where('plan_id', $plan_id);
		$rs = $db->get("financial_assumptions");
		//echo $query = $db->getLastQuery();exit();
		
		if(!($db->count)) {			
			if($db->insert("financial_assumptions",$update_data)) {
					$response=array(
						'error'=>false,
						'msg'=>"Record updated successfully",
						//'redirect'=> "loadTab('#generateDocument')",							
						'redirect'=>"loadTab('#revenue-assumptions')",
					);
			}
			else {
				$response=array(
					'error'=>true,
					'msg'=>$db->getLastError()
					);
			}
		}
		else {
			//echo "else part run";exit();
			unset($update_data['last_md5']);
			$db->query("update financial_assumptions set last_md5=current_md5 where plan_id='".$plan_id."'");
			$db->where("plan_id",$plan_id);

			$db->where("plan_id",$plan_id);
			if($db->update("financial_assumptions",$update_data)) {
					$response=array(
						'error'=>false,
						'msg'=>"Record updated successfully",
						//'redirect'=> "loadTab('#generateDocument')",
						'redirect'=>"loadTab('#revenue-assumptions')",
					);
					/*if(!$callPlan){
					//$response['redirect']='';
					}*/
			}
			else
			{
				$response=array(
					'error'=>true,
					'msg'=>$db->getLastError()
					);
			}
		}
	break;
	//n1 13-02-2024 existing company code end
	//n1 13-02-2024	existing revenue assumption update code start
	case 'existing_revenue_numbers':

		//print_r($_POST);exit();
		$db->where("md5(plan_id)",$_REQUEST['plan_id']);
		//$db->where("md5(plan_id)",$_REQUEST['plan']);
		$r = $db->getOne("plan_master");
		$plan_id= $r['plan_id'];
		
		$update_data = array(
			"plan_id"=>$plan_id,
			"existing_revenue_validate"=>$_REQUEST['rev_existing_number'],			
		);
		$db->where('plan_id', $plan_id);
		$rs = $db->get("financial_assumptions");
		//echo $query = $db->getLastQuery();exit();
		
		if(!($db->count)) {			
			if($db->insert("financial_assumptions",$update_data)) {
					$response=array(
						'error'=>false,
						'msg'=>"Record updated successfully",
						'redirect'=> "loadTab('#generateDocument')",							
						//'redirect'=>"loadTab('#revenue-assumptions')",
					);
			}
			else {
				$response=array(
					'error'=>true,
					'msg'=>$db->getLastError()
					);
			}
		}
		else {
			//echo "else part run";exit();
			unset($update_data['last_md5']);
			$db->query("update financial_assumptions set last_md5=current_md5 where plan_id='".$plan_id."'");
			$db->where("plan_id",$plan_id);

			$db->where("plan_id",$plan_id);
			if($db->update("financial_assumptions",$update_data)) {
					$response=array(
						'error'=>false,
						'msg'=>"Record updated successfully",
						'redirect'=> "loadTab('#generateDocument')",
						//'redirect'=>"loadTab('#revenue-assumptions')",
					);
					/*if(!$callPlan){
					//$response['redirect']='';
					}*/
			}
			else
			{
				$response=array(
					'error'=>true,
					'msg'=>$db->getLastError()
					);
			}
		}
	break;
	//n1 13-02-2024 existing revenue assumption update code end

	//n1 21-02-2024 coupon code apply process code start
	case 'coupon_check':
		//print_R($_POST);exit();

		$coupon_name = $_POST["coupon_name"];

		/*$db->where("coupon_name",$coupon_name);
		$db->where("status","Y");
		$coupon_detail = $db->getOne("coupon_master");*/
		//echo $query = $db->getLastQuery();exit();		

		$coupon_detail1 = $db->query("SELECT * FROM coupon_master WHERE BINARY coupon_name = '".$coupon_name."' and status = 'Y'");		
		if($db->count > 0){		
			foreach($coupon_detail1 as $coupon_detail){
				$coupon_id = $coupon_detail["coupon_id"];
				$coupon_name = $coupon_detail["coupon_name"];
				$coupon_value = $coupon_detail["value"];
			}

			$response = array(
				'error'=>false,				
				'id'=>$coupon_id,
				'coupon_value'=>$coupon_value,
				'coupon_name'=>$coupon_name
			);

		}
		else{
			$response=array(
				'error'=>true,
				'msg'=>'Coupon not Match',
				'id'=>'0'
			);
		}

	break;
	//n1 21-02-2024 coupon code apply process code end

	//Assumption Tab Rvenue Stream data Edit process code Start
	case 'assumption_revenue_edit':
		

		$db->where("md5(plan_id)",$_POST['plan_id']);
		$r = $db->getOne("financial_assumptions");

		$operatingExpenses = json_decode($r['operating_expenses'], true);
	        
		// Update the revenue data
		foreach ($operatingExpenses['revenue'] as &$item) {
			if ($item['item_name'] === $_POST['org_revenue_name']) {
				$item['item_name'] = $_POST['assum_revenue_name'];
				$item['monthly_sales_units'] = $_POST['assum_qantity'];
				$item['annual_sales_growth_rate'] = $_POST['assum_revenue_growthrate'];
				$item['price_per_unit'] = $_POST['assum_revenue'];
				break;
			}
		}
		
		$updatedJson = json_encode($operatingExpenses);
		
		$update_data = array(			
			"operating_expenses"=>$updatedJson,			
		);		
		$db->where("md5(plan_id)",$_POST['plan_id']);
		if($db->update("financial_assumptions",$update_data)){
			$response=array(
				'error'=>false,
				'msg'=>"Revenue Stream Data Updated Successfully",				
			);
		}
		else
		{
			$response=array(
				'error'=>true,
				'msg'=>$db->getLastError()
			);
		}
		

	break;
	//Assumption Tab Rvenue Stream data Edit process code end
	case 'assumption_revenue_add':
		// print_r($_POST);exit();

		$db->where("md5(plan_id)",$_POST['plan_id']);
		$r = $db->getOne("financial_assumptions");

		$operatingExpenses = json_decode($r['operating_expenses'], true);
		$newItem = array(
            'item_name' => $_POST['add_revenue_name'],
            'monthly_sales_units' => intval($_POST['add_assum_quantity']),
            'annual_sales_growth_rate' => floatval($_POST['add_assum_growth_rate']),
            'price_per_unit' => floatval($_POST['add_assum_revenue']),
            'cost_per_unit_sold' => 0, // Default value or can be passed in newItemData
            'subscription' => array(
                'monthly_membership_price' => 0,
                'initial_members' => 0,
                'projected_monthly_member_growth_rate' => 0,
                'projected_monthly_member_churn_rate' => 0,
                'average_monthly_cost_per_member' => 0
            )
        );
        
        // Get the next key for revenue array
        $nextKey = max(array_keys($operatingExpenses['revenue'])) + 1;
        
        // Add new item to revenue array
        $operatingExpenses['revenue'][$nextKey] = $newItem;
        $updatedJson = json_encode($operatingExpenses);
		
		$update_data = array(			
			"operating_expenses"=>$updatedJson,			
		);		
		//print_R($update_data);exit();

		// $dhtml ='<tr class="expandable" data-item="'.htmlspecialchars(json_encode($newItem)).'"><td class="bg-28A271" data-toggle="modal" data-target="#myModalRevenue" data-revenuename="'.$_POST['add_revenue_name'].'" data-growthrate="'.$_POST['add_assum_growth_rate'].'" data-qantity="'.$_POST['add_assum_quantity'].'" data-revenue="'.$_POST['add_assum_revenue'].'" style="cursor: pointer;">'.$_POST['add_revenue_name'].'</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>';
		$db->where("md5(plan_id)",$_POST['plan_id']);
		if($db->update("financial_assumptions",$update_data)){
			$dhtml ='<tr class="expandable" ><td class="bg-28A271" data-toggle="modal" data-target="#myModalRevenue" data-item="'.htmlspecialchars(json_encode($newItem)).'" style="cursor: pointer;">'.$_POST['add_revenue_name'].'</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>';
			$response=array(
				'error'=>false,
				'msg'=>"Revenue Stream Data Inserted Successfully",
				'contnet'=>$dhtml				
			);
		}
		else
		{
			$response=array(
				'error'=>true,
				'msg'=>$db->getLastError()
			);
		}
		/*$response=array(
			'error'=>false,
			'msg'=>"Revenue Stream Data Inserted Successfully",				
			'contnet'=>$dhtml
		);*/
	break;

}
echo json_encode($response);


function validateInput($input){

	return true;

}

function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}


function processFinancialData($capital_requirement, $operating_expenses, $revenue_validated) {
    // Decode the JSON strings into PHP arrays


    $capitalData = json_decode($capital_requirement, true);
    $operatingData = json_decode($operating_expenses, true);
	$revenueData = json_decode($revenue_validated, true);

	

	// Extract years from the balance sheet data
    $years = array_map(function($entry) {
        return date('Y', strtotime($entry['date']));
    }, $revenueData['balance_sheet']);

    // Prepare the output array
    $output = array(
        "revenue_line_items" => [],
        "annual_cogs" => [],
        "annual_ebitda" => [],
        "annual_revenues" => [],
    );

    // Process each revenue item
    foreach ($operatingData['revenue'] as $key => $revenueItem) {
       
		

		if($revenueItem['subscription']['monthly_membership_price'] > 0 ){
			$revenueLineItem = array(
				'type'=>'Recurring',
				"name" => $revenueItem['item_name'],
				"annual_monthly_sales_units" => array_fill_keys($years, $revenueItem['subscription']['initial_members']),
				"cost_per_units" => array_fill_keys($years, $revenueItem['subscription']['average_monthly_cost_per_member']),
				"growth_rate" => array_fill_keys($years, $revenueItem['subscription']['projected_monthly_member_growth_rate']),
				"price_per_units" => $revenueItem['subscription']['monthly_membership_price'],
			);
	
		}
		else{
			
		
		$revenueLineItem = array(
			'type'=>'One-Time',
            "name" => $revenueItem['item_name'],
            "annual_monthly_sales_units" => array_fill_keys($years, $revenueItem['monthly_sales_units']),
            "cost_per_units" => array_fill_keys($years, $revenueItem['cost_per_unit_sold']),
            "growth_rate" => array_fill_keys($years, $revenueItem['annual_sales_growth_rate']),
            "price_per_units" => $revenueItem['price_per_unit'],
        );
	}
        $output['revenue_line_items'][] = $revenueLineItem;
    }

    // Fill annual COGS, EBITDA, and Revenues
    foreach ($years as $index => $year) {
        $output['annual_cogs'][$year] = $revenueData['pnl_annual'][$index]['Total COGS'] ?? 0;
        $output['annual_ebitda'][$year] = $revenueData['pnl_annual'][$index]['EBITDA'] ?? 0;
        $output['annual_revenues'][$year] = $revenueData['pnl_annual'][$index]['Total Revenue'] ?? 0;
    }

    return $output;
}
//REvenue Assumption new functions code Start
function processFinancialData_New($capital_requirement, $operating_expenses, $revenue_validated) {
    // Decode the JSON strings into PHP arrays


    $capitalData = json_decode($capital_requirement, true);
    $operatingData = json_decode($operating_expenses, true);
	$revenueData = json_decode($revenue_validated, true);

	

	// Extract years from the balance sheet data
    $years = array_map(function($entry) {
        return date('Y', strtotime($entry['date']));
    }, $revenueData['balance_sheet']);

    // Prepare the output array
    $output = array(
        "revenue_line_items" => [],
        "annual_cogs" => [],
        "annual_ebitda" => [],
        "annual_revenues" => [],
    );

    // Process each revenue item
    foreach ($operatingData['revenue'] as $key => $revenueItem) {
       
		
		$revenueItemunit = "";
		if($revenueItem['subscription']['monthly_membership_price'] > 0 ){
          	$revenueItemunit = $revenueItem['subscription']['initial_members'];
			$revenueLineItem = array(
				'type'=>'Recurring',
				"name" => $revenueItem['item_name'],
				"annual_monthly_sales_units" => array_fill_keys($years, $revenueItem['subscription']['initial_members']),
				"cost_per_units" => array_fill_keys($years, $revenueItem['subscription']['average_monthly_cost_per_member']),
				"growth_rate" => array_fill_keys($years, $revenueItem['subscription']['projected_monthly_member_growth_rate']),
				"price_per_units" => $revenueItem['subscription']['monthly_membership_price'],
			);
	
		}
		else{	
			$revenueItemunit = $revenueItem['monthly_sales_units'];
			$revenueLineItem = array(
				'type'=>'One-Time',
	            "name" => $revenueItem['item_name'],
	            "annual_monthly_sales_units" => array_fill_keys($years, $revenueItem['monthly_sales_units']),
	            "cost_per_units" => array_fill_keys($years, $revenueItem['cost_per_unit_sold']),
	            "growth_rate" => array_fill_keys($years, $revenueItem['annual_sales_growth_rate']),
	            "price_per_units" => $revenueItem['price_per_unit'],
	        );
		}

	
      	$revenue_itemkey = 'Revenue - '.$revenueItem['item_name'];
		$revenue_unit_key = 'Quantity - ' . $revenueItem['item_name'];
      	$revenue_costperitem_key = 'CostPerItem - ' . $revenueItem['item_name'];
      	foreach ($years as $index => $year) {
          	$revenuunitsoldvalue = $revenueData['pnl_annual'][$index][$revenue_unit_key];
          	$revenucostperitemvalue = $revenueData['pnl_annual'][$index][$revenue_costperitem_key];
          	$revenueLineItem['annual_monthly_sales_units'][$year] = $revenuunitsoldvalue ?? 0;
	        $revenueLineItem['cost_per_units'][$year] = $revenucostperitemvalue ?? 0;
        }
      	//exit();
        $output['revenue_line_items'][] = $revenueLineItem;
    }

    // Fill annual COGS, EBITDA, and Revenues
    foreach ($years as $index => $year) {
        $output['annual_cogs'][$year] = $revenueData['pnl_annual'][$index]['Total COGS'] ?? 0;
        $output['annual_ebitda'][$year] = $revenueData['pnl_annual'][$index]['EBITDA'] ?? 0;
        $output['annual_revenues'][$year] = $revenueData['pnl_annual'][$index]['Total Revenue'] ?? 0;
    }

    return $output;
}
//Revenue Assumption new functions code End

?>