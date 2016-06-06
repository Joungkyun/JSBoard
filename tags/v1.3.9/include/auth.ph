<?
# 사용자 인증 함수
#
# header    - HTTP 헤더 전송 (아무 것도 출력되지 않은 상태에서 사용되어야 함)
#             http://www.php.net/manual/function.header.php
# $PHP_AUTH - 사용자 인증 관련 PHP 환경 변수
#             http://www.php.net/manual/features.http-auth.php
function auth($id = 0, $passwd = 0) {
  global $PHP_AUTH_USER, $PHP_AUTH_PW;
  global $sadmin, $admin; # 관리자 설정 (config/global.ph)

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
    print_error("아이디와 패스워드가 틀립니다. 다시 시도하시기 바랍니다.\n", 250, 130);
    exit;
  }
}
?>
