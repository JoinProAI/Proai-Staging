<?php
	include_once "includes/config.php";
	extract($_REQUEST);
	$MODE = "cancelstripepayment";
?>

<!DOCTYPE HTML>
<html>
<head prefix="og: http://ogp.me/ns#">
    <meta content=IE=edge http-equiv=X-UA-Compatible>
    <meta charset="utf-8">
    <meta content="noindex,nofollow" name=robots>
    <meta content=yes name=apple-mobile-web-app-capable>
    <meta content=black name=apple-mobile-web-app-status-bar-style>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" name="viewport">
    <link href="<?php echo SITE_URL; ?>/assets/image/favicon.png" rel=icon type="image/x-icon" />
    <title>Pro AI | Pro Business Plans</title>
    <meta content="Voted the number one pitch deck experts; work with a consultant your can trust." name="description">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
    <link href="<?php echo SITE_URL; ?>/assets/image/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">
    <meta content="#ffffff" name="msapplication-TileColor">
    <meta content="en_US" property="og:locale">
    <meta content="Voted the number one pitch deck experts; work with a consultant your can trust." property="og:description">
    <meta content="og-logo.png" property="og:image">
    <meta content="article" property="og:type">
    <meta content="Pro AI | Pro Business Plans" property="og:site_name">
    <meta content="telephone_no" name="format-detection">
    <meta content="address_no" name="format-detection">

	<link href="<?php echo SITE_URL; ?>/assets/css/extra.css?<?php strtotime('now'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/bootnavbar.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/nice-select.css">
    <link href="<?php echo SITE_URL; ?>/assets/css/planbuilder_style.css" rel="stylesheet">
  	<link href="<?php echo SITE_URL; ?>/assets/css/sweetalert2.min.css" rel="stylesheet" />
  	<link href="<?php echo SITE_URL; ?>/assets/css/thankyou.css?<?php strtotime('now'); ?>" rel="stylesheet">
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

<!-- Google tag (gtag.js) -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WV9XB6G');</script>
<!-- End Google Tag Manager -->

<script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o), m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-46044477-1', 'auto');
    ga('send', 'pageview');

</script>

</head>

<body class=no-js>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WV9XB6G"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!--BODYJS-->
    <div class="dashboard_body">

        <section class="header_section">
            <div class="container justify-content-center">
                <div class="logo_box">
                    <a href="./"><img src="<?php echo SITE_URL; ?>/assets/image/logo-proai.png" alt=""></a>
                </div>                
            </div>
        </section>

        <section class="thankyou_wrapper">            
            <div class="thankyou_block">
                <h1>Sorry!</h1>
                <p>Your payment has been cancelled.</p>
            </div>                    
        </section>

        <section class="footer">
            <div class="container">
                <div class="footer_main">
                    <div class="footer_right">
                        <p>&copy; <?php echo date("Y"); ?> Pro Business Plans&#8482;| ProAI. All Rights Reserved.</p>
                    </div>
                    <div class="footer_navmenu">
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/contact-us">Contact Us</a></li>
                            <li><a href="#">Help</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script src="<?php echo SITE_URL; ?>/assets/js/sweetalert.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery_form.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/bootnavbar.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jquery.nice-select.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/jqueryui.js"></script>    

</body>
</html>
