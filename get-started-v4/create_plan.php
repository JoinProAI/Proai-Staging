<?php
    include_once "includes/config.php";
    $db->where("USER_ID",   $_SESSION['PRO_USER_ID']);
    $db->orderby("plan_id", "desc");
    $res = $db->getOne("plan_master");

    if($db->count){
        $plan_id= $res['plan_id'];
        $modify_plan = SITE_URL."/modify_plan?plan=".md5($plan_id)."#overview";    
    }
    else{
			$company_id =  $db->insert("company_master", array(
				"company_name" => "Default Company",
				"company_city"=>"",
				"company_state_country"=>"",
				"status"=>"Y",
				"payment_type"=>"",
				"user_id"=>$_SESSION['PRO_USER_ID'],
			));

			$_SESSION['company_id'] = $company_id;
			$_SESSION['company_name'] = $company;

            $i_data=array(
            "user_id"=>$_SESSION['PRO_USER_ID'],
            "plan_title"=>"AI Generated Plan",
			"company_id"=>$company_id
            );
            $db->insert("plan_master",$i_data);

            $plan_id = $db->getInsertId();   
            $modify_plan = SITE_URL."/modify_plan?plan=".md5($plan_id)."#overview";       
    }

    header("Location: ".$modify_plan);
    exit;

?>