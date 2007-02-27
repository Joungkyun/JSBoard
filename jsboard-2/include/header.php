<?php
if(preg_match("/(write|edit|reply|read)\.php/i",$_SERVER['PHP_SELF']))
  session_cache_limiter('nocache');

# config of magic quotes
set_magic_quotes_runtime(0); 
ini_set(magic_quotes_gpc,1);
ini_set(magic_quotes_sybase,0);
ini_set(precision,15);

# table 변수 체크
$table = trim ($table);
if ( preg_match ('!/\.+|%00$!', $table) ) {
  echo "<script>\nalert('Ugly access with table variable with \'{$table}\'');\n" .
       "history.back();\nexit;\n</script>\n";
  exit;
}

include_once "include/error.php";
include_once "include/print.php";
# GET/POST 변수를 제어
parse_query_str();

if ( ! @file_exists("config/global.php") ) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
  exit;
} else { include_once "config/global.php"; }

session_start();
if(!session_is_registered("$jsboard") && !preg_match("/session\.php/i",$_SERVER['PHP_SELF']))
  session_destroy();

##############################################################################
#  이 정보들은 건들지 말도록 한다!!!!!
##############################################################################
if(trim($table)) {
  if(@file_exists("data/$table/config.php") && $board['uconf'])
    { @include_once "data/$table/config.php"; }

  if(@file_exists("data/$table/stylesheet.php")) {
    @include_once"data/$table/stylesheet.php";
    if($user_stylesheet) {
       $user_stylesheet = preg_replace("/<[\/]*STYLE[^>]*>/i","",$user_stylesheet);
       $user_stylesheet = "<!-- ======================= User define stylesheet ======================= -->\n".
                          "$user_stylesheet\n".
                          "<!-- ======================= User define stylesheet ======================= -->\n";
    }
  }

  # 게시판 관리자가 null 일 경우를 대비하여 null 일때 admin 으로 강제 설정
  $board['ad'] = !$board['ad'] ? "admin" : $board['ad'];

  # theme 의 설정 파일을 호출
  if(!$path['type']) {
    include_once "./theme/{$print['theme']}/config.php";
  }
} else include_once "theme/{$print['theme']}/config.php";

if(file_exists("./config/external.php")) { 
  unset($edb);
  include_once "./config/external.php";
}

include_once "include/version.php";
include_once "include/lang.php";
include_once "include/check.php";
if(!check_windows()) { include_once "include/exec.php"; }
include_once "include/get.php";
include_once "include/list.php";
include_once "include/parse.php";
include_once "include/sql.php";
include_once "include/replicate.php";
include_once "include/sendmail.php";

$agent = get_agent();
$db = replication_mode($db);

if(!ini_get("file_uploads") || $agent['tx']) $noup = 1;

if(preg_match("/(act|write|edit|reply)\.php/i",$_SERVER['PHP_SELF']))
  $upload['maxsize'] = get_upload_value($upload);

# 외부 hyper link 를 막기 위한 설정
check_dhyper($board['usedhyper'],$board['endhyper'],$board['dhyper'],$enable['dhyper'],$enable['plink']);
check_access($board['useipbl'],$board['ipbl'],$enable['ipbl']);

# write, edit, reply page form size ========================
$size['name'] = !$size['name'] ? form_size(14) : form_size($size['name']);
$size['pass'] = !$size['pass'] ? form_size(4) : form_size($size['pass']);
$size['titl'] = !$size['titl'] ? form_size(25) : form_size($size['titl']);
$size['text'] = !$size['text'] ? form_size(30) : form_size($size['text']);
$size['uplo'] = !$size['uplo'] ? form_size(19) : form_size($size['uplo']);

# table 이 없거나 meta character 존재 유무 체크
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

# 이메일 주소 변형 체크
$rmail['chars'] = !$rmail['chars'] ? "__at__" : $rmail['chars'];

# 라이센스 출력 관련 설정
$gpl_link = "http://jsboard.kldp.org/ext.php?l=gpl2_en";
switch ($designer['license']) {
  case '0' :
    $designer['license'] = " And follow <A HREF=$gpl_link TARGET=_blank>GPL2</A>";
    break;
  case '1' :
    $designer['license'] = " All right reserved";
    break;
}

# 관리자 정보
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

# login button 출력
if(session_is_registered("$jsboard")) {
  if(@file_exists("./theme/{$print['theme']}/img/logout.gif"))
    $print['lout'] = "<IMG SRC=./theme/{$print['theme']}/img/logout.gif BORDER=0 ALT='LOGOUT'>";
  else $print['lout'] = "<FONT STYLE=\"font:12px tahoma;color:{$color['text']}\">&gt;&gt; logout </FONT>";

  $print['lout'] = "<A HREF=./session.php?m=logout&table=$table TITLE='LOGOUT'>{$print['lout']}</A>";
}
?>
