<?
# ����� ���� �Լ�
#
# header    - HTTP ��� ���� (�ƹ� �͵� ��µ��� ���� ���¿��� ���Ǿ�� ��)
#             http://www.php.net/manual/function.header.php
# $PHP_AUTH - ����� ���� ���� PHP ȯ�� ����
#             http://www.php.net/manual/features.http-auth.php
function auth($id = 0, $passwd = 0) {
  global $PHP_AUTH_USER, $PHP_AUTH_PW;
  global $sadmin, $admin; # ������ ���� (config/global.ph)

  if(!$id && !$passwd) {
    $id     = array($sadmin[id], $admin[id]);
    $passwd = array($sadmin[passwd], $admin[passwd]);
  } else {
    $id = array($id);
    $passwd = array($passwd);
  }

  for($i = 0; $i < count($id); $i++) {
    if(($id[$i] && $passwd[$i]) && ($PHP_AUTH_USER == $id[$i] && $PHP_AUTH_PW == $passwd[$i]))
      $auth = 1;
  }

  if(!$auth) {
    header("WWW-Authenticate: Basic realm=\"Admin\"");
    header("HTTP/1.0 401 Unauthorized");
    print_error("���̵�� �н����尡 Ʋ���ϴ�. �ٽ� �õ��Ͻñ� �ٶ��ϴ�.\n", 250, 130);
    exit;
  }
}
?>
