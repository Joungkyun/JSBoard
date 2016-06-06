<?php
if(preg_match("/(write|edit|reply|read)\.php/i",$_SERVER['PHP_SELF']))
  session_cache_limiter('nocache, must-revalidate');

# config of magic quotes
set_magic_quotes_runtime(0); 
ini_set(magic_quotes_gpc,1);
ini_set(magic_quotes_sybase,0);
ini_set(precision,15);

include_once "include/error.ph";
include_once "include/print.ph";
# GET/POST ������ ����
parse_query_str();

if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include_once "config/global.ph"; }

session_start();
if(!session_is_registered("$jsboard") && !preg_match("/session\.php/i",$_SERVER['PHP_SELF']))
  session_destroy();

##############################################################################
#  �� �������� �ǵ��� ������ �Ѵ�!!!!!
##############################################################################
if(trim($table)) {
  if(@file_exists("data/$table/config.ph") && $board['uconf'])
    { @include_once "data/$table/config.ph"; }

  if(@file_exists("data/$table/stylesheet.ph")) {
    @include_once"data/$table/stylesheet.ph";
    if($user_stylesheet) {
       $user_stylesheet = preg_replace("/<[\/]*STYLE[^>]*>/i","",$user_stylesheet);
       $user_stylesheet = "<!-- ======================= User define stylesheet ======================= -->\n".
                          "$user_stylesheet\n".
                          "<!-- ======================= User define stylesheet ======================= -->\n";
    }
  }

  # �Խ��� �����ڰ� null �� ��츦 ����Ͽ� null �϶� admin ���� ���� ����
  $board['ad'] = !$board['ad'] ? "admin" : $board['ad'];

  # theme �� ���� ������ ȣ��
  if(!$path['type']) {
    include_once "./theme/{$print['theme']}/config.ph";
  }
} else include_once "theme/{$print['theme']}/config.ph";

if(file_exists("./config/external.ph")) { 
  unset($edb);
  include_once "./config/external.ph";
}

include_once "include/version.ph";
include_once "include/lang.ph";
include_once "include/check.ph";
if(!check_windows()) { include_once "include/exec.ph"; }
include_once "include/get.ph";
include_once "include/list.ph";
include_once "include/parse.ph";
include_once "include/sql.ph";
include_once "include/replicate.ph";
include_once "include/sendmail.ph";

$agent = get_agent();
$db = replication_mode($db);

if(!ini_get("file_uploads") || $agent['br'] == "LYNX") $noup = 1;

if(preg_match("/(act|write|edit|reply)\.php/i",$_SERVER['PHP_SELF']))
  $upload['maxsize'] = get_upload_value($upload);

if(preg_match("/links|w3m|lynx/i",$agent['br'])) $textBrowser = 1;

# �ܺ� hyper link �� ���� ���� ����
check_dhyper($board['usedhyper'],$board['endhyper'],$board['dhyper'],$enable['dhyper'],$enable['plink']);
check_access($board['useipbl'],$board['ipbl'],$enable['ipbl']);

# write, edit, reply page form size ========================
$size['name'] = !$size['name'] ? form_size(14) : form_size($size['name']);
$size['pass'] = !$size['pass'] ? form_size(4) : form_size($size['pass']);
$size['titl'] = !$size['titl'] ? form_size(25) : form_size($size['titl']);
$size['text'] = !$size['text'] ? form_size(30) : form_size($size['text']);
$size['uplo'] = !$size['uplo'] ? form_size(19) : form_size($size['uplo']);

# table �� ���ų� meta character ���� ���� üũ
if(!preg_match("/(user|session|regist|error|image)\.php/i",$_SERVER['PHP_SELF'])) {
  if($dn['tb']) $table = $dn['tb'];
  meta_char_check($table,0,1);
  meta_char_check($print['theme'],0,1);
}

if ($upload['yesno'] && $cupload['yesno']) $colspan = "7";
else $colspan = "6";

if(strtoupper($color['bgcol']) == strtoupper($color['l4_bg']) && preg_match("/list\.php/i",$_SERVER['PHP_SELF'])) {
  $form_border = "1x";
} elseif(strtoupper($color['bgcol']) == strtoupper($color['r5_bg']) && preg_match("/read\.php/i",$_SERVER['PHP_SELF'])) {
  $form_border = "1x";
} else $form_border = "2x";

# �̸��� �ּ� ���� üũ
$rmail['chars'] = !$rmail['chars'] ? "__at__" : $rmail['chars'];

# ���̼��� ��� ���� ����
$gpl_link = "http://jsboard.kldp.org/ext.php?l=gpl2_en";
switch ($designer['license']) {
  case '0' :
    $designer['license'] = " And follow <A HREF=$gpl_link TARGET=_blank>GPL2</A>";
    break;
  case '1' :
    $designer['license'] = " All right reserved";
    break;
}

# ������ ����
if (session_is_registered("$jsboard")) {
  if($_SESSION[$jsboard]['pos'] == 1) $board['super'] = 1;
  if(strstr($board['ad'],";")) {
    if(preg_match("/{$_SESSION[$jsboard]['id']};|;{$_SESSION[$jsboard]['id']}/",$board['ad'])) $board['adm'] = 1;
  } else {
    if(preg_match("/^{$_SESSION[$jsboard]['id']}$/",$board['ad'])) $board['adm'] = 1;
  }
}

if(preg_match("/(read|list)\.php/i",$_SERVER['PHP_SELF'])) {
  if($theme['ver'] != $designer['ver']) print_error($langs['nomatch_theme'],250,150,1);
}

# login button ���
if(session_is_registered("$jsboard")) {
  if(@file_exists("./theme/{$print['theme']}/img/logout.gif"))
    $print['lout'] = "<IMG SRC=./theme/{$print['theme']}/img/logout.gif BORDER=0 ALT='LOGOUT'>";
  else $print['lout'] = "<FONT STYLE=\"font:12px tahoma;color:{$color['text']}\">&gt;&gt; logout </FONT>";

  $print['lout'] = "<A HREF=./session.php?m=logout&table=$table TITLE='LOGOUT'>{$print['lout']}</A>";
}
?>
