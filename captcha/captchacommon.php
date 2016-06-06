<?
# $Id: captchacommon.php,v 1.5 2009-11-21 16:58:29 oops Exp $
if ( $board['captcha'] ) {
	require_once 'captcha/captcha.php';
	$capt = new Captcha ($board['captcha']);

	if ( $capt->disable === true ) {
		$pcaptcharcolspan = ' colspan=3';
		return;
	}

    if ( $board['super'] || $board['adm'] ) {
		$pcaptcharcolspan = ' colspan=3';
		$capt->disable = true;
		return;
    }

	if ( preg_match ('/reply\.php$/', $_SERVER['PHP_SELF']) )
		$pattern = '/^[25-7]$/';
	else
		$pattern = '/^[2457]$/';

	if ( preg_match ($pattern, $board['mode']) ) {
		$pcaptcharcolspan = ' colspan=3';
		$capt->disable = true;
		return;
	}

	$pcaptcharcolspan = ' style="width: 80px;"';
	$ckey = $capt->captchakey ();
	$captsize = $size['pass'] * 2;

	$pcaptcha = <<<EOF
</td>
<td style="width: 88px;">
<script type="text/javascript">
function captcha_insert() {
	document.getElementById('id_ckeyv').value = "{$capt->captchadata ($ckey)}";
}
</script>
<img src="./captcha/captchaimg.php?{$ckey}" alt="Input {$capt->captchadata($ckey)}" onclick="captcha_insert(); return false;"></td>
<td valign="top">
{$_('captstr')}<br />
<input type="text" size="{$captsize}" name="atc[ckeyv]" id="id_ckeyv" onclick="captcha_insert(); return false;">
<input type="hidden" name="atc[ckey]" value="{$ckey}">
EOF;
}
?>
