<?
session_start(); // session�� �����Ѵ�.
if ($mode == "login") {
  session_register("mysqlpass"); //���� ����Ѵ�.
  header("Location: install.php?langss=$langss");
} elseif ($mode == "logout") {
  session_destroy("mysqlpass"); // ������ �����Ѵ�
  header("Location: auth.php?mode=first&langss=$langss");
} elseif ($mode == "first") {
  session_destroy("mysqlpass"); // ������ �����Ѵ�
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
