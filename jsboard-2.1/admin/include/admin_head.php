<?php
$_pself = $_SERVER['PHP_SELF'];
$sadmin['pern']   = 10;
if($path['type'] == "user_admin") {
  $dpath = "..";
  $ipath = "../..";
} else {
  $dpath = ".";
  $ipath = "..";
}

set_magic_quotes_runtime(0);
ini_set('magic_quotes_gpc', 1);
ini_set('magic_quotes_sybase', 0);
ini_set ('track_errors', 1);

$_ = '_lang';

include_once "$ipath/include/error.php";
include_once "$ipath/include/check.php";
include_once "$ipath/include/get.php";
include_once "$ipath/include/print.php";
if ( ! check_windows () ) {
  include_once "$ipath/include/exec.php";
}

# GET/POST 변수를 제어
parse_query_str();

$agent = get_agent();

if ( ! @file_exists ("{$ipath}/config/global.php") ) {
  echo "<script type=\"text/javascript\">alert('Don\'t exist Global configuration file')\n" .
       "history.back()<\/script>";
  die;
} else { include_once "{$ipath}/config/global.php"; }

if ( $db['type'] == 'sqlite' ) {
  $db['server'] = '.' . $db['server'];
  if ( $path['type'] == 'user_admin' )
    $db['server'] = '../' . $db['server'];
}

require_once "{$ipath}/theme/{$print['theme']}/config.php";
putenv ("JSLANG={$_code}");
require_once ("{$dpath}/language/lang.php");
require_once ("{$ipath}/database/db.php");
require_once "$dpath/include/lib.php";
table_name_check ($print['theme']);

# session을 시작
sessionInit($ipath . '/' . $board['sessTmp']);
session_start ();
if( ! session_is_registered ($jsboard) )
  session_destroy();

# table 이름을 체크한다.
if ( $path['type'] == "user_admin" || $table )
  table_name_check ($table);

if ( $path['type'] == "user_admin" && $table ) {
  if ( file_exists ("{$ipath}/data/{$table}/config.php") ) {
    require_once "{$ipath}/data/{$table}/config.php";
    require_once "{$ipath}/theme/{$print['theme']}/config.php";
    putenv ("JSLANG={$_code}");
    require_once "{$ipath}/language/{$_code}.lang";
  }
}

# 외부 회원 DB 를 사용할 경우 설정 파일 include
if ( file_exists ("{$ipath}/config/external.php") ) {
  require_once "$ipath/config/external.php";
}

table_name_check ($print['theme']);
require_once "{$ipath}/include/replicate.php";
require_once "{$dpath}/include/print.php";
require_once "$dpath/include/first_reg.php";

# 관리자 정보
if ( session_is_registered ($jsboard) ) {
  if( $_SESSION[$jsboard]['pos'] == 1 )
    $board['super'] = 1;

  if ( strstr ($board['ad'],";") ) {
    if ( preg_match ("/{$_SESSION[$jsboard]['id']};|;{$_SESSION[$jsboard]['id']}/",$board['ad']) )
      $board['adm'] = 1;
  } else {
    if ( preg_match ("/^{$_SESSION[$jsboard]['id']}$/",$board['ad']) )
      $board['adm'] = 1;
  }
}

$db = replication_mode ($db);
?>
