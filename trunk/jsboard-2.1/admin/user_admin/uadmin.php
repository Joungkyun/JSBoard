<?php
# $Id: uadmin.php,v 1.4 2009-11-16 21:52:46 oops Exp $
$path['type'] = 'user_admin';
include "../include/admin_head.php";

if( ! session_is_registered ($jsboard) || (!$board['adm'] && $board['super'] != 1) )
  print_error($_('login_err'));

$dsize = form_size (7);
$lsize = form_size (24);

if($_code == 'ko') $tsize = form_size (36);
else $tsize = form_size (38);

$ssize = form_size (3);
$user = strtoupper ($table);

if ( $board['super'] == 1 )
  $board['adput'] = "<input type=\"text\" name=\"ua[ad]\" size=\"$dsize\" value=\"{$board['ad']}\">";
else
  $board['adput'] = "{$board['ad']}\n<input type=\"hidden\" name=\"ua[ad]\" value=\"{$board['ad']}\">";

# SELECT check 분류
if ( ! $board['mode'] ) $smode0 = ' selected="selected"';
else if ( $board['mode'] == 1 ) $smode1 = ' selected="selected"';
else if ( $board['mode'] == 2 ) $smode2 = ' selected="selected"';
else if ( $board['mode'] == 3 ) $smode3 = ' selected="selected"';
else if ( $board['mode'] == 4 ) $smode4 = ' selected="selected"';
else if ( $board['mode'] == 5 ) $smode5 = ' selected="selected"';
else if ( $board['mode'] == 6 ) $smode6 = ' selected="selected"';
else if ( $board['mode'] == 7 ) $smode7 = ' selected="selected"';

# check of logout page
$print['dopage'] = trim ($print['dopage']) ? $print['dopage'] : "{$board['path']}list.php?table=$table";

# Radio Box check 분류
$ore_no = $ore_ok = '';
if ( $enable['ore'] ) $ore_no = 'checked="checked"';
else $ore_ok = 'checked="checked"';

$re_list_ok = $re_list_no = '';
if ( $enable['re_list'] ) $re_list_ok = 'checked="checked"';
else $re_list_no = 'checked="checked"';

$comment_ok = $comment_no = '';
if ( $enable['comment'] ) $comment_ok = 'checked="checked"';
else $comment_no = 'checked="checked"';

$emoticon_ok = $emoticon_no = '';
if ( $enable['emoticon'] ) $emoticon_ok = 'checked="checked"';
else $emoticon_no = 'checked="checked"';

if ( ! trim ($enable['tag']) )
  $htmltag = 'b,i,u,ul,ol,li,span,font,table,tr,td';
else
  $htmltag = $enable['tag'];

$pview_ok = $pview_no = '';
if ( $enable['pre'] ) $pview_ok = 'checked="checked"';
else $pview_no = 'checked="checked"';

$dhost_ok = $dhost_no = '';
if( $enable['dhost'] ) $dhost_ok = 'checked="checked"';
else $dhost_no = 'checked="checked"';

$dlook_ok = $dlook_no = '';
if( $enable['dlook'] ) $dlook_ok = 'checked="checked"';
else $dlook_no = 'checked="checked"';

$dwho_ok = $dwho_no = '';
if( $enable['dwho'] ) $dwho_ok = 'checked="checked"';
else $dwho_no = 'checked="checked"';

$upload_disable = '';
if( ! $upload['yesno'] ) $upload_disable = " disabled";

$up_ok = $up_no = '';
if( $cupload['yesno'] ) $up_ok = 'checked="checked"';
else $up_no = 'checked="checked"';

$uplink_ok = $uplink_no = '';
if ( $cupload['dnlink'] ) $uplink_ok = 'checked="checked"';
else $uplink_no = 'checked="checked"';

$amail_ok = $amail_no = '';
if( $rmail['admin'] ) $amail_ok = 'checked="checked"';
else $amail_no = 'checked="checked"';

$rss_use_ok = $rss['use'] ? 'checked' : '';
$rss_use_no = ! $rss['use'] ? 'checked' : '';

$rss_des_ok = $rss['is_des'] ? 'checked' : '';
$rss_des_no = ! $rss['is_des'] ? 'checked' : '';

$rss_align_ok = $rss['align'] ? 'checked' : '';
$rss_align_no = ! $rss['align'] ? 'checked' : '';

$umail_ok = $umail_no = '';
if ( $rmail['user'] ) $umail_ok = 'checked="checked"';
else $umail_no = 'checked="checked"';

$url_ok = $url_no = '';
if ( $view['url'] ) $url_ok = 'checked="checked"';
else $url_no = 'checked="checked"';

$vmail_ok = $vmail_no = '';
if ( $view['email'] ) $vmail_ok = 'checked="checked"';
else $vmail_no = 'checked="checked"';

$dhyper_no = $dhyper_ok = '';
if ( $enable['dhyper'] ) $dhyper_no = 'checked="checked"';
else $dhyper_ok = 'checked="checked"';

$align_l = $align_r = $align_c = '';
if ( $board['align'] == "left" ) $align_l = 'checked="checked"';
elseif ( $board['align'] == "right" ) $align_r = 'checked="checked"';
else $align_c = 'checked="checked"';

$nameck_r = $nameck_n = '';
if ( $board['rnname'] ) $nameck_r = 'checked="checked"';
else $nameck_n = 'checked="checked"';

$ipbl = trim ($enable['ipbl']) ? parse_ipvalue ($enable['ipbl'], 1) : $_('ua_dhyper3');
if ( ! $board['useipbl'] ) {
  $ipbl = "Prevent this function by super user!\n".
          "If you want to this function, config \"\$board['useipbl'] = 1;\" in global.php";
  $ipbllinkro = " disabled";
}

$denylink = trim ($enable['plink']) ? parse_ipvalue ($enable['plink'], 1) : $_('ua_dhyper3');
if(!$board['usedhyper']) {
  $denylink = "Prevent this function by super user!\n".
              "If you want to this function, config \"\$board['usedhyer'] = 1;\" in global.php";
  $denylinkro = " disabled";
}

$board['hls'] = preg_replace ('/<FONT COLOR=/i', '', $board['hl']);
$board['hls'] = preg_replace ('/><B><U>STR<\/U><\/B><\/FONT>/i', '', $board['hls']);

# html header의 정보를 가져 온다
$top_head = readfile_r ("../../html/head.php");

$top_head = htmlspecialchars ($top_head);
$top_head = str_replace ("&lt;? echo ","",$top_head);
$top_head = preg_replace ("/ \?&gt;(;|\}|&lt;|&quot;| -)/i","\\1",$top_head);
$top_head = str_replace ("\$table",$table,$top_head);
$top_head = str_replace ("\$version",$version,$top_head);
$top_head = str_replace ("\$_('charset')", $_('charset'), $top_head);
$top_head = str_replace ("  ", "&nbsp;&nbsp;", $top_head);
$top_head = nl2br ($top_head);
$top_head = trim ($top_head);

$html_head = readfile_r ("../../data/$table/html_head.php");

# html tail의 정보를 가져온다
$html_tail = readfile_r ("../../data/$table/html_tail.php");

if($agent['tx']) {
  $html_head = str_replace('<', '&lt;', $html_head);
  $html_head = str_replace('>', '&gt;', $html_head);
  $html_tail = str_replace('<', '&lt;', $html_tail);
  $html_tail = str_replace('>', '&gt;', $html_tail);
}

$bottom_tail = readfile_r ('../../html/tail.php');
$bottom_tail = preg_replace("/<\?(.*)\?>/i", '', $bottom_tail);
$bottom_tail = trim ($bottom_tail);
$bottom_tail = htmlspecialchars ($bottom_tail);
$bottom_tail = nl2br ($bottom_tail);

# 사용자 정의 styel sheet
if ( file_exists ("../../data/$table/stylesheet.php") ) {
  include "../../data/$table/stylesheet.php";
}

if($agent['tx']) {
  $ipbl_button = $dlin_button = $styl_button = $head_button = $tail_button = $noti_button = "&nbsp;";
} else {
  $ipbl_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'i');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'i');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'i');\" title=\"Up Down\">";
  $dlin_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'d');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'d');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'d');\" title=\"Up Down\">";
  $styl_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'s');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'s');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'s');\" title=\"Up Down\">";
  $head_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'h');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'h');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'h');\" title=\"Up Down\">";
  $tail_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'t');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'t');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'t');\" title=\"Up Down\">";
  $noti_button = "<input type=\"button\" value=\"&#9655;\" onClick=\"fresize(1,'n');\" title=\"Left Right\">".
                 "<input type=\"button\" value=\"&#9635;\" onClick=\"fresize(0,'n');\" title=\"RESET\">".
                 "<input type=\"button\" value=\"&#9661;\" onClick=\"fresize(2,'n');\" title=\"Up Down\">";
}

htmlhead ();
?>

<script type="text/javascript">
<!--
function fresize(value,name) {
  if(name == 'h') {
    if (value == 0) {
      document.uadmin.uaheader.cols = <?=$tsize?>;
      document.uadmin.uaheader.rows = 10;
    }
    if (value == 1) document.uadmin.uaheader.cols += 5;
    if (value == 2) document.uadmin.uaheader.rows += 5;
  } else if (name == 't') {
    if (value == 0) {
      document.uadmin.uatail.cols = <?=$tsize?>;
      document.uadmin.uatail.rows = 10;
    }
    if (value == 1) document.uadmin.uatail.cols += 5;
    if (value == 2) document.uadmin.uatail.rows += 5;
  } else if (name == 's') {
    if (value == 0) {
      document.uadmin.uastyle.cols = <?=$tsize?>;
      document.uadmin.uastyle.rows = 5;
    }
    if (value == 1) document.uadmin.uastyle.cols += 5;
    if (value == 2) document.uadmin.uastyle.rows += 5;
  } else if (name == 'd') {
    if (value == 0) {
      document.uadmin.denylink.cols = <?=$tsize?>;
      document.uadmin.denylink.rows = 5;
    }
    if (value == 1) document.uadmin.denylink.cols += 5;
    if (value == 2) document.uadmin.denylink.rows += 5;
  } else if (name == 'i') {
    if (value == 0) {
      document.uadmin.ipbl.cols = <?=$tsize?>;
      document.uadmin.ipbl.rows = 5;
    }
    if (value == 1) document.uadmin.ipbl.cols += 5;
    if (value == 2) document.uadmin.ipbl.rows += 5;
  } else if (name == 'n') {
    if (value == 0) {
      document.uadmin.noti.cols = <?=$tsize?>;
      document.uadmin.noti.rows = 5;
    }
    if (value == 1) document.uadmin.noti.cols += 5;
    if (value == 2) document.uadmin.noti.rows += 5;
  } else {
    document.uadmin.uaheader.cols = <?=$tsize?>;
    document.uadmin.uaheader.rows = 10;
    document.uadmin.uatail.cols = <?=$tsize?>;
    document.uadmin.uatail.rows = 10;
  }
}
// -->
</script>

<table width=600 border=0 cellpadding=0 cellspacing=1 align="<?=$board['align']?>">
<tr><td>

<br><br>
<div class="usertitle"><?=$user?> User Configuration</div>

<!-- ======================= Main ======================= -->

<br><form method="post" name="uadmin" action="act.php">
<table width="90%" border=0 cellpadding=3 cellspacing=1 align="center">
<tr><td align="center" colspan=6 class="tcolor">Administartion Information</td></tr>

<tr>
<td width="17%" class="mcolor"><?=$_('ua_ad')?></td>
<td width="25%"><?=$board['adput']?></td>
<td width="8%" class="dcolor">&nbsp;</td>
<td width="17%" class="mcolor">Permission</td>
<td width="25%">
<select name="ua[mode]">
<option value="0"<?=$smode0?>>No Login</option>
<option value="2"<?=$smode2?>>Login mode</option>
<option value="1"<?=$smode1?>>Admin Only (N)</option>
<option value="3"<?=$smode3?>>Admin Only (L)</option>
<option value="4"<?=$smode4?>>Read,Reply Only (N)</option>
<option value="5"<?=$smode5?>>Read,Reply Only (L)</option>
<option value="6"<?=$smode6?>>Reply Only Admin(N)</option>
<option value="7"<?=$smode7?>>Reply Only Admin(L)</option>
</select>
</td>
<td width="8%" class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_pname')?></td>
<td colspan=4>
<?=$_('ua_namemt1')?>
<input type="radio" name="ua[rnname]" <?=$nameck_r?> value="1"><?=$_('ua_realname')?>
<input type="radio" name="ua[rnname]" <?=$nameck_n?> value="0"><?=$_('ua_nickname')?>
<?=$_('ua_namemt2')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">Logout Page</td>
<td colspan=4>
<input type="text" size="<?=$lsize?>" name="ua[dopage]" value="<?=$print['dopage']?>">
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Board Basic Configuration</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_b1')?></td>
<td colspan=4><input type="text" name="ua[title]" size=<?=$lsize?> value="<?=$board['title']?>"></td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">Theme</td>
<td colspan=4>
<select name="ua[theme_c]">

<? get_theme_list($path['type'],$print['theme']); ?>

</select>
</td>
<td class="dcolor">&nbsp;</td>
</tr

<tr>
<td class="mcolor"><?=$_('ua_align')?></td>
<td colspan=4>
<input type="radio" name="ua[align]" <?=$align_c?> value="center"><?=$_('ua_align_c')?>
<input type="radio" name="ua[align]" <?=$align_l?> value="left"><?=$_('ua_align_l')?>
<input type="radio" name="ua[align]" <?=$align_r?> value="right"><?=$_('ua_align_r')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_b21')?></td>
<td colspan=4><?=$_('ua_b22')?> 
<input type="text" name="ua[wwrap]" size=<?=$ssize?> value="<?=$board['wwrap']?>">
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_b5')?></td>
<td><input type="text" name="ua[width]" size=<?=$dsize?> value="<?=$board['width']?>"></td>
<td class="dcolor"><?=$_('ua_b6')?></td>
<td class="mcolor"><?=$_('ua_b7')?></td>
<td><input type="text" name="ua[tit_l]" size=<?=$dsize?> value="<?=$board['tit_l']?>"></td>
<td class="dcolor"><?=$_('ua_b8')?></td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_b9')?></td>
<td><input type="text" name="ua[nam_l]" size=<?=$dsize?> value="<?=$board['nam_l']?>"></td>
<td class="dcolor"><?=$_('ua_b8')?></td>
<td class="mcolor"><?=$_('ua_b10')?></td>
<td><input type="text" name="ua[perno]" size=<?=$dsize?> value="<?=$board['perno']?>"></td>
<td class="dcolor"><?=$_('ua_b11')?></td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_b12')?></td>
<td><input type="text" name="ua[plist]" size=<?=$ssize?> value="<?=$board['plist']?>">*2+1</td>
<td class="dcolor"><?=$_('ua_b11')?></td>
<td class="mcolor"><?=$_('ua_b13')?></td>
<td><input type="text" name="ua[cookie]" size=<?=$dsize?> value="<?=$board['cookie']?>"></td>
<td class="dcolor"><?=$_('ua_b14')?></td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Width Of Form Size</td></tr>

<tr>
<td class="mcolor">NAME</td>
<td><input type="text" name="ua[s_name]" size=<?=$dsize?> value="<?=$size['name']?>"></td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor">SUBMIT</td>
<td><input type="text" name="ua[s_pass]" size=<?=$dsize?> value="<?=$size['pass']?>"></td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">TITLE</td>
<td><input type="text" name="ua[s_titl]" size=<?=$dsize?> value="<?=$size['titl']?>"></td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor">TEXT</td>
<td><input type="text" name="ua[s_text]" size=<?=$dsize?> value="<?=$size['text']?>"></td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">UPLOAD</td>
<td><input type="text" name="ua[s_uplo]" size=<?=$dsize?> value="<?=$size['uplo']?>"></td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor">&nbsp;</td>
<td>&nbsp;</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Print Option</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_ore')?></td>
<td colspan=4>
<input type="radio" name="ua[ore]" <?=$ore_ok?> value="0"><?=$_('ua_ore_y')?>
<input type="radio" name="ua[ore]" <?=$ore_no?> value="1"><?=$_('ua_ore_n')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_re_list')?></td>
<td colspan=4>
<input type="radio" name="ua[re_list]" <?=$re_list_ok?> value="1"><?=$_('ua_re_list_y')?>
<input type="radio" name="ua[re_list]" <?=$re_list_no?> value="0"><?=$_('ua_re_list_n')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_comment')?></td>
<td colspan=4>
<input type="radio" name="ua[comment]" <?=$comment_ok?> value="1"><?=$_('ua_comment_y')?>
<input type="radio" name="ua[comment]" <?=$comment_no?> value="0"><?=$_('ua_comment_n')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_emoticon')?></td>
<td colspan=4>
<input type="radio" name="ua[emoticon]" <?=$emoticon_ok?> value="1"><?=$_('ua_emoticon_y')?>
<input type="radio" name="ua[emoticon]" <?=$emoticon_no?> value="0"><?=$_('ua_emoticon_n')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Allow HTML Code</td></tr>

<tr>
<td colspan=6>
<?=$_('ua_html_tag')?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="ua[tag]" cols=<?=$tsize?> rows=5><?=$htmltag?></textarea>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Article Preview Check</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_pr')?></td>
<td align="center">
<input type="radio" name="ua[pre]" <?=$pview_ok?> value="1"><?=$_('ua_p')?>
<input type="radio" name="ua[pre]" <?=$pview_no?> value="0"><?=$_('ua_n')?>
</td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor"><?=$_('ua_pren')?></td>
<td align="center">
<input type="text" name="ua[pren]" size=<?=$dsize?> value="<?=$enable['preren']?>">
</td>
<td class="dcolor"><?=$_('ua_b8')?></td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Host Address Configuration</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_ha1')?></td>
<td colspan=4> <?=$_('ua_ha2')?> [
<input type="radio" name="ua[dhost]" <?=$dhost_ok?> value=1> <?=$_('ua_ha3')?>
<input type="radio" name="ua[dhost]" <?=$dhost_no?> value=0> <?=$_('ua_ha4')?>]
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_ha5')?></td>
<td colspan=4><?=$_('ua_ha6')?> [
<input type="radio" name="ua[dlook]" <?=$dlook_ok?> value=1> <?=$_('ua_ha7')?>
<input type="radio" name="ua[dlook]" <?=$dlook_no?> value=0> <?=$_('ua_ha8')?>
]
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_ha9')?></td>
<td colspan=4><?=$_('ua_ha10')?> [
<input type="radio" name="ua[dwho]" <?=$dwho_ok?> value=1> yes
<input type="radio" name="ua[dwho]" <?=$dwho_no?> value=0> no
]
</td>
<td class="dcolor">&nbsp;</td>
</tr>


<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">File Upload Configuration</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_fp')?></td>
<td colspan=4>
<input type="radio"<?=$upload_disable?> name="ua[upload]" <?=$up_ok?> value=1><?=$_('ua_p')?>
<input type="radio"<?=$upload_disable?> name="ua[upload]" <?=$up_no?> value=0><?=$_('ua_n')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_fl')?></td>
<td colspan=4>
<input type="radio"<?=$upload_disable?> name="ua[uplink]" <?=$uplink_ok?> value=1><?=$_('ua_fld')?>
<input type="radio"<?=$upload_disable?> name="ua[uplink]" <?=$uplink_no?> value=0><?=$_('ua_flh')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Mail Configuration</td></tr>

<tr>
<td class="mcolor">Admin</td>
<td align="center">

<?
if ($rmail['uses']) {
  echo "<input type=\"radio\" name=\"ua[admin]\" $amail_ok value=1 class=\"radio_c\">" . $_('ua_mail_p') . "\n".
       "<input type=\"radio\" name=\"ua[admin]\" $amail_no value=0 class=\"radio_c\">" . $_('ua_mail_n') . "\n";
} else {
  echo "( )" . $_('ua_mail_p') . "\n".
       "<input type=\"radio\" name=\"ua[admin]\" checked value=0 class=\"radio_c\">" . $_('ua_mail_n') . "\n";
}
?>

</td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor">User</td>
<td align="center">

<?
if ($rmail['uses']) {
  echo "<input type=\"radio\" name=\"ua[user]\" $umail_ok value=1 class=\"radio_c\">" . $_('ua_mail_p') . "\n".
       "<input type=\"radio\" name=\"ua[user]\" $umail_no value=0 class=\"radio_c\">" . $_('ua_mail_n') . "\n";
} else {
  echo "( )" . $_('ua_mail_p') . "\n".
       "<input type=\"radio\" name=\"ua[user]\" checked value=0 class=\"radio_c\">" . $_('ua_mail_n') . "\n";
}
?>


</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">E-mail</td>
<td colspan=4>

<?
if ($rmail['uses']) echo "<input type=\"text\" name=\"ua[toadmin]\" size=$lsize value=\"{$rmail['toadmin']}\">";
else echo "<center><font color=\"red\"><b>" . $_('ua_while_wn') . "</b></font></center>";
?>

</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">RSS Configuration</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_rs_u')?></td>
<td align="center">
<input type="radio" name="ua[rss_use]" <?=$rss_use_ok?> value=1><?=$_('ua_rs_ok')?>
<input type="radio" name="ua[rss_use]" <?=$rss_use_no?> value=0><?=$_('ua_rs_no')?>
</td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor"><?=$_('ua_rs_de')?></td>
<td align="center">
<input type="radio" name="ua[rs_is_des]" <?=$rss_des_ok?> value=1><?=$_('ua_rs_ok')?>
<input type="radio" name="ua[rs_is_des]" <?=$rss_des_no?> value=0><?=$_('ua_rs_no')?>
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_rs_ln')?></td>
<td align="center">
<input type="radio" name="ua[rss_align]" <?=$rss_align_no?> value=0><?=$_('ua_rs_lf')?>
<input type="radio" name="ua[rss_align]" <?=$rss_align_ok?> value=1><?=$_('ua_rs_rg')?>
</td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor">&nbsp;</td>
<td>&nbsp;</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_rs_na')?></td>
<td colspan=4>

<input type="text" name="ua[rss_channel]" size=<?=$lsize?> value="<?=$rss['channel']?>">

</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Etc Configuration</td></tr>

<tr>
<td class="mcolor"><?=$_('ua_etc1')?></td>
<td align="center">
<input type="radio" name="ua[url]" <?=$url_ok?> value=1>yes
<input type="radio" name="ua[url]" <?=$url_no?> value=0>no
</td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor"><?=$_('ua_etc2')?></td>
<td align="center">
<input type="radio" name="ua[email]" <?=$vmail_ok?> value=1>yes
<input type="radio" name="ua[email]" <?=$vmail_no?> value=0>no
</td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor"><?=$_('ua_etc3')?></td>
<td><input type="text" name="ua[d_name]" size=<?=$dsize?> value="<?=$ccompare['name']?>"></td>
<td class="dcolor">&nbsp;</td>
<td class="mcolor"><?=$_('ua_etc4')?></td>
<td><input type="text" name="ua[d_email]" size=<?=$dsize?> value="<?=$ccompare['email']?>"></td>
<td class="dcolor">&nbsp;</td>
</tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Blocking IP Address</td></tr>

<tr>
<td colspan=6 class="tarea">
TEXTAREA SIZE OPERATION
<?=$ipbl_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="ipbl" cols=<?=$tsize.$ipbllinkro?> rows=5><?=$ipbl?></textarea>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Deny Invalid Hyper Link</td></tr>

<tr>
<td colspan=3>
<?=$_('ua_dhyper')?>
<input type="radio" name="ua[dhyper]" <?=$dhyper_ok?> value="0"><?=$_('ua_dhyper1')."\n"?>
<input type="radio" name="ua[dhyper]" <?=$dhyper_no?> value="1"><?=$_('ua_dhyper2')?>
</td>
<td colspan=3 class="tarea">
TEXTAREA SIZE OPERATION
<?=$dlin_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="denylink" cols=<?=$tsize.$denylinkro?> rows=5><?=$denylink?></textarea>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">Board Notice</td></tr>

<tr>
<td class="mcolor">Subject</td>
<td colspan=4>
<input type="text" name="ua[notices]" size=55 value="<?=$notice['subject']?>">
</td>
<td class="mcolor">&nbsp;</td>
</tr>

<tr>
<td class="mcolor">Contents</td>
<td colspan=5 class="tarea">
TEXTAREA SIZE OPERATION
<?=$noti_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="noti" cols=<?=$tsize?> rows=5><?=$notice['contents']?></textarea>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>

<tr><td align="center" colspan=6 class="tcolor">HTML Header/Tail</td></tr>

<tr><td colspan=6  class="mcolor">
&lt;HTML&gt;<br>
&lt;HEAD&gt;<br>
&lt;TITLE&gt; JSBoard blar blah blah.. :-) &lt;/TITLE&gt;<br>
&lt;STYLE&gt;<br>
&lt;!-- ======================= User define stylesheet ======================= --&gt;<br>
</td></tr>

<tr>
<td colspan=3 align="left">
<font style="font-weight:bold">[ USER DEFINED STYLESHEET ]</font>
</td>
<td colspan=3 class="tarea">
TEXTAREA SIZE OPERATION
<?=$styl_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="uastyle" cols=<?=$tsize?> rows=5><?=$user_stylesheet?></textarea>
</td></tr>

<tr><td colspan=6 class="mcolor">
&lt;!-- ======================= User define stylesheet ======================= --&gt;<br>
&lt;/STYLE&gt;<br>
&lt;/HEAD&gt;<br>
&lt;BODY&gt;

</td></tr>

<tr>
<td colspan=6 class="tarea">
TEXTAREA SIZE OPERATION
<?=$head_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="uaheader" cols=<?=$tsize?> rows=10><?=$html_head?></textarea>
</td></tr>

<tr><td colspan=6 align="center" class="mcolor">
<?=$top_head?>
<br><br><br>
[ <?=$_('ua_etc5')?> ]
<br><br><br>
<?=$bottom_tail?>
</td></tr>

<tr>
<td colspan=6 class="tarea">
TEXTAREA SIZE OPERATION
<?=$tail_button?>
</td></tr>

<tr><td align="center" colspan=6>
<textarea name="uatail" cols=<?=$tsize?> rows=10><?=$html_tail?></textarea>
</td></tr>

<tr><td colspan=6 class="mcolor">
<br><br>
&lt;/BODY&gt;<br>
&lt;/HTML&gt;<br>
</td></tr>

<tr><td colspan=6 class="bg">&nbsp;</td></tr>


<tr><td align="center" colspan=6 class="tcolor">
<input type="hidden" name="table" value="<?=$table?>">
<input type="hidden" name="passwd" value="<?=$passwd?>">
<input type="submit" value="<?=$_('b_sm')?>">
<input type="reset" value="<?=$_('b_reset')?>" onClick="fresize()">
</td></tr>

<tr><td align="center" colspan=6 class="tcolor">
Scripted by <a href="http://jsboard.kldp.net" target="_blank">JSBoard Open Project</a><br>
and all rights follow GPL2 License
</td></tr>

</table>
</form>
<!-- ======================= Main ======================= -->

<br>

</td></tr>
</table>

<?
htmltail();
?>
