<?php
$db->where('md5(plan_id)', $plan);
$list = $db->getOne('compitition_directs');
$rcount = $db->count;


$direct_competitors_val_arr = isset($list['details']) ? json_decode($list['details'], true) : array();
$json_data = array();
$print_omit = ' checked ';

$json_data1 = array();
$json_data2 = array();
$json_data3 = array();
foreach ($direct_competitors_val_arr as $key => $itm) {
    $json_data1[] = array("name" => "Direct Competitor #" . ($key + 1), "val" => "");
    $json_data1[] = array("name" => "Competitor's Name", "val" => $itm['compititor_name']);
    $json_data1[] = array("name" => "Location", "val" => $itm['compititor_location']);
    $json_data1[] = array("name" => "&nbsp;", "val" => "&nbsp;");
}

?>
<?php
$db->where('md5(plan_id)', $plan);
$list = $db->getOne('compitition_indirect');
$rcount = $db->count;

$indirect_competitors_val_arr = isset($list['details']) ? json_decode($list['details'], true) : array();

foreach ($indirect_competitors_val_arr as $key => $itm) {
    $json_data2[] = array("name" => "Compititor #" . ($key + 1), "val" => "");
    $json_data2[] = array("name" => "Name", "val" => $itm['compititor_name']);
    $json_data2[] = array("name" => "Overview", "val" => $itm['compititor_overview']);
    $json_data2[] = array("name" => "Product/Services", "val" => $itm['compititor_product_services']);
    $json_data2[] = array("name" => "Pricing", "val" => $itm['compititor_pricing']);
    $json_data2[] = array("name" => "Revenues", "val" => $itm['compititor_revenues']);
    $json_data2[] = array("name" => "Location", "val" => $itm['compititor_location']);
    $json_data2[] = array("name" => "Segments", "val" => $itm['compititor_segments']);
    $json_data2[] = array("name" => "Strenghts", "val" => $itm['compititor_strengths']);
    $json_data2[] = array("name" => "Weakness", "val" => $itm['compititor_weaknesses']);
    $json_data2[] = array("name" => "&nbsp;", "val" => "&nbsp;");
}

?>
<?php
$db->where('md5(plan_id)', $plan);
$list = $db->getOne('compitition_analysis');
$rcount3 = $db->count;

$target_customers_val_arr = isset($list['details']) ? json_decode($list['details'], true) : array();

$target_customers_arr = $db->get("master_compitition_analysis");

foreach ($target_customers_arr as $key => $itm) {

    if (isset($target_customers_val_arr['check'][$itm['slug']]) && $target_customers_val_arr['check'][$itm['slug']] == 'Y') {
        $json_data3[] = array('name' => $itm['name'], 'val' => $target_customers_val_arr['val'][$itm['slug']]);
    } else {
        if ($rcount3) {
            $json_data3[] = array('name' => $itm['name'], 'val' => "N/A");
        }
    }
}


$json_data1_1 = array();
$json_data1_1[] = array('name' => '', 'val' => 'Direct Compititor #');
$json_data1_1[] = array('name' => 'Competitor\'s Name:', 'val' => '$this.find(".compititor_name").val()');
$json_data1_1[] = array('name' => 'Location(s)', 'val' => '$this.find(".compititor_location").val()');


$json_data3_1 = array();
$json_data3_1[] = array('name' => 'Products and/or Services',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_products_or_services")');
$json_data3_1[] = array('name' => 'Human Resources',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_human_resources")');
$json_data3_1[] = array('name' => 'Location',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_location")');
$json_data3_1[] = array('name' => 'Operational Systems',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_operational_systems")');
$json_data3_1[] = array('name' => 'Intellectual Property (IP)',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_intellectual_property")');
$json_data3_1[] = array('name' => 'Customers',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_customers")');
$json_data3_1[] = array('name' => 'Marketing',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_marketing")');
$json_data3_1[] = array('name' => 'Other',   'val' => 'grabValue("#Competition_advantages .submit_form","compitition_analysis_other")');

?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/direct-competitors.css?<?php echo strtotime('now'); ?>">

<div class="overview_right_wp" id="col_direct-competitors" >

    <div class="right_col ">
        <div class="start_your direct_competitors">
            <h2 class="d-flex justify-content-between">Competitive
            </h2>
        </div>
        <div class="right_list direct_competitors_wrapper" id="CompetitionPopup">
            <div class="d-flex justify-content-between align-items-center md-gap-1 gap-2 max-w-4xl w-100 flex-wrap pb-5">
                <div class="competive_wrapeer_text">
                    <p class="main_text">Direct Competitors</p>
                    <p class="overview_text">Please add each of your direct competitors below, or let the AI generate them (recommended):</p>
                </div>
                <div class="competive_wrapeer_btn">
                    <a href="#"  name="generateAI" class="generate_btn generate_ai" data-slug="competitors" data-callback="loadCompititors">Generate from AI <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" /></button></a>
                </div>
            </div>

            <form action="" id="submit_form" name="submit_form" class="submit_form cover_form">
                <fieldset>
                <div class="boardMember-container" id="boardMember-container">
                <?php                  
                if(count($direct_competitors_val_arr)){
                foreach($direct_competitors_val_arr as $k => $itm) {
                $a = ($k + 1);
                ?>

                <div class="d-flex align-items-center gap-4 group competitorbox" style="padding-top: 20px;">               
                    <div class="serive_pricing_skils border solid rounded-lg lg-p-8 p-2-5 max-w-4xl w-100">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="prouduct_sell_first">
                                <label for="compititor_name_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 pb-2-5">Competitor's Name</label>
                                <input type="text" placeholder="Type here..." class="company_wrapper_input compititor_name" name="compititor_name[<?php echo $a; ?>]" id="compititor_name_<?php echo $a; ?>" maxlength="100" onblur="javascript:changeName(<?php echo $a; ?>)" value="<?php echo @$itm['compititor_name']; ?>" >
                            </div>
                            <div class="prouduct_sell_sec">
                                <label for="compititor_location_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 pb-2-5">Location(s)</label>
                                <input type="text" placeholder="Type here..." class="lg-w-72 company_wrapper_input compititor_location" name="compititor_location[<?php echo $a; ?>]" id="compititor_location_<?php echo $a; ?>" maxlength="100"  value="<?php echo @$itm['compititor_location']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="delet_btn">
                        <a href="javascript:;" class="red right" title="Delete Board Member" onclick="deleteItem(this)">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                                <path d="M8.67672 0.0494118C8.33082 0.116795 7.85914 0.37285 7.59859 0.633396C7.16285 1.06914 6.96969 1.62617 6.96519 2.43476V2.78515H5.60855C4.07672 2.78515 3.7398 2.82109 3.33551 3.00976C2.97164 3.18047 2.5359 3.61172 2.36969 3.9666C1.99234 4.76621 2.15406 5.6916 2.77398 6.30703C2.98961 6.52266 3.49722 6.82812 3.63648 6.82812C3.67242 6.82812 3.6859 8.72383 3.69488 13.7551L3.70836 20.6865L3.82965 21.0459C4.00035 21.5445 4.24293 21.9264 4.58433 22.2543C4.93922 22.5867 5.31207 22.7934 5.77926 22.9102C6.11617 22.9955 6.29586 23 11.5023 23C16.7087 23 16.8884 22.9955 17.2253 22.9102C18.3169 22.6316 19.1031 21.7242 19.2738 20.5428C19.3007 20.3541 19.3187 17.6723 19.3187 13.535V6.83711L19.4894 6.77871C20.1318 6.57207 20.6888 5.88476 20.8056 5.16601C20.9269 4.40683 20.5496 3.55781 19.8982 3.14004C19.4086 2.83008 19.3322 2.8166 17.6072 2.79414L16.0529 2.77617L16.0259 2.25058C15.9945 1.67109 15.9002 1.33418 15.68 0.983788C15.3791 0.5166 14.8355 0.148241 14.292 0.04492C13.9955 -0.00898552 8.96422 -0.00898552 8.67672 0.0494118ZM14.3548 1.17695C14.7591 1.39707 14.9209 1.70703 14.9523 2.32246L14.9748 2.78515H11.5023H8.03433L8.04781 2.3C8.06578 1.87324 8.07926 1.79687 8.19156 1.6082C8.25894 1.4914 8.37574 1.35215 8.45211 1.29824C8.76207 1.07812 8.74859 1.07812 11.5697 1.0916C13.9416 1.10058 14.2336 1.10957 14.3548 1.17695ZM19.2064 3.98457C19.4355 4.10586 19.5658 4.24512 19.6601 4.46972C19.8308 4.88301 19.6871 5.38164 19.3232 5.63769L19.13 5.77246L11.6011 5.78594C6.34078 5.79492 4.01832 5.78594 3.90152 5.75C3.4523 5.61972 3.1648 5.10312 3.2816 4.63144C3.36695 4.27656 3.64996 3.99804 4.02281 3.90371C4.09918 3.88574 7.50426 3.87676 11.5921 3.87676L19.0267 3.88574L19.2064 3.98457ZM18.2316 13.499C18.2406 18.0047 18.2271 20.2014 18.1957 20.417C18.1013 21.0369 17.7779 21.4951 17.2613 21.7512L16.9603 21.8994L11.6191 21.9129C5.82418 21.9264 5.97691 21.9309 5.58609 21.6838C5.21773 21.4502 4.91226 20.9965 4.81344 20.5383C4.7775 20.3676 4.76402 18.3865 4.76402 13.5844V6.87304L11.4933 6.88203L18.2181 6.89551L18.2316 13.499Z" fill="#D3524D"></path>
                                <path d="M7.96212 8.48563C7.89024 8.562 7.81837 8.68328 7.8004 8.76414C7.78692 8.84051 7.77344 11.1989 7.77344 14.002C7.77344 19.4331 7.76895 19.2669 7.98458 19.4645C8.12384 19.5858 8.36192 19.6083 8.55059 19.5184C8.86954 19.3657 8.85157 19.7296 8.85157 13.9481C8.85157 8.31043 8.86505 8.59793 8.60899 8.42723C8.5461 8.3868 8.40684 8.35536 8.29454 8.35536C8.13282 8.35536 8.06993 8.38231 7.96212 8.48563Z" fill="#D3524D"></path>
                                <path d="M11.1807 8.45868C11.1178 8.51258 11.0414 8.60243 11.0144 8.65633C10.9785 8.72371 10.965 10.278 10.965 13.9481C10.965 19.7296 10.9471 19.3657 11.266 19.5184C11.4547 19.6083 11.6928 19.5858 11.832 19.4645C12.0476 19.2669 12.0432 19.4331 12.0432 14.002C12.0432 11.1989 12.0297 8.84051 12.0162 8.76414C11.9982 8.68328 11.9264 8.562 11.8545 8.48563C11.6838 8.31493 11.3648 8.30145 11.1807 8.45868Z" fill="#D3524D"></path>
                                <path d="M14.3051 8.5082L14.1523 8.66094V13.9213C14.1523 18.4449 14.1613 19.2041 14.2197 19.3119C14.2871 19.4512 14.5297 19.5859 14.7049 19.5859C14.8711 19.5859 15.1092 19.4018 15.1721 19.2266C15.2439 19.0199 15.2529 9.00234 15.1855 8.75078C15.1182 8.5082 14.9115 8.35547 14.66 8.35547C14.4893 8.35547 14.4354 8.37793 14.3051 8.5082Z" fill="#D3524D"></path>
                            </svg>
                        </button>
                        </a>
                    </div>
                </div>

                <?php
                }
                }
                ?>
                </div>
                </fieldset>

                <div class="add_another_btn_wrapper">
                    <a href="javascript:;" id="btn-AddBoardMember1" class="add_another_btn btn-AddBoardMember1">Add Another</a>
                </div>
                <div class="input_checkbox"  style="display:none;">
                            <input type="checkbox" name="print_omit" id="print_omit20" value="Y"  checked >
                            <label for="print_omit20">Click this box to omit this section from your printed business plan</label>
                </div>

                <input type="hidden" id="step" name="step" value="competition-direct" />
                <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
                <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
            </form>
            <div class="form_btn">

            <button class="themebtn_new theme_btn" onClick="submit_form_btn('CompetitionPopup')">
                Save & Continue  <?php //submit_form_btn('CompetitionPopup,CompetitionPopup_indirect,Competition_advantages') ?>
                <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
            </button>
        </div>
        </div>
        
    </div>

    <div class="right_col rightv2_product right_colv2 d-none">

        
    </div>



</div>
