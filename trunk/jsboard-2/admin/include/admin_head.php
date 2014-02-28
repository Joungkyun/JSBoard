<?php
# $Id: admin_head.php,v 1.7 2014-02-28 21:37:17 oops Exp $
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

# GET/POST ������ ����
parse_query_str();

$agent = get_agent();

# table �̸��� üũ�Ѵ�.
if($path['type'] == "user_admin") table_name_check($table);

if(!@file_exists("{$ipath}/config/global.php")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include_once "{$ipath}/config/global.php"; }

$sqlfunc = extension_loaded('mysqli') ? 'sqli' : 'sql';
include_once "{$ipath}/include/{$sqlfunc}.php";

# session�� ����
session_start();

if($path['type'] == "user_admin" && $table) {
  if(file_exists("{$ipath}/data/{$table}/config.php"))
    { include_once "{$ipath}/data/{$table}/config.php"; }
}

# �ܺ� ȸ�� DB �� ����� ��� ���� ���� include
if(file_exists("$ipath/config/external.php")) { include_once "{$ipath}/config/external.php"; }

# �̸��� �ּ� ���� üũ
$rmail['chars'] = !$rmail['chars'] ? "__at__" : $rmail['chars'];

table_name_check($print['theme']);
include_once "{$ipath}/theme/{$print['theme']}/config.php";
include_once "{$ipath}/include/lang.php";
include_once "{$ipath}/include/replicate.php";
include_once "{$dpath}/include/print.php";

# ������ ����
if (isset($_SESSION[$jsboard])) {
  if($_SESSION[$jsboard]['pos'] == 1) $board['super'] = 1;
  if(strstr($board['ad'],";")) {
    if(preg_match("/{$_SESSION[$jsboard]['id']};|;{$_SESSION[$jsboard]['id']}/",$board['ad'])) $board['adm'] = 1;
  } else {
    if(preg_match("/^{$_SESSION[$jsboard]['id']}$/",$board['ad'])) $board['adm'] = 1;
  }
}

$db = replication_mode($db);
?>
