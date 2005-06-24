<?php
$path['type'] = "admin";
require_once "./include/admin_head.php";

htmlhead ();
# session 이 등록되어 있지 않으면 로그인 화면으로.
if ( ! session_is_registered ($jsboard) || $_SESSION[$jsboard]['pos'] != 1 )
  print_error ($_('login_err'));

# input 문의 size를 browser별로 맞추기 위한 설정
$size = form_size (9);
$textsize = form_size (40);

$configfile = "../config/global.php";
$spamlistfile = "../config/spam_list.txt";

# global 설정 가져오기
$global_con = readfile_r ($configfile);
$global_con = preg_replace ("/<\?|\?>/i","",$global_con);

# spam list 가져오기
if ( file_exists ($spamlistfile) ) $spamlist = readfile_r ($spamlistfile);
else $spamlist = "spam_list.txt is not found into jsboard/config";

$global_con = trim ($global_con);
$spamlist = trim ($spamlist);
?>

<br>
<form name="global_chg" method="post" action="act.php">
<table border=0 cellpadding=2 cellspacing=1 width="100%">
<tr><td class="gbtitle">

<table border=0 cellpadding=1 cellspacing=1 width="100%">
<tr><td class="gbtitle">Global Configuration</td></tr>
<tr><td class="gbbackground">
<textarea name="glob[vars]" rows=25 cols="<?=$textsize?>"><?=$global_con?></textarea>
</td></tr>

<tr><td class="gbtitle">SPAMER LIST</td></tr>
<tr><td class="gbbackground">&nbsp;
<div class="spamcomment"><?=$_('spamer_m')?></div>
<center><textarea name="glob[spam]" rows=10 cols="<?=$textsize?>"><?=$spamlist?></textarea></center>
</td></tr>

<tr><td align="center">
<input type="submit" value="<?=$_('b_sm')?>">
<input type="reset" value="<?=$_('b_reset')?>">
<input type="hidden" name="mode" value="global_chg">
</td></tr>
</table>

</td></tr>
</table>
</form>

<?  htmltail (); ?>
