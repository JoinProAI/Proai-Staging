<?php
$db->where("md5(plan_id)", $plan);
$fres  = $db->getOne("financial_assumptions");

$financial_assumptions = "";

$capital_requirement = "";
$operating_expenses = "";
$revenue_validated = "";

$json_data = array();

if ($db->count) {
    $financial_assumptions = $fres['details'];
    $json_data = json_decode($fres['details'], true);
    if (json_last_error()) {
        $json_data = array();
    }
    $capital_requirement = $fres['capital_requirement'];
    $cr_json_data = json_decode($capital_requirement, true);
    $operating_expenses = $fres['operating_expenses'];
    $oe_json_data = json_decode($operating_expenses, true);
    $revenue_validated = $fres['revenue_validated'];
}

$json_data1 = array();

if(!isset($json_data['financial'])){
  $json_data['financial']=array();
}

$json_data2 = array();
 
$naisc_code = ($cr_json_data['Sector_Assignment']['NAICS Code']) ? $cr_json_data['Sector_Assignment']['NAICS Code'] : "";

$total_funding = ($json_data['financial']['total_funding']) ? $json_data['financial']['total_funding'] : "";
$funds_product_development = ($json_data['financial']['funds_product_development']) ? $json_data['financial']['funds_product_development'] : "";
$funds_marketing = ($json_data['financial']['funds_marketing']) ? $json_data['financial']['funds_marketing'] : "";
$funds_manufacturing = ($json_data['financial']['funds_manufacturing']) ? $json_data['financial']['funds_manufacturing'] : "";
$funds_staffing = ($json_data['financial']['funds_staffing']) ? $json_data['financial']['funds_staffing'] : "";
$funds_rent = ($json_data['financial']['funds_rent']) ? $json_data['financial']['funds_rent'] : "";
$funds_other = ($json_data['financial']['funds_other']) ? $json_data['financial']['funds_other'] : "";

$monthly_salary = ($json_data['costs']['owner_monthly_salary']) ? $json_data['costs']['owner_monthly_salary'] : "";
$employee_monthly_salary = ($json_data['costs']['total_employee_monthly_salary']) ? $json_data['costs']['total_employee_monthly_salary'] : "";
$monthly_marketing_cost = ($json_data['costs']['monthly_marketing_cost']) ? $json_data['costs']['monthly_marketing_cost'] : "";
$monthly_rent_and_utilities = ($json_data['costs']['monthly_rent_and_utilities']) ? $json_data['costs']['monthly_rent_and_utilities'] : "";
$monthly_insurance_cost = ($json_data['costs']['monthly_insurance_cost']) ? $json_data['costs']['monthly_insurance_cost'] : "";
$monthly_office_supplies_cost = ($json_data['costs']['monthly_office_supplies_cost']) ? $json_data['costs']['monthly_office_supplies_cost'] : "";
$monthly_other_expenses = ($json_data['costs']['monthly_other_expenses']) ? $json_data['costs']['monthly_other_expenses'] : "";

if (isset($cr_json_data['financials'])) {
    foreach ($cr_json_data['financials'] as $key => $val) {

        if (strtolower($val['funding_name']) == strtolower('Initial Capital')) {
            $initial_capital_amount = ($val['funding_value']) ? $val['funding_value'] . "" : "--";
            $initial_capital_assumption = ($val['funding_assumptions']) ? $val['funding_assumptions'] . "" : "--";
        } else if (strtolower($val['funding_name']) == strtolower('Working Capital')) {
            $working_capital_amount = ($val['funding_value']) ? $val['funding_value'] . "" : "--";
            $working_capital_assumption = ($val['funding_assumptions']) ? $val['funding_assumptions'] . "" : "--";
        } else if (strtolower($val['funding_name']) == strtolower('Capital Expenditure')) {
            $capital_expenditure_amount = ($val['funding_value']) ? $val['funding_value'] . "" : "--";
            $capital_expenditure_assumption = ($val['funding_assumptions']) ? $val['funding_assumptions'] . "" : "--";
        }
    }
}

$json_data_1 = array();
$json_data_1[] = array('name' => 'Initial Capital', 'val' => "&nbsp;");
$json_data_1[] = array('name' => 'Amount',     'val' => $initial_capital_amount . "");
$json_data_1[] = array('name' => 'Assumption', 'val' => $initial_capital_assumption . "");

$json_data_1[] = array('name' => "", 'val' => "");
$json_data_1[] = array('name' => 'Working Capital', 'val' => "&nbsp;");
$json_data_1[] = array('name' => 'Amount',     'val' => $working_capital_amount . "");
$json_data_1[] = array('name' => 'Assumption', 'val' => $working_capital_assumption . "");

$json_data_1[] = array('name' => "", 'val' => "");
$json_data_1[] = array('name' => 'Capital Expenditure', 'val' => "&nbsp;");
$json_data_1[] = array('name' => 'Amount',    'val' => $capital_expenditure_amount . "");
$json_data_1[] = array('name' => 'Assumption', 'val' => $capital_expenditure_assumption . "");

$json_data_11 = array();
$json_data_11[] = array('name' => '', 'val' => "Initial Capital");
$json_data_11[] = array('name' => 'Amount',     'val' => 'jQuery("#initial_capital_amount").val()');
$json_data_11[] = array('name' => 'Assumption', 'val' => 'jQuery("#initial_capital_assumptions").val()');

$json_data_11[] = array('name' => '', 'val' => "Working Capital");
$json_data_11[] = array('name' => 'Amount',     'val' => 'jQuery("#working_capital_amount").val()');
$json_data_11[] = array('name' => 'Assumption', 'val' => 'jQuery("#working_capital_assumptions").val()');

$json_data_11[] = array('name' => '', 'val' => "Capital Expenditure");
$json_data_11[] = array('name' => 'Amount',    'val' => 'jQuery("#capital_expenditure_amount").val()');
$json_data_11[] = array('name' => 'Assumption', 'val' => 'jQuery("#capital_expenditure_assumptions").val()');

$json_data_2 = array();

if (isset($oe_json_data['costs'])) {
    foreach ($oe_json_data['costs'] as $val) {
        $json_data_2[] = array('name' => "Expense Category", 'val' => $val['cost_name']);
        $json_data_2[] = array('name' => "Monthly Cost", 'val' => $val['cost_value']);
        $json_data_2[] = array('name' => "Annual Growth Rate", 'val' => $val['cost_growthrate']);
        $json_data_2[] = array('name' => "Assumptions", 'val' => $val['cost_assumptions']);
        $json_data_2[] = array('name' => "", 'val' => "");
    }
}

$json_data_21 = array();
$json_data_21[] = array('name' => 'Expense Category', 'val' => '$this.find(".category_name").val()');
$json_data_21[] = array('name' => 'Monthly Cost', 'val' => '$this.find(".category_amount").val()');
$json_data_21[] = array('name' => 'Annual Growth Rate', 'val' => '$this.find(".category_growthrate").val()');
$json_data_21[] = array('name' => 'Assumptions', 'val' => '$this.find(".category_assumption").val()');
$json_data_21[] = array('name' => "", 'val' => "");
?>

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/financial-assumptions.css?<?php echo strtotime('now'); ?>">

<div class="overview_right_wp" id="col_financial-assumptions">
    <div class="right_col competitive_advantage pb-10" style="position:relative;">
        <div class="start_your executive_tooltip_mobile">
            <h2>Expense Assumptions</h2>
        </div>

        <div class="loading_info">
            <div class="loading_msg_container">
            <p>Please wait as the AI calculates your capital requirements and operational expenses.</p>
            <p>Upon completion, you may refine these estimates.</p>
            <p>Our methodology combines industry benchmarking from over 700 sectors with insights from top-tier consultants.</p>
            <div style="text-align:center;"><img style="width:50px;" src="<?php echo SITE_URL; ?>/loading.svg" /></div> 
            </div>
        </div>

        <?php
             $OECategory_arr = array();
        if (isset($oe_json_data['costs'])) {
            $OECategory_arr = $oe_json_data['costs'];
        }
        ?>
        <div class="right_list financial_assumptions_wrapper">
            <p class="main_text">Financial Assumptions</p>
            <p class="overview_text mb-3">Please share if your business is raising funding:</p>
            <form action="" method="post" id="financial_asmp" name="frmsubmit" class="frmsubmit submit_form border-bottom">
                <div class="input_group pt-5">
                    <label class="title_form text-sm font-medium mb-2-5 md-line-height-6"> Is the business raising funding? <span class="red">*</span></label>
                    <div class="input_field_2">
                        <div class="d-flex">
                            <label for="raising_funding_yes">
                                <input type="radio" id="raising_funding_yes" name="raising_funding"   value="yes" checked>
                                <span>Yes</span>
                            </label>
                            <label class="ml-2" for="raising_funding_no">
                                <input type="radio" id="raising_funding_no" name="raising_funding" value="no">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input_group pt-8  pb-10">
                    <label class="title_form text-sm font-medium mb-2-5 md-line-height-6">If raising funds, is the business seeking a bank loan? <span class="red">*</span></label>
                    <div class="input_field_2">
                        <div class="d-flex">
                            <label for="seeking_bank_loan_yes">
                                <input type="radio" id="seeking_bank_loan_yes" name="seeking_bank_loan" value="yes" checked>
                                <span>Yes</span>
                            </label>
                            <label class="ml-2" for="seeking_bank_loan_no">
                                <input type="radio" id="seeking_bank_loan_no" name="seeking_bank_loan" value="no">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                </div>
                <textarea style="display:none;" id="financial-assumptions" name="financial-assumptions" class="financial-assumptions"><?php echo $financial_assumptions; ?></textarea>
                <textarea style="display:none;" id="capital_requirement" name="capital_requirement" class="capital_requirement"><?php echo $capital_requirement; ?></textarea>
                <textarea style="display:none;" id="operating_expenses" name="operating_expenses" class="operating_expenses"><?php echo $operating_expenses; ?></textarea>
                <textarea style="display:none;" id="revenue_validated" name="revenue_validated" class="revenue_validated"><?php echo $revenue_validated; ?></textarea>

                <input type="hidden" name="naisc_code" id="naisc_code" value="<?php echo $naisc_code; ?>" />
                <input type="hidden" id="step" name="step" value="financial-assumptions" />
                <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
            </form>
            <div>
                <div class="financial_firt_sec pb-10 border-bottom pt-10">

                    <div id="capital_requirements">
                    <form action="" id="submit_form" name="submit_form" class="submit_form cover_form">
                        <div id="frm-section-board-members" class="bpform_edit">
                            <fieldset>
                                <div class="capital_content accordion" id="faq">

                                    <div class="d-flex justify-content-between align-items-center md-gap-1 gap-2 max-w-4xl w-100 pb-5 flex-wrap">
                                        <div class="competive_wrapeer_text">
                                            <p class="main_text">Capital Requirements</p>
                                            <p class="overview_text">Please add your capital requirements</p>
                                        </div>
                                        <div class="competive_wrapeer_btn">
                                            <button name="generateAI" type="button" class="generate_btn generate_ai" data-slug="capital_financials" data-callback="loadCapitalFinancial">Generate from AI <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" /></button>
                                        </div>
                                    </div>
                                    <div class="serive_pricing_skils border solid rounded-lg p-8 max-w-4xl w-100">
                                        <div class="prouduct_sell_sec">
                                            <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Initial Capital</label>
                                            <input type="text" class="max-w-293  company_wrapper_input" id="initial_capital_amount" name="initial_captial['amount']" value="<?php echo $initial_capital_amount; ?>" placeholder="Amount" required>
                                        </div>
                                        <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6 pt-5">Assumptions</label>
                                        <textarea type="text" id="initial_capital_assumptions" name="initial_captial['assumptions']" placeholder="Amount" class="w-100 mw-100 h-20 company_wrapper_input" required><?php echo $initial_capital_assumption;?></textarea>
                                    </div>
                                    <div class="serive_pricing_skils border solid rounded-lg p-8 max-w-4xl w-100 mt-8">
                                        <div class="prouduct_sell_sec">
                                            <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Working Capital</label>
                                            <input type="text" id="working_capital_amount" name="working_captial['amount']" value="<?php echo $working_capital_amount; ?>" placeholder="Amount" class="max-w-293  company_wrapper_input" required>
                                        </div>
                                        <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6 pt-5">Assumptions</label>
                                        <textarea type="text" id="working_capital_assumptions" name="working_captial['assumptions']" placeholder="Amount" class="w-100 mw-100 h-20 company_wrapper_input" required><?php echo $working_capital_assumption; ?></textarea>
                                    </div>
                                    <div class="serive_pricing_skils border solid rounded-lg p-8 max-w-4xl w-100 mt-8">
                                        <div class="prouduct_sell_sec">
                                            <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Capital Expenditure</label>
                                            <input type="text" id="capital_expenditure_amount" name="capital_expenditure['amount']" value="<?php echo $capital_expenditure_amount; ?>" placeholder="Amount" class="max-w-293  company_wrapper_input" required>
                                        </div>
                                        <label for="" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6 pt-5">Assumptions</label>
                                        <textarea type="text" id="capital_expenditure_assumptions" name="capital_expenditure['assumptions']" placeholder="Amount" class="w-100 mw-100 h-20 company_wrapper_input" required><?php echo $capital_expenditure_assumption; ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="input_checkbox"  style="display:none;">
                            <input type="checkbox" name="print_omit" id="print_omit20" value="Y"  checked >
                            <label for="print_omit20">Click this box to omit this section from your printed business plan</label>
                        </div>
                        <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />                
                        <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
                    </form>
                    </div>

                </div>
                <div class="service_pricing_wrapper  pt-10" id="operatingExpenses">
                    <div class="d-flex justify-content-between align-items-center md-gap-1 gap-2 max-w-4xl w-100 flex-wrap pb-5">
                        <div class="service_pricing_text">
                            <h2 class="text-lg font-semibold color-011627 mb-0 md-line-height-6">Operating Expenses</h2>
                            <p class="text-sm font-normal color-011627 mb-0 md-line-height-6">Please list your monthly operating expenses:</p>
                        </div>
                        <div class="btm_pricing_service">
                            <button name="generateAI" type="button" class="generate_btn generate_ai" data-slug="operating_expenses" data-callback="loadOperatingExpenses">Generate from AI <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" /></button>
                        </div>
                    </div>

                    <div>
                        <form action="" id="submit_form" name="submit_form" class="submit_form cover_form">
                            <div id="frm-section-board-members" class="bpform_edit">
                                <fieldset>
                                    <div class="oecategory-container" id="oecategory-container">
                                        <?php                                        
                                        foreach($OECategory_arr as $k => $itm) {
                                        $a= $k+1;
                                        $category_name = $itm['cost_name'];
                                        $amount = $itm['cost_value'];
                                        $growthrate = $itm['cost_growthrate'];
                                        $assumption = $itm['cost_assumptions'];
                                        ?>
                                        <div class="d-flex align-items-center gap-4 group competitorbox" id="oecategory-<?php echo $a; ?>" style="padding-top: 20px;">
                                            <div class="serive_pricing_skils border solid rounded-lg p-8 max-w-4xl w-100">
                                                <div class="d-flex gap-3 flex-wrap">
                                                    <div class="prouduct_sell_first w-100 max-w-xs">
                                                        <label for="category_name_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Category</label>
                                                        <input type="text" class="w-100 company_wrapper_input category_name" name="category_name[]" id="category_<?php echo $a; ?>" maxlength="100" placeholder="0.00" value="<?php echo $category_name; ?>"/>
                                                    </div>
                                                    <div class="prouduct_sell_sec position-relative  max-w-180 w-100">
                                                        <label for="category_amount_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Amount</label>
                                                        <div class="fixBlocks">
                                                            <input type="text" class="w-44 company_wrapper_input category_amount" name="amount[]" id="amount_<?php echo $a; ?>" maxlength="100" placeholder="0.00" value="<?php echo $amount; ?>"/>
                                                            <p class="position-absolute">USD</p>
                                                        </div>
                                                    </div>
                                                    <div class="prouduct_sell_sec position-relative w-100 max-w-160">
                                                        <label for="category_growthrate_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 mb-2-5 md-line-height-6">Growth Rate</label>
                                                        <div class="fixBlocks">
                                                            <input type="text" class="mw-100 w-100 company_wrapper_input category_growthrate" name="growthrate[]" id="growthrate_<?php echo $a; ?>" maxlength="100" placeholder="0.00" value="<?php echo $growthrate; ?>"/>
                                                            <p class="position-absolute">%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label for="category_assumption_<?php echo $a; ?>" class="d-block text-sm font-medium color-011627 pb-2-5 pt-5">Assumptions</label>
                                                <textarea class="w-100 h-24 company_wrapper_input category_assumption" name="assumption[]" id="assumption_<?php echo $a; ?>" placeholder="Assumption" word-limit=true max-words="250"><?php echo $assumption; ?></textarea>
                                            </div>
                                            <div class="delet_btn">
                                                <a href="javascript:;" class="red right" title="Delete Category" onclick="deleteItem(this)">
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
                                        ?>
                                    </div> 
                                </fieldset>
                            </div>
                            <div class="input_checkbox"  style="display:none;">
                                <input type="checkbox" name="print_omit" id="print_omit20" value="Y"  checked >
                                <label for="print_omit20">Click this box to omit this section from your printed business plan</label>
                            </div>
                            <input type="hidden" id="step" name="step" value="oecategory" />
                            <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />                
                            <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
                        </form>
                    </div>
                </div>
                <div class="add_another_btn_wrapper">
                    <a href="javascript:;" id="btn-Addoecategory" class="add_another_btn" data-blk="oecategory">Add Another</a>
                </div>
                <div class="form_btn">

                <button class="themebtn_new theme_btn" onClick="update_json_and_submit('col_financial-assumptions');">
                    Save & Continue
                    <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
                </button>
                <input type="hidden" id="step" name="step" value="executive-summary-product-success-factors" />
                <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
            </div>
            </div>
            
        </div>

    </div>
    
</div>