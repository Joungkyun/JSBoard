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

  if ($langss == "ko") $langs[code] = "ko";
  else $langs[code] = "en";

  $path[type] = "Install";
  include("../include/lang.ph");
  echo("<script>\n" .
       "  alert('$langs[first_acc]')\n" .
       "  document.location='../admin/index.php'\n" .
       "</script> ");
  exit;
}
?>