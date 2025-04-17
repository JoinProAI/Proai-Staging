<?php
set_time_limit(0);
ini_set('max_execution_time', 600); // 5 minutes
ini_set('memory_limit', '5G');

include_once "includes/config.php";


$db->orderby("sort_no","asc");
$prompt_list  = $db->get("openai_prompt",null, "main_title, name, slug, sort_no");

$order_list = array();
foreach($prompt_list as $p){
  if($p['slug'] != "financial_model"){
    $order_list[] = $p['slug'];
  }
}

$db->where("md5(plan_id)", $_REQUEST['plan']);
$db->where("topic_slug","financial_model","!=");
$db->orderby("topic_slug", "asc", $order_list);
$business_plan_input = $db->get("openai_output");



$db->join("cover_page_information_master cpi", "cpi.plan_id=pm.plan_id", "LEFT");
$db->where("md5(pm.plan_id)", $_REQUEST['plan']);
$company_info = $db->get("plan_master pm");


$file_name = txt_to_fol($company_info[0]['company_name']);



$db->where("md5(plan_id)", $_REQUEST['plan']);
$financial_data = $db->getOne("financial_assumptions");


$capital_requirement = json_decode($financial_data['capital_requirement'],true);
$operating_expenses = json_decode($financial_data['operating_expenses'],true);

$revenue_validated = $financial_data['revenue_validated'];

$revenue_validated = extract5YearsData($revenue_validated);

$revenue_validated = json_decode($revenue_validated,true);

$tables = generateStartupTables($capital_requirement, $revenue_validated, $operating_expenses);


if(isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == "true"){

  include_once 'html2pdf/autoload.php';

  ob_start();

}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
  
    <title><?php echo $company_info[0]['company_name']." - Business Plan"; ?></title>
   

    <?php

    $is_pdf = isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == true;
   
    $stylesheet = $is_pdf ? "plan_show_pdf.css?" . strtotime('now')  : "plan_show.css?" . strtotime('now');


    ?>

    <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>/assets/css/<?php echo $stylesheet; ?>">

    <style>
      body{background-color: #fff;}
      .section {
        page-break-after: always;
      }
      .no-page-break { page-break-after: inherit; }
      .section:last-child {
        page-break-after: avoid;
      }
    </style>



    
  </head>
  <body>


    <?php
    $a4_width = 774; // 210mm
    $a4_height = 1100; // 297mm
    ?>

    <?php

    if(isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == true){
    }
    else {
    ?>

    <style>
      .business-plan {
        min-height: <?php echo $a4_height; ?>px;
        margin: 0 auto;
        /*  padding: 0px;*/
        padding: 40px;

        width: 100%;
        box-sizing: border-box;
      }


    </style>


    <?php

    }

    ?> 
    <?php
    $is_pdf = isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == true;
    ?>

    <?php if (!isset($_REQUEST['pdf']) || $_REQUEST['pdf'] != "true") { ?>
    <?php startdiv(); ?>

    <div class="section-header" >

      <div class="content">
        <h1><?php echo $company_info[0]['company_name']; ?></h1>
        <div class="divider"></div>
        <h2>Business Plan</h2>
        <div class="date"><?php echo date('F Y'); ?></div>
      </div>
    </div>

    <?php enddiv(); ?>
    <?php } ?>
   
    <!-- Table of Contents Section Start-->
   
 <?php if (!isset($_REQUEST['pdf']) || $_REQUEST['pdf'] != "true") { ?>
    <?php startdiv(); ?>
    
    <pagebreak />
    <div class="section_table_of_contents">
        <h1 class="section_table_of_contents_header">Table of Contents</h1>
        <ul>
            <li><strong><a href="#executive-summary">Executive Summary</a></strong></li>
            <ul>
                <li><a href="#problem-statement">Problem Statement</a></li>
                <li><a href="#proposed-solution">Proposed Solution</a></li>
                <li><a href="#value-proposition">Value Proposition</a></li>
                <li><a href="#three-year-objectives">Three Year Objectives</a></li>
                <li><a href="#keys-to-success">Keys to Success</a></li>
            </ul>

            <li><strong><a href="#company-description">Company Description</a></strong></li>
            <ul>
                <li><a href="#overview">Overview</a></li>
                <li><a href="#products/services">Products/Services</a></li>
                <li><a href="#mission">Mission</a></li>
                <li><a href="#vision">Vision</a></li>
                <li><a href="#values">Values</a></li>
            </ul>

            <li><strong><a href="#industry-overview">Industry Overview</a></strong></li>
            <ul>
                <li><a href="#industry-description">Industry Description</a></li>
                <li><a href="#market-needs">Market Needs</a></li>
                <li><a href="#market-trends">Market Trends</a></li>
                <li><a href="#market-segmentation">Market Segmentation</a></li>
            </ul>

            <li><strong><a href="#competitive-comparison">Competitive Comparison</a></strong></li>
            <ul>
                <li><a href="#competitors">Competitors</a></li>
                <li><a href="#competitive-advantage">Competitive Advantage</a></li>
            </ul>

            <li><strong><a href="#strategy-implementation">Strategy & Implementation Summary</a></strong></li>
            <ul>
                <li><a href="#management-team">Management Team</a></li>
                <li><a href="#swot-analysis">SWOT Analysis</a></li>
                <li><a href="#pestle-analysis">PESTLE Analysis</a></li>
            </ul>

            <li><strong><a href="#marketing-plan">Marketing Plan</a></strong></li>
            <ul>
                <li><a href="#promotion-strategy">Promotion Strategy</a></li>
            </ul>

            <li><strong><a href="#financial-forecasts">Financial Forecasts</a></strong></li>
            <ul>
                <li><a href="#use-of-funds">Use of Funds</a></li>
                <li><a href="#profit-loss-projection">Projected Profit and Loss</a></li>
                <li><a href="#projected-cashflow">Projected Cashflow </a></li> 
                 <li><a href="#projected-balancesheet">Projected Balance Sheet</a></li> 
                <li><a href="#profit-loss">Profit & Loss (Year 1)</a></li>
                <li><a href="#cash-flow-projection">Cash Flow Projection</a></li>
                
            </ul>
        </ul>
    </div>
    <pagebreak />
<?php } ?>
    <?//php endif; ?>

    <?php enddiv(); ?>

    <!-- Table of Contents Section End-->

    <?php startdiv(); ?>

    
    <div class="section">
      <?php
      $main_title = array();
      foreach($business_plan_input as $k => $v){
        if(!in_array($v['topic_title'], $main_title)){
          if(count($main_title) > 0){

      ?>
    </div> <?php enddiv(); ?> <?php startdiv(); ?> <div class="section">
    <?php
          }

          $main_title[] = $v['topic_title'];
    ?>
    <h2 class="main-title" id="<?php echo strtolower(str_replace(' ', '-', $v['topic_title'])); ?>">
      <?php echo htmlspecialchars($v['topic_title']); ?>
    </h2>
    <?php
        }
    ?>
    <h3 class="subtitle" id="<?php echo strtolower(str_replace(' ', '-', $v['topic_subtitle'])); ?>">
      <?php echo htmlspecialchars($v['topic_subtitle']); ?>
    </h3>
    <div class="content">
      <?php 
        if($v["api_output_edited"] != ""){
          $edited_content = $v["api_output_edited"];
        }
        else{
          $edited_content = $v['api_output'];
        }
      ?>
      <?php echo nl2br($edited_content); //echo nl2br($v['api_output']); ?>
    </div>
    <?php
      }

    ?>
    </div>



    <?php //Financial Forcast Code Start ?>

    <div class="section">
      <h2 class="main-title" id="financial-forecasts" >Financial Forecasts</h2>
      <h3 class="subtitle"  id="use-of-funds">Use of Funds</h3>

      <!-- Financial Tables -->

      <table class="<?php echo $is_pdf ? 'pdf-table-of-contents' : 'table-of-contents'; ?>" cellpadding="2" cellspacing="0">

        <tr>
          <td width="50%" >
            <?php 

            $tables_to_display = ['startup_assets', 'startup_expenses', 'total_requirements'];

            foreach ($tables_to_display as $table_name) { ?>
            <table class="financial-table">
              <tr>
                <th colspan="2" class="main-header">
                  <?php echo ucwords(str_replace('_', ' ', $table_name)); ?>
                </th>
              </tr>
              <?php foreach ($tables[$table_name] as $category => $data) { 
              $amount = is_array($data) ? $data['amount'] ?? $data['annual'] : $data;
              ?>
              <tr>
                <td class="text-left"><?php echo $category; ?></td>
                <td class="text-right"><?php echo number_format($amount, 2); ?></td>
              </tr>
              <?php } ?>
            </table>

            <?php } ?>
          </td>
          <td class="financial-table-container <?php echo $is_pdf ? 'pdf-padding' : 'web-padding'; ?>" valign="top">
            <?php 
            $tables_to_display_right = ['startup_liabilities', 'startup_investments', 'startup_funding'];

            foreach ($tables_to_display_right as $table_name) { ?>
            <table class="financial-table">
              <tr>
                <th colspan="2" class="main-header">
                  <?php echo ucwords(str_replace('_', ' ', $table_name)); ?>
                </th>
              </tr>
              <?php foreach ($tables[$table_name] as $category => $data) { ?>
              <tr>
                <td class="text-left"><?php echo $category; ?></td>
                <td class="text-right"><?php echo number_format($data['amount'], 2); ?></td>
              </tr>
              <?php } ?>
            </table>
            <?php } ?>
          </td>

        </tr>
      </table>

      <!-- Graph Section -->
      <div >
        <?php
        require_once('svgGraph/autoloader.php');

        $values = [];
        $colors = [];
        foreach ($tables['startup_expenses'] as $type => $data) {
          if ($type != 'Total Startup Expenses') {
            $values[$type] = $data['monthly'];
            $colors[] = '#4267B2';
          }
        }

        $settings = [
          'axis_text_angle_h' => -45,
          'back_colour' => 'white',
          'pad_bottom' => $is_pdf ? 5 : 40,
        ];
        $width = $is_pdf ? '400px' : '100%';
        $height = $is_pdf ? '200px' : '75%';
        $paddingBottom = $is_pdf ? '28%' : '0%'; 

        if ($is_pdf) {
          echo "<div style='width: $width; height: $height; position: relative; margin: 5px auto;'>";
        } else {
          echo "<div style='width: $width; height: $height; padding-bottom: $paddingBottom; text-align: center;'>";
        }


        $graph = new Goat1000\SVGGraph\SVGGraph($is_pdf ? '100%' : '100%', $is_pdf ? '75px' : '100%', $settings);
        $graph->axis_font = 'Arial';
        $graph->legend_font = 'Arial';
        $graph->graph_title_font = 'Arial';
        $graph->colours($colors);
        $graph->values($values);

        echo $graph->render('BarGraph');

        echo '</div>';
        echo '</div>';
        ?>
      </div>


      <?php //Revenue Forcast Code Start ?>		
      <div class="section">
        <?php
        $pnl_annual = $revenue_validated['pnl_annual'];
        $extracted_data = exractForecastData($pnl_annual);

        $scenario_data = [
          'best_case' => [],
          'worst_case' => [],
          'most_likely' => []
        ];
        ?>

        <!-- Revenue Forecast Table -->
      
        <table class="financial-table" <?php echo $is_pdf ? 'style="font-size: 15px;"' : ''; ?> id="revenue-forecast" >

          <?php  
          $plan_id = $_SESSION['PRO_PLAN_ID'];
          $plan_data = $db->where('plan_id', $plan_id)->getOne("plan_master");

          ?>
          <tr>
            <th colspan="6" class="main-header">Revenue Forecast (Thousands <?php echo $plan_data['currency']; ?>)</th>
          </tr>
          <tr>
            <th class="sub-header"></th>
            <?php
      
            $years = [];
            foreach ($pnl_annual as $entry) {
              $date = new DateTime($entry['date']);
              $years[] = $date->format('Y');
            }
            foreach ($years as $index => $year) {
              echo "<th class='sub-header text-right'>Year " . ($index + 1) . "</th>";
            }
            ?>
          </tr> 
          <tr>
            <td colspan="6" class="sub-header"><strong>Revenue by Product</strong></td>
          </tr>
          <?php foreach ($extracted_data['revenue'] as $product => $yearly_data) { ?>
          <tr>
            <td class="product-name"><?php echo $product; ?></td>
            <?php foreach ($yearly_data as $year => $value) { ?>
            <td class="text-right"><?php echo number_format($value / 1000, 0); ?></td>
            <?php } ?>
          </tr>
          <?php } ?>
          <tr>
            <td class="sub-header"><strong>Total Revenue</strong></td>
            <?php foreach ($extracted_data['total']['revenue'] as $v) { ?>
            <td class="sub-header text-right"><?php echo number_format($v / 1000, 0); ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td colspan="6" class="sub-header"><strong>Cost by Product</strong></td>
          </tr>
          <?php foreach ($extracted_data['cogs'] as $product => $yearly_data) { ?>
          <tr>
            <td class="product-name"><?php echo $product; ?></td>
            <?php foreach ($yearly_data as $year => $value) { ?>
            <td class="text-right"><?php echo number_format($value / 1000, 0); ?></td>
            <?php } ?>
          </tr>
          <?php } ?>
          <tr>
            <td class="total-row"><strong>Total Cost of Goods Sold</strong></td>
            <?php 
            $i = 1;
            foreach ($extracted_data['total']['cogs'] as $k => $v) {
              $gross = $extracted_data['total']['revenue'][$k] - $v;
              $scenario_data['most_likely']["Year " . $i] = round($gross / 1000, 0);
            ?>
            <td class="total-row text-right"><?php echo number_format($v / 1000, 0); ?></td>
            <?php 
              $i++; 
            } 
            ?>
          </tr>

        </table>
      </div>

      <?php //Revenue Forcast Code End ?>

      <?php //Best & Worst Case Code Start ?>

      <table class="financial-table <?php echo $is_pdf ? 'pdf-financial-table' : ''; ?>">  
        <!-- Best Case Header -->
        <tr>
          <th colspan="6" class="main-header" >Best Case Scenario (Revenue Increase by 15%) (Thousands <?php echo $plan_data['currency']; ?>)</th>
        </tr>
        <tr>
          <th class="sub-header"> </th>
          <?php
          $years = [];
          foreach ($pnl_annual as $entry) {
            $date = new DateTime($entry['date']);
            $years[] = $date->format('Y');
          }
          foreach ($years as $index => $year) {
            echo "<th class='sub-header text-right-pad-10'>Year " . ($index + 1) . "</th>";
          }
          ?>
        </tr>			    
        <tr>
          <td class="sub-header"><strong>Revenue</strong></td>
          <?php
          foreach ($extracted_data['total']['revenue'] as $v) {
            $revenue_15 = $v * 1.15;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($revenue_15 / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>			    
        <tr>
          <td class="sub-header"><strong>Total Cost of Goods Sold</strong></td>
          <?php
          foreach ($extracted_data['total']['cogs'] as $v) {
            $expense_15 = $v * 1.15;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($expense_15 / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>
        <tr>
          <td class="sub-header"><strong>Gross Margin</strong></td>
          <?php
          foreach ($extracted_data['total']['cogs'] as $k => $v) {
            $expense_15 = $v * 1.15;
            $revenue_15 = $extracted_data['total']['revenue'][$k] * 1.15;
            $gross_margin = $revenue_15 - $expense_15;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($gross_margin / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>
        <tr>
          <td class="sub-header"><strong>Gross Margin Revenue</strong></td>
          <?php
          $i = 1; 
          foreach ($extracted_data['total']['cogs'] as $k => $v) {
            $expense_15 = $v * 1.15;
            $revenue_15 = $extracted_data['total']['revenue'][$k] * 1.15;
            $gross_margin = $revenue_15 - $expense_15;
            $gross_per = ($gross_margin / $revenue_15) * 100;

            $scenario_data['best_case']["Year " . $i] = round($gross_margin / 1000, 0);

            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($gross_per, 2) . "</b></td>";

            $i++; 
          }
          ?>
        </tr>

        <!-- Worst Case Header -->
        <tr>
          <th colspan="6" class="main-header">Worst Case Scenario (Revenue Decrease by 15%) (Thousands <?php echo $plan_data['currency']; ?>)</th>
        </tr>
        <tr>
          <th class="sub-header"> </th>
          <?php
          foreach ($years as $index => $year) {
            echo "<th class='sub-header text-right-pad-10'>Year " . ($index + 1) . "</th>";
          }
          ?>
        </tr>			    
        <tr>
          <td class="sub-header"><strong>Revenue</strong></td>
          <?php
          foreach ($extracted_data['total']['revenue'] as $v) {
            $revenue_15 = $v * 0.85;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($revenue_15 / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>			    
        <tr>
          <td class="sub-header"><strong>Total Cost of Goods Sold</strong></td>
          <?php
          foreach ($extracted_data['total']['cogs'] as $v) {
            $expense_15 = $v * 0.85;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($expense_15 / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>
        <tr>
          <td class="sub-header"><strong>Gross Margin</strong></td>
          <?php
          foreach ($extracted_data['total']['cogs'] as $k => $v) {
            $expense_15 = $v * 0.85;
            $revenue_15 = $extracted_data['total']['revenue'][$k] * 0.85;
            $gross_margin = $revenue_15 - $expense_15;
            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($gross_margin / 1000, 0) . "</b></td>";
          }
          ?>
        </tr>
        <tr>
          <td class="sub-header"><strong>Gross Margin Revenue</strong></td>
          <?php
          $i = 1; 
          foreach ($extracted_data['total']['cogs'] as $k => $v) {
           
            $expense_15 = $v * 0.85;
            $revenue_15 = $extracted_data['total']['revenue'][$k] * 0.85;
            $gross_margin = $revenue_15 - $expense_15;

            $gross_per = ($revenue_15 != 0) ? ($gross_margin / $revenue_15) * 100 : 0;

            $scenario_data['worst_case']["Year " . $i] = round($gross_margin / 1000, 0);

            echo "<td class='sub-header text-right-pad-10'><b>" . number_format($gross_per, 2) . "</b></td>";

            $i++; 
          }
          ?>
        </tr>

      </table>

      <!-- Graph Section -->
      <div style="width: 100%; margin: 20px 0px; text-align: center;">
        <?php
        $values = [
          $scenario_data['best_case'],
          $scenario_data['most_likely'], 
          $scenario_data['worst_case']
        ];

        $settings = [
          'graph_title' => 'Scenarios Analysis ($000)',
          'show_grid' => true,
          'legend_entries' => ['Best Case', 'Most Likely', 'Worst Case'],
          'legend_colour' => ['#4267B2', '#34A853', '#FBBC05']
        ];
       
        $colors = array(
          '#4267B2', 
          '#34A853',
          '#FBBC05'  
        );

        if ($is_pdf) {
          echo '<div style="width:100%; max-width:800px; height:auto; text-align:center; margin:0 auto;">';
        } else {
          echo '<div style="width:100%; height:0; padding-bottom:80%; position:relative;">';
          echo '<div style="position:absolute; width:100%; height:100%;">';
        }

        $graph = new Goat1000\SVGGraph\SVGGraph('100%', '100%', $settings);
        $graph->values($values);
      
        $graph->colours($colors);
     
        $graph->group_space = 20; 
        $graph->bar_space = 0; 
        $graph->legend_position = 'bottom'; 
        $graph->legend_columns = 3; 
        echo $graph->render('GroupedBarGraph');

        echo '</div></div>';
        ?>
      </div>


      <?php //Best & Worst Case Code End ?>


      <?php //Projected Profit & Loss Code Start ?>
      <div class="section">
        <h3 class="main-title" id="profit-loss-projection">Projected Profit and Loss</h3>
      
        <table class="financial-table" <?php echo $is_pdf ? 'style="font-size: 15px;"' : ''; ?>>

          <tr>
            <th colspan="6" class="main-header">Pro Forma Profit and Loss (Thousands <?php echo $plan_data['currency']; ?>)</th>
          </tr>
          <tr>
            <th class="sub-header"> </th>
            <?php
           
            $years = array();
            foreach ($pnl_annual as $entry) {
              $date = new DateTime($entry['date']);
              $years[] = $date->format('Y');
            }
            foreach ($years as $index => $year) {
            ?>
            <th class="sub-header text-right-pad-10">Year <?php echo ($index + 1); ?></th>
            <?php
            }
            ?>
          </tr>
          <tr>
            <td class="sub-header"><b>Revenue</b></td>
            <?php
            foreach($extracted_data['total']['revenue'] as $v){
            ?>
            <td class="sub-header text-right-pad-10"><b><?php echo number_format($v/1000, 0); ?></b></td>
            <?php
            }
            ?>
          </tr>
          <tr>
            <td class="sub-header"><b>Total Cost of Revenue</b></td>
            <?php
            foreach($extracted_data['total']['cogs'] as $v){
            ?>
            <td class="sub-header text-right-pad-10"><b><?php echo number_format($v/1000, 0); ?></b></td>
            <?php
            }
            ?>
          </tr>
          <?php
          $gross_margin=array();
          $total_expenses=array();
          ?>
          <tr>
            <td class="sub-header"><b>Gross Margin</b></td>
            <?php
            foreach($extracted_data['total']['cogs'] as $k=>$v){
              $revenue=$extracted_data['total']['revenue'][$k];
              $cogs = $extracted_data['total']['cogs'][$k];
              $gross_margin = $revenue - $cogs;
              $gross_margin_array[]=$gross_margin;
            ?>
            <td class="sub-header text-right-pad-10"><b><?php echo number_format($gross_margin/1000, 0); ?></b></td>
            <?php
            }
            ?>
          </tr>
          <tr>
            <td class="sub-header"><b>Gross Margin Revenue</b></td>
            <?php
            foreach($extracted_data['total']['cogs'] as $k=>$v){
              $revenue=$extracted_data['total']['revenue'][$k];
              $cogs = $extracted_data['total']['cogs'][$k];
              $gross_margin = $revenue - $cogs;
              $gross_margin_per=($gross_margin/$revenue) * 100;
            ?>
            <td class="sub-header text-right-pad-10"><b><?php echo number_format($gross_margin_per, 2); ?></b></td>
            <?php
            }
            ?>
          </tr>
          <tr>
            <td colspan="6"> </td>
          </tr>
          <tr>
            <td class="sub-header" ><b>Expenses</b></td>
            <?php
            foreach($extracted_data['total']['expense'] as $v){
            ?>
            <td class="sub-header"> </td>
            <?php
            }
            ?>
          </tr>
          <?php
          foreach($extracted_data['expenses'] as $k=>$v){
          ?>
          <tr>
            <td>    <?php echo $k; ?></td>
            <?php
            foreach($v as $k1=>$v1){
              $total_expenses[]=$v1;
            ?>
            <td class="text-right-pad-10"><?php echo number_format($v1/1000, 0); ?></td>
            <?php
            }
            ?>
          </tr>
          <?php
          }
          ?>

          <tr>
            <td class="sub-header" ><b>Total Operating Expenses</b></td>
            <?php
            foreach($extracted_data['total']['expense'] as $v){
            ?><td class="sub-header text-right-pad-10" ><?php echo number_format($v/1000, 0); ?></td>
            <?php
            }
            ?>
          </tr>
          
          <tr>
            <td class="sub-header" ><b>EBITDA</b></td>
            <?php
            foreach($extracted_data['total']['ebitda'] as $v){
            ?><td class="sub-header text-right-pad-10" ><b><?php echo number_format($v/1000, 0); ?></b></td>
            <?php
            }
            ?>
          </tr>
         
          <tr>
            <td>EBITDA Margin</td>
            <?php
            $currentYear = date('Y'); 
            for ($year = $currentYear; $year < $currentYear + 5; $year++) {
              $ebitda = isset($extracted_data['total']['ebitda'][$year]) ? $extracted_data['total']['ebitda'][$year] : 0;
              $totalRevenue = isset($extracted_data['total']['revenue'][$year]) ? $extracted_data['total']['revenue'][$year] : 1; // Avoid division by zero

              
              $formattedValue = ($totalRevenue > 0) ? number_format(($ebitda / $totalRevenue) * 100, 2) . '%' : 'N/A';
            ?>
            <td class="text-right-pad-10"><?php echo $formattedValue; ?></td>
            <?php
            }
            ?>
          </tr>
          
          <?php 
          $cashflow=array();
          ?>
          <?php if (isset($pnl_annual[0]['Net Income'])) { // Check if 'Net Income' key exists ?>
       
          	<tr>
              <td class="sub-header" ><b>Net Income</b></td>
              <?php foreach ($pnl_annual as $yearData) { 
                      $netincome = isset($yearData['Net Income']) ? $yearData['Net Income'] : 0;
              ?>
              <td class="sub-header text-right-pad-10"><b><?php echo number_format($netincome / 1000, 2); ?></b></td> 
              <?php } ?>
            </tr>
            
          
            <tr class='sub-category net-income-row show'>
                <td>Interest Expense</td>
                <?php foreach ($pnl_annual as $yearData) { 
                    $interestExpense = isset($yearData['Interest Expense']) ? $yearData['Interest Expense'] : 0;
                ?>
                <td class="text-right-pad-10"><?php echo number_format($interestExpense / 1000, 2); ?></td> 
                <?php } ?>
            </tr>
         

         
       
            <tr class='sub-category net-income-row show'>
                <td>Taxes</td>
                <?php foreach ($pnl_annual as $yearData) { 
                $taxExpense = isset($yearData['Tax Expense']) ? $yearData['Tax Expense'] : 0; ?>
                <td class="text-right-pad-10"><?php echo number_format($taxExpense/1000, 2); ?></td> 
                <?php } ?>
            </tr>
         


          
            <tr class='sub-category net-income-row show'>
                <td> Net Profit Margin</td>
                <?php foreach ($pnl_annual as $yearData) { 
                $netIncome = isset($yearData['Net Income']) ? $yearData['Net Income'] : 0;
                $totalRevenue = isset($yearData['Total Revenue']) ? $yearData['Total Revenue'] : 1; 
                $netProfitMargin = number_format(($netIncome / $totalRevenue) * 100, 2) . '%'; 
                          ?>
                <td class="text-right-pad-10"><?php echo $netProfitMargin ; ?></td>
            
                <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div>
          <?php //Projected Profit & Loss Code End ?>

          <?php //Projected Cashflow Code Start ?>
          <div class="section">
            <?php 			
            $cashflow = calculateYearlyCashflow($revenue_validated['cash_flow']);
            ?>
            <h3 class="main-title" id="projected-cashflow">Projected Cashflow</h3>

            
        <?php //Cashflow new Table Code Start ?>
              <table class="financial-table">
                  <tr>
                    <th colspan="6" class="main-header">Pro Forma Cash Flow (Thousands <?php echo $plan_data['currency']; ?>)</th>
                  </tr>
                  <tr class="sub-header">
                    <th> </th>
                    <?php
                    for($i=0; $i<5; $i++){
                    ?>
                    <th class="sub-header text-right-pad-10">Year <?php echo $i+1; ?></th>
                    <?php
                    }
                    ?>
                  </tr>
                  <tr>
                    <td>    Capital Expenditures</td>
                    <?php foreach($cashflow['Capital Expenditures'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Financing</td>
                    <?php foreach($cashflow['Financing'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Cash from Operations</td>
                    <?php foreach($cashflow['Cash Flow from Operations'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Tax Payments</td>
                    <?php foreach($cashflow['Tax Payments'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Change in Working Capital</td>
                    <?php foreach($cashflow['Change in Working Capital'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>Free Cash Flow</td>
                    <?php foreach($cashflow['Free Cash Flow'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Operating Cash Flow</td>
                    <?php foreach($cashflow['Operating Cash Flow'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Cash at Beginning of Period</td>
                    <?php foreach($cashflow['Cash at Beginning of Period'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>    Net Change in Cash</td>
                    <?php foreach($cashflow['Net Change in Cash'] as $value) { ?>
                    <td class="text-right-pad-10"><?php echo number_format($value/1000, 0); ?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td class="sub-header"><b>    Cash at End of Period</b></td>
                    <?php foreach($cashflow['Cash at End of Period'] as $value) { ?>
                    <td class="sub-header text-right-pad-10"><b><?php echo number_format($value/1000, 0); ?></b></td>
                    <?php } ?>
                  </tr>
              </table>
            <?php //Cashflow New Table Code End ?>
        

        <div style="width: 100%; margin: 20px 0px; text-align: center;">
          <?php
        
          require_once('svgGraph/autoloader.php');

          $values = [];

          $settings = array(
            'graph_title' => 'Annual Cashflow ($000)',
            'show_grid' => true,
            'axis_colour' => '#333',
            'axis_text_colour' => '#333',
            'back_colour' => '#f8f9fa',
            'stroke_colour' => '#333',
            'show_legend' => false,
            'legend_position' => 'right',
            'bar_space' => 20,
            'bar_width' => 40,
          );

          foreach ($cashflow as $k => $v) {
            $i = 1; 
            foreach ($v as $k1 => $v1) {
              $yearLabel = "Year " . $i;
              $values[$yearLabel] = $v1; 
              $i++; 
            }
          }


          $graph = new Goat1000\SVGGraph\SVGGraph(670, 400,$settings);

          $graph->colours($colors);

          $graph->values($values);

          echo $graph->render('BarGraph');
          ?>
        </div>

      </div>
      <?php //Projected Cashflow Code End ?>
      <?php //Projected Balance Sheet Code Start ?>
      <div class="section">
        <?php 
        $data = processBalanceSheet($revenue_validated['balance_sheet']);
        ?>
        <h3 class="main-title" id="projected-balancesheet">Projected Balance Sheet</h3>
        <?php
        $headers = array_keys($data['Assets']);
        ?>

        <table class="profit-loss-table table-responsive">
            <thead>
              <tr>
                <th colspan="6" class="main-header">Pro Forma Balance Sheet (Thousands <?php echo $plan_data['currency']; ?>)</th>
              </tr>
              <tr>
                <th class="sub-header" style="background-color: #f8f9fa;color: #333;"> </th>
                <?php
                $k=1;
                foreach ($headers as $year) {
                ?>
                <th  class="sub-header text-right-pad-10" style="background-color: #f8f9fa;color: #333;font-size: 15px;text-align: center;">Year <?php echo $k++; ?></th>
                <?php }	?>
              </tr>
            </thead>
            <tbody>
              <tr class="main-category expandable" data-category="Assets">
                <td colspan='<?php echo (count($headers) + 1); ?>'><strong>Assets</strong></td>
              </tr>
              <tr class="sub-category Assets-row expandable show" data-category="FixedAssets">
                <td colspan='<?php echo (count($headers) + 1); ?>' ><strong>Fixed Assets</strong></td>
              </tr>
              <tr class="sub-row FixedAssets-row show">
                <td >Intangible Assets</td>
                <?php for($i=1; $i <=5; $i++) { ?>
                <td style="text-align: right;"><?php echo number_format(0/1000, 0); ?></td>
                <?php } ?>
              </tr>
              <tr class="sub-row FixedAssets-row show">
                <td >Tangible Assets</td>
                <?php foreach ($data['Fixed Assets'] as $year => $value) { ?>
                <td style="text-align: right;"><?php echo number_format($value / 1000, 0);?></td>
                <?php } ?>
              </tr>

              <tr class="main-category expandable show" data-category="LiabilitiesEquity">
                <td colspan='<?php echo (count($headers) + 1); ?>'><strong>Liabilities and Equity</strong></td>
              </tr>
              <tr class="sub-category LiabilitiesEquity-row expandable show" data-category="Liabilities">
                <td colspan='<?php echo (count($headers) + 1); ?>'><strong>Liabilities</strong></td>
              </tr>
              <tr class="sub-row Liabilities-row show">
                <td >Current Liabilities</td>
                <?php foreach ($data['Liabilities'] as $year => $value) { ?>
                <td style="text-align: right;"><?php echo number_format($value / 1000, 0); ?></td>
                <?php } ?>
              </tr>
              <tr class="sub-row Liabilities-row show">
                <td>Long-Term Liabilities</td>
                <?php 
                
                foreach ($headers as $year) {
                  
                  $value = isset($data['Long Term Debt'][$year]) ? $data['Long Term Debt'][$year] : 0;
                  
                  
                  echo "<td style='text-align: right;'>" . number_format($value / 1000, 0) . "</td>";

                }
                ?>
              </tr>

              <tr class="sub-category LiabilitiesEquity-row expandable show" data-category="Equity">
                <td colspan='<?php echo (count($headers) + 1); ?>' ><strong>Equity</strong></td>
              </tr>
              <tr class="sub-row Equity-row show">
                <td >Share Capital</td>
                <?php for($i=1; $i <=5; $i++) { ?>
                <td style="text-align: right; "><?php echo number_format(0, 0); ?></td>
                <?php } ?>
              </tr>
              <tr class="sub-row Equity-row show">
                <td >Retained Earnings</td>
                <?php foreach ($data['Capital'] as $year => $value) { ?>
                <td style=" text-align: right;"><?php echo number_format($value / 1000, 0); ?></td>
                <?php } ?>
              </tr>
            </tbody>
          </table>
      </div>
      <?php //Projected Balance Sheet Code End ?>
      <?php //Profit & Loss (Year 1) Code Start ?>
      <div class="section" id="profit-loss-projection">
        <h3 class="main-title" id="profit-loss">Profit &amp; Loss (Year 1)</h3>
        <?php
        
        
        $headers = array_keys($data['Assets']);

        $monthly_data = exractForecastDataMonth($revenue_validated['pnl_monthly']);

       
        $monthly_totals = array();

        // Get revenue totals
        foreach($monthly_data['total']['revenue'] as $product => $months) {
          foreach($months as $month => $value) {
            if(!isset($monthly_totals[$month]['revenue'])) {
              $monthly_totals[$month]['revenue'] = 0;
            }
            $monthly_totals[$month]['revenue'] += $value;
          }
        }

        foreach($monthly_data['total']['cogs'] as $product => $months) {
          foreach($months as $month => $value) {
            if(!isset($monthly_totals[$month]['cogs'])) {
              $monthly_totals[$month]['cogs'] = 0;
            }
            $monthly_totals[$month]['cogs'] += $value;
          }
        }

      
        if(isset($monthly_data['total']['operating_expenses'])) {
          foreach($monthly_data['total']['operating_expenses'] as $expense => $months) {
            foreach($months as $month => $value) {
              if(!isset($monthly_totals[$month]['operating_expenses'])) {
                $monthly_totals[$month]['operating_expenses'] = 0;
              }
              $monthly_totals[$month]['operating_expenses'] += $value;
            }
          }
        }
        
        $jsonString = json_encode($monthly_data['total']);
        $year=  array_key_first($monthly_data['total']['revenue']);
        $dashboard = new FinancialDashboard($jsonString, $year);
        echo $dashboard->generateTable();
        echo $dashboard->generateSVG();
        ?>
      </div> 
      <?php //Profit & Loss (year 1) Code End ?>
    </div>

    <?php //Financial Forcast Code End ?>
    <?php enddiv(); ?>
      <?php //Year 1 Pro Forma Cash Flow (Thousands USD) Code Start ?>
      <h3 class="main-title" id="cash-flow-projection">Cash Flow Statement (Year 1)</h3>
      <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $jsonData = json_encode($revenue_validated, JSON_PRETTY_PRINT);
 
      displayCashFlow($jsonData);
    
      ?>
      <?php //Year 1 Pro Forma Cash Flow (Thousands USD) Code End ?>
    </body>
  </html>
  <?php

if (isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == "true") {
   
        $html = ob_get_clean();
        ob_clean();

        $html = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', '', $html);
        $html = preg_replace('/<\?xml[^>]+\?>/', '', $html);
        $html = preg_replace('/<!--.*?-->/', '', $html);
        $html = preg_replace('/\s+/', ' ', $html);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 8,
            'margin_footer' => 2,
            'keep_table_proportions' => true
        ]);

        $mpdf->setHTMLFooter('
            <div style="text-align: center; font-size: 10px; width:100%; border-top:1px solid #000; padding:5px;">
                Page {PAGENO} of {nb}
            </div>
        ');

      
        $css = "
            .section-header {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                width: 100%;
                text-align: center;
                flex-direction: column;
                padding-top: 450px;
                padding-bottom: 200px;
            }
            
            .content {
                padding: 0 20px;
                text-align: justify;
            }
        ";

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

       
        $company_page = "
            <div class='section-header'>
                <div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>
                    <h1 >{$company_info[0]['company_name']}</h1>
                    <h2>Business Plan</h2>
                    <div >" . date('F Y') . "</div>
                </div>
            </div>
            <pagebreak />";

        $mpdf->WriteHTML($company_page);

       
        $mpdf->h2toc = array(
            'H1' => 0,
            'H2' => 1,
            'H3' => 2
        );

        $mpdf->TOCpagebreakByArray([
            'tocfont' => 'Arial',
            'tocfontsize' => '12',
            'tocindent' => '5',
            'TOCusePaging' => true,
            'toc_mgl' => 15,
            'toc_mgr' => 15,
            'toc_mgt' => 15,
            'toc_mgb' => 15,
            'toc_preHTML' => '<h2 style="text-align: center; font-size: 24px; margin-bottom: 30px;">Table of Contents</h2>',
            'toc_postHTML' => '',
            'toc_bookmarkText' => 'Table of Contents',
            'links' => true,
            'outdent' => '2em',
            'tocmarks' => true,
            'pagebreaktype' => 'avoid'
        ]);

      
        $html = preg_replace('/<div class="section_table_of_contents">.*?<\/div>\s*<pagebreak \/>/s', '', $html);
        $html = preg_replace('/<pagebreak\s*\/>\s*<pagebreak\s*\/>/', '<pagebreak />', $html);
        $html = preg_replace('/<div>\s*<\/div>/', '', $html);

        $mpdf->WriteHTML($html);
        $mpdf->Output($file_name . '.pdf', 'D');
    
    exit;
  
  
  
  
}


function calculateYearlyCashflow($monthlyData) {
  $yearlyCashflow = [];

  foreach ($monthlyData as $monthData) {
    $date = new DateTime($monthData['date']);
    $year = $date->format('Y');

    if (!isset($yearlyCashflow['Capital Expenditures'][$year])) {
      $yearlyCashflow['Capital Expenditures'][$year]= 0;
    }
    if (!isset($yearlyCashflow['Financing'][$year])) {
      $yearlyCashflow['Financing'][$year]= 0;
    }
    if(!isset($yearlyCashflow['Cash Flow from Operations'][$year])){
      $yearlyCashflow['Cash Flow from Operations'][$year]= 0;
    }
    if(!isset($yearlyCashflow['Change in Working Capital'][$year])){
      $yearlyCashflow['Change in Working Capital'][$year]= 0;
    }
    if(!isset($yearlyCashflow['Free Cash Flow'][$year])){
      $yearlyCashflow['Free Cash Flow'][$year]= 0;
    }
    if(!isset($yearlyCashflow['Operating Cash Flow'][$year])){
      $yearlyCashflow['Operating Cash Flow'][$year]= 0;
    }
    if(!isset($yearlyCashflow['Tax Payments'][$year])){
      $yearlyCashflow['Tax Payments'][$year]= 0;
    }
    if (!isset($yearlyCashflow['Cash at Beginning of Period'][$year])) {
        $yearlyCashflow['Cash at Beginning of Period'][$year] = 0;
    }
    if (!isset($yearlyCashflow['Net Change in Cash'][$year])) {
        $yearlyCashflow['Net Change in Cash'][$year] = 0;
    }
    if (!isset($yearlyCashflow['Cash at End of Period'][$year])) {
        $yearlyCashflow['Cash at End of Period'][$year] = 0;
    }
   
    $yearlyCashflow['Capital Expenditures'][$year] += $monthData['Capital Expenditures'];
    $yearlyCashflow['Financing'][$year] += $monthData['Financing'];
    $yearlyCashflow['Cash Flow from Operations'][$year] += $monthData['Cash Flow from Operations'];
    $yearlyCashflow['Change in Working Capital'][$year] += $monthData['Change in Working Capital'];
    $yearlyCashflow['Free Cash Flow'][$year] += $monthData['Free Cash Flow'];
    $yearlyCashflow['Tax Payments'][$year] += $monthData['Tax Payments'];
    $yearlyCashflow['Operating Cash Flow'][$year] += $monthData['Operating Cash Flow'];    

    $yearlyCashflow['Net Change in Cash'][$year] += $monthData['Free Cash Flow']; 
   
    if ($year > min(array_keys($yearlyCashflow['Cash at Beginning of Period']))) {
        $previousYear = $year - 1;
        $yearlyCashflow['Cash at Beginning of Period'][$year] = $yearlyCashflow['Cash at End of Period'][$previousYear];
    } else {
        $yearlyCashflow['Cash at Beginning of Period'][$year] = 0;
    }

    $yearlyCashflow['Cash at End of Period'][$year] = $yearlyCashflow['Cash at Beginning of Period'][$year] + $yearlyCashflow['Net Change in Cash'][$year];    
  }
  

  
  foreach ($yearlyCashflow as &$yearData) {
    foreach ($yearData as &$value) {
      $value = round($value, 2);
    }
  }

  return $yearlyCashflow;
}

function calculateMonthlyCashflow($monthlyData) {
  $yearlyCashflow = [];



  foreach ($monthlyData as $monthData) {
   
    $date = new DateTime($monthData['date']);
    $year = $date->format('Y');
    $month = $date->format('m');
   
    if (!isset($yearlyCashflow['Capital Expenditures'][$year][$month])) {
      $yearlyCashflow['Capital Expenditures'][$year][$month]= 0;
    }
    if(!isset($yearlyCashflow['Cash Flow from Operations'][$year][$month])){
      $yearlyCashflow['Cash Flow from Operations'][$year][$month]= 0;
    }
    if(!isset($yearlyCashflow['Change in Working Capital'][$year][$month])){
      $yearlyCashflow['Change in Working Capital'][$year][$month]= 0;
    }
    if(!isset($yearlyCashflow['Free Cash Flow'][$year][$month])){
      $yearlyCashflow['Free Cash Flow'][$year][$month]= 0;
    }
    if(!isset($yearlyCashflow['Operating Cash Flow'][$year][$month])){
      $yearlyCashflow['Operating Cash Flow'][$year][$month]= 0;
    }


    $yearlyCashflow['Capital Expenditures'][$year][$month] += $monthData['Capital Expenditures'];
    $yearlyCashflow['Cash Flow from Operations'][$year][$month] += $monthData['Cash Flow from Operations'];
    $yearlyCashflow['Change in Working Capital'][$year][$month] += $monthData['Change in Working Capital'];
    $yearlyCashflow['Free Cash Flow'][$year][$month] += $monthData['Free Cash Flow'];
    $yearlyCashflow['Operating Cash Flow'][$year][$month] += $monthData['Operating Cash Flow'];
  }


  foreach ($yearlyCashflow as &$yearData) {
    foreach ($yearData as &$monthData) {
      foreach ($monthData as &$value) {
        $value = round($value, 2);
      }
    }
  }
  return $yearlyCashflow;
}
function generateStartupTables($capital_requirement, $revenue_validated, $operating_expenses) {

  $startup_assets = [
    'Cash (Initial Capital)' => [
      'amount' => $capital_requirement['financials'][1]['funding_value'],
      'notes' => 'From initial funding'
    ],
    'Fixed Assets' => [
      'amount' => $capital_requirement['financials'][3]['funding_value'],
      'notes' => 'Capital expenditure allocation'
    ],
    'Working Capital' => [
      'amount' => $capital_requirement['financials'][2]['funding_value'],
      'notes' => 'For operational expenses'
    ]
  ];

  $total_assets = array_sum(array_column($startup_assets, 'amount'));
  $startup_assets['Total Assets'] = [
    'amount' => $total_assets,
    'notes' => ''
  ];

  $startup_expenses = [];
  foreach ($operating_expenses['costs'] as $cost) {
    $startup_expenses[$cost['cost_name']] = [
      'monthly' => round($cost['cost_value'] / 12, 2),
      'annual' => $cost['cost_value']
    ];
  }

  $total_monthly = array_sum(array_column($startup_expenses, 'monthly'));
  $total_annual = array_sum(array_column($startup_expenses, 'annual'));
  $startup_expenses['Total Startup Expenses'] = [
    'monthly' => $total_monthly,
    'annual' => $total_annual
  ];

  $total_requirements = [
    'Total Assets' => $total_assets,
    'First Year Operating Expenses' => $total_annual,
    'Total Capital Requirements' => $total_assets + $total_annual
  ];

  $startup_liabilities = [
    'Accounts Payable' => [
      'amount' => $revenue_validated['balance_sheet'][0]['Accounts Payable'],
      'notes' => 'From balance sheet data'
    ],
    'Initial Debt' => [
      'amount' => 0,
      'notes' => 'No initial debt recorded'
    ]
  ];

  $total_liabilities = array_sum(array_column($startup_liabilities, 'amount'));
  $startup_liabilities['Total Liabilities'] = [
    'amount' => $total_liabilities,
    'notes' => ''
  ];

  $startup_investments = [
    'Technology Infrastructure' => [
      'amount' => $capital_requirement['financials'][3]['funding_value'] * 0.6, // 60% of Capital Expenditure
      'notes' => 'Software, hardware, and IT infrastructure'
    ],
    'Office Equipment' => [
      'amount' => $capital_requirement['financials'][3]['funding_value'] * 0.4, // 40% of Capital Expenditure
      'notes' => 'Furniture, fixtures, and office equipment'
    ],
    'Initial Inventory' => [
      'amount' => $revenue_validated['balance_sheet'][0]['Inventory'],
      'notes' => 'Starting inventory and supplies'
    ]
  ];

  $total_investments = array_sum(array_column($startup_investments, 'amount'));
  $startup_investments['Total Investments'] = [
    'amount' => $total_investments,
    'notes' => ''
  ];

  $startup_funding = [
    'Initial Capital' => [
      'amount' => $capital_requirement['financials'][1]['funding_value'],
      'source' => 'Seed funding',
      'notes' => $capital_requirement['financials'][1]['funding_assumptions']
    ],
    'Working Capital' => [
      'amount' => $capital_requirement['financials'][2]['funding_value'],
      'source' => 'Operating capital',
      'notes' => $capital_requirement['financials'][2]['funding_assumptions']
    ],
    'Owner Investment' => [
      'amount' => $total_requirements['Total Capital Requirements'] - 
      ($capital_requirement['financials'][1]['funding_value'] + 
       $capital_requirement['financials'][2]['funding_value']),
      'source' => 'Owner equity',
      'notes' => 'Additional capital required from owners/investors'
    ]
  ];

  $total_funding = array_sum(array_column($startup_funding, 'amount'));
  $startup_funding['Total Funding'] = [
    'amount' => $total_funding,
    'source' => 'Total',
    'notes' => ''
  ];

  // Return all tables in an array
  return [
    'startup_assets' => $startup_assets,
    'startup_expenses' => $startup_expenses,
    'total_requirements' => $total_requirements,
    'startup_liabilities' => $startup_liabilities,
    'startup_investments' => $startup_investments,
    'startup_funding' => $startup_funding
  ];
}

function displayFinancialTables($tables) {
 
  echo "<h3>5. Startup Investments</h3>";
  echo "<table border='1'>
            <tr><th>Investment Type</th><th>Amount ($)</th><th>Notes</th></tr>";
  foreach ($tables['startup_investments'] as $type => $data) {
    echo "<tr>
                <td>{$type}</td>
                <td>" . number_format($data['amount'], 2) . "</td>
                <td>{$data['notes']}</td>
              </tr>";
  }
  echo "</table>";

  echo "<h3>6. Startup Funding</h3>";
  echo "<table border='1'>
            <tr><th>Funding Type</th><th>Amount ($)</th><th>Source</th><th>Notes</th></tr>";
  foreach ($tables['startup_funding'] as $type => $data) {
    echo "<tr>
                <td>{$type}</td>
                <td>" . number_format($data['amount'], 2) . "</td>
                <td>{$data['source']}</td>
                <td>{$data['notes']}</td>
              </tr>";
  }
  echo "</table>";

  echo "<h3>Funding Summary</h3>";
  echo "<table border='1'>
            <tr><th>Category</th><th>Amount ($)</th></tr>
            <tr><td>Total Requirements</td><td>" . 
    number_format($tables['total_requirements']['Total Capital Requirements'], 2) . 
    "</td></tr>
            <tr><td>Total Funding</td><td>" . 
    number_format($tables['startup_funding']['Total Funding']['amount'], 2) . 
    "</td></tr>
            <tr><td>Funding Gap</td><td>" . 
    number_format(
    $tables['startup_funding']['Total Funding']['amount'] - 
    $tables['total_requirements']['Total Capital Requirements'], 
    2
  ) . 
    "</td></tr>
          </table>";
}

function generateFundingReport($tables) {
  $report = [
    'total_requirements' => $tables['total_requirements']['Total Capital Requirements'],
    'total_funding' => $tables['startup_funding']['Total Funding']['amount'],
    'funding_gap' => $tables['startup_funding']['Total Funding']['amount'] - 
    $tables['total_requirements']['Total Capital Requirements'],
    'funding_sources' => array_column($tables['startup_funding'], 'amount', 'source'),
    'investment_allocation' => array_column($tables['startup_investments'], 'amount', 'notes')
  ];

  return $report;
}


function exractForecastData($pnl_annual) {


  $output= array();
  $cogs=$expenses=$revenue=array();
  $totals = array();
  foreach($pnl_annual as $k => $v){

    $year = date('Y', strtotime($v['date']));

    $totals['revenue'][$year] = $v['Total Revenue'];
    $totals['expense'][$year] = $v['Total Expenses'];
    $totals['cogs'][$year] = $v['Total COGS'];
    $totals['ebitda'][$year] = $v['EBITDA'];
    $totals['gross_profit'][$year] = $v["Gross Profit"];

    foreach($v as $key => $value){

      if (strpos($key, '%') !== false) {
        continue;
      }

      if (strpos($key, 'COGS -') === 0) {

        $key1 = trim(str_replace('COGS -', '', $key));
        $key1."<BR>";
        $cogs[$key1][$year] = $value;
      } elseif (strpos($key, 'Expense -') === 0) {
        $key1 = trim(str_replace('Expense -', '', $key));
       
        $expenses[$key1][$year] = $value;
      } elseif (strpos($key, 'Revenue -') === 0) {

        $key1 = trim(str_replace('Revenue -', '', $key));
    
        $revenue[$key1][$year] = $value;
      }                
    }
  }

  $output = array('cogs'=>$cogs, 'expenses'=>$expenses, 'revenue'=>$revenue,'total'=>$totals);


  return $output;

}

function exractForecastDataMonth($pnl_month) {


  $output= array();
  $cogs=$expenses=$revenue=array();
  $totals = array();
  foreach($pnl_month as $k => $v){

    $year = date('Y', strtotime($v['date']));
    $month = date('m', strtotime($v['date']));
    $totals['revenue'][$year][$month] = $v['Total Revenue'];
    $totals['expense'][$year][$month] = $v['Total Expenses'];
    $totals['cogs'][$year][$month] = $v['Total COGS'];
    $totals['gross_margin'][$year][$month] = $v['Gross Profit'];
    $totals['gross_margin_per'][$year][$month] = $v['Gross Profit %'];

    foreach($v as $key => $value){

      if (strpos($key, '%') !== false) {
        continue;
      }

      if (strpos($key, 'COGS -') === 0) {

        $key1 = trim(str_replace('COGS -', '', $key));
        $key1."<BR>";
        $cogs[$key1][$year][$month] = $value;
      } elseif (strpos($key, 'Expense -') === 0) {
        $key1 = trim(str_replace('Expense -', '', $key));
      
        $expenses[$key1][$year][$month] = $value;
      } elseif (strpos($key, 'Revenue -') === 0) {

        $key1 = trim(str_replace('Revenue -', '', $key));
        
        $revenue[$key1][$year][$month] = $value;
      }
    }
  }
  $output = array('cogs'=>$cogs, 'expenses'=>$expenses, 'revenue'=>$revenue,'total'=>$totals);

  return $output;

}


function processBalanceSheet($data) {
  $categories = [
    'Assets' => [],
    'Fixed Assets' => [],
    'Liabilities' => [],
    'Equity' => [],
    'Capital' => [],
    'Long Term Debt' =>[]
  ];

  foreach ($data as $entry) {
    $year = date('Y', strtotime($entry['date']));
    $categories['Assets'][$year] = $entry['Total Assets'] ?? 0;
    $categories['Fixed Assets'][$year] = $entry['Fixed Assets'] ?? 0;
    $categories['Liabilities'][$year] = $entry['Total Liabilities'] ?? 0;
    $categories['Equity'][$year] = $entry['Total Equity'] ?? 0;
    $categories['Capital'][$year] = $entry['Retained Earnings'] ?? 0; 
   	$categories['Long Term Debt'][$year] = $entry['Long Term Debt'] ?? 0;

  }

  return $categories;
}



function processBalanceSheetMonthly($data) {
  $categories = [];

  foreach ($data as $entry) {
    $year = date('Y', strtotime($entry['date']));
    $month = date('m', strtotime($entry['date']));

    foreach($entry as $key => $value){
      if($key == 'date'){
        continue;
      }
      $categories[$key][$year][$month] = $value;
    }

  }

  return $categories;
}


function txt_to_fol($text) {

  $text = strtolower($text);

  $folderName = preg_replace('/\s+/', '_', $text);

  $folderName = preg_replace('/[^\w\-]/', '', $folderName);

  $folderName = trim($folderName, '_-');

  return $folderName;
}


class FinancialDashboard {
  private $data;
  private $year;

  public function __construct($jsonString, $year) {
    $this->data = json_decode($jsonString, true);
    $this->year = $year;
  }

  public function generateSVG() {
    $yearIndex = 1; 
    $yearLabel = "Year " . ($yearIndex); // Start from Year 1 instead of Year 2
    $yearData = [
      'revenue' => $this->data['revenue'][$this->year],
      'expense' => $this->data['expense'][$this->year],
      'cogs' => $this->data['cogs'][$this->year],
      'gross_margin' => $this->data['gross_margin'][$this->year]
    ];

    $maxValue = 0;
    foreach ($yearData as $type => $months) {
      foreach ($months as $value) {
        $maxValue = max($maxValue, $value);
      }
    }

    $maxValue = ceil($maxValue / 10000) * 10000;

    $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 500">
            <!-- Background -->
            <rect width="800" height="500" fill="#f8fafc" />

            <!-- Title -->
            <text x="400" y="40" text-anchor="middle" font-family="Arial" font-size="20" font-weight="bold">

                {$yearLabel} Monthly Financial Performance
            </text>

            <!-- Legend -->
            <rect x="250" y="450" width="15" height="15" fill="#4299e1"/>
            <text x="270" y="463" font-family="Arial" font-size="12">Revenue</text>
            <rect x="365" y="450" width="15" height="15" fill="#48bb78"/>
            <text x="385" y="463" font-family="Arial" font-size="12">Gross Margin</text>
            <rect x="490" y="450" width="15" height="15" fill="#f56565"/>
            <text x="510" y="463" font-family="Arial" font-size="12">Expenses</text>
SVG;

    $svg .= $this->generateGridAndLabels($maxValue);

    $svg .= $this->generateDataLines($yearData, $maxValue);

    $svg .= "</svg>";


    return $svg;
  }

  private function generateGridAndLabels($maxValue) {
    $svg = '';
   
    for ($i = 0; $i <= 4; $i++) {
      $y = 400 - ($i * 75);
      $value = ($maxValue / 4) * $i;
      $svg .= "<line x1='100' y1='$y' x2='700' y2='$y' stroke='#e2e8f0' stroke-width='1' />";
      $svg .= "<text x='90' y='" . ($y + 5) . "' text-anchor='end' font-family='Arial' font-size='12'>" 
        . number_format($value / 1000, 0) . "k</text>";
    }

    $months = [
      "Month 1", "Month 2", "Month 3", "Month 4", "Month 5", 
      "Month 6", "Month 7", "Month 8", "Month 9", "Month 10", 
      "Month 11", "Month 12"
    ];
    foreach ($months as $i => $month) {
      $x = 130 + ($i * 50);
      $svg .= "<text x='$x' y='420' text-anchor='middle' font-family='Arial' font-size='10'>$month</text>";
    }

    return $svg;
  }

  private function generateDataLines($yearData, $maxValue) {
    $colors = [
      'revenue' => '#4299e1',
      'gross_margin' => '#48bb78',
      'expense' => '#f56565'
    ];

    $svg = '';
    foreach ($colors as $type => $color) {
      $points = '';
      $monthData = $yearData[$type];
      foreach ($monthData as $i => $value) {
        $x = 130 + (($i - 1) * 50);
        $y = 400 - ($value / $maxValue * 300);
        $points .= "$x,$y ";
      }
      $svg .= "<polyline fill='none' stroke='$color' stroke-width='2' points='$points' />";
    }

    return $svg;
  }

  public function generateTable() {
    $yearData = [
      'revenue' => $this->data['revenue'][$this->year],
      'cogs' => $this->data['cogs'][$this->year],
      'gross_margin' => $this->data['gross_margin'][$this->year],
      'gross_margin_per' => $this->data['gross_margin_per'][$this->year],
      'expense' => $this->data['expense'][$this->year]
    ];


    $months = [
      "Month 1", "Month 2", "Month 3", "Month 4", "Month 5", 
      "Month 6", "Month 7", "Month 8", "Month 9", "Month 10", 
      "Month 11", "Month 12"
    ];
    $table = "<table class='financial-table'>\n";
    $table .= "<tr><th class='main-header'>Month</th><th class='main-header'>Revenue</th><th class='main-header'>COGS</th><th class='main-header'>Gross Margin</th><th class='main-header'>Gross Margin %</th><th class='main-header'>Expenses</th><th class='main-header' >Net Income</th></tr>\n";

    $totals = [
      'revenue' => 0,
      'cogs' => 0,
      'gross_margin' => 0,
      'expense' => 0,
      'net_income' => 0
    ];

    foreach ($months as $i => $month) {
      $index = sprintf("%02d", $i + 1);
      $revenue = $yearData['revenue'][$index];
      $cogs = $yearData['cogs'][$index];
      $grossMargin = $yearData['gross_margin'][$index];
      $grossMarginPer = $yearData['gross_margin_per'][$index] * 100;
      $expense = $yearData['expense'][$index];
      $netIncome = $grossMargin - $expense;

      $totals['revenue'] += $revenue;
      $totals['cogs'] += $cogs;
      $totals['gross_margin'] += $grossMargin;
      $totals['expense'] += $expense;
      $totals['net_income'] += $netIncome;

      $table .= sprintf(
        "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%.2f%%</td><td>%s</td><td>%s</td></tr>\n",
        $month,
        number_format($revenue/1000, 2),
        number_format($cogs/1000, 2),
        number_format($grossMargin/1000, 2),
        $grossMarginPer,
        number_format($expense/1000, 2),
        number_format($netIncome/1000, 2)
      );
    }

    
    $avgGrossMarginPer = ($totals['gross_margin'] / $totals['revenue']) * 100;
    $table .= sprintf(
      "<tr style='font-weight: bold;' class='total-row'><td>Total</td><td>$%s</td><td>$%s</td><td>$%s</td><td>%.2f%%</td><td>$%s</td><td>$%s</td></tr>\n",
      number_format($totals['revenue']/1000, 2),
      number_format($totals['cogs']/1000, 2),
      number_format($totals['gross_margin']/1000, 2),
      $avgGrossMarginPer,
      number_format($totals['expense']/1000, 2),
      number_format($totals['net_income']/1000, 2)
    );

    $table .= "</table>";

    return $table;
  }
}




function extract5YearsData($jsonString) {
  $data = json_decode($jsonString, true);

  $years = [];
  foreach ($data['pnl_annual'] as $entry) {
    $date = new DateTime($entry['date']);
    $year = $date->format('Y');
    if (!in_array($year, $years)) {
      $years[] = $year;
    }
  }

  rsort($years);

  // Take only the recent 5 years
  $recentYears = array_slice($years, 0, 5);

  $filteredData = [
    'balance_sheet' => [],
    'cash_flow' => [],
    'pnl_annual' => [],
    'pnl_monthly' => []
  ];

  // Filter balance sheet data
  foreach ($data['balance_sheet'] as $entry) {
    $date = new DateTime($entry['date']);
    $year = $date->format('Y');
    if (in_array($year, $recentYears)) {
      $filteredData['balance_sheet'][] = $entry;
    }
  }

  // Filter cash flow data
  foreach ($data['cash_flow'] as $entry) {
    $date = new DateTime($entry['date']);
    $year = $date->format('Y');
    if (in_array($year, $recentYears)) {
      $filteredData['cash_flow'][] = $entry;
    }
  }

  // Filter pnl annual data
  foreach ($data['pnl_annual'] as $entry) {
    $date = new DateTime($entry['date']);
    $year = $date->format('Y');
    if (in_array($year, $recentYears)) {
      $filteredData['pnl_annual'][] = $entry;
    }
  }

  // Filter pnl monthly data
  foreach ($data['pnl_monthly'] as $entry) {
    $date = new DateTime($entry['date']);
    $year = $date->format('Y');
    if (in_array($year, $recentYears)) {
      $filteredData['pnl_monthly'][] = $entry;
    }
  }

  // Return JSON encoded filtered data
  return json_encode($filteredData, JSON_PRETTY_PRINT);
}



function displayCashFlow($jsonData) {
    
    $data = json_decode($jsonData, true);

    // Extract cash flow data for first year
    $initialData = array_slice($data['cash_flow'], 0, 12);

   
    $runningBalance = 0;
    $cashFlowData = array_map(function($entry) use (&$runningBalance) {
        $runningBalance += $entry['Operating Cash Flow'] + $entry['Capital Expenditures'];
        $entry['Ending Cash Balance'] = $runningBalance;
        return $entry;
    }, $initialData);

    // SVG dimensions
    $width = 1000;
    $height = 550;
    $padding = 110;
    $graphWidth = $width - (2 * $padding);
    $graphHeight = $height - (2 * $padding);

    $maxValue = max(
        max(array_column($cashFlowData, 'Operating Cash Flow')),
        max(array_column($cashFlowData, 'Ending Cash Balance')),
        abs(min(array_column($cashFlowData, 'Capital Expenditures')))
    );

    $minValue = min(
        min(array_column($cashFlowData, 'Operating Cash Flow')),
        min(array_column($cashFlowData, 'Ending Cash Balance')),
        min(array_column($cashFlowData, 'Capital Expenditures'))
    );
  
    $yScale = ($maxValue - $minValue > 0) ? ($graphHeight / ($maxValue - $minValue)) : 0;
    $xSpacing = $graphWidth / (count($cashFlowData) - 1);

    echo '<div class="max-w-6xl mx-auto bg-white shadow rounded-lg">';
    echo '<div class="px-6 py-4 border-b">';
    echo '</div>';
    echo '<div class="p-6">';

    // Generate table
    echo '<div class="overflow-x-auto mb-8">';
    echo '<table class="financial-table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="main-header">Date</th>';
    echo '<th class="main-header text-right">Operating <BR> Cash Flow</th>';
    echo '<th class="main-header text-right">Investment <BR> Cash Flow</th>';
    echo '<th class="main-header text-right">Financing <BR> Cash Flow</th>';
    echo '<th class="main-header text-right">Ending <BR> Cash Balance</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $monthCounter = 1;
    foreach ($cashFlowData as $entry) {
        $dateLabel = "Month " . $monthCounter++; 

        echo '<tr>';
        echo "<td class=''>{$dateLabel}</td>";
        echo "<td class='text-right'>" . number_format($entry['Operating Cash Flow'], 2) . "</td>";
        echo "<td class='text-right'>" . number_format($entry['Capital Expenditures'], 2) . "</td>";
        echo "<td class='text-right'>0.00</td>";
        echo "<td class='text-right'>" . number_format($entry['Ending Cash Balance'], 2) . "</td>";
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    // Generate SVG Graph
    echo '<div class="overflow-x-auto" style="width:100%">';
    echo "<svg width='100%' height='100%' class='bg-white' style='font-family:Arial;' viewBox='0 0 {$width} {$height}' preserveAspectRatio='xMidYMid meet'>";

    // Draw axes
    echo "<line x1='{$padding}' y1='" . ($height - $padding) . "' x2='" . ($width - $padding) . 
        "' y2='" . ($height - $padding) . "' stroke='black' />";
    echo "<line x1='{$padding}' y1='{$padding}' x2='{$padding}' y2='" . 
        ($height - $padding) . "' stroke='black' />";

    // Y-axis labels
    for ($i = 0; $i <= 8; $i++) {
        $yPos = $height - $padding - ($i * ($graphHeight / 8));
        $value = number_format(($minValue + ($i * ($maxValue - $minValue) / 8)), 0);
        echo "<text x='" . ($padding - 10) . "' y='{$yPos}' text-anchor='end' alignment-baseline='middle' style='font-size:12px'>" . 
            $value . "</text>";
    }

    // X-axis labels
    foreach ($cashFlowData as $i => $entry) {
        $xPos = $padding + ($i * $xSpacing);
        $monthLabel = "Month " . ($i + 1); 
        echo "<text x='{$xPos}' y='" . ($height - $padding + 20) . 
            "' text-anchor='middle' style='font-size:12px'>{$monthLabel}</text>";
    }

    // Generate points for each series
    $generatePoints = function($data, $key) use ($padding, $xSpacing, $height, $yScale, $minValue) {
        $points = [];
        foreach ($data as $i => $entry) {
            $x = $padding + ($i * $xSpacing);
            // Adjust Y calculation to handle negative values
            $y = $height - $padding - (($entry[$key] - $minValue) * $yScale);
            $points[] = "{$x},{$y}";
        }
        return implode(' ', $points);
    };

    $series = [
        ['key' => 'Operating Cash Flow', 'color' => '#4CAF50'],
        ['key' => 'Capital Expenditures', 'color' => '#F44336'],
        ['key' => 'Ending Cash Balance', 'color' => '#2196F3']
    ];

    foreach ($series as $s) {
        $points = $generatePoints($cashFlowData, $s['key']);
        echo "<polyline points='{$points}' fill='none' stroke='{$s['color']}' stroke-width='2' />";
    }

    
    foreach ($cashFlowData as $i => $entry) {
        $x = $padding + ($i * $xSpacing);
        foreach ($series as $s) {
            $y = $height - $padding - (($entry[$s['key']] - $minValue) * $yScale);
            echo "<circle cx='{$x}' cy='{$y}' r='4' fill='{$s['color']}' />";
        }
    }

    // Legend at bottom center
    $legendY = $height - 10; // Position from bottom
    $legendWidth = 500; // Total width of legend
    $legendStartX = ($width - $legendWidth) / 2; // Center the legend

    $legendItems = [
        ['label' => 'Operating Cash Flow', 'color' => '#4CAF50', 'x' => $legendStartX],
        ['label' => 'Investment Cash Flow', 'color' => '#F44336', 'x' => $legendStartX + 180],
        ['label' => 'Ending Cash Balance', 'color' => '#2196F3', 'x' => $legendStartX + 360]
    ];

    foreach ($legendItems as $item) {
        echo "<circle cx='{$item['x']}' cy='{$legendY}' r='4' fill='{$item['color']}'/>";
        echo "<text x='" . ($item['x'] + 10) . "' y='{$legendY}' alignment-baseline='middle' style='font-size:12px'>{$item['label']}</text>";
    }

    echo '</svg>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function clear_output($text){
  
  $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $text = preg_replace('/&[a-zA-Z0-9#]+;/', '', $text);

  $text = preg_replace('/\s+/', ' ', $text);
  $text = trim($text);

  $text = preg_replace('/[\x00-\x1F\x7F]/', '', $text);

  $text = str_replace(["\r\n", "\r"], "\n", $text);

  return $text;
}

// Define custom footer
class CustomFooter
{
  protected $mpdf;
  protected $firstPage = true;

  public function __construct($mpdf)
  {
    $this->mpdf = $mpdf;
  }

  public function footer()
  {
    if ($this->firstPage) {
      $this->mpdf->WriteHTML(date('Y-m-d'));
      $this->firstPage = false;
    } else {
      $this->mpdf->WriteHTML('Page ' . $this->mpdf->PageNo());
    }
  }
}


function startdiv(){

  if(isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == true){
  }
  else {
    echo "<div class='business-planview'>";
  }
}
function enddiv(){
  if(isset($_REQUEST['pdf']) && $_REQUEST['pdf'] == true){
  }
  else {
    echo "</div>";
  }
}
?>