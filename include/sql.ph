<?
// SQL 관련 함수들에 대한 프론트 엔드 함수
//
// 비정상적인 동작에 대한 에러 출력 등에 사용됨
// http://www.php.net/manual/ref.mysql.php
// mysql_errno - 바로 전 MySQL 동작에서 생긴 에러 메시지의 번호를 반환
//               http://www.php.net/manual/function.mysql-errno.php
// mysql_error - 바로 전 MySQL 동작에서 생긴 에러 메시지를 반한
//               http://www.php.net/manual/function.mysql-error.php

// mysql_connect - MySQL 서버와 연결을 염
//                 http://www.php.net/manual/function.mysql-connect.php
function sql_connect($server, $user, $pass) {
  $return = mysql_connect($server, $user, $pass);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

// mysql_select_db - MySQL 데이타베이스를 선택함
//                   http://www.php.net/manual/function.mysql-select-db.php
function sql_select_db($name) {
  $return = mysql_select_db($name);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

// mysql_query - MySQL에 SQL 질의를 보냄
//               http://www.php.net/manual/function.mysql-query.php
function sql_query($query) {
  $return = mysql_query($query);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

// mysql_result - 데이타 결과값을 가져옴
//                http://www.php.net/manual/function.mysql-result.php
function sql_result($result, $row, $field) {
  if(mysql_num_rows($result)) $return = mysql_result($result, $row, $field);
  else $return = 0;

  sql_error(mysql_errno(), mysql_error());
  return $return;
}

// mysql_fetch_row - 결과값을 하나씩 배열로 가져옴
//                   http://www.php.net/manual/function.mysql-fetch-row.php
function sql_fetch_row($result) {
    if(mysql_num_rows($result)) $return = mysql_fetch_row($result);
    else $return = 0;

    sql_error(mysql_errno(), mysql_error());
    return $return;
}

// mysql_fetch_array - 결과값을 하나씩 배열로 가져옴
//                    http://www.php.net/manual/function.mysql-fetch-array.php
function sql_fetch_array($result) {
  if(mysql_num_rows($result)) $return = mysql_fetch_array($result);
  else $return = 0;

  sql_error(mysql_errno(), mysql_error());
  return $return;
}

// mysql_num_rows - 결과값의 갯수를 가져옴
//                  http://www.php.net/manual/function.mysql-num-rows.php
function sql_num_rows($result) {
  $return = mysql_num_rows($result);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

// mysql_free_result - 결과값이 저장된 메모리를 비움
//                     http://www.php.net/manual/function.mysql-free-result.php
function sql_free_result($result) {
  $return = mysql_free_result($result);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

// sprintf - 정형화된 문자열을 반환함
//           http://www.php.net/manual/function.sprintf.php
function sql_error($errno, $error) {
  global $langs;
  if($errno) {
    $error = sprintf("$langs[sql_m]<P>\n%s\n", $error);
    print_error($error, 280, 150);
  }
}
?>
