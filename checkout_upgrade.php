<?php
extract($_REQUEST);
require_once "includes/config.php";

$page_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') ."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$i_arr=array(
"page"=>"checkout",
"url"=>$page_url,
"ip"=>isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
"real_ip"=>isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : '',
"referer"=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
"agent"=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
'full_log'=>serialize($_SERVER)
);
$db->insert("pageview_log_subch",$i_arr);

require_once 'PaypalExpress.class.php';
$paypal = new PaypalExpress;
$plan_prices=array(
	"BPLAN"=> ItemPrice("BPLAN"),
	"IPLAN"=>ItemPrice("IPLAN"),
	"MARKETRESEARCH"=>ItemPrice("MARKETRESEARCH"),
	"MPLAN"=>ItemPrice("MPLAN"), 
	"BUSINESSREGISTRATION"=>0,
	);

if(!(isset($_REQUEST['key']))) {
	header("Location: ".SITE_URL);
	exit;
}

$stripe_api_key =STRIPE_PUBLISHABLE_KEY;
$stripe_api_secret=STRIPE_API_KEY;

$r = explode("--", $_REQUEST['key']);
$_REQUEST['plan'] = $r[0];
$plan = $_REQUEST['plan'];
$plan_type = $r[1];
$key_text = $plan_type;

$plan_type = decryptText($plan_type);
$product_price = $plan_prices[$plan_type];

	if($plan_type=='BPLAN') {
		$product_name = "Entrepreneur Starter Kit";
	}
	if($plan_type == 'IPLAN') {
		$product_name = "Investor Magnet";
	}
	if($plan_type == 'MPLAN') {
		$product_name = "Market Insight Essentials";
	}

$product_amount = $product_price;
?>

<!DOCTYPE html>
<html>
<head>
<script async src="https://ob.cheekybranding.com/i/03f21d23d56bec1382b26e6a06155ed3.js" class="ct_clicktrue"></script>
<!-- Start VWO Async SmartCode -->
<link rel="preconnect" href="https://dev.visualwebsiteoptimizer.com" />
<script type='text/javascript' id='vwoCode'>
window._vwo_code || (function() {
var account_id=834329,
version=2.0,
settings_tolerance=2000,
hide_element='body',
hide_element_style = 'opacity:0 !important;filter:alpha(opacity=0) !important;background:none !important',
/* DO NOT EDIT BELOW THIS LINE */
f=false,w=window,d=document,v=d.querySelector('#vwoCode'),cK='_vwo_'+account_id+'_settings',cc={};try{var c=JSON.parse(localStorage.getItem('_vwo_'+account_id+'_config'));cc=c&&typeof c==='object'?c:{}}catch(e){}var stT=cc.stT==='session'?w.sessionStorage:w.localStorage;code={use_existing_jquery:function(){return typeof use_existing_jquery!=='undefined'?use_existing_jquery:undefined},library_tolerance:function(){return typeof library_tolerance!=='undefined'?library_tolerance:undefined},settings_tolerance:function(){return cc.sT||settings_tolerance},hide_element_style:function(){return'{'+(cc.hES||hide_element_style)+'}'},hide_element:function(){return typeof cc.hE==='string'?cc.hE:hide_element},getVersion:function(){return version},finish:function(){if(!f){f=true;var e=d.getElementById('_vis_opt_path_hides');if(e)e.parentNode.removeChild(e)}},finished:function(){return f},load:function(e){var t=this.getSettings(),n=d.createElement('script'),i=this;if(t){n.textContent=t;d.getElementsByTagName('head')[0].appendChild(n);if(!w.VWO||VWO.caE){stT.removeItem(cK);i.load(e)}}else{n.fetchPriority='high';n.src=e;n.type='text/javascript';n.onerror=function(){_vwo_code.finish()};d.getElementsByTagName('head')[0].appendChild(n)}},getSettings:function(){try{var e=stT.getItem(cK);if(!e){return}e=JSON.parse(e);if(Date.now()>e.e){stT.removeItem(cK);return}return e.s}catch(e){return}},init:function(){if(d.URL.indexOf('__vwo_disable__')>-1)return;var e=this.settings_tolerance();w._vwo_settings_timer=setTimeout(function(){_vwo_code.finish();stT.removeItem(cK)},e);var t=d.currentScript,n=d.createElement('style'),i=this.hide_element(),r=t&&!t.async&&i?i+this.hide_element_style():'',c=d.getElementsByTagName('head')[0];n.setAttribute('id','_vis_opt_path_hides');v&&n.setAttribute('nonce',v.nonce);n.setAttribute('type','text/css');if(n.styleSheet)n.styleSheet.cssText=r;else n.appendChild(d.createTextNode(r));c.appendChild(n);this.load('https://dev.visualwebsiteoptimizer.com/j.php?a='+account_id+'&u='+encodeURIComponent(d.URL)+'&vn='+version)}};w._vwo_code=code;code.init();})();
</script>
<!-- End VWO Async SmartCode -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link href="#" rel=icon type="image/x-icon" />
	<link href="assets/image/favicon.jpg" rel=icon type="image/x-icon" />
    <title>Pro AI | Check Out</title>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel=stylesheet>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/common.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/check-out-style.css?8">

    <script src="https://www.googleoptimize.com/optimize.js?id=OPT-56DQ57W"></script>
	 <!-- Hotjar Tracking Code for PROAI -->
	<script>
		(function(h,o,t,j,a,r){
			h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
			h._hjSettings={hjid:3439309,hjsv:6};
			a=o.getElementsByTagName('head')[0];
			r=o.createElement('script');r.async=1;
			r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
			a.appendChild(r);
		})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
	</script>

 
<script>
var SITE_URL = "<?php echo SITE_URL; ?>";
var KEYTXT = "<?php echo $plan."--".urlencode(encryptText($plan_type)); ?>";
var PLANID = "<?php echo $plan; ?>";
var paypalEnv = '<?php echo $paypal->paypalEnv; ?>';
var paypalClientID = '<?php echo $paypal->paypalClientID; ?>';
var STRIPE_API_KEY = "<?php echo $stripe_api_key; ?>"
</script>
<?php
        include_once __DIR__."/../tagcode/affiliate.php";
	    include_once __DIR__."/../tagcode/meta.php";
		include_once __DIR__."/../tagcode/gtag.php";
		include_once __DIR__."/../tagcode/tiktok.php";
    ?>

</head>
<body>

			<?php
				if(!isLogin())
				{
					$_SESSION['CHECKOUT_LOGIN'] = true;
					$_SESSION['PAYMENT_URL'] = SITE_URL."/checkout?plan=".$plan."&key=".$plan."--".urlencode(encryptText($plan_type));
					$_SESSION['MODAL'] = false;
				?>
				<div class="purchase_block">
					<div style="padding:50px 0px;">&nbsp;</div>
					<div class="purchase_content" style="position:fixed; z-index:110; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.2);">
						<div style="position:absolute; left:50%; top:50%; transform: translate(-50%,-50%); background:#fff;">
							<h3>Please you need to create login with <b>ProAI</b> app.</h3>
							<p>you need login with existing account to Proceed Checkout</p>
							<button id="openGlogin" class="submit_btn" data-plan="<?php echo $plan; ?>" href="#create-or-login">Create or Login</button>
						</div>
					</div>
					</div>
				<?php
				}
			?>

	<section class="check_wrapper xl-pt-5 xl-pr-20 xl-pb-10 xl-pl-16">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<div class="thankyou_wrap  mb-5">
						<div class="logo_box">
							<img src="<?php echo SITE_URL; ?>/assets/image/logo-proai-2.png" alt="">
						</div>						
					</div>
				</div>
			</div>
			<div class="row justify-content-between">
				<div class="col-xl-7 col-lg-7 col-md-7 col-sm-12">
					<div class="go_back mb-10">
						<a href="<?php echo SITE_URL; ?>/subscribe-2" class="text-sm font-normal no-underline color-688AA6 font-montserrat"><svg class="mr-2-5" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none"><path d="M6.50008 12L1 6.49992L6.50008 0.999837" stroke="#688AA6" stroke-width="1.65002" stroke-linecap="round" stroke-linejoin="round"/></svg>Go back to Pricing & Plans</a>
					</div>
					<div class="thank-content_block">
						<div class="thank_title">
							<h1 class="color-2C323A font-bold m-0 font-montserrat text-40">Thank you</h1>
							<p class="color-2C323A md-text-xl font-normal m-0 font-montserrat">For choosing ProAI's AI-powered business growth tools.</p>
						</div>

						<div class="subscription_cont py-10 mt-6 mb-10">
							<h6 class="color-2C323A text-base font-semibold mb-3-5 font-montserrat">Here's what you'll receive with your Purchase:</h6>
							<ul class="p-0 m-0">
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">⚡ Instant access to the ProAI platform</li>
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">📄 Your custom-made business plan, ready for download</li>
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">💬 Dedicated support from our AI Business Advisor</li>
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">🛠️ A suite of tools to craft pitch decks, marketing plans, and financial models</li>
							</ul>
						</div>

						<div class="iframe_wrap d-flex align-items-center justify-content-center gap-md-4 gap-2 w-100">
							<span><iframe id='gdm-vp-snippet-quotations_8a46120e-476a-42b8-8732-c19238e58ea7' src='https://datainsights-cdn.dm.aws.gartner.com/vp/snippet/8a46120e-476a-42b8-8732-c19238e58ea7' frameborder='0' style='height: 92vw; width: 100%;min-height: 456px; min-width: 500px; max-height: 100%;overflow: hidden;' scrolling='yes' ></iframe></span>
							<span><iframe id='gdm-vp-snippet-quotations_443d2fda-75ed-40f2-b28a-0965c41a85cc' src='https://datainsights-cdn.dm.aws.gartner.com/vp/snippet/443d2fda-75ed-40f2-b28a-0965c41a85cc' frameborder='0' style='height: 92vw; width: 100%;min-height: 456px; min-width: 500px; max-height: 100%;overflow: hidden;' scrolling='yes' ></iframe></span>
						</div>
						
					</div>
				</div>

				<div class="col-xl-4 col-lg-4 col-md-5 col-sm-12">
					<div class="select-block_plan max-w-sm w-100">
						<div class="select_plan bg-white">
							<div class="title p-6 pb-0">
								<h6 class="color-2C323A text-lg font-bold xl-mb-1-5 font-montserrat">Selected Plan</h6>
								<p class="color-2C323A text-sm font-normal m-0 font-montserrat"><?php echo $product_name; ?></p>
							</div>
							<div class="plan_box mt-7 mb-5 px-6 xl-py-0 background-F9FBFD">
								<div class="plan_title d-flex justify-content-between align-items-center py-4 px-0">
								<input type="checkbox" id="ai_plan" name="product_price" class="product_price" value="1" data-amount="<?php echo $product_price ; ?>" data-coupon="" style="display:none;" checked  data-atr="<?php echo encryptText(1); ?>"  /><?php //n1 21-02-2024 put data-coupon="" ?>
									
									<div class="sub_title">
										<h6 class="color-2C323A text-base font-semibold mb-1 font-montserrat"><?php echo $product_name; ?></h6>
										<p class="color-688AA6 text-xs font-normal m-0 font-montserrat">&nbsp;</p>
									</div>
									<div class="price text-right">
										<p class="color-2C323A text-base font-semibold m-0 font-montserrat">$<?php echo ($product_amount); ?></p>
									</div>
								</div>
								<div class="plan_content">
									<ul class="m-0 pt-4 pb-7 px-0">
										<li class="color-2C323A text-xs font-normal mb-3-5 font-montserrat"><svg class="mr-2-5" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
											<path
												d="M8.95264 0.907227C4.50877 0.907227 0.90625 4.50975 0.90625 8.95362C0.90625 13.3977 4.50877 17 8.95264 17C13.3968 17 16.999 13.3977 16.999 8.95362C16.999 4.50975 13.3968 0.907227 8.95264 0.907227ZM8.95264 16.0101C5.07051 16.0101 1.91205 12.8358 1.91205 8.95359C1.91205 5.07145 5.07051 1.91299 8.95264 1.91299C12.8348 1.91299 15.9932 5.07147 15.9932 8.95359C15.9932 12.8357 12.8348 16.0101 8.95264 16.0101ZM12.1639 6.00939L7.44292 10.76L5.31691 8.63403C5.12053 8.43764 4.8022 8.43764 4.60556 8.63403C4.40918 8.83041 4.40918 9.14874 4.60556 9.34513L7.09466 11.8345C7.29105 12.0306 7.60938 12.0306 7.80602 11.8345C7.82865 11.8118 7.84802 11.7872 7.86562 11.7616L12.8755 6.72073C13.0716 6.52435 13.0716 6.20601 12.8755 6.00939C12.6789 5.81301 12.3605 5.81301 12.1639 6.00939Z"
												fill="#28A271" />
										</svg>Includes AI Business Advisor access</li>
										<li class="color-2C323A text-xs font-normal mb-3-5 font-montserrat">
											<svg class="mr-2-5" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
											<path
												d="M8.95264 0.907227C4.50877 0.907227 0.90625 4.50975 0.90625 8.95362C0.90625 13.3977 4.50877 17 8.95264 17C13.3968 17 16.999 13.3977 16.999 8.95362C16.999 4.50975 13.3968 0.907227 8.95264 0.907227ZM8.95264 16.0101C5.07051 16.0101 1.91205 12.8358 1.91205 8.95359C1.91205 5.07145 5.07051 1.91299 8.95264 1.91299C12.8348 1.91299 15.9932 5.07147 15.9932 8.95359C15.9932 12.8357 12.8348 16.0101 8.95264 16.0101ZM12.1639 6.00939L7.44292 10.76L5.31691 8.63403C5.12053 8.43764 4.8022 8.43764 4.60556 8.63403C4.40918 8.83041 4.40918 9.14874 4.60556 9.34513L7.09466 11.8345C7.29105 12.0306 7.60938 12.0306 7.80602 11.8345C7.82865 11.8118 7.84802 11.7872 7.86562 11.7616L12.8755 6.72073C13.0716 6.52435 13.0716 6.20601 12.8755 6.00939C12.6789 5.81301 12.3605 5.81301 12.1639 6.00939Z"
												fill="#28A271" />
											</svg>Includes Business Plan Editor access</li>
										<li class="color-2C323A text-xs font-normal font-montserrat">
											<svg class="mr-2-5" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
											<path
												d="M8.95264 0.907227C4.50877 0.907227 0.90625 4.50975 0.90625 8.95362C0.90625 13.3977 4.50877 17 8.95264 17C13.3968 17 16.999 13.3977 16.999 8.95362C16.999 4.50975 13.3968 0.907227 8.95264 0.907227ZM8.95264 16.0101C5.07051 16.0101 1.91205 12.8358 1.91205 8.95359C1.91205 5.07145 5.07051 1.91299 8.95264 1.91299C12.8348 1.91299 15.9932 5.07147 15.9932 8.95359C15.9932 12.8357 12.8348 16.0101 8.95264 16.0101ZM12.1639 6.00939L7.44292 10.76L5.31691 8.63403C5.12053 8.43764 4.8022 8.43764 4.60556 8.63403C4.40918 8.83041 4.40918 9.14874 4.60556 9.34513L7.09466 11.8345C7.29105 12.0306 7.60938 12.0306 7.80602 11.8345C7.82865 11.8118 7.84802 11.7872 7.86562 11.7616L12.8755 6.72073C13.0716 6.52435 13.0716 6.20601 12.8755 6.00939C12.6789 5.81301 12.3605 5.81301 12.1639 6.00939Z"
												fill="#28A271" />
											</svg>Includes Basic Support
										</li>
									</ul>
								</div>							
							</div>
							<div class="additional_box px-6 pb-7">
								<h6 class="color-2C323A text-base font-semibold mb-4 font-montserrat">Additional Add-Ons</h6>
								<div class="add-ons_price d-flex justify-content-between align-items-center">
									<div class="payment_check m-0 color-2C323A text-sm font-normal pl-6">
										<input type="checkbox" id="market" name="product_price" value="2" class="product_price" data-amount="295" style="display:none;" data-atr="<?php echo encryptText(2); ?>"  >
										<label for="market" class="m-0 cursor-pointer font-montserrat">Market Research Report <i flow="down" data-toggle="tooltip" data-placement="bottom" title="Custom industry analysis research report covering market size, growth rates, trends, and more" class="fas fa-info-circle" aria-hidden="true"></i></label>
									</div>								
									<span class="color-688AA6 text-base font-normal font-montserrat">$295</span>
								</div>

								<div class="add-ons_price d-flex justify-content-between align-items-center" style="margin-top:10px; border-top:2px solid #d5d5d5; padding-top:10px;">
									<div class="payment_check m-0 color-2C323A text-sm font-normal pl-6">
										<input type="checkbox" id="market3" name="product_price" value="3" class="product_price" data-amount="0" style="display:none;" data-atr="<?php echo encryptText(3); ?>"  >
										<label for="market3" class="m-0 cursor-pointer font-montserrat">LLC Registration <i flow="down" data-toggle="tooltip" data-placement="bottom" title="Register your LLC for free with our partner.  State fees apply." class="fas fa-info-circle" aria-hidden="true"></i></label>
									</div>								
									<span class="color-688AA6 text-base font-normal font-montserrat">Free</span>
								</div>
							</div>
						</div>
						<div class="payment_method d-flex justify-content-center align-items-center px-6 pt-4 pb-2-5">
							<div class="payment_option d-flex align-items-center gap-1 xl-pr-16 mr-9">
								<input class="d-none mr-2-5 rdopayment" type="radio" name="payment" id="stripe" checked value="stripe">
								<label class="mb-0 cursor-pointer d-flex align-items-center font-montserrat" for="stripe"><svg xmlns="http://www.w3.org/2000/svg" width="56" height="24" viewBox="0 0 56 24" fill="none"><g clip-path="url(#clip0_7661_3086)"><path fill-rule="evenodd" clip-rule="evenodd" d="M56.0799 12.3037C56.0799 8.3003 54.1411 5.14044 50.4336 5.14044C46.7125 5.14044 44.4595 8.29962 44.4595 12.2717C44.4595 16.9792 47.118 19.3561 50.9336 19.3561C52.7949 19.3561 54.2023 18.9343 55.2656 18.3398V15.2119C54.2023 15.7438 52.9826 16.0717 51.4343 16.0717C49.9173 16.0717 48.5724 15.5398 48.4003 13.6949H56.0466C56.0466 13.4908 56.0778 12.6785 56.0778 12.303L56.0799 12.3037ZM48.3608 10.8187C48.3608 9.05132 49.4398 8.31663 50.4255 8.31663C51.3792 8.31663 52.3962 9.05132 52.3962 10.8187H48.3608ZM38.4309 5.1418C36.8983 5.1418 35.9139 5.86084 35.3656 6.36152L35.1615 5.39214H31.7146V23.6268L35.6241 22.7983L35.6398 18.3724C36.203 18.7792 37.0316 19.3574 38.4085 19.3574C41.2078 19.3574 43.7568 17.1125 43.7568 12.1479C43.7411 7.61255 41.1608 5.14112 38.4234 5.14112L38.4309 5.1418ZM37.4921 15.9173C36.5697 15.9173 36.0227 15.5887 35.6466 15.1826L35.6309 9.37989C36.0377 8.92615 36.6003 8.61391 37.4921 8.61391C38.9153 8.61391 39.9003 10.2091 39.9003 12.2574C39.9003 14.3527 38.9309 15.9173 37.4921 15.9173ZM26.3357 4.21935L30.2608 3.37581V0.20166L26.3357 1.03023V4.21935ZM26.3357 5.40778H30.2608V19.0915H26.3357V5.40778ZM22.1282 6.56425L21.8778 5.40778H18.4996V19.0928H22.4091V9.81799C23.3316 8.6139 24.8955 8.83295 25.3806 9.00506V5.40846C24.8799 5.22071 23.0506 4.87649 22.1275 6.56493L22.1282 6.56425ZM14.3091 2.00642L10.4935 2.82071L10.4778 15.3472C10.4778 17.6615 12.2139 19.3663 14.5282 19.3663C15.8105 19.3663 16.7486 19.1316 17.2649 18.8493V15.6744C16.7642 15.8785 14.2935 16.5969 14.2935 14.2826V8.73908H17.2649V5.40778H14.2921L14.3091 2.00642ZM3.73771 9.37241C3.73771 8.76221 4.23839 8.52887 5.06696 8.52887C6.41945 8.55768 7.74679 8.89999 8.94452 9.52887V5.86152C7.64656 5.34452 6.36424 5.14248 5.06696 5.14248C1.89213 5.14248 -0.21875 6.8003 -0.21875 9.56833C-0.21875 13.8847 5.72411 13.1962 5.72411 15.0581C5.72411 15.7772 5.09826 16.0119 4.22275 16.0119C2.92479 16.0119 1.26696 15.4799 -0.046641 14.7602V18.4813C1.40778 19.1071 2.87853 19.3656 4.22275 19.3656C7.47581 19.3656 9.71254 17.7547 9.71254 14.9554C9.6969 10.2955 3.73771 11.1241 3.73771 9.37241Z" fill="#6772E5" /></g><defs><clipPath id="clip0_7661_3086"><rect width="56" height="24" fill="white" /></clipPath></defs></svg></label>
							</div>
							<div class="payment_option pay_pal d-flex align-items-center gap-1">
								<input class="d-none mr-2-5 rdopayment" type="radio" name="payment" id="paypal"  value="paypal">
								<label class="mb-0 cursor-pointer d-flex align-items-center font-montserrat" for="paypal"><svg class="ml-6" xmlns="http://www.w3.org/2000/svg" width="99" height="24" viewBox="0 0 99 24" fill="none"><g clip-path="url(#clip0_7661_3088)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.58776 5.61182H15.3126C18.9232 5.61182 20.2824 7.43971 20.0724 10.1251C19.7252 14.5586 17.045 17.0114 13.4899 17.0114H11.695C11.2072 17.0114 10.8791 17.3343 10.7472 18.2092L9.98516 23.2953C9.93481 23.6251 9.76123 23.8161 9.50084 23.8421H5.27569C4.87817 23.8421 4.73756 23.5383 4.84172 22.8804L7.41778 6.57524C7.51846 5.92254 7.87605 5.61182 8.58776 5.61182Z" fill="#009EE3" /><path fill-rule="evenodd" clip-rule="evenodd" d="M37.7858 5.30957C40.0546 5.30957 42.148 6.54032 41.8616 9.60763C41.5144 13.253 39.5616 15.2701 36.4804 15.2788H33.788C33.4009 15.2788 33.2134 15.5947 33.1127 16.2422L32.592 19.5525C32.5139 20.0525 32.257 20.299 31.8785 20.299H29.3736C28.9744 20.299 28.8355 20.0438 28.924 19.4727L30.9915 6.20529C31.0939 5.5526 31.3387 5.30957 31.7848 5.30957H37.7805H37.7858ZM33.7064 12.4128H35.7461C37.022 12.3642 37.8691 11.4807 37.9541 9.88711C38.0062 8.90286 37.3414 8.19808 36.2842 8.20329L34.3643 8.21198L33.7064 12.4128ZM48.6715 19.2835C48.9006 19.0752 49.1333 18.9675 49.1003 19.2244L49.0187 19.8389C48.977 20.1601 49.1037 20.3302 49.4023 20.3302H51.6277C52.0027 20.3302 52.1849 20.1792 52.2769 19.5994L53.6483 10.9929C53.7177 10.5606 53.6119 10.3489 53.2838 10.3489H50.8362C50.6157 10.3489 50.5081 10.4721 50.4508 10.8089L50.3605 11.3383C50.3137 11.6143 50.1869 11.6629 50.0689 11.3852C49.654 10.4027 48.5951 9.96175 47.1179 9.99647C43.686 10.0676 41.3721 12.6732 41.1239 16.0131C40.9329 18.5961 42.7834 20.6253 45.224 20.6253C46.9946 20.6253 47.7862 20.1045 48.6785 19.2887L48.6715 19.2835ZM46.8072 17.959C45.3299 17.959 44.3005 16.7803 44.5141 15.3361C44.7276 13.8918 46.1093 12.7131 47.5866 12.7131C49.0638 12.7131 50.0932 13.8918 49.8797 15.3361C49.6662 16.7803 48.2861 17.959 46.8072 17.959ZM58.0036 10.3211H55.747C55.2818 10.3211 55.0926 10.6683 55.2401 11.0953L58.0418 19.2991L55.2939 23.2031C55.063 23.5294 55.2418 23.8263 55.5665 23.8263H58.1026C58.2504 23.8434 58.4 23.8176 58.5336 23.7521C58.6672 23.6865 58.7791 23.5839 58.856 23.4565L67.4729 11.097C67.7385 10.7169 67.6135 10.3176 67.1778 10.3176H64.7771C64.3657 10.3176 64.2008 10.4808 63.9647 10.8228L60.3714 16.0304L58.7657 10.8106C58.672 10.4947 58.4376 10.3211 58.0054 10.3211H58.0036Z" fill="#113984" /><path fill-rule="evenodd" clip-rule="evenodd"d="M76.4364 5.30963C78.7052 5.30963 80.7987 6.54037 80.5122 9.60769C80.1651 13.2531 78.2122 15.2702 75.131 15.2788H72.4404C72.0533 15.2788 71.8658 15.5948 71.7651 16.2422L71.2443 19.5526C71.1662 20.0525 70.9093 20.299 70.5309 20.299H68.026C67.6267 20.299 67.4879 20.0438 67.5764 19.4727L69.6473 6.20187C69.7497 5.54918 69.9945 5.30615 70.4406 5.30615H76.4364V5.30963ZM72.357 12.4129H74.3967C75.6726 12.3643 76.5197 11.4807 76.6047 9.88716C76.6568 8.90291 75.992 8.19814 74.9348 8.20335L73.0149 8.21203L72.357 12.4129ZM87.3221 19.2835C87.5513 19.0752 87.7839 18.9676 87.7509 19.2245L87.6693 19.839C87.6276 20.1601 87.7543 20.3303 88.0529 20.3303H90.2783C90.6533 20.3303 90.8356 20.1792 90.9276 19.5995L92.2989 10.9929C92.3683 10.5607 92.2625 10.3489 91.9344 10.3489H89.4902C89.2698 10.3489 89.1622 10.4722 89.1049 10.8089L89.0146 11.3384C88.9677 11.6144 88.841 11.663 88.723 11.3852C88.3081 10.4027 87.2492 9.96181 85.772 9.99652C82.3401 10.0677 80.0262 12.6733 79.7779 16.0131C79.587 18.5961 81.4375 20.6254 83.8781 20.6254C85.6487 20.6254 86.4403 20.1046 87.3325 19.2887L87.3221 19.2835ZM85.4595 17.959C83.9823 17.959 82.9529 16.7804 83.1664 15.3361C83.3799 13.8919 84.7617 12.7132 86.2389 12.7132C87.7162 12.7132 88.7455 13.8919 88.532 15.3361C88.3185 16.7804 86.9368 17.959 85.4595 17.959ZM95.7238 20.3442H93.1547C93.11 20.3461 93.0654 20.3383 93.0242 20.321C92.9829 20.3038 92.9459 20.2777 92.9159 20.2445C92.8859 20.2113 92.8636 20.172 92.8505 20.1292C92.8375 20.0864 92.8341 20.0412 92.8405 19.997L95.0972 5.7002C95.1187 5.60263 95.1726 5.51522 95.2502 5.45218C95.3277 5.38915 95.4243 5.3542 95.5242 5.35302H98.0933C98.138 5.35103 98.1826 5.35892 98.2238 5.37614C98.2651 5.39337 98.3021 5.41949 98.3321 5.45266C98.3621 5.48583 98.3845 5.52521 98.3975 5.568C98.4105 5.61079 98.4139 5.65594 98.4075 5.7002L96.1508 19.997C96.13 20.0952 96.0764 20.1834 95.9988 20.2471C95.9212 20.3108 95.8242 20.3463 95.7238 20.3476V20.3442Z"fill="#009EE3" /><path fill-rule="evenodd" clip-rule="evenodd"d="M4.38109 0H11.1129C13.0085 0 15.2582 0.0607561 16.7614 1.38871C17.7665 2.27575 18.2942 3.68703 18.1727 5.20766C17.7596 10.3476 14.6853 13.2275 10.5609 13.2275H7.24183C6.67593 13.2275 6.30272 13.6024 6.14301 14.6162L5.21605 20.5182C5.15529 20.9001 4.99039 21.1258 4.69528 21.1535H0.541305C0.081295 21.1535 -0.08188 20.8064 0.0378963 20.0391L3.02362 1.12138C3.1434 0.361063 3.56175 0 4.38109 0Z"fill="#113984" /><path fill-rule="evenodd" clip-rule="evenodd"d="M6.24219 14.0173L7.41738 6.57551C7.5198 5.92282 7.87739 5.61035 8.58911 5.61035H15.3139C16.4266 5.61035 17.3276 5.78394 18.0323 6.10508C17.3571 10.6809 14.3974 13.2222 10.5229 13.2222H7.20908C6.76469 13.224 6.43834 13.4462 6.24219 14.0173Z"fill="#172C70" /></g>
								<defs><clipPath id="clip0_7661_3088"><rect width="98.4127" height="23.8424" fill="white" /></clipPath></defs></svg>
								</label>

							</div>
						</div>

						<div class="px-7 mt-2 mb-4">
						</div>

						<div>
							<div class="px-5 pt-4 pb-4 select_plan mb-5">
								<label for="coupon_code" class="color-2C323A text-base font-semibold mb-2 font-montserrat" style="white-space: nowrap;">Coupon Code</label>
								<div class="d-flex align-items-center gap-1 justify-content-between position-relative w-100 ">
									<div class="w-75">
										<input type="text" name="coupon_code" id="coupon_code" class="coupon_code w-100 d-block rounded font-poppins text-sm color-2C323A border h-9 py-2-5 px-3">
										<span class="coupon_msg w-100 d-block position-absolute text-xs" style="bottom:-16px; left: 0;"></span>
									</div>
									<button type="button" name="coupon_check" id="coupon_check" class="btn-blue-1 font-poppins text-sm rounded font-medium py-2 px-3 ml-1 border-0">Apply</button><?php //mt-6 xl-py-3-5 xl-px-12 class remove ?>
								</div>
								
							</div>
						</div>
						<div id="paymentResponse" class="hidden"></div>
						<div class="total_payment px-5 pt-6 pb-5 background-F9FBFD">
							<div class="payment_content d-flex justify-content-between align-items-center"> 
								<div class="payment_title">
									<p class="total color-2C323A text-lg font-medium m-0 font-montserrat">Total Due Today</p>
									<p class="color-688AA6 text-sm font-normal m-0 font-montserrat">Inc GST</p>
								</div>
								<p class="color-2C323A text-xl font-bold m-0 font-montserrat"><span id="totalamount"></span></p>
							</div>
							<div class="complete_btn" style="cursor: pointer;">
									<div class="stripecontainer" style="width=100%; text-align:center;">
										<button type="button" name="btncheckout" id="btncheckout" class="text-white font-poppins text-base font-medium rounded-lg bg-28A271 d-inline-block mt-6 xl-py-3-5 xl-px-12">Complete Purchase</button>
									</div>
									<div class="paypal_button_label_container mt-6" style="display:none;text-align:center;">
											<div id="paypal-button-container"></div>
									</div>
							</div>
							<div class="completeText mt-5">By clicking "Complete Purchase", you are agreeing to the <a href="https://proai.co/terms-and-conditions" target="_blank">terms of service</a> and the <a href="https://proai.co/privacy-policy" target="_blank">privacy policy</a>.</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<div class='check_load'>
		<div class="lds-ripple"><div></div><div></div></div>
</div>


	<script src="https://www.paypalobjects.com/api/checkout.js"></script>
	<script src="https://js.stripe.com/v3/"></script>
	<script src="<?php echo SITE_URL; ?>/dashboard/js/jquery-3.6.4.min.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/jquery.validate.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/additional-methods.js"></script>
	<script src="https://proai.co/get-started-v3/dashboard/js/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/checkout.js"></script>

  <?php
    include_once "assets/js/checkout_upgrade_js.php";
   ?>

	<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/642aa9e64247f20fefe98436/1gt3afbsv';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>

</html>