<?
session_start();
include "include/config.ph";
include "include/check.ph";

if ($mode == "login") {
  $login[pass] = compare_pass($sadmin,$logins,1);
  # ���� ���
  session_register("login");
  # admn login ���¸� �˸��� ���� cookie ����
  SetCookie("adminsession","$login[pass]",time()+900,"/");
  header("Location: admin.php");
} else if ($mode == "logout") {
  # ������ ����
  session_unregister("login");
  # admin login ���¸� ����
  SetCookie("adminsession","",0,"/");
  header("Location: auth.php");
} else if ($mode == "back") {
  header("Location:admin.php");
}

?>
