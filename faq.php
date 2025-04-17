<?php
	include_once "includes/config.php";
	include_once "includes/function.php";
	include_once __DIR__."/header.php";
$MODE = "plan_create";
?>
        <section class="banner_section">
            <div class="container">
                <div class="text_box">
                    <h1>FAQs</h1>
                </div>
            </div>
        </section>

        <section class="business_index height_full">
            <div class="container">
                <div class="row justify-content-between">

                    <div class="right_col">
						<div class="faq_block">
							<div class="accordion" id="faq">

								<div class="card">
									<div class="card-header" id="faqhead1">
										<a href="#" class="btn-header-link" data-toggle="collapse" data-target="#faq1"
										aria-expanded="true" aria-controls="faq1">What is ProAI and how does it work?</a>
									</div>
									<div id="faq1" class="collapse show" aria-labelledby="faqhead1" data-parent="#faq">
										<div class="card-body">
										ProAI is a web application that helps users create custom business plans using artificial intelligence. Users answer a series of questions about their business, and ProAI's AI system auto-fills any responses that are not completed. ProAI then conducts third-party market research on the user's competition and target market, and generates a complete twenty-five page business plan with five-year financial projections that can be exported to a Google Doc and Google Sheet.
										</div>
									</div>
								</div>							 

								<div class="card">
									<div class="card-header" id="faqhead3">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq3"
										aria-expanded="true" aria-controls="faq3">How long does it take to create a custom business plan with ProAI?</a>
									</div>
									<div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
										<div class="card-body">
										The amount of time it takes to create a business plan with ProAI varies depending on the user's input and the complexity of their business. However, most users are able to complete a business plan in less than 30 minutes.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead4">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq4"
										aria-expanded="true" aria-controls="faq4">Can I export my business plan to a format that I can edit outside of ProAI?</a>
									</div>
									<div id="faq4" class="collapse" aria-labelledby="faqhead4" data-parent="#faq">
										<div class="card-body">
										Yes, users can export their business plan to a Google Doc and Google Sheet, which can be edited outside of ProAI.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead5">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq5"
										aria-expanded="true" aria-controls="faq5">How secure is the information that I provide to ProAI?</a>
									</div>
									<div id="faq5" class="collapse" aria-labelledby="faqhead5" data-parent="#faq">
										<div class="card-body">
										ProAI takes the security of its users' information seriously and uses industry-standard security measures to protect user data. All information provided to ProAI is encrypted and stored securely.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead6">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq6"
										aria-expanded="true" aria-controls="faq6">Does ProAI offer any additional resources or support for business planning beyond the creation tool?</a>
									</div>
									<div id="faq6" class="collapse" aria-labelledby="faqhead6" data-parent="#faq">
										<div class="card-body">
										Yes, ProAI offers additional resources and support for business planning, including access to expert advisors, design services, and additional market research.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead7">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq7"
										aria-expanded="true" aria-controls="faq7">Can I collaborate with others on my business plan within ProAI?</a>
									</div>
									<div id="faq7" class="collapse" aria-labelledby="faqhead7" data-parent="#faq">
										<div class="card-body">
										ProAI does not offer in-app collaboration features, but users can export their completed business plan to a Google Doc or Google Sheet to collaborate with others.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead8">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq8"
										aria-expanded="true" aria-controls="faq8">What happens if I have additional questions or issues while using ProAI?</a>
									</div>
									<div id="faq8" class="collapse" aria-labelledby="faqhead8" data-parent="#faq">
										<div class="card-body">
										ProAI has a support team available to assist users with any questions or issues they may encounter while using the tool. Users can reach out to the support team through the ProAI website, live chat, or by phone.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead9">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq9"
										aria-expanded="true" aria-controls="faq9">Does ProAI offer any integrations with other software or services?</a>
									</div>
									<div id="faq9" class="collapse" aria-labelledby="faqhead9" data-parent="#faq">
										<div class="card-body">
										ProAI does not offer direct integrations with other software or services, but it does allow users to export their completed business plan to a Google Doc or Google Sheet, which can then be stored in Google Drive. Google Drive supports many integrations with other software and services, which allows users to further customize and work with their completed business plan.
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-header" id="faqhead10">
										<a href="#" class="btn-header-link collapsed" data-toggle="collapse" data-target="#faq10"
										aria-expanded="true" aria-controls="faq10">Can I save my progress and come back to it later?</a>
									</div>
									<div id="faq10" class="collapse" aria-labelledby="faqhead10" data-parent="#faq">
										<div class="card-body">
										Yes, ProAI allows users to save their progress and come back to it later. Users can access their saved business plans through the ProAI website.
										</div>
									</div>
								</div>

							</div>							
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
	include_once __DIR__."/footer.php";
?>