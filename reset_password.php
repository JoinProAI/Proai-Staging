<?php
	include_once "includes/config.php";
	include_once "includes/function.php";
	include_once __DIR__."/header.php";
	extract($_REQUEST);
	$JSFOOTER = "";
?>	

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/reset_password.css?<?php echo strtotime('now'); ?>">
<section class="banner_section">
            <div class="container">
                <div class="text_box">
                    <h1></h1>
                </div>
            </div>
        </section>

        <section class="detail_section height_full login_dashboard">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 c	ol-lg-6 col-md-8 col-sm-10 col-12">
                        <div class="login_items">
                            <form id="frmresetpassword" name="frmresetpassword" method="post" autocomplete="off" aria-autocomplete="off">
								<?php
									$db->where("hashkey", $db->escape($key));
									$db->where("user_email",$db->escape($email));
									$result = $db->getOne("user_master");
									if($db->count==0) {
?>
													<h3 class="login_title">Invalid KEY</h3>
<?php
									}
									else {
?>
                                <h3 class="login_title">Set Password</h3>

									<div class="pass_input" style="text-align:left;position:relative;">
										Registered Email: &nbsp;&nbsp;<b><?php echo $result['user_email']; ?></b>
                                    </div>

                                    <div class="pass_input">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                        <input class="input_field" type="password"  name="new_password" id="new_password" placeholder="Enter New Password"  autocomplete="off" aria-autocomplete="off">
										<label class="labelinput">Enter New Password</label>
                                    </div>
                                    <div class="pass_input">
                                        <i class="fa fa-key" aria-hidden="true"></i>
                                        <input class="input_field" type="password"  name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password"  autocomplete="off" aria-autocomplete="off">
										<label class="labelinput">Enter Confirm Password</label>
                                    </div>
                                <div class="submit_buttons">
                                    <div class="login_btn">
										<input type="hidden" name="step" id="step" value="reset_password" />
										<input type="hidden" name="key" id="key" value="<?php echo $_REQUEST['key']; ?>" />
                                        <!-- a href="#" class="login themebtn_new">LOGIN</a -->
									<button id="btnupdatepassword" type="submit" name="btnupdatepassword" class="login themebtn_new">
										Update Password
										<img class="loading_icon" src="<?php echo SITE_URL; ?>/assets/image/spinner.svg" />
									</button>

                                    </div>
                                </div>
								<?php
									}
								?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
ob_start();
?>

<?php
$JSFOOTER = "";
?>
	
<?php 
 include_once "assets/js/reset_password_js.php";
?>

<?php
$JSFOOTER = ob_get_clean();
?>
<?php
	include_once __DIR__."/footer.php";
?>