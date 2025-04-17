<?php
	include_once "includes/config.php";
	include_once "includes/function.php";
	include_once __DIR__."/header.php";

$MODE = "plan_create";
?>

        <section class="banner_section">
            <div class="container">
                <div class="text_box">
                    <h1>Contact Us</h1>
                </div>
            </div>
        </section>

        <section class="business_index height_full">
            <div class="container">
                <div class="row justify-content-center">
					<div class="overview_right_wp" style="display:block;box-shadow: 0px 1px 50px rgba(75, 82, 140, 0.08);">
						<div class="right_col">							
							<form class="contactform" id="contactform" name="contactform" method="post">
								<div class="form_group">
									<input type="text" name="name" id="name" placeholder="Your Name" class="form_control">
								</div>
								<div class="form_group">
									<input type="email" name="email" id="email" placeholder="Email" class="form_control">
								</div>
								<div class="form_group">
									<input type="tel" name="number" id="number" placeholder="Phone Number" class="form_control">
								</div>
								<div class="form_group">
									<textarea name="message" id="message" placeholder="Your Message" class="form_control"></textarea>
								</div>
								<div class="button_group">
									<input type="button" value="Submit" class="themebtn_new theme_btn">
								</div>
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
$JSFOOTER = ob_get_clean();
?>

<?php
	//include_once __DIR__."/footer.php";
	include_once __DIR__."/footer-nologin.php";
?>