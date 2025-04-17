<div class="overview_right_wp" id="col_overview">

    <div class="right_col">

        <?php
        $db->where("md5(plan_id)", $plan);
        $plan_data = $db->getOne("plan_master");
      
       
		$currencies = $db->query("SELECT currency_code, symbol FROM world_currencies ORDER BY ID");

        $selY = $selN = '';
        ${"sel" . $plan_data['existing_company']} = ' checked ';
        $sel1 = $sel2 = $sel3 = '';


        $sel_English = "";
        $sel_French = "";
        $sel_German = "";
        $sel_Spanish = "";
        $sel_Chinese = "";
        $sel_Portuguese = "";
        $sel_Japanese = "";
        $sel_Russian = "";
        $sel_Korean = "";

        if ($plan_data['seeking_fund'] == 'Yes') {
            $sel1 = ' selected ';
        }
        if ($plan_data['seeking_fund'] == 'No') {
            $sel2 = ' selected ';
        }
        if ($plan_data['seeking_fund'] == 'Not Sure') {
            $sel3 = ' selected ';
        }

        if($plan_data['goal_business_plan'] == "Business Funding"){
            $sel_business_funding = "selected";
        }
        if($plan_data['goal_business_plan'] == "Business Growth"){
            $sel_business_growth = "selected";
        }
        if($plan_data['goal_business_plan'] == "Market Research"){
            $sel_market_research = "selected";
        }
        if($plan_data['goal_business_plan'] == "Other"){
            $sel_other = "selected";
        }


        ${"sel_" . $plan_data['language']} = " selected ";

        ?>
        <div class="start_your">
            <h2>Overview</h2>
        </div>
        <form action="" method="post" id="frmsubmit" name="frmsubmit" class="frmsubmit submit_form right_list">
            <div class="input_group">
                <label class="title_form text-sm font-medium mb-2-5 md-line-height-6"> Is this a new or existing company?</label>
                <div class="input_field_2">
                    <div class="d-flex">
                        <label>
                            <input type="radio" id="new_company" name="company_type" value="N" <?php echo $selN; ?> />
                            <span class="new_company">New</span>
                        </label>
                        <label class="ml-2">
                            <input type="radio" id="existing_company" name="company_type" value="Y" <?php echo $selY; ?> />
                            <span class="exist_lable">Existing</span>                  
                        </label>
                    </div>
                </div>
            </div>

            <div class="input_group forexisting" style="display:none;">
                <label class="title_form" for="industry"><span class="right_arrow">&gt;</span> Describe what your company is/does in a sentence<span class="red">*</span>&nbsp;<i flow="down" tooltip="Please describe what your business does in a sentence by being as specific as possible about the industry. (E.g. Eco friendly beach hotel)" class="fa fa-question-circle" aria-hidden="true"></i></label>
                <textarea name="industry" id="industry" cols="30" rows="10" class="custom_input py-2-5 px-5" placeholder="(E.g. Eco friendly beach hotel)" word-limit="true" max-words="500"></textarea>
                <span></span>
                <div  class="writing_error max-w-2xl text-right"></div>
            </div>
            <div class="describe_modify_new forexisting">
                    <label class="title_form text-sm font-medium mb-2-5 md-line-height-6  d-block"> Describe what your company is/does </label>
                    <textarea name="industry" id="industry" class="describe_input rounded-lg" placeholder="Please type here ....."  word-limit="true" max-words="500" style="height:100px !important; "><?php echo $plan_data['industry_operate']; ?></textarea>
                    <span></span>
                    <div style="margin-left:10px;" class="writing_error"></div>
            </div>
            <div class="input_group forexisting">
                <label class="title_form text-sm font-medium mb-2-5 md-line-height-6 pt-5">What is the main goal of your business plan?</label>
                <select class="custom_select" id="goal_business_plan" name="goal_business_plan">
                    <option value="Business Funding" <?php echo $sel_business_funding; ?>>Business Funding</option>
                    <option value="Business Growth" <?php echo $sel_business_growth; ?>>Business Growth</option>
                    <option value="Market Research" <?php echo $sel_market_research; ?>>Market Research</option>
                    <option value="Other" <?php echo $sel_other; ?>>Other</option>
                </select>
            </div>
            <div class="input_group forexisting">
                <label class="title_form text-sm font-medium mb-2-5 md-line-height-6 pt-5">Select language for your plan</label>
                <select class="custom_select" id="language" name="language">
                    <option value="English" <?php echo $sel_English; ?>>English</option>
                    <option value="French" <?php echo $sel_French; ?>>French</option>
                    <option value="German" <?php echo $sel_German; ?>>German</option>
                    <option value="Spanish" <?php echo $sel_Spanish; ?>>Spanish</option>
                    <option value="Chinese" <?php echo $sel_Chinese; ?>>Chinese</option>
                    <option value="Portuguese" <?php echo $sel_Portuguese; ?>>Portuguese</option>
                    <option value="Japanese" <?php echo $sel_Japanese; ?>>Japanese</option>
                    <option value="Russian" <?php echo $sel_Russian; ?>>Russian</option>
                    <option value="Korean" <?php echo $sel_Korean; ?>>Korean</option>
                </select>
            </div>
          	
			<div class="input_group forexisting">
              <label class="title_form text-sm font-medium mb-2-5 md-line-height-6 pt-5">
                Select Currency for your plan
              </label>
              <select class="custom_select" id="currency" name="currency">
                <?php
                $selected_currency = "";
                $selected_currency = isset($plan_data['currency']) ? $plan_data['currency'] : '';
				if($selected_currency == ""){
                  $selected_currency = "USD";
                }                
                foreach ($currencies as $currency) {
                  $currency_code = $currency['currency_code'];                  
                  $selected = ($currency_code == $selected_currency) ? 'selected' : '';
                  echo "<option value=\"$currency_code\" $selected>$currency_code</option>";
                }
                ?>
              </select>
          </div>

            <div class="input_group" style="display:none;">
                <label class="title_form" for="seeking_fund"><span class="right_arrow">&gt;</span> Will you be seeking funding for your business plan? <span class="red">*</span></label>
                <select class="custom_select" id="seeking_fund" name="seeking_fund">
                    <option value="select industry">Please Select</option>
                    <option value="Yes" <?php echo $sel1; ?>>Yes</option>
                    <option value="No" <?php echo $sel2; ?>>No</option>
                    <option value="Not Sure" <?php echo $sel3; ?>>Not Sure</option>
                </select>
            </div>

            <div class="form_btn">
                <button class="themebtn_new theme_btn">
                    Save & Continue
                    <img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
                </button>
                <input type="hidden" id="step" name="step" value="overviewdata" />
                <input type="hidden" id="plan" name="plan" value="<?php echo $_REQUEST['plan']; ?>" />
                <textarea type="hidden" id="generate_text" name="generate_text" class="generate_text"></textarea>
            </div>

        </form>
    </div>

</div>