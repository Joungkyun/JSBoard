<?
session_start(); // session을 시작한다.
include_once "../include/print.ph";
parse_query_str();
if ($mode == "login") {
  session_register("mysqlpass"); //세션 등록한다.
  header("Location: install.php?langss=$langss");
} elseif ($mode == "logout") {
  session_unregister("mysqlpass"); // 세션을 삭제한다
  header("Location: auth.php?mode=first&langss=$langss");
} elseif ($mode == "first") {
  session_unregister("mysqlpass"); // 세션을 삭제한다
  $langs[code] = ($langss == "ko") ? "ko" : "en";

  $path[type] = "Install";
  include "../include/lang.ph";
  $str = str_replace("\n","\\n",$langs[first_acc]);
  echo "<script>\n" .
       "  alert('$str')\n" .
       "  document.location='../login.php?type=admin'\n" .
       "</script> ";
  exit;
}
?>
