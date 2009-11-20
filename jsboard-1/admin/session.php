<?php
# $Id: session.php,v 1.10 2009-11-20 14:03:59 oops Exp $
session_start();
$path['type'] = 'admin';
include '../include/variable.ph';
include '../include/print.ph';
include '../include/lang.ph';
# register_globals �ɼ��� ������ ���� �ʱ� ���� �Լ�
if(!$parse_query_str_check) parse_query_str();

include 'include/config.ph';
include 'include/check.ph';

#
# php 4.2.3 ���� �������� session ���� warning�� �߻��Ѵ�.
# �̸� �߻����� �ʰ� �ϱ� ���ؼ� 4.2.3���� ������ ���� ���
# session.bug_compat_42�� off �Ѵ�.
#
# version_compare �Լ��� 4.1.0 ���� �����ϱ� ������ version_compare
# �Լ��� �������� �ʴ´ٸ�, �����ص� ��� ���� �ڵ尡 �ȴ�.
#
if (function_exists('version_compare')) {
  if (version_compare(phpversion(), '4.2.3', '>'))
    ini_set ('session.bug_compat_warn', false);
}

switch($mode) {
  case 'login' :
    $login['pass'] = compare_pass($sadmin,$logins,1);
    # ���� ���
    session_register('login');
    # admn login ���¸� �˸��� ���� cookie ����
    SetCookie('adminsession',$login['pass'],time()+900,'/');
    move_page('./admin.php',0);
    break;
  case 'logout' :
    # ������ ����
    session_unregister('login');
    # admin login ���¸� ����
    SetCookie('adminsession','',0,'/');
    move_page('./auth.php',0);
    break;
  default :
    move_page('./admin.php',0);
}
?>
