<?
# session을 시작
session_start();

# Copyright 정보
$copy[name]     = "JoungKyun Kim";
$copy[email]    = "";
$copy[url]      = "http://www.oops.org";
$copy[version]  = "4.0";
$sadmin[pern]   = 10;

if($path[type] == "user_admin") {
  $dpath = "..";
  $ipath = "../..";
} else {
  $dpath = ".";
  $ipath = "..";
}

include "$ipath/include/error.ph";
include "$ipath/include/check.ph";
include "$ipath/include/get.ph";
include "$ipath/include/print.ph";
include "$ipath/include/sql.ph";
include "$ipath/include/exec.ph";
include "$dpath/include/check.ph";
include "$dpath/include/first_reg.ph";

$agent = get_agent();

# table 이름을 체크한다.
if($path[type] == "user_admin") table_name_check($table);

if(!@file_exists("$ipath/config/global.ph")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include "$ipath/config/global.ph"; }

if($path[type] == "user_admin" && $table) {
  if(file_exists("$ipath/data/$table/config.ph"))
    { include "$ipath/data/$table/config.ph"; }
}

# 이메일 주소 변형 체크
$rmail[chars] = !$rmail[chars] ? "__at__" : $rmail[chars];

table_name_check($print[theme]);
include "$ipath/theme/$print[theme]/config.ph";
include "$ipath/include/lang.ph";
include "$ipath/include/replicate.ph";
include "$dpath/include/print.ph";

$db = replication_mode($db);
?>
