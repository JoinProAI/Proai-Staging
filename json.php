<?php

function generateJsonPitchDeck($plan){
	global $db;

	$db->where('md5(auto_id)',$plan);
	$res = $db->getOne("pitchdeck_storage");
	$json1 = json_decode($res['jsondata'],true);
	$output = array_merge_recursive($json1, array());

	$db->where('md5(plan_id)',$plan);
	$lists = $db->get("pitchdeck_output");
	foreach($lists as $list){
		$json1 = json_decode($list['api_output'],true);
		$output = array_merge_recursive($output, $json1);
	}

	$q = "select * from plan_master where plan_id='".getPlanId()."'";
	$rs = $db->rawQuery($q);
	if($db->count){
		foreach($rs as $list){
			$output['company_info']['overview'] = $list['industry_operate'];
		}
	}

	$output['overview']['language'] ='English'; 
	return $output;
}

function generateJsonSmall($plan) {
	global $db;

	$prompt_list =array();

	$db->where('md5(plan_id)',@$plan);
	$list1 = $db->getOne('plan_master');
	$plan_id = $list1['plan_id'];

	$db->where('md5(plan_id)',@$plan);
	$list2 = $db->getOne('company_overview');

	$db->where('md5(plan_id)',@$plan);
	$list3 = $db->getOne('cover_page_information_master');

	$db->where('md5(plan_id)',@$plan);
	$list4 =$db->getOne("marketing_products_pricing");
	$list_pricing= array();

	$l_cnt = $db->count;
	$exe_overview='';
	$service_list=array();
	if($l_cnt){
		$det = @json_decode(@$list4['details'],true);
		foreach($det as $key=>$k) {
			$exe_overview .=$k['service_name'].",";
			$list_pricing[$key]['service_name']=trim($k['service_name']);
			$list_pricing[$key]['service_description']=trim($k['service_description']);
			$list_pricing[$key]['service_price']=trim($k['service_price']);
			$service_list[]= "Product/Service: ".trim($k['service_name']).".\r\nPricing: ".trim($k['service_price']).".\r\nDescirption:".trim($k['service_description']).".";
		}
		$exe_overview = trim($exe_overview,",");
	}

	$db->where('md5(plan_id)',@$plan);
	$list5 = $db->getOne('executive_success_factores');
	
	$output=array();
	$output['Sector_Assignment']= $parseJson;
	$output["overview"]= array(
	'language'=>trim(@$list1['language']),
	'existing_company'=> (@$list1['existing_company']=='Y') ? "Existing" : 'New',
	'industry'=> $industry,
	'seeking_fund'=> trim(@$list1['seeking_fund']),
	'formation_date'=> isset($list2['formation_date']) ? trim(@$list2['formation_date']) : "0000-00-00",
	'business_operational'=> isset($list2['business_oprational']) ? trim(@$list2['business_oprational']) : "No",
	'legal_structure'=> isset($legal_structure_arr[$list2['legal_structure']]) ? @$legal_structure_arr[@$list2['legal_structure']] : '',
	);

	$output['cover_page']=array(
		'company_name'=>@$list3['company_name'],
		'city'=>@$list3['city'],
		'full_name'=>@$list3['full_name'],
		'title'=>@$list3['title'],
		'email'=>@$list3['email'],
		'street_address'=>@$list3['street_address'],
		'city'=>@$list3['city'],
		'state'=>@$list3['state'],
		'pincodes'=>@$list3['zipcode'],
	);

	$success_factors = @json_decode(@$list5['success_factor'],true);

	$output['executive_summary']=array(
	'product_servicing_price'=>$list_pricing,
	'product_service_offering'=>array('value'=>@$exe_overview),
		'overview'=>array(
			'success_factors'=>array(
				'product_and_services'=>grabValue('products_or_services',@$success_factors),
				'human_resources'=>grabValue('human_resources',@$success_factors),
				'location'=>grabValue('location',@$success_factors),
				'operational_systems'=>grabValue('operational_systems',@$success_factors),
				'intelluctual_property'=>grabValue('intellectual_property',@$success_factors),
				'customers'=>grabValue('customers',@$success_factors),
				'marketing'=>grabValue('marketing',@$success_factors),
				'successes_achieved_to_date'=>grabValue('successes_achieved_to_date',@$success_factors),
				'other'=>grabValue('other',@$success_factors),
				),
			)
		);

	return $output;
}

function generateJson($plan) {
	global $db;

	$prompt_list =array();

	$db->where('md5(plan_id)',@$plan);
	$list1 = $db->getOne('plan_master');
	$plan_id = $list1['plan_id'];

	$db->where('md5(plan_id)',@$plan);
	$list2 = $db->getOne('company_overview');

	$db->where('md5(plan_id)',@$plan);
	$list3 = $db->getOne('cover_page_information_master');

	$db->where('md5(plan_id)',@$plan);
	$list4 =$db->getOne("marketing_products_pricing");
	$list_pricing= array();

	$l_cnt = $db->count;
	$exe_overview='';
	$service_list=array();
	if($l_cnt){
		$det = @json_decode(@$list4['details'],true);
		foreach($det as $key=>$k) {
			$exe_overview .=$k['service_name'].",";
			$list_pricing[$key]['service_name']=trim($k['service_name']);
			$list_pricing[$key]['service_description']=trim($k['service_description']);
			$list_pricing[$key]['service_price']=trim($k['service_price']);
			$service_list[]= "Product/Service: ".trim($k['service_name']).".\r\nPricing: ".trim($k['service_price']).".\r\nDescirption:".trim($k['service_description']).".";
		}
		$exe_overview = trim($exe_overview,",");
	}

	$db->where('md5(plan_id)',@$plan);
	$list5 = $db->getOne('executive_success_factores');

	$db->where('md5(plan_id)',@$plan);
	$list6 = $db->getOne('industry_overview');

	$db->where('md5(plan_id)',@$plan);
	$list7 = $db->getOne('target_customers');

	$db->where('md5(plan_id)',@$plan);
	$list8 = $db->getOne('customers_need');

	$db->where('md5(plan_id)',@$plan);
	$list9 = $db->getOne('compitition_directs');

	$db->where('md5(plan_id)',@$plan);
	$list10 = $db->getOne('compitition_indirect');

	$db->where('md5(plan_id)',@$plan);
	$list11 = $db->getOne('marketing_promotions_plan');

	$db->where('md5(plan_id)',@$plan);
	$list12 = $db->getOne('marketing_distribution_plan');

	$db->where('md5(plan_id)',@$plan);
	$list13 = $db->getOne('operational_processes');

	$db->where('md5(plan_id)',@$plan);
	$list14 = $db->getOne('operational_milestones');

	$db->where('md5(plan_id)',@$plan);
	$list15 = $db->getOne('team_members');
  
	$db->where('md5(plan_id)',@$plan);
	$list16 = $db->getOne('team_management_gaps');

	$db->where('md5(plan_id)',@$plan);
	$list17 = $db->getOne('compitition_analysis');

	$accomp = @json_decode(@$list2['accomplishment'],true);
	$output=array();

	$db->where("md5(plan_id)", @$plan);
	$list18 = $db->getOne("niac_code");

	if(!$db->count){
	
		$prompt= array(
			array("role"=>"user","content"=>"Based on what this company does, provide the closest NAICS code, and then separately the most relevant Industry and Subindustry in JSON Format: '".trim(@$list1['industry_operate'])."'")
		);
		$response = callgpt35($prompt);

		if(isset($response['choices'][0]['message']['content'])) {
				$jsontext = $response['choices'][0]['message']['content'];
				$s = strpos($jsontext, "{");
				$e = strpos($jsontext, "}") +1;
				$jsontext = substr($jsontext, $s, $e-$s);
				$parseJson = json_decode($jsontext, true);
				if(is_array($parseJson)) {
						$db->query("insert into `niac_code` (plan_id, content) values ('".$plan_id."','".$db->escape($jsontext)."') on duplicate key update content='".$db->escape($jsontext)."'"); 
				}
		}
	}
	else
	{
		$parseJson = json_decode($list18['content'], true);
	}

	$parseJson['SubIndustry']=trim($parseJson['Subindustry']);

	$industry = trim(@$list1['industry_operate']);

	if(TokenizerX::count($industry) >= 45)  {
		$db->where('slug',"industry");
		$sum_input = $db->getOne("openai_summerize_input_prompt");
		$prompt= array(
			array("role"=>"system", "content"=>trim($sum_input['system_prompt'])),
			array("role"=>"user","content"=>trim(@$list1['industry_operate']))
		);
		$response = callgpt35($prompt);
		if(isset($response['choices'][0]['message']['content'])) {
			$industry = trim($response['choices'][0]['message']['content']);
		}
	}

	$output['Sector_Assignment']= $parseJson;

	$output["overview"]= array(
	'language'=>trim(@$list1['language']),
	'existing_company'=> (@$list1['existing_company']=='Y') ? "Existing" : 'New',
	'industry'=> $industry,
	'seeking_fund'=> trim(@$list1['seeking_fund']),
	'formation_date'=> isset($list2['formation_date']) ? trim(@$list2['formation_date']) : "0000-00-00",
	'business_operational'=> isset($list2['business_oprational']) ? trim(@$list2['business_oprational']) : "No",
	'legal_structure'=> isset($legal_structure_arr[$list2['legal_structure']]) ? @$legal_structure_arr[@$list2['legal_structure']] : '',
	'accomplishments'=> array(
						 'locations'=> grabValue('company_location',@$accomp),
						 'prior_funding'=>grabValue('company_prior_funding',@$accomp),
						 'products_services_launched'=>grabValue('company_product_and_services_launched',@$accomp),
						 'revenue'=>grabValue('company_revenue',@$accomp),
						 'partnerships'=>grabValue('company_partnerships',@$accomp),
						 'patents'=>grabValue('company_patents',@$accomp),
						 'number_employees'=>grabValue('company_employees',@$accomp),
						 'customer_contracts'=>grabValue('company_customer_contracts',@$accomp),
						 'other_milestones'=>grabValue('company_other_milestones',@$accomp),
						 ),
	);

	$output['cover_page']=array(
		'company_name'=>@$list3['company_name'],
		'city'=>@$list3['city'],
		'full_name'=>@$list3['full_name'],
		'title'=>@$list3['title'],
		'email'=>@$list3['email'],
		'street_address'=>@$list3['street_address'],
		'city'=>@$list3['city'],
		'state'=>@$list3['state'],
		'pincodes'=>@$list3['zipcode'],
	);

	$success_factors = @json_decode(@$list5['success_factor'],true);

	$output['executive_summary']=array(
	'product_servicing_price'=>$list_pricing,
	'product_service_offering'=>array('value'=>@$exe_overview),
		'overview'=>array(
			'success_factors'=>array(
				'product_and_services'=>grabValue('products_or_services',@$success_factors),
				'human_resources'=>grabValue('human_resources',@$success_factors),
				'location'=>grabValue('location',@$success_factors),
				'operational_systems'=>grabValue('operational_systems',@$success_factors),
				'intelluctual_property'=>grabValue('intellectual_property',@$success_factors),
				'customers'=>grabValue('customers',@$success_factors),
				'marketing'=>grabValue('marketing',@$success_factors),
				'successes_achieved_to_date'=>grabValue('successes_achieved_to_date',@$success_factors),
				'other'=>grabValue('other',@$success_factors),
				),
			)
		);

	$output['industry']=array(
	'overview'=>array(
	'last_year_monetary_sales'=>@$list6['last_year_sale'],
	'last_year_operators'=>@$list6['last_year_operators'],
	'key_product_and_services_segments'=>'service_segments'),
	);

	$target_customers= @json_decode($list7['details'],true);

	$output['customers']['target_customer']=array(
								'age'=>grabValue('target_customers_age', @$target_customers),
								'income'=>grabValue('target_customers_income', @$target_customers),
								'gender'=>grabValue('target_customers_gender', @$target_customers),
								'location'=>grabValue('target_customers_location', @$target_customers),
								'marital_status'=>grabValue('target_customers_marital_status', @$target_customers),
								'familty_size'=>grabValue('target_customers_family_size', @$target_customers),
								'occupation'=>grabValue('target_customers_occupation', @$target_customers),
								'language'=>grabValue('target_customers_language', @$target_customers),
								'education'=>grabValue('target_customers_education', @$target_customers),
								'values_beliefs'=>grabValue('target_customers_values_beliefs', @$target_customers),
								'activities'=>grabValue('target_customers_activities', @$target_customers),
								'business_size'=>grabValue('target_customers_business_size', @$target_customers),
								'other'=>grabValue('target_customers_other', @$target_customers),
								);

	$customer_needs= @json_decode($list8['details'],true);
	$output['customers']['customer_needs']=array(
								'speed'=>grabValue('customers_needs_speed', @$customer_needs),
								'quality'=>grabValue('customers_needs_quality', @$customer_needs),
								'location'=>grabValue('customers_needs_location', @$customer_needs),
								'reliability'=>grabValue('customers_needs_reliability', @$customer_needs),
								'comfort'=>grabValue('customers_needs_comfort', @$customer_needs),
								'price'=>grabValue('customers_needs_price', @$customer_needs),
								'value'=>grabValue('customers_needs_value', @$customer_needs),
								'customer_service'=>grabValue('customers_needs_customer_service', @$customer_needs),
								'convenience'=>grabValue('customers_needs_convenience', @$customer_needs),
								'ease_of_use'=>grabValue('customers_needs_ease_of_use', @$customer_needs),
								'other'=>grabValue('customers_needs_other', @$customer_needs),
								);

	$direct_comp = @json_decode(@$list9['details'],true);

	$output['competition']=array(
	'direct_competitors'=>array(0=>array(
	'competitor_name'=>'',
	'overview'=>'',
	'products_services_offered'=>'',
	'price'=>'',
	'revenues'=>'',
	'location'=>'',
	'customer_segments'=>'',
	'key_strengths'=>'',
	'key_weaknesses'=>'',
	)),
	'indirect_competitors'=>array(0=>array(
	'competitor_name'=>'',
	'compititor_overview'=>'',
	'compititor_product_services'=>'',
	'compititor_pricing'=>'',
	'compititor_revenues'=>'',
	'compititor_location'=>'',
	'compititor_segments'=>'',
	'compititor_strengths'=>'',
	'compititor_weaknesses'=>'',
	))
	);

		$comp_text=array();
	if(is_array($direct_comp)) {
		foreach($direct_comp as $key => $comp){
			$location = (@$comp['compititor_location']=='') ? "N/A" :  @$comp['compititor_location'];;
			$output['competition']['direct_competitors'][$key]['competitor_name'] = @$comp['compititor_name'];
			$output['competition']['direct_competitors'][$key]['overview'] = (@$comp['compititor_overview']=='') ? "N/A"  :@$comp['compititor_overview'];
			$output['competition']['direct_competitors'][$key]['products_services_offered'] = (@$comp['compititor_product_services']=='') ? "N/A" : @$comp['compititor_product_services'];
			$output['competition']['direct_competitors'][$key]['price'] = (@$comp['compititor_pricing']=='') ? "N/A" : @$comp['compititor_pricing'];;
			$output['competition']['direct_competitors'][$key]['revenues'] = (@$comp['compititor_revenues']=='') ? "N/A" : @$comp['compititor_revenues'];;
			$output['competition']['direct_competitors'][$key]['location'] = $location;
			$output['competition']['direct_competitors'][$key]['customer_segments'] = (@$comp['compititor_segments']=='') ? "N/A" :  @$comp['compititor_segments'];;
			$output['competition']['direct_competitors'][$key]['key_strengths'] = (@$comp['compititor_strengths']=='') ? "N/A" :  @$comp['compititor_strengths'];;
			$output['competition']['direct_competitors'][$key]['key_weaknesses'] = (@$comp['compititor_weaknesses']=='') ? "N/A" :  @$comp['compititor_weaknesses'];;
			$comp_text[]="Competitor Name: ".@$comp['compititor_name'].".\nLocation: ".$location."."; 
		}
	}

	$output['competitors_all'] = implode(",",$comp_text);

	$indirect_comp = @json_decode($list10['details'],true);

	if(is_array($direct_comp)) {
		foreach($direct_comp as $key => $comp){
			$output['competition']['indirect_competitors'][$key]['competitor_name'] = @$comp['compititor_name'];
			$output['competition']['indirect_competitors'][$key]['compititor_overview'] = @$comp['compititor_overview'];
			$output['competition']['indirect_competitors'][$key]['compititor_product_services'] = @$comp['compititor_product_services'];
			$output['competition']['indirect_competitors'][$key]['compititor_pricing'] = @$comp['compititor_pricing'];
			$output['competition']['indirect_competitors'][$key]['compititor_revenues'] = @$comp['compititor_revenues'];
			$output['competition']['indirect_competitors'][$key]['compititor_location'] = @$comp['compititor_location'];
			$output['competition']['indirect_competitors'][$key]['compititor_segments'] = @$comp['compititor_segments'];
			$output['competition']['indirect_competitors'][$key]['compititor_strengths'] = @$comp['compititor_strengths'];
			$output['competition']['indirect_competitors'][$key]['compititor_weaknesses'] = @$comp['compititor_weaknesses'];
		}
	}

	$promotions_plan = @json_decode($list11['details'],true);

	$output['product_service_all']=implode("\r\n", $service_list);

	$output['promotions_plan']=array(
	'banners_and_billboards'=>grabValue('marketing_promotions_plan_banners_billboards',@$promotions_plan),
	'blogs'=>grabValue('marketing_promotions_plan_blogs_podcasts',@$promotions_plan),
	'podcasts'=>grabValue('marketing_promotions_plan_blogs_podcasts', @$promotions_plan),
	'catalogs'=>grabValue('marketing_promotions_plan_catalogs', @$promotions_plan),
	'classified_ads'=>grabValue('marketing_promotions_plan_classified_ads', @$promotions_plan),
	'contests'=>grabValue('marketing_promotions_plan_contests', @$promotions_plan),
	'coupons'=>grabValue('marketing_promotions_plan_coupons', @$promotions_plan),
	'direct_mail'=>grabValue('marketing_promotions_plan_direct_mail', @$promotions_plan),
	'door_hangers'=>grabValue('marketing_promotions_plan_door_hangers', @$promotions_plan),
	'email_marketing'=>grabValue('marketing_promotions_plan_email_marketing', @$promotions_plan),
	'event_marketing'=>grabValue('marketing_promotions_plan_event_marketing', @$promotions_plan),
	'flyers'=>grabValue('marketing_promotions_plan_flyers', @$promotions_plan),
	'gift_certificates'=>grabValue('marketing_promotions_plan_gift_certificates', @$promotions_plan),
	'networking'=>grabValue('marketing_promotions_plan_networking', @$promotions_plan),
	'news_letters'=>grabValue('marketing_promotions_plan_newsletters', @$promotions_plan),
	'news_magazines'=>grabValue('marketing_promotions_plan_newspaper_magazine_ads', @$promotions_plan),
	'online_marketing'=>grabValue('marketing_promotions_plan_online_marketing', @$promotions_plan),
	'partnership_joint_ventures'=>grabValue('marketing_promotions_plan_partnerships_joint_ventures', @$promotions_plan),
	'postcards'=>grabValue('marketing_promotions_plan_postcards', @$promotions_plan),
	'press_releases'=>grabValue('marketing_promotions_plan_press_releases_pr', @$promotions_plan),
	'radio_ads'=>grabValue('marketing_promotions_plan_radio_tv_infomercials', @$promotions_plan),
	'telemarketing'=>grabValue('marketing_promotions_plan_telemarketing', @$promotions_plan),
	'trade_shows'=>grabValue('marketing_promotions_plan_trade_shows', @$promotions_plan),
	'word_of_mouth'=>grabValue('marketing_promotions_plan_word_of_mouth_viral_marketing', @$promotions_plan),
	'yellow_pages'=>grabValue('marketing_promotions_plan_yellow_pages', @$promotions_plan),
	'other'=>grabValue('marketing_promotions_plan_other', @$promotions_plan)
	);

	$distribution_plan = @json_decode($list12['details'],true);

	$output['distribution_plan']=array(
	'retail_location'=>grabValue('marketing_distribution_plan_retail_location', @$distribution_plan),
	'company_website'=>grabValue('marketing_distribution_plan_company_website', @$distribution_plan),
	'company_phone'=>grabValue('marketing_distribution_plan_company_phone', @$distribution_plan),
	'direct_mail'=>grabValue('marketing_distribution_plan_direct_mail', @$distribution_plan),
	'distributors'=>grabValue('marketing_distribution_plan_distributors', @$distribution_plan),
	'other_retailors'=>grabValue('marketing_distribution_plan_other_retailers_websites', @$distribution_plan),
	'partners'=>grabValue('marketing_distribution_plan_partners', @$distribution_plan),
	'other'=>grabValue('marketing_distribution_plan_other', @$distribution_plan)
	);

	$key_operational_processes = @json_decode($list13['details'],true);

	$output['operations']['key_operational_processes']= array(
	'product_development'=>grabValue('operational_processes_product_development',@$key_operational_processes),
	'sales'=>grabValue('operational_processes_sales', @$key_operational_processes),
	'marketing'=>grabValue('operational_processes_marketing', @$key_operational_processes),
	'finance'=>grabValue('operational_processes_finance', @$key_operational_processes),
	'customer_service'=>grabValue('operational_processes_customer_service', @$key_operational_processes),
	'manufacturing'=>grabValue('operational_processes_manufacturing', @$key_operational_processes),
	'administration'=>grabValue('operational_processes_administration', @$key_operational_processes),
	'accounting_payroll'=>grabValue('operational_processes_accounting_payroll', @$key_operational_processes),
	'human_resources'=>grabValue('operational_processes_human_resources', @$key_operational_processes),
	'legal'=>grabValue('operational_processes_legal', @$key_operational_processes),
	'purchasing'=>grabValue('operational_processes_purchasing', @$key_operational_processes),
	'other'=>grabValue('operational_processes_other', @$key_operational_processes));

	$key_operational_milestones = @json_decode(@$list14['details'],true);

	$output['operations']['milestones']=array(
	'key_accomplishment_date'=>grabValue('key_accomplishment_date',@$key_operational_milestones),
	'detail_accomplishment_date_1'=>grabValue('accomplishment_1', @$key_operational_milestones),
	'detail_accomplishment_date_2'=>grabValue('accomplishment_2', @$key_operational_milestones),
	'detail_accomplishment_date_3'=>grabValue('accomplishment_3', @$key_operational_milestones),
	);

	$team_members = @json_decode($list15['details'],true);
	if(is_array($team_members)){
		foreach($team_members as $key => $team_member){

			$background = @$team_member['background'];

			if(TokenizerX::count($background) >= 65)  {
				$db->where('slug',"management_team");
				$sum_input = $db->getOne("openai_summerize_input_prompt");
				$prompt= array(
					array("role"=>"system", "content"=>trim($sum_input['system_prompt'])),
					array("role"=>"user","content"=>trim(@$background))
				);
				$response = callgpt35($prompt);		
				if(isset($response['choices'][0]['message']['content'])) {
					$background = trim($response['choices'][0]['message']['content']);
				}
			}
			$output['team']['management']['members'][$key]['name']= @$team_member['name'];
			$output['team']['management']['members'][$key]['background']= @$team_member['background'];
			$output['team']['management']['members'][$key]['title']= @$team_member['title'];
		}
	}

	$team_gaps = @json_decode($list16['details'],true);

	if(is_array($team_gaps)) {
		foreach($team_gaps as $key => $team_gap) {
			$output['team']['management_gaps']['members'][$key]['title']= @$team_gap['title'];
			$output['team']['management_gaps']['members'][$key]['key_functional_area']= @$team_gap['key_functional_area'];
			$output['team']['management_gaps']['members'][$key]['qualities']= @$team_gap['qualities'];
		}
	}

$compitition_analytics=array();
	if($list17['details']!= null)
	{
	$compitition_analytics = json_decode($list17['details'],true);
	}
	$output['competitive_advantages']=array(
	'product_and_services'=>grabValue('compitition_analysis_products_or_services',@$compitition_analytics),
	'human_resources'=>grabValue('compitition_analysis_human_resources',@$compitition_analytics),
	'location'=>grabValue('compitition_analysis_location',@$compitition_analytics),
	'operational_systems'=>grabValue('compitition_analysis_operational_systems',@$compitition_analytics),
	'intelluctual_property'=>grabValue('compitition_analysis_intellectual_property',@$compitition_analytics),
	'customers'=>grabValue('compitition_analysis_customers',@$compitition_analytics),
	'marketing'=>grabValue('compitition_analysis_marketing',@$compitition_analytics),
	'other'=>grabValue('compitition_analysis_other',@$compitition_analytics),

	);

	$db->where("plan_id",$plan_id);
	$rs_api = $db->get("openai_output");
	foreach($rs_api as $itm) {
		$output['overview'][$itm['topic_slug']]= trim($itm['api_output']);
		$output['company_description'][$itm['topic_slug']]= trim($itm['api_output']);
	}
	return $output;
}


function grabJsonText($field,$array){

	$keys= explode(".", $field);
	$return_string = '';

	$past_array = $array;
	foreach($keys as $key){
		if(isset($past_array[$key]) && is_array($past_array[$key])) {
			$past_array = $past_array[$key];
		}
		else
		{
			if(isset($past_array[$key])) {
				$return_string = $past_array[$key];
			}
		}
	}

return $return_string;
}
function listWords($prompt){
	$matches=array();
	if(preg_match_all('/{+(.*?)}/', $prompt, $matches)) {
		return $matches[1];
	}
	return false;
}

function grabValue($key, $array){
	if(isset($array['check'][$key]) && $array['check'][$key]=='Y') {
			if($array['val'][$key]==''){ 
				return 'Yes';
			}
			else {
				return $array['val'][$key];
			}
	}
	else  {
		return 'N/A';
	}
}
?>