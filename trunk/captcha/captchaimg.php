<?
# $Id: captchaimg.php,v 1.3 2012-10-11 16:25:39 oops Exp $
require_once ('../config/global.php');
require_once ('./captcha.php');

if ( ! $board['captcha'] )
	exit;

$capt = new Captcha ($board['captcha']);
#$key = $_GET['k'] ? $_GET['k'] : $capt->captchakey ();

if ( $_SERVER['argv'][0] )
	$key = $_SERVER['argv'][0];
else if ( is_numeric ($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] < 1000 ) {
	$key = $_SERVER['QUERY_STRING'];
} else
	$key = $capt->captchakey ();

$capt->print_img_header ();
$capt->create_captcha ($capt->captchadata ($key));
?>
