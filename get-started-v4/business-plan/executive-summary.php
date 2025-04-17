<?php

$db->where("md5(plan_id)", $plan);
$list = $db->getOne("executive_overview_summary");
$rcount1 = $db->count;

$overview = isset($list['overview']) ? $list['overview'] : "";
$print_omit = '';
$json_data = array();

if (isset($list['print_omit'])) {
    if ($list['print_omit'] == 'Y') {
        $print_omit = ' checked ';
    }
}

$db->where('md5(plan_id)', $plan);
$list = $db->getOne('executive_success_factores');
$rcount2 = $db->count;

$db->where("status", "Y");
$success_factors_arr = $db->get("master_success_factors");
$success_factores_val_arr = isset($list['success_factor']) ? json_decode($list['success_factor'], true) : array();
$print_omit = '';
if (isset($list['print_omit'])) {
    if ($list['print_omit'] == 'Y') {
        $print_omit = ' checked ';
    }
}

$json_data1 = array();
$json_data2 = array();

$json_data1_1 = array();
$json_data2_1 = array();

$db->where('md5(plan_id)', $plan);
$list = $db->getOne('marketing_products_pricing');
$product_services_val_arr = isset($list['details']) ? json_decode($list['details'], true) : array();
$json_data = array();
$print_omit = '';

foreach ($product_services_val_arr as $key => $itm) {
    $json_data1[] = array("name" => "Product/Service #" . ($key + 1), "val" => "");
    $json_data1[] = array("name" => "Product/Service Name", "val" => $itm['service_name']);
    $json_data1[] = array("name" => "Product/Service Description/Benefits", "val" => $itm['service_description']);
    $json_data1[] = array("name" => "Product/Service Price", "val" => $itm['service_price']);
    $json_data1[] = array("name" => "&nbsp;", "val" => "&nbsp;");
}

foreach ($success_factors_arr as $key => $itm) {
    $chk = '';
    if (isset($success_factores_val_arr['check'][$itm['slug']]) && $success_factores_val_arr['check'][$itm['slug']] == 'Y') {

        $val = $success_factores_val_arr['val'][$itm['slug']];

        if ($val == '') {
            $val = 'N/A';
        }
        $json_data2[] = array('name' => $itm['name'], 'val' => $val);

    } else {

        if ($rcount2) {
            $json_data2[] = array('name' => $itm['name'], 'val' => "N/A");
        }
    }
}

$json_data1_1 = array();
$json_data1_1[] = array('name' => '', 'val' => 'Products/Services #');
$json_data1_1[] = array('name' => 'Product/Service Name', 'val' => '$this.find(".service_name").val()');
$json_data1_1[] = array('name' => 'Product/Service Description/Benefits', 'val' => '$this.find(".service_description").val()');
$json_data1_1[] = array('name' => 'Product/Service Price', 'val' => '$this.find(".service_price").val()');

$json_data2_1 = array();
$json_data2_1[] = array('name' => 'Other', 'val' => 'grabValue("#SuccessFactors .submit_form","other")');
?>
<div class="overview_right_wp" id="col_executive-summary">
    <div class="right_col competitive_advantage">
        <div class="start_your executive_tooltip_mobile">
            <h2>Company</h2>
        </div>
        <div class="company_modify_wrapper pl-5">
            <div class="company_modify_skils border-bottom solid" id="SuccessFactors">
                <div class="d-flex justify-content-between align-items-center md-gap-1 gap-2 max-w-4xl w-100 flex-wrap pb-5">
                    <div class="service_pricing_text">
                 <h2 class="color-011627 text-xl font-semibold pt-7 md-mb-5 md-line-height-6 mb-2-5 modal-title">Success Factors</h2>
                    </div>
                    <div class="btm_pricing_service">
				 <a href="#"  name="generateAI" class="generate_btn generate_ai btn-AddBoardMember" data-slug="successfactor" data-callback="loadSuccessFactor">Generate from AI <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" /></a>
                    </div>
                </div>

                <form action="" id="submit_form" name="submit_form" class="submit_form cover_form">
                    <?php
                        foreach($success_factors_arr as $key => $itm) {
                            $chk =" checked ";
                ?>
                   <input type="checkbox" class="checkbox <?php echo $itm['slug']; ?>" id="<?php echo $itm['slug']; ?>" name="success_factors[<?php echo $itm['slug']; ?>]" data-name="<?php echo $itm['slug']; ?>" value="Y" <?php echo $chk; ?> style="display:none;">
                    <label for="<?php echo $itm['slug']; ?>_val" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Please explain the unique features of your business compared to the competitors.</label>
                    <input type="checkbox" class="checkbox <?php echo $itm['slug']; ?>" id="<?php echo $itm['slug']; ?>" name="success_factors[<?php echo $itm['slug']; ?>]" data-name="<?php echo $itm['slug']; ?>" value="Y" <?php echo $chk; ?>>
                    <textarea id="<?php echo $itm['slug']; ?>_val" name="<?php echo $itm['slug']; ?>_val" cols="30" rows="10" class="company_modify_skils_textarea py-2-5 px-5" word-limit="true" max-words="250" placeholder="Type here..."><?php echo $val; ?></textarea>
                    <div class="d-flex justify-content-end max-w-2xl w-100">
                        <p class="text-xs font-normal font-montserrat mb-10 md-line-height-6 writing_error">Max 250 Words</p>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="input_checkbox" style="display:none;">
                        <input type="checkbox" name="print_omit" id="print_omit3" value="Y"  checked >
                        <label for="print_omit3">Click this box to omit this section from your printed business plan</label>
                    </div>
                    <input type="hidden" id="step" name="step" value="executive-summary-product-success-factors" />
                    <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
                    <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
                </form>
            </div>
            <div class="service_pricing_wrapper pt-9" id="product_services_modal">
                <div class="d-flex justify-content-between align-items-center md-gap-1 gap-2 max-w-4xl w-100 flex-wrap pb-5">
                    <div class="service_pricing_text">
                        <h2 class="text-lg font-semibold color-011627 mb-0 md-line-height-6">Products/Services & Pricing</h2>
                        <p class="text-sm font-normal color-011627 mb-0 md-line-height-6">Please list each of your businesses' products and/or services below or let the AI generate them (recommended):</p>
                    </div>
                    <div class="btm_pricing_service">
                        <a href="#"  name="generateAI" class="generate_btn generate_ai btn-AddBoardMember" data-slug="services" data-callback="loadService">Generate from AI <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" /></a>
                    </div>
                </div>
                
                <form action="" id="submit_form" name="submit_form" class="submit_form cover_form">
                        <fieldset>
                            <div id="boardMember-container" class="marketing_products_pricing-container">
                                <?php
                                if (count($product_services_val_arr)) {
                                foreach ($product_services_val_arr as $k => $itm) {
                                $a = ($k + 1);
                                ?>
                                <div id="boardMember-<?php echo $a; ?>" class="group competitorbox">
                                    <div class="d-flex align-items-center xl-gap-4 gap-1 mb-10">
                                        <div class="serive_pricing_skils border solid rounded-lg p-8 max-w-4xl w-100">
                                            <div class="d-flex gap-2 flex-wrap w-100">
                                                <div class="prouduct_sell_first w-100 max-w-556">
                                                    <label for="service_name_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Product/Service Name</label>
                                                    <input type="text" placeholder="Type here..." name="service_name[<?php echo $a; ?>]" id="service_name<?php echo $a; ?>" class="max-w-556  w-100 company_wrapper_input service_name" value="<?php echo $itm['service_name']; ?>"/>
                                                </div>
                                                <div class="prouduct_sell_sec position-relative">
                                                    <label for="service_price_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Price</label>
                                                    <input type="text" placeholder="0.00" class="w-100 max-w-160 company_wrapper_input service_price" name="service_price[<?php echo $a; ?>]" id="service_price_<?php echo $a; ?>" value="<?php echo $itm['service_price']; ?>">
                                                </div>
                                            </div>
                                            <label for="service_description_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6 pt-5">Product/Service Description/Benefits</label>
                                            <textarea class="w-100 h-20 mw-100 company_wrapper_input service_description" name="service_description[<?php echo $a; ?>]" id="service_description_<?php echo $a; ?>" placeholder="Type here..." maxlength="350"><?php echo $itm['service_description']; ?></textarea>
                                        </div>
                                        <div class="delet_btn">
                                            <a href="javascript:;" class="red right" title="Delete Board Member" onclick="deleteItem(this)">
                                                <button>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                                                        <path d="M8.67672 0.0494118C8.33082 0.116795 7.85914 0.37285 7.59859 0.633396C7.16285 1.06914 6.96969 1.62617 6.96519 2.43476V2.78515H5.60855C4.07672 2.78515 3.7398 2.82109 3.33551 3.00976C2.97164 3.18047 2.5359 3.61172 2.36969 3.9666C1.99234 4.76621 2.15406 5.6916 2.77398 6.30703C2.98961 6.52266 3.49722 6.82812 3.63648 6.82812C3.67242 6.82812 3.6859 8.72383 3.69488 13.7551L3.70836 20.6865L3.82965 21.0459C4.00035 21.5445 4.24293 21.9264 4.58433 22.2543C4.93922 22.5867 5.31207 22.7934 5.77926 22.9102C6.11617 22.9955 6.29586 23 11.5023 23C16.7087 23 16.8884 22.9955 17.2253 22.9102C18.3169 22.6316 19.1031 21.7242 19.2738 20.5428C19.3007 20.3541 19.3187 17.6723 19.3187 13.535V6.83711L19.4894 6.77871C20.1318 6.57207 20.6888 5.88476 20.8056 5.16601C20.9269 4.40683 20.5496 3.55781 19.8982 3.14004C19.4086 2.83008 19.3322 2.8166 17.6072 2.79414L16.0529 2.77617L16.0259 2.25058C15.9945 1.67109 15.9002 1.33418 15.68 0.983788C15.3791 0.5166 14.8355 0.148241 14.292 0.04492C13.9955 -0.00898552 8.96422 -0.00898552 8.67672 0.0494118ZM14.3548 1.17695C14.7591 1.39707 14.9209 1.70703 14.9523 2.32246L14.9748 2.78515H11.5023H8.03433L8.04781 2.3C8.06578 1.87324 8.07926 1.79687 8.19156 1.6082C8.25894 1.4914 8.37574 1.35215 8.45211 1.29824C8.76207 1.07812 8.74859 1.07812 11.5697 1.0916C13.9416 1.10058 14.2336 1.10957 14.3548 1.17695ZM19.2064 3.98457C19.4355 4.10586 19.5658 4.24512 19.6601 4.46972C19.8308 4.88301 19.6871 5.38164 19.3232 5.63769L19.13 5.77246L11.6011 5.78594C6.34078 5.79492 4.01832 5.78594 3.90152 5.75C3.4523 5.61972 3.1648 5.10312 3.2816 4.63144C3.36695 4.27656 3.64996 3.99804 4.02281 3.90371C4.09918 3.88574 7.50426 3.87676 11.5921 3.87676L19.0267 3.88574L19.2064 3.98457ZM18.2316 13.499C18.2406 18.0047 18.2271 20.2014 18.1957 20.417C18.1013 21.0369 17.7779 21.4951 17.2613 21.7512L16.9603 21.8994L11.6191 21.9129C5.82418 21.9264 5.97691 21.9309 5.58609 21.6838C5.21773 21.4502 4.91226 20.9965 4.81344 20.5383C4.7775 20.3676 4.76402 18.3865 4.76402 13.5844V6.87304L11.4933 6.88203L18.2181 6.89551L18.2316 13.499Z" fill="#D3524D" />
                                                        <path d="M7.96212 8.48563C7.89024 8.562 7.81837 8.68328 7.8004 8.76414C7.78692 8.84051 7.77344 11.1989 7.77344 14.002C7.77344 19.4331 7.76895 19.2669 7.98458 19.4645C8.12384 19.5858 8.36192 19.6083 8.55059 19.5184C8.86954 19.3657 8.85157 19.7296 8.85157 13.9481C8.85157 8.31043 8.86505 8.59793 8.60899 8.42723C8.5461 8.3868 8.40684 8.35536 8.29454 8.35536C8.13282 8.35536 8.06993 8.38231 7.96212 8.48563Z" fill="#D3524D" />
                                                        <path d="M11.1807 8.45868C11.1178 8.51258 11.0414 8.60243 11.0144 8.65633C10.9785 8.72371 10.965 10.278 10.965 13.9481C10.965 19.7296 10.9471 19.3657 11.266 19.5184C11.4547 19.6083 11.6928 19.5858 11.832 19.4645C12.0476 19.2669 12.0432 19.4331 12.0432 14.002C12.0432 11.1989 12.0297 8.84051 12.0162 8.76414C11.9982 8.68328 11.9264 8.562 11.8545 8.48563C11.6838 8.31493 11.3648 8.30145 11.1807 8.45868Z" fill="#D3524D" />
                                                        <path d="M14.3051 8.5082L14.1523 8.66094V13.9213C14.1523 18.4449 14.1613 19.2041 14.2197 19.3119C14.2871 19.4512 14.5297 19.5859 14.7049 19.5859C14.8711 19.5859 15.1092 19.4018 15.1721 19.2266C15.2439 19.0199 15.2529 9.00234 15.1855 8.75078C15.1182 8.5082 14.9115 8.35547 14.66 8.35547C14.4893 8.35547 14.4354 8.37793 14.3051 8.5082Z" fill="#D3524D" />
                                                    </svg>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>  
                                    <?php 
                                    } 
                                    }
                                    ?>                             
                            </div>
                        
                            <div class="add_another_btn_wrapper">
                                <a href="javascript:;" id="btn-AddBoardMemberpsp" class="add_another_btn btn-AddBoardMemberpsp theme_btn">Add Another</a>
                            </div>
                        </fieldset>
                    <div class="input_checkbox" style="display:none;">
                        <input type="checkbox" name="print_omit" id="print_omit_3" value="Y"  checked >
                        <label for="print_omit_3">Click this box to omit this section from your printed business plan</label>
                    </div>

                    <input type="hidden" id="step" name="step" value="marketing-products-and-pricing" />
                    <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
                    <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
                </form>
            </div>
            <div class="form_btn">
                <button class="themebtn_new theme_btn" onClick="submit_form_btn('SuccessFactors,product_services_modal')">
                    Save & Continue
                    <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
                </button>
            </div>
        </div>
    </div>
</div>

