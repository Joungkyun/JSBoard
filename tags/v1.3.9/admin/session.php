<?
session_start();
include "../include/print.ph";
# register_globals �ɼ��� ������ ���� �ʱ� ���� �Լ�
if(!$parse_query_str_check) parse_query_str();

include "include/config.ph";
include "include/check.ph";

if ($mode == "login") {
  $login[pass] = compare_pass($sadmin,$logins,1);
  # ���� ���
  session_register("login");
  # admn login ���¸� �˸��� ���� cookie ����
  SetCookie("adminsession",$login[pass],time()+900,"/");
  move_page("./admin.php",0);
} else if ($mode == "logout") {
  # ������ ����
  session_unregister("login");
  # admin login ���¸� ����
  SetCookie("adminsession","",0,"/");
  move_page("./auth.php",0);
} else if ($mode == "back") {
  move_page("./admin.php",0);
}

?>
