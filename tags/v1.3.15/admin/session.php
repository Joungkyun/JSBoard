<?php
# $Id: session.php,v 1.10 2009-11-20 14:03:59 oops Exp $
session_start();
$path['type'] = 'admin';
include '../include/variable.ph';
include '../include/print.ph';
include '../include/lang.ph';
# register_globals 옵션의 영향을 받지 않기 위한 함수
if(!$parse_query_str_check) parse_query_str();

include 'include/config.ph';
include 'include/check.ph';

#
# php 4.2.3 상위 버전에서 session 관련 warning이 발생한다.
# 이를 발생하지 않게 하기 위해서 4.2.3보다 버전이 높을 경우
# session.bug_compat_42를 off 한다.
#
# version_compare 함수가 4.1.0 부터 지원하기 때문에 version_compare
# 함수가 지원하지 않는다면, 무시해도 상관 없는 코드가 된다.
#
if (function_exists('version_compare')) {
  if (version_compare(phpversion(), '4.2.3', '>'))
    ini_set ('session.bug_compat_warn', false);
}

switch($mode) {
  case 'login' :
    $login['pass'] = compare_pass($sadmin,$logins,1);
    # 세션 등록
    session_register('login');
    # admn login 상태를 알리기 위한 cookie 설정
    SetCookie('adminsession',$login['pass'],time()+900,'/');
    move_page('./admin.php',0);
    break;
  case 'logout' :
    # 세션을 삭제
    session_unregister('login');
    # admin login 상태를 삭제
    SetCookie('adminsession','',0,'/');
    move_page('./auth.php',0);
    break;
  default :
    move_page('./admin.php',0);
}
?>
