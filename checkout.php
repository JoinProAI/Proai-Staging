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

require_once 'checkout_process/PaypalExpress.class.php';

$paypal = new PaypalExpress;
$bplan = getSubscription("BPLAN");
$iplan = getSubscription("IPLAN");
$mplan = getSubscription("MPLAN");

$plan_prices=array(
	"BPLAN"=> $bplan['price'],
	"IPLAN"=>$iplan['price'],
	"MPLAN"=>$mplan['price'], 
	"BUSINESSREGISTRATION"=>0,
	);

$plan_sub_price_id = array(
    "BPLAN" => array("stripe" => $bplan['stripe_price_id'], "paypal" => $bplan['paypal_price_id']),
    "IPLAN" => array("stripe" => $iplan['stripe_price_id'], "paypal" => $iplan['paypal_price_id']),
    "MPLAN" => array("stripe" => $mplan['stripe_price_id'], "paypal" => $mplan['paypal_price_id'])
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
$subscription_stripe = $plan_sub_price_id[$plan_type]['stripe'];
$subscription_paypal = $plan_sub_price_id[$plan_type]['paypal'];

if($_REQUEST['key']!='') {
			$db->where('keystr',$_REQUEST['key']);
			$db->where("customer_id",$_SESSION['PRO_USER_ID']);
			$r = $db->getOne("paypal_mapping");		
		if($db->count){
			$auto_id = $r['auto_id'];
		}
		else {
			$auto_id= $db->insert("paypal_mapping",array("keystr"=>$_REQUEST['key'], 'customer_id'=>intval(@$_SESSION['PRO_USER_ID'])));
		}
}

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
    <title>ProAI | Checkout</title>
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
var STRIPE_API_KEY = "<?php echo $stripe_api_key; ?>";
var PAYPAL_PLAN_ID = "<?php echo $plan_sub_price_id[$plan_type]['paypal']; ?>";

var STRIPE_PLAN_ID = "<?php echo $plan_sub_price_id[$plan_type]['stripe']; ?>";
var CUST_ID = "<?php echo $_SESSION['PRO_USER_ID']; ?>";
var AT_ID = "<?php echo $auto_id; ?>";

var SUBSCRIPTION_STRIPE = "<?php echo $subscription_stripe; ?>";
var SUBSCRIPTION_PAYPAL = "<?php echo $subscription_paypal; ?>";
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
							<h3>Please you need to create login with <b>ProAI</b>.</h3>
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
						<a href="<?php echo SITE_URL; ?>/../subscribe-2?plan=<?php echo $_REQUEST['plan']; ?>" class="text-sm font-normal no-underline color-688AA6 font-montserrat"><svg class="mr-2-5" xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none"><path d="M6.50008 12L1 6.49992L6.50008 0.999837" stroke="#688AA6" stroke-width="1.65002" stroke-linecap="round" stroke-linejoin="round"/></svg>Go back to Pricing & Plans</a>
					</div>
					<div class="thank-content_block">
						<div class="thank_title">
							<h1 class="color-2C323A font-bold m-0 font-montserrat text-40">You're Almost There!</h1>
							<p class="color-2C323A md-text-xl font-normal m-0 font-montserrat" style="margin-top: 1.5rem;">In just a few minutes, you'll have a professional business plan ready to impress investors</p>
						</div>
						<div class="subscription_cont py-10 mt-6 mb-10">
							<h6 class="color-2C323A text-base font-semibold mb-3-5 font-montserrat"></h6>
							<ul class="p-0 m-0">
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">• <b>Save 40+ hours of work </b>- get your custom business plan in minutes!</li>
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">•<b> No complex software to learn </b>- your AI-powered business advisor is ready to go</li>
								<li class="color-2C323A text-sm md-text-base font-normal font-montserrat">• Join 100,000 + successful entrepreneurs using ProAI's cutting-edge tools</li>
							</ul>
						</div>
						<div class="iframe_wrap d-flex align-items-center justify-content-center gap-md-4 gap-2 w-100">
							<span><iframe id='gdm-vp-snippet-quotations_8a46120e-476a-42b8-8732-c19238e58ea7' src='https://datainsights-cdn.dm.aws.gartner.com/vp/snippet/8a46120e-476a-42b8-8732-c19238e58ea7' frameborder='0' style='height: 92vw; width: 100%;min-height: 456px; min-width: 500px; max-height: 100%;overflow: hidden;' scrolling='yes' ></iframe></span>
							<span><iframe id='gdm-vp-snippet-quotations_443d2fda-75ed-40f2-b28a-0965c41a85cc' src='https://datainsights-cdn.dm.aws.gartner.com/vp/snippet/443d2fda-75ed-40f2-b28a-0965c41a85cc' frameborder='0' style='height: 92vw; width: 100%;min-height: 456px; min-width: 500px; max-height: 100%;overflow: hidden;' scrolling='yes' ></iframe></span>
						</div>						
					</div>
				</div>
				<?php 
					include_once "checkout_process/checkout_plan.php";
				?>
			</div>
		</div>
	</section>
	
	<div class='check_load'>
		<div class="lds-ripple"><div></div><div></div></div>
	</div>
 
	<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_API_CLIENT_ID; ?>&vault=true" data-namespace="paypal"></script>
	<script src="https://js.stripe.com/v3/"></script>
	<script src="<?php echo SITE_URL; ?>/dashboard/js/jquery-3.6.4.min.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/jquery.validate.js"></script>
	<script src="<?php echo SITE_URL; ?>/assets/js/additional-methods.js"></script>
	<script src="https://proai.co/get-started-v4/dashboard/js/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	
	<script src="<?php echo SITE_URL; ?>/assets/js/checkout.js?<?php echo strtotime('now'); ?>"></script>
    <?php 
     include_once "assets/js/checkout_js.php";
	?>
<template id="showCError">
  <swal-title>
    <h3 style="font-size:16pt; color:#000;">Creating Your Business Industry Report</h3>
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
			<p style="margin-top:15px;">Please wait to review your complete Report.
			<br/>
			Estimated time: 8-10 minutes</p>
		</span>
	</center>
	</span>
  </swal-html>
</template>

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