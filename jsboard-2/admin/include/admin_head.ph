<?
# session을 시작
session_start();
if(!session_is_registered("$jsboard")) session_destroy();
$sadmin[pern]   = 10;

if($path[type] == "user_admin") {
  $dpath = "..";
  $ipath = "../..";
} else {
  $dpath = ".";
  $ipath = "..";
}

include_once "$ipath/include/error.ph";
include_once "$ipath/include/check.ph";
include_once "$ipath/include/get.ph";
include_once "$ipath/include/print.ph";
include_once "$ipath/include/sql.ph";
if(!check_windows())
  { include_once "$ipath/include/exec.ph"; }
include_once "$dpath/include/check.ph";
include_once "$dpath/include/first_reg.ph";

# GET/POST 변수를 제어
parse_query_str();

$agent = get_agent();

# table 이름을 체크한다.
if($path[type] == "user_admin") table_name_check($table);

if(!@file_exists("$ipath/config/global.ph")) {
  echo"<script>alert('Don\'t exist Global configuration file')\n" .
      "history.back()</script>";
  die;
} else { include_once "$ipath/config/global.ph"; }

if($path[type] == "user_admin" && $table) {
  if(file_exists("$ipath/data/$table/config.ph"))
    { include_once "$ipath/data/$table/config.ph"; }
}

# 외부 회원 DB 를 사용할 경우 설정 파일 include
if(file_exists("$ipath/config/external.ph")) { include_once "$ipath/config/external.ph"; }

# 이메일 주소 변형 체크
$rmail[chars] = !$rmail[chars] ? "__at__" : $rmail[chars];

table_name_check($print[theme]);
include_once "$ipath/theme/$print[theme]/config.ph";
include_once "$ipath/include/lang.ph";
include_once "$ipath/include/replicate.ph";
include_once "$dpath/include/print.ph";

$db = replication_mode($db);
?>
