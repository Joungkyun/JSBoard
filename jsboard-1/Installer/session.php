<?
session_start(); // session을 시작한다.
if ($mode == "login") {
  session_register("mysqlpass"); //세션 등록한다.
  header("Location: install.php?langss=$langss");
} elseif ($mode == "logout") {
  session_destroy("mysqlpass"); // 세션을 삭제한다
  header("Location: auth.php?mode=first&langss=$langss");
} elseif ($mode == "first") {
  session_destroy("mysqlpass"); // 세션을 삭제한다

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
