<?
session_start();
include "../include/print.ph";
# register_globals 옵션의 영향을 받지 않기 위한 함수
if(!$parse_query_str_check) parse_query_str();

include "include/config.ph";
include "include/check.ph";

if ($mode == "login") {
  $login[pass] = compare_pass($sadmin,$logins,1);
  # 세션 등록
  session_register("login");
  # admn login 상태를 알리기 위한 cookie 설정
  SetCookie("adminsession",$login[pass],time()+900,"/");
  move_page("./admin.php",0);
} else if ($mode == "logout") {
  # 세션을 삭제
  session_unregister("login");
  # admin login 상태를 삭제
  SetCookie("adminsession","",0,"/");
  move_page("./auth.php",0);
} else if ($mode == "back") {
  move_page("./admin.php",0);
}

?>
