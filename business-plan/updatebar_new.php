<?php

include_once "../includes/config.php";
extract($_REQUEST);

$per_items = array(
	"plan_master" => 5,
	"cover_page_information_master" => 10,
	"executive_overview_summary" => 10,
	"executive_success_factores" => 10,
	"target_customers" => 5,
	"customers_need" => 5,
	"compitition_directs" => 5,
	"compitition_indirect" => 5,
	"marketing_products_pricing" => 5,
	"marketing_distribution_plan" => 5,
	"operational_processes" => 5,
	"operational_milestones" => 5,
	"team_members" => 3,
	"team_management_gaps" => 3,
	"team_board_members" => 2,
	"financial_fund_requirement" => 5,
	"financial_revenue_assumptions" => 5,
	"financial_cost_assumption" => 5,
	"financial_other_assumption" => 2,
);

$per_items = array(
	"plan_master" => 60,
	"cover_page_information_master" => 8,
	"marketing_products_pricing" => 8,
	"executive_success_factores" => 8,
	"compitition_directs" => 8,
	"team_members" => 8,
);

$total = 0;

foreach ($per_items as $tbl => $val) {

	$db->where("md5(plan_id)", $plan);
	$plan_data = $db->getOne($tbl);

	if ($db->count) {
		$total += $val;
	}
}

$per = $total;
$per1 = abs(0 + $total);
$per2 = abs(100 - $total);
?>

<div class="progress blue" data-per="<?php echo $per; ?>%">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="-1 -1 34 34">
		<circle cx="16" cy="16" r="15.9155" stroke-dasharray="100" class="progress-bar__background" />
		<circle cx="16" cy="16" r="15.9155" stroke-dasharray="<?php echo $per1 . " " . $per2; ?>" class="progress-bar__progress js-progress-bar" />
	</svg>
</div>

<p class="d-none"><?php echo $per; ?>% Completed!</p>