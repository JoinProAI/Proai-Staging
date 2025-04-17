<div id="generateDocument" class="modal gcustom_wrapper fade" role="dialog" data-plan="<?php echo $_REQUEST["plan"]; ?>">
			<div class="modal-dialog new-modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<section class="gcustom_block middle_wrapper">
							<div class="container">
								<div class="row">

									<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
										<div class="viewer_card viewer_card_iframe">
											<div id="overlay"></div>
											<div id="displayError" style=""></div>
											<iframe id="blockframe" src="" width="100%" height="100%" style="width:100%; height:100%; border: 0px; display:none;" aria-readonly="true"></iframe>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 pl-0">
										<div class="sticky_div">
											<div class="viewer_block">

												<h2>Your Custom Business Plan</h2>
												<p>Subscribe now to download your completed plan. Also unlocks our Business Plan Editor, Pitch Deck Creator, AI Advisor and Investor List</p>
												<?php
												if (checkSubscription($_GET['plan'])) {
												?>
													<a href="<?php echo SITE_URL; ?>/plan_show_full_v2.php?plan=<?php echo $_GET['plan']; ?>&pdf=true" class="tmplt_btn_theme green_btn downloadbtn" data-plan="<?php echo $_GET['plan']; ?>">Download</a>
												<?php
												} else {
												?>
													<a href="https://proai.co/subscribe-2v4" class="tmplt_btn_theme green_btn checkoutbtn" data-plan="<?php echo $_GET['plan']; ?>">Subscribe & Download</a>
												<?php
												}
												?>

												<div class="some_items">
													<h3>What is Included?</h3>
													<ul>
														<li>
															<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M15.4167 18.75H4.58337C3.89296 18.75 3.33337 18.1904 3.33337 17.5V2.5C3.33337 1.80958 3.89296 1.25 4.58337 1.25H12.5L16.6667 5.41667V17.5C16.6667 18.1904 16.1071 18.75 15.4167 18.75Z" fill="#2196F3"></path>
																<path d="M16.6667 5.41667H12.5V1.25L16.6667 5.41667Z" fill="#BBDEFB"></path>
																<path d="M12.5 5.41669L16.6667 9.58335V5.41669H12.5Z" fill="#1565C0"></path>
																<path d="M6.25 9.58331H13.75V10.4166H6.25V9.58331ZM6.25 11.25H13.75V12.0833H6.25V11.25ZM6.25 12.9166H13.75V13.75H6.25V12.9166ZM6.25 14.5833H10.4167V15.4166H6.25V14.5833Z" fill="#E3F2FD"></path>
															</svg>
                                                          	Business Plan Editable 24/7 using our AI plan editor
														</li>
														<li>
															<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M15.4167 18.75H4.58337C3.89296 18.75 3.33337 18.1904 3.33337 17.5V2.5C3.33337 1.80958 3.89296 1.25 4.58337 1.25H12.5L16.6667 5.41667V17.5C16.6667 18.1904 16.1071 18.75 15.4167 18.75Z" fill="#43A047"></path>
																<path d="M16.6667 5.41667H12.5V1.25L16.6667 5.41667Z" fill="#C8E6C9"></path>
																<path d="M12.5 5.41669L16.6667 9.58335V5.41669H12.5Z" fill="#2E7D32"></path>
																<path d="M12.9167 9.58331H7.08333H6.25V10.4166V11.25V12.0833V12.9166V13.75V14.5833V15.4166H13.75V14.5833V13.75V12.9166V12.0833V11.25V10.4166V9.58331H12.9167ZM7.08333 10.4166H8.75V11.25H7.08333V10.4166ZM7.08333 12.0833H8.75V12.9166H7.08333V12.0833ZM7.08333 13.75H8.75V14.5833H7.08333V13.75ZM12.9167 14.5833H9.58333V13.75H12.9167V14.5833ZM12.9167 12.9166H9.58333V12.0833H12.9167V12.9166ZM12.9167 11.25H9.58333V10.4166H12.9167V11.25Z" fill="#E8F5E9"></path>
															</svg>
                                                          	Financial Model Editable 24/7 using our financial editor
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</section>

						<section class="how_its_wrapper">
							<div class="container">
								<div class="row">
									<div class="col-md-12">
										<h2 class="page_titlenew">Streamline Your Business <br> Planning with ProAI</h2>
										<p class="paragraph_txtnew">
											Create, Edit, and Collaborate on Your Business Plan with Ease
										</p>
									</div>
									<div class="how_its_column row">
										<div class="col-lg-4 col-md-4 col-sm-6 col-12">
											<div class="how_its_blocks how_its_mble">
												<h5 class="icon_bg1"><i class="fa fa-file-text" aria-hidden="true"></i></h5>
                                              <p>Immediately download your plan and continue to conduct research, do financial planning, and make edits as needed</p>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-12">
											<div class="how_its_blocks how_its_mble">
												<h5 class="icon_bg2"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></h5>
												<p>Make any edits you would like to the document quickly and easily.</p>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-12">
											<div class="how_its_blocks">
												<h5 class="icon_bg3"><i class="fa fa-headphones" aria-hidden="true"></i>
												</h5>
												<p>If you need any help, our 24/7 live support team is standing by to help.
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>

						<section class="accordion_wrapper new_accordion_wrapper">
							<div class="container">
								<div class="row">
									<div class="col-xl-12 client_title" style="max-width:1160px; margin:0 auto;">
										<h2 class="page_titlenew">FAQ </h2>
									</div>
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="accordion new_accordion" id="faq">
											<div class="card">
												<div class="card-header" id="faqhead1">
													<button href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq1" aria-expanded="true" aria-controls="faq1"> How do I pay to download my completed business plan from ProAI? <i class="fa fa-angle-up" aria-hidden="true"></i></button>
												</div>

												<div id="faq1" class="collapse" aria-labelledby="faqhead1" data-parent="#faq">
													<div class="card-body">
														You can pay for your completed business plan using any credit card or debit card through the Stripe payment gateway.
													</div>
												</div>
											</div>
											<div class="card">
												<div class="card-header" id="faqhead2">
													<button href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq2" aria-expanded="true" aria-controls="faq2"> What format will my completed business plan be in once I download it? <i class="fa fa-angle-up" aria-hidden="true"></i></button>
												</div>

												<div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
													<div class="card-body">
                                                      	Your completed business plan will be available as a printable PDF file at any time. You can simply export these files and continue to edit them at any time within ProAI.
													</div>
												</div>
											</div>
											<div class="card">
												<div class="card-header" id="faqhead3">
													<button href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq3" aria-expanded="true" aria-controls="faq3"> Can I make changes to my completed business plan once I download it? <i class="fa fa-angle-up" aria-hidden="true"></i></button>
												</div>

												<div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
													<div class="card-body">
                                                      	Yes, you will unlock our full editor to gain access to a complete set of AI editing tools including a CoPilot to rewrite sections and doing custom financial forecasting.
													</div>
												</div>
											</div>
											<div class="card">
												<div class="card-header" id="faqhead4">
													<button href="#" class="btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq4" aria-expanded="true" aria-controls="faq4"> What if I have additional questions or need help with my completed business plan after downloading it? <i class="fa fa-angle-up" aria-hidden="true"></i></button>
												</div>

												<div id="faq4" class="collapse" aria-labelledby="faqhead4" data-parent="#faq">
													<div class="card-body">
														ProAI offers email and live chat support for any additional questions or assistance you may need. You can also call in during our normal business hours from 9:00 AM - 5:00 PM (EST).
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>

				</div>
			</div>
		</div>
<?php
/*
Following code is not in system now
?>
<div id="checkoutDocument" class="modal fade gcustom_wrapper checkout_wrapper" role="dialog">
			<div class="modal-dialog new-modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M6.4 19L5 17.6L10.6 12L5 6.4L6.4 5L12 10.6L17.6 5L19 6.4L13.4 12L19 17.6L17.6 19L12 13.4L6.4 19Z" fill="#011627" />
							</svg>
						</button>
					</div>
					<div class="modal-body">

						<div class="checkout_block">

							<div class="row m-0">

								<div class="col-lg-6 col-md-12 col-sm-12 col-12 p-0">
									<img class="checkout_logo" src="<?php echo SITE_URL; ?>/assets/image/logo-proai.png" />
									<div class="express_checkout">
										<span class="head">Express Checkout</span>

										<div class="paypal_button_label_container">
											<div id="paypal-button-container"></div>
										</div>
									</div>

									<div class="border_hr"></div>
									<div class="purchase_block">
										<form class="payment_form" action="" method="post">

											<div class="form_head">
												<h3 class="h3title_text">Payment Info</h3>
											</div>

											<div class="form_group visa_cardInput">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<g clip-path="url(#clip0_2395_1646)">
														<path d="M4 9C4 8.73478 4.10536 8.48043 4.29289 8.29289C4.48043 8.10536 4.73478 8 5 8H9C9.26522 8 9.51957 8.10536 9.70711 8.29289C9.89464 8.48043 10 8.73478 10 9C10 9.26522 9.89464 9.51957 9.70711 9.70711C9.51957 9.89464 9.26522 10 9 10H5C4.73478 10 4.48043 9.89464 4.29289 9.70711C4.10536 9.51957 4 9.26522 4 9Z" fill="#7C8F9F" />
														<path fill-rule="evenodd" clip-rule="evenodd" d="M4 3C2.93913 3 1.92172 3.42143 1.17157 4.17157C0.421427 4.92172 0 5.93913 0 7L0 17C0 18.0609 0.421427 19.0783 1.17157 19.8284C1.92172 20.5786 2.93913 21 4 21H20C21.0609 21 22.0783 20.5786 22.8284 19.8284C23.5786 19.0783 24 18.0609 24 17V7C24 5.93913 23.5786 4.92172 22.8284 4.17157C22.0783 3.42143 21.0609 3 20 3H4ZM20 5H4C3.46957 5 2.96086 5.21071 2.58579 5.58579C2.21071 5.96086 2 6.46957 2 7V14H22V7C22 6.46957 21.7893 5.96086 21.4142 5.58579C21.0391 5.21071 20.5304 5 20 5ZM22 16H2V17C2 17.5304 2.21071 18.0391 2.58579 18.4142C2.96086 18.7893 3.46957 19 4 19H20C20.5304 19 21.0391 18.7893 21.4142 18.4142C21.7893 18.0391 22 17.5304 22 17V16Z" fill="#7C8F9F" />
													</g>
													<defs>
														<clipPath id="clip0_2395_1646">
															<rect width="24" height="24" fill="white" />
														</clipPath>
													</defs>
												</svg>
												<input type="tel" id="cardNumber" name="cardNumber" class="form_control" placeholder="Card number" required>
												<img src="<?php echo SITE_URL; ?>/assets/image/card_visa.png" />
											</div>

											<div class="form_group month_cvcBlock row mb24">
												<div class="col-lg-6 col-md-6 col-sm-6 col-12 pl-0">
													<input type="text" id="monthYear" name="monthYear" class="form_control" placeholder="MM / YY" required>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-12 cvc_block pr-0">
													<input type="text" id="cvc" name="cvc" class="form_control" placeholder="CVC" required>
													<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M10 2C8.41775 2 6.87103 2.46919 5.55544 3.34824C4.23985 4.22729 3.21447 5.47672 2.60897 6.93853C2.00347 8.40034 1.84504 10.0089 2.15372 11.5607C2.4624 13.1126 3.22433 14.538 4.34315 15.6569C5.46197 16.7757 6.88743 17.5376 8.43928 17.8463C9.99113 18.155 11.5997 17.9965 13.0615 17.391C14.5233 16.7855 15.7727 15.7602 16.6518 14.4446C17.5308 13.129 18 11.5822 18 10C17.9977 7.87898 17.1541 5.8455 15.6543 4.34572C14.1545 2.84593 12.121 2.00233 10 2ZM10 16.4C8.7342 16.4 7.49683 16.0246 6.44435 15.3214C5.39188 14.6182 4.57158 13.6186 4.08717 12.4492C3.60277 11.2797 3.47603 9.9929 3.72298 8.75142C3.96992 7.50994 4.57946 6.36957 5.47452 5.47452C6.36958 4.57946 7.50995 3.96992 8.75142 3.72297C9.9929 3.47603 11.2797 3.60277 12.4492 4.08717C13.6186 4.57157 14.6182 5.39187 15.3214 6.44435C16.0246 7.49682 16.4 8.7342 16.4 10C16.3979 11.6967 15.7229 13.3234 14.5231 14.5231C13.3234 15.7229 11.6967 16.3979 10 16.4ZM10 9.6C9.78783 9.6 9.58435 9.68428 9.43432 9.83431C9.28429 9.98434 9.2 10.1878 9.2 10.4V12.8C9.2 13.0122 9.28429 13.2157 9.43432 13.3657C9.58435 13.5157 9.78783 13.6 10 13.6C10.2122 13.6 10.4157 13.5157 10.5657 13.3657C10.7157 13.2157 10.8 13.0122 10.8 12.8V10.4C10.8 10.1878 10.7157 9.98434 10.5657 9.83431C10.4157 9.68428 10.2122 9.6 10 9.6ZM10 6.4C9.80222 6.4 9.60888 6.45865 9.44443 6.56853C9.27998 6.67841 9.15181 6.83459 9.07612 7.01731C9.00044 7.20004 8.98063 7.40111 9.01922 7.59509C9.0578 7.78907 9.15304 7.96725 9.2929 8.10711C9.43275 8.24696 9.61093 8.3422 9.80491 8.38078C9.99889 8.41937 10.2 8.39957 10.3827 8.32388C10.5654 8.24819 10.7216 8.12002 10.8315 7.95557C10.9414 7.79112 11 7.59778 11 7.4C11 7.13478 10.8946 6.88043 10.7071 6.69289C10.5196 6.50536 10.2652 6.4 10 6.4Z" fill="#011627" />
													</svg>
												</div>
											</div>

											<div class="form_group mb24">
												<h3 class="h3title_text mb16">Name on Card</h3>
												<input type="text" id="fullName" name="fullName" class="form_control" placeholder="Full Name" required>
											</div>

											<div class="form_group mb24">
												<h3 class="h3title_text mb16">Country or Region</h3>
												<select id="" name="" class="form_control_select">
													<option>-- Select Country --</option>
													<option selected>United States of America</option>
													<option>India</option>
													<option>Australia</option>
												</select>
											</div>

											<div class="form_group mb24 checkbox_button">
												<input type="checkbox" id="agree" name="checkbox-group">
												<label for="agree">I agree to Business plan's <a href="#"><b>Terms of Service</b></a> and <a href="#"><b>Privacy Policy</b></a>.</label>
											</div>

											<input type="submit" id="submi" name="submit" value="Pay Now" class="submit_btn_green">

										</form>

										<div class="secure_blocks">
											<h4 class="h4title_text">Secure Payment</h4>
											<div class="secure_imgs">
												<img src="<?php echo SITE_URL; ?>/assets/image/secure-payment-01.png" />
												<img src="<?php echo SITE_URL; ?>/assets/image/secure-payment-02.png" />
												<img src="<?php echo SITE_URL; ?>/assets/image/secure-payment-03.png" />
												<img src="<?php echo SITE_URL; ?>/assets/image/secure-payment-04.png" />
												<img src="<?php echo SITE_URL; ?>/assets/image/secure-payment-05.png" />
											</div>
										</div>

										<div class="testimonial_slider owl-carousel owl-theme">
											<div class="testimonial_yellow_blocks">
												<div>
													<img src="<?php echo SITE_URL; ?>/assets/image/chaseHughes.png" />
												</div>
												<h5>Chase Hughes</h5>
												<p>I was able to secure funding and launch my startup successfully. I highly recommend it to anyone who needs a business plan for their venture.</p>
											</div>

											<div class="testimonial_yellow_blocks">
												<div>
													<img src="<?php echo SITE_URL; ?>/assets/image/chaseHughes.png" />
												</div>
												<h5>Chase Hughes</h5>
												<p>I was able to secure funding and launch my startup successfully. I highly recommend it to anyone who needs a business plan for their venture.</p>
											</div>

											<div class="testimonial_yellow_blocks">
												<div>
													<img src="<?php echo SITE_URL; ?>/assets/image/chaseHughes.png" />
												</div>
												<h5>Chase Hughes</h5>
												<p>I was able to secure funding and launch my startup successfully. I highly recommend it to anyone who needs a business plan for their venture.</p>
											</div>
										</div>

									</div>
								</div>

								<div class="col-lg-6 col-md-12 col-sm-12 col-12 p-0">
									<div class="purchase_block planSummry_blocks">
										<div class="planSummry_table">
											<div class="plan_summry_img"><img src="<?php echo SITE_URL; ?>/assets/image/plan_Summry_Docs.svg" /></div>
											<div class="aiGenerated_text">AI-Generated Business Plan: <span>$19.95</span></div>
											<div class="financialModel_text">Financial Model: <span><img src="<?php echo SITE_URL; ?>/assets/image/check_green.svg" /> Included</span></div>
											<div class="border_dashed"></div>
											<div class="planSummry_optional_blocks">
												<div class="optional_txt">Optional Add-ons <span class="opt_box">Optional</span></div>
												<div class="checkbox_button_opt">
													<input type="checkbox" id="market" name="market-group" data-amount="295">
													<label for="market">Market Research Report</label>
													<span>$295</span>
												</div>
												<div class="checkbox_button_opt">
													<input type="checkbox" id="review" name="market-group" data-amount="195">
													<label for="review">Business Plan Review</label>
													<span>$195</span>
												</div>
											</div>
											<div class="border_dashed"></div>
											<div class="subTotal_text">
												<span>Subtotal <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M10 2C8.41775 2 6.87103 2.46919 5.55544 3.34824C4.23985 4.22729 3.21447 5.47672 2.60897 6.93853C2.00347 8.40034 1.84504 10.0089 2.15372 11.5607C2.4624 13.1126 3.22433 14.538 4.34315 15.6569C5.46197 16.7757 6.88743 17.5376 8.43928 17.8463C9.99113 18.155 11.5997 17.9965 13.0615 17.391C14.5233 16.7855 15.7727 15.7602 16.6518 14.4446C17.5308 13.129 18 11.5822 18 10C17.9977 7.87898 17.1541 5.8455 15.6543 4.34572C14.1545 2.84593 12.121 2.00233 10 2ZM10 16.4C8.7342 16.4 7.49683 16.0246 6.44435 15.3214C5.39188 14.6182 4.57158 13.6186 4.08717 12.4492C3.60277 11.2797 3.47603 9.9929 3.72298 8.75142C3.96992 7.50994 4.57946 6.36957 5.47452 5.47452C6.36958 4.57946 7.50995 3.96992 8.75142 3.72297C9.9929 3.47603 11.2797 3.60277 12.4492 4.08717C13.6186 4.57157 14.6182 5.39187 15.3214 6.44435C16.0246 7.49682 16.4 8.7342 16.4 10C16.3979 11.6967 15.7229 13.3234 14.5231 14.5231C13.3234 15.7229 11.6967 16.3979 10 16.4ZM10 9.6C9.78783 9.6 9.58435 9.68428 9.43432 9.83431C9.28429 9.98434 9.2 10.1878 9.2 10.4V12.8C9.2 13.0122 9.28429 13.2157 9.43432 13.3657C9.58435 13.5157 9.78783 13.6 10 13.6C10.2122 13.6 10.4157 13.5157 10.5657 13.3657C10.7157 13.2157 10.8 13.0122 10.8 12.8V10.4C10.8 10.1878 10.7157 9.98434 10.5657 9.83431C10.4157 9.68428 10.2122 9.6 10 9.6ZM10 6.4C9.80222 6.4 9.60888 6.45865 9.44443 6.56853C9.27998 6.67841 9.15181 6.83459 9.07612 7.01731C9.00044 7.20004 8.98063 7.40111 9.01922 7.59509C9.0578 7.78907 9.15304 7.96725 9.2929 8.10711C9.43275 8.24696 9.61093 8.3422 9.80491 8.38078C9.99889 8.41937 10.2 8.39957 10.3827 8.32388C10.5654 8.24819 10.7216 8.12002 10.8315 7.95557C10.9414 7.79112 11 7.59778 11 7.4C11 7.13478 10.8946 6.88043 10.7071 6.69289C10.5196 6.50536 10.2652 6.4 10 6.4Z" fill="#7C8F9F" />
													</svg></span>
												<span id="subtotalamount">$274</span>
											</div>
											<div class="aiGenerated_text mb-0">Total to Pay <span id="totalamount">$274</span></div>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

    <?php
    */
    ?>


