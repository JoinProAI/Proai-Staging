<?php
include_once "includes/config.php";
$_SESSION['modal']=false;
$login_url = $auth0->login(SITE_URL."/callback");

if(isset($_REQUEST['plan']) && $_REQUEST['plan'] !='undefined' && $_REQUEST['plan'] != NOPLANID ){
	$_SESSION['plan']=$_REQUEST['plan'];

	if(isset($_REQUEST['pitch'])){
		$login_url = $auth0->signup(SITE_URL."/callbackPitch");
	}
	else if(isset($_REQUEST['step']) && $_REQUEST['step']=='mr')
	{

		$login_url = $auth0->signup(SITE_URL."/callbackMr");

	}
	else{
		$login_url = $auth0->signup(SITE_URL."/callback");
	}
	$_SESSION['modal'] = true;
}

	if(isset($_REQUEST['step']) && $_REQUEST['step']=='mr') {
		$login_url = $auth0->signup(SITE_URL."/callbackMr");
	}


if(isset($_SESSION['CHECKOUT_LOGIN']) && $_SESSION['CHECKOUT_LOGIN'] === true) {
	unset($_SESSION['modal']);
}
?>
<html>
<head>
</head>
<body>
<img src="<?php echo SITE_URL; ?>/loading.svg" style="width:120px;position:fixed; height:120px; left:50%; top:50%; transform: translate(-50%, -50%);" />
<?php
ob_flush();
flush();

?>

<script type="text/javascript">
window.location="<?php echo $login_url; ?>";
</script>

</body>
</html>