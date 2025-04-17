<?php

$db->where("md5(plan_id)", $plan);
$list = $db->getOne("cover_page_information_master");
$button_text = "Start Now";

$json_data = array();
if ($db->count) {
    $button_text = "Start Now";
    $json_data[] = array('name' => 'Company name', "val" => $list['company_name']);
    $json_data[] = array('name' => 'Full name', "val" => $list['full_name']);
    $json_data[] = array('name' => 'Email', "val" => $list['email']);
    $json_data[] = array('name' => 'City', "val" => $list['city']);
    $json_data[] = array('name' => 'State', "val" => $list['state']);
}

$json_data1 = array();
$json_data1[] = array('name' => 'Company name',  "val" => 'jQuery("#CoverPopup2").find(\'#company\').val()');
$json_data1[] = array('name' => 'Full name',     "val" => 'jQuery("#CoverPopup2").find(\'#name\').val()');
$json_data1[] = array('name' => 'Email',         "val" => 'jQuery("#CoverPopup2").find(\'#email\').val()');
$json_data1[] = array('name' => 'City',          "val" => 'jQuery("#CoverPopup2").find(\'#city\').val()');
$json_data1[] = array('name' => 'State',         "val" => 'jQuery("#CoverPopup2").find(\'#state\').val()');

$overview = isset($list['overview']) ? $list['overview'] : "";
$print_omit = '';
if (isset($list['print_omit'])) {
    if ($list['print_omit'] == 'Y') {
        $print_omit = ' checked ';
    }
}

${"sel_" . $list['state']} = " selected ";
${"sel_city_" . $list['city']} = " selected ";

?>

<div class="overview_right_wp" id="col_cover-page-information">
	<div class="right_col competitive_advantage">

		<div class="start_your executive_tooltip_mobile">
			<h2>Cover Page Information</h2>
		</div>

		<div class="right_list" id="CoverPopup2">
			<form id="submit_form" name="submit_form" class="submit_form cover_form">
                    <div class="company_wrapper">
                        <label for="company" class="text-sm font-medium color-011627 pb-2-5 mb-0">Company Name</label>
                        <input type="text" name="company" id="company" class="testing company_wrapper_input rounded-lg" placeholder="Type here..." value="<?php echo @$list['company_name'];?>">
                    </div>
                    <div class="compnay_wrapper_sec d-flex gap-2 align-items-start pt-5">
                        <div class="testing input_group forexisting p-0 w-100">
                            <label class="title_form text-sm font-medium mb-0 pb-2-5">Country/State</label>
                            <input type="text" id="state" name="state" value="<?php echo $list['state']; ?>" class="company_wrapper_input " />
                        </div>
                        <div class="testing input_group forexisting p-0 w-100">
                            <label class="title_form text-sm font-medium mb-0 pb-2-5">City</label>
                            <input type="text" id="city" name="city" value="<?php echo $list['city']; ?>"  class="company_wrapper_input "/>
                        </div>
                    </div>
                    <div class="d-flex company_skils_wrapper align-items-start gap-2" style="display:none !important">
                        <div class="company_wrapper">
                            <label for="name" class="text-sm font-medium color-011627 pb-2-5 mb-0">Your Full Name</label>
                            <input type="text" name="name" id="name" class="testing company_wrapper_input rounded-lg" placeholder="Type here..." value="<?php echo @$list['full_name'];?>">
                        </div>
                        <div class="company_wrapper">
                            <label for="email" class="text-sm font-medium color-011627 pb-2-5 mb-0">Your Email Address</label>
                            <input type="text" name="email" id="email" class="testing company_wrapper_input rounded-lg" placeholder="youremail@mail.com" value="<?php echo @$list['email'];?>">
                        </div>
                    </div>
                    <input type="hidden" id="step" name="step" value="cover-page-information" />
					<input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
            </form>
            <div class="form_btn">
            <button class="themebtn_new theme_btn" onclick="submit_form_btn('CoverPopup2')">
                Save & Continue
                <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
            </button>

        </div>
        </div>

    </div>
</div>
