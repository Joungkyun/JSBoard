<?php
# $Id: admin_head.php,v 1.4 2009-11-19 05:29:50 oops Exp $
$sadmin['pern']   = 10;
if($path['type'] == "user_admin") {
  $dpath = "..";
  $ipath = "../..";
} else {
  $dpath = ".";
  $ipath = "..";
}

set_magic_quotes_runtime(0);
ini_set(magic_quotes_gpc,1);
ini_set(magic_quotes_sybase,0);

include_once "$ipath/include/variable.php";
include_once "$ipath/include/error.php";
include_once "$ipath/include/check.php";
include_once "$ipath/include/get.php";
include_once "$ipath/include/print.php";
include_once "$ipath/include/sql.php";
if(!check_windows())
  { include_once "$ipath/include/exec.php"; }
include_once "$dpath/include/check.php";
include_once "$dpath/include/first_reg.php";

# GET/POST ������ ����
parse_query_str();

$agent = get_agent();

# table �̸��� üũ�Ѵ�.
if($path['type'] == "user_admin") table_name_check($table);

if(!@file_exists("$ipath/config/global.php")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include_once "$ipath/config/global.php"; }

# session�� ����
session_start();
if(!session_is_registered("$jsboard")) session_destroy();

if($path['type'] == "user_admin" && $table) {
  if(file_exists("$ipath/data/$table/config.php"))
    { include_once "$ipath/data/$table/config.php"; }
}

# �ܺ� ȸ�� DB �� ����� ��� ���� ���� include
if(file_exists("$ipath/config/external.php")) { include_once "$ipath/config/external.php"; }

# �̸��� �ּ� ���� üũ
$rmail['chars'] = !$rmail['chars'] ? "__at__" : $rmail['chars'];

table_name_check($print['theme']);
include_once "$ipath/theme/{$print['theme']}/config.php";
include_once "$ipath/include/lang.php";
include_once "$ipath/include/replicate.php";
include_once "$dpath/include/print.php";

# ������ ����
if (session_is_registered("$jsboard")) {
  if($_SESSION[$jsboard]['pos'] == 1) $board['super'] = 1;
  if(strstr($board['ad'],";")) {
    if(preg_match("/{$_SESSION[$jsboard]['id']};|;{$_SESSION[$jsboard]['id']}/",$board['ad'])) $board['adm'] = 1;
  } else {
    if(preg_match("/^{$_SESSION[$jsboard]['id']}$/",$board['ad'])) $board['adm'] = 1;
  }
}

$db = replication_mode($db);
?>
