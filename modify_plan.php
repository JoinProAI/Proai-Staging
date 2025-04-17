<?php

include_once "includes/config.php";
include_once "includes/function.php";

extract($_REQUEST);

if (!isLogin()) {
	header("Location: ./logout.php");
	exit;
}
if (!isset($plan) || $plan == "") {
	header("Location: ./");
	exit;
}

$db->where("md5(plan_id)", $plan);
$res = $db->getOne("plan_master");
$user_id = $res['user_id'];
$m_plan_id = $res['plan_id'];

include_once __DIR__ . "/header_new.php";
$MODE = "plan_create";
?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/common.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/modify_custme_style.css?<?php echo strtotime('now'); ?>">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/modify-plan-style.css">
 
<section class="dream_business_wrapper border-bottom">
	<div class="row align-items-center justify-content-between">
		<div class="col-lg-8">
			<div class="dream_business_skils">
				<div class="d-flex align-items-center gap-2">
					<div class="progress_wrap" id="updatebar">
						<?php include_once "business-plan/updatebar_new.php"; ?>
					</div>
					<div class="dream_business_text">
						<h2 class="text-black font-bold text-2xl mb-0">Let's Build Your Dream Business! 🚀</h2>
						<p class="text-black font-normal text-sm mb-0 md-line-height-6">Fill out the questionare to generate your business plan</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 ">
			<div class="dream_business_img">
				<img src="<?php echo SITE_URL; ?>/assets/image/bulid-business-vector.png" alt="...">
			</div>
		</div>
	</div>
</section>

<div class="main_d_flex">
	<div class="first">

		<section class="detail_section modify_new_wrapper pt-6">

			<div class="row justify-content-between mr-8">
				<div class="left_col">
					<div class="left_first_box">

						<?php
						$menu_items = array(
							array(
								'name' => 'Overview',
								'display' => true,
								'url' => 'overview',
								'submenu' => array()
							),
							array(
								'name' => 'Cover',
								'display' => false,
								'url' => 'cover',
								'submenu' => array()
							),
							array(
								'name' => 'Cover Page Information',
								'display' => true,
								'url' => 'cover-page-information',
								'submenu' => array()
							),
							array(
								'name' => 'Company',
								'display' => true,
								'url' => 'executive-summary',
								'submenu' => array()
							),
							array(
								'name' => 'Competition',
								'display' => true,
								'url' => 'direct-competitors',
								'submenu' => array()
							),
							array(
								'name' => 'Team',
								'display' => true,
								'url' => 'management-team-members',
								'submenu' => array()
							),
							array(
								'name' => 'Expense Assumptions',
								'display' => true,
								'url' => 'financial-assumptions',
								'submenu' => array()
							),
							array(
								'name' => 'Revenue Assumptions',
								'display' => true,
								'url' => 'revenue-assumptions',
								'submenu' => array()
							),
						);


						$file_items = array(
							"overview",
							"cover-page-information",
							"company-overview",
							"executive-summary",
							"industry-overview",
							"target-customers",
							"direct-competitors",
							"products-services-pricing",
							"key-operational-processes",
							"management-team-members",
							"financial-assumptions",
							"revenue-model",
							"funding-requirements-funds",
							"financialplan-exitstrategy",
							"revenue-assumptions",
							"cost-assumptions",
							"other-assumptions",
							"summary-financials",
							"print-download",

						);

						$db_tables = array(
							"overview" => "plan_master",
							"cover-page-information" => "cover_page_information_master",
							"executive-summary" => "marketing_products_pricing,executive_success_factores",
							"direct-competitors" => "compitition_directs",
							"management-team-members" => "team_members",
							"financial-assumptions" => "financial_assumptions",
						);

						$total_menu_items = count($menu_items, COUNT_RECURSIVE);

						?>

						 
						<div class="list_data">

							<ul>
								<?php
								$i = 1;
								foreach ($menu_items as $item) {
									$cls = '';

									if ($item['display']) {
										$class = '';
										if (count($item['submenu'])) {
											$class = 'dropdownlink';
										}

										if (strtolower($item['name']) == 'financials') {
											$cls = ' nogenerate';
										}

										$tbls = array();
										if (isset($db_tables[$item['url']])) {
											$tbls = explode(",", $db_tables[$item['url']]);
										}
										$greencls = "";
										$active = "";
										$cnt = 0;
										foreach ($tbls as $tbl) {
											$db->where("md5(plan_id)", $plan);
											$r = $db->get($tbl);

											if ($db->count > 0) {
												$cnt++;
											}
										}

										if ($cnt == count($tbls) && count($tbls) > 0) {
											$greencls = " greendot ";
										}


								?>
										<li class="company_tag li_<?php echo $item['url']; ?>" >
											<span class="bullet <?php echo $greencls; ?>"></span>
											<a href="#<?php echo $item['url']; ?>" class="navlinks <?php echo $greencls . " " . $class; ?> <?php echo $cls; ?>"><?php echo $item['name']; ?></a>
											<?php
											if (count($item['submenu'])) {
											?>
												<ul class="drop_list">
													<?php
													foreach ($item['submenu'] as $sub_item) {
													?>
														<li><span class="sub_drop_bullet"></span>
															<a href="#<?php echo $sub_item['url']; ?>" class="drop_list <?php echo $cls; ?>"><?php echo $sub_item['name']; ?></a>
														</li>
													<?php
													}
													?>
												</ul>
											<?php
											}
											?>
										</li>
								<?php
									}
								}
								?>
								 
							</ul>


						</div>
					</div>

				</div>

				<?php
				$included = array();
				foreach ($file_items as $itm) {
					if (file_exists("business-plan/".$itm . ".php")) {
						include_once  "business-plan/".$itm . ".php";
					}
				}
				?>
			</div>

		</section>
	</div>

	


	<div class="sec">

	<?php 
		include_once __DIR__."/business-plan/modal.php";
	?>

		<?php
		$JSFOOTER = "";

		ob_start();
		?>
		<script src="<?php echo SITE_URL; ?>/assets/js/jquery.tmpl.js"></script>
		<template id="showCError">
		<swal-title>
			<h3 style="font-size:16pt; color:#000;">Creating Your Business Plan</h3>
		</swal-title>
		<swal-html>
			<center>
					<h3 style="font-size:13pt;">ProAI is crafting your strategic roadmap:</h3>
				<span style="color:#4285f4;">
						<ul style="list-style-type: circle;">
							<li>&#9679; Analyzing Market trends</li>
							<li>&#9679; Tailoring financial projections</li>
							<li>&#9679; Optimizing growth strategies</li>
						</ul>

			<span  style="color:#0e0e0e;">
					<p style="margin-top:15px;">Please wait to review your complete plan.
					<br/>
					Estimated time: 2-3 minutes</p>
				</span>
			</center>
			</span>
		</swal-html>
		</template>
		<?php 
		 	include_once __DIR__."/assets/js/modify_plan_js.php";
		?>
		<?php
			include_once __DIR__ . "/footer_new.php";
		?>
	</div>
</div>