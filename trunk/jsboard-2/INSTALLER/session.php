<?
session_start(); // session�� �����Ѵ�.
include_once "../include/print.ph";
parse_query_str();
if ($mode == "login") {
  session_register("mysqlpass"); //���� ����Ѵ�.
  header("Location: install.php?langss=$langss");
} elseif ($mode == "logout") {
  session_unregister("mysqlpass"); // ������ �����Ѵ�
  header("Location: auth.php?mode=first&langss=$langss");
} elseif ($mode == "first") {
  session_unregister("mysqlpass"); // ������ �����Ѵ�
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
