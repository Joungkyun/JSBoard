<?php
# $Id: admin_head.php,v 1.8 2014/03/02 17:11:30 oops Exp $
$sadmin['pern']   = 10;
if($path['type'] == "user_admin") {
  $dpath = "..";
  $ipath = "../..";
} else {
  $dpath = ".";
  $ipath = "..";
}

if(version_compare(PHP_VERSION,'5.4.0','<')) {
  set_magic_quotes_runtime(0);
  ini_set(magic_quotes_gpc,1);
  ini_set(magic_quotes_sybase,0);
}

include_once "{$ipath}/include/variable.php";
include_once "{$ipath}/include/error.php";
include_once "{$ipath}/include/check.php";
include_once "{$ipath}/include/get.php";
include_once "{$ipath}/include/print.php";
if(!check_windows())
  { include_once "{$ipath}/include/exec.php"; }
include_once "{$dpath}/include/check.php";
include_once "{$dpath}/include/first_reg.php";

# GET/POST 변수를 제어
parse_query_str();

$agent = get_agent();

# table 이름을 체크한다.
if($path['type'] == "user_admin") table_name_check($table);

if(!@file_exists("{$ipath}/config/global.php")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include_once "{$ipath}/config/global.php"; }

$sqlfunc = extension_loaded('mysqli') ? 'sqli' : 'sql';
include_once "{$ipath}/include/{$sqlfunc}.php";

# session을 시작
session_start();

if($path['type'] == "user_admin" && $table) {
  if(file_exists("{$ipath}/data/{$table}/config.php"))
    { include_once "{$ipath}/data/{$table}/config.php"; }
}

# 외부 회원 DB 를 사용할 경우 설정 파일 include
if(file_exists("$ipath/config/external.php")) { include_once "{$ipath}/config/external.php"; }

# 이메일 주소 변형 체크
$rmail['chars'] = !$rmail['chars'] ? "__at__" : $rmail['chars'];

table_name_check($print['theme']);
include_once "{$ipath}/theme/{$print['theme']}/config.php";
include_once "{$ipath}/include/lang.php";
include_once "{$ipath}/include/replicate.php";
include_once "{$dpath}/include/print.php";

# 관리자 정보
if (isset($_SESSION[$jsboard])) {
  if($_SESSION[$jsboard]['pos'] == 1) $board['super'] = 1;
  if(strstr($board['ad'],";")) {
    if(preg_match("/{$_SESSION[$jsboard]['id']};|;{$_SESSION[$jsboard]['id']}/",$board['ad'])) $board['adm'] = 1;
  } else {
    if(preg_match("/^{$_SESSION[$jsboard]['id']}$/",$board['ad'])) $board['adm'] = 1;
  }
}

$db = replication_mode($db);

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
