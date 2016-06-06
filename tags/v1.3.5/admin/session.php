<?
session_start();
include "include/config.ph";
include "include/check.ph";

if ($mode == "login") {
  $login[pass] = compare_pass($sadmin,$logins,1);
  # 세션 등록
  session_register("login");
  # admn login 상태를 알리기 위한 cookie 설정
  SetCookie("adminsession","$login[pass]",time()+900,"/");
  header("Location: admin.php");
} else if ($mode == "logout") {
  # 세션을 삭제
  session_unregister("login");
  # admin login 상태를 삭제
  SetCookie("adminsession","",0,"/");
  header("Location: auth.php");
} else if ($mode == "back") {
  header("Location:admin.php");
}

?>
