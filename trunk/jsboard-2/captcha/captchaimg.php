<?php
# $Id: captchaimg.php,v 1.5 2014-03-02 17:11:30 oops Exp $
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

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim: filetype=php et ts=2 sw=2
 */
?>
