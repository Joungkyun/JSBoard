<?
# SQL ���� �Լ��鿡 ���� ����Ʈ ���� �Լ�
#
# ���������� ���ۿ� ���� ���� ��� � ����
# http://www.php.net/manual/ref.mysql.php
# mysql_errno - �ٷ� �� MySQL ���ۿ��� ���� ���� �޽����� ��ȣ�� ��ȯ
#               http://www.php.net/manual/function.mysql-errno.php
# mysql_error - �ٷ� �� MySQL ���ۿ��� ���� ���� �޽����� ����
#               http://www.php.net/manual/function.mysql-error.php

# mysql_connect - MySQL ������ ������ ��
#                 http://www.php.net/manual/function.mysql-connect.php
function sql_connect($server,$user,$pass,$mode='w') {
  if($mode == "r") print_error("System Checking NOW !! \n\nSorry, Read only enable.",250,130,1);
  $return = @mysql_connect($server,$user,$pass);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

# mysql_select_db - MySQL ����Ÿ���̽��� ������
#                   http://www.php.net/manual/function.mysql-select-db.php
function sql_select_db($name,$c='') {
  $return = $c ? mysql_select_db($name,$c) : mysql_select_db($name);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

# mysql_query - MySQL�� SQL ���Ǹ� ����
#               http://www.php.net/manual/function.mysql-query.php
function sql_query($query,$c='',$noerr='') {
  $return = $c ? mysql_query($query,$c) : mysql_query($query);
  if(!$noerr) sql_error(mysql_errno(), mysql_error());

  return $return;
}

# mysql_db_query - MySQL�� Database �� ������ SQL ���Ǹ� ����
#               http://www.php.net/manual/function.mysql-db-query.php
function sql_db_query($db,$query,$c='',$noerr='') {
  $return = $c ? mysql_db_query($db,$query,$c) : mysql_db_query($db,$query);
  if(!$noerr) sql_error(mysql_errno(), mysql_error());

  return $return;
}

# mysql_result - ����Ÿ ������� ������
#                http://www.php.net/manual/function.mysql-result.php
function sql_result($result,$row,$field) {
  if(mysql_num_rows($result)) $return = mysql_result($result,$row,$field);
  else $return = 0;

  sql_error(mysql_errno(), mysql_error());
  return $return;
}

# mysql_fetch_row - ������� �ϳ��� �迭�� ������
#                   http://www.php.net/manual/function.mysql-fetch-row.php
function sql_fetch_row($result) {
    if(mysql_num_rows($result)) $return = mysql_fetch_row($result);
    else $return = 0;

    sql_error(mysql_errno(), mysql_error());
    return $return;
}

# mysql_fetch_array - ������� �ϳ��� �迭�� ������
#                    http://www.php.net/manual/function.mysql-fetch-array.php
function sql_fetch_array($result) {
  if(mysql_num_rows($result)) $return = mysql_fetch_array($result);
  else $return = 0;

  sql_error(mysql_errno(), mysql_error());
  return $return;
}

# mysql_num_rows - ������� ������ ������
#                  http://www.php.net/manual/function.mysql-num-rows.php
function sql_num_rows($result) {
  $return = mysql_num_rows($result);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

# mysql_free_result - ������� ����� �޸𸮸� ���
#                     http://www.php.net/manual/function.mysql-free-result.php
function sql_free_result($result) {
  $return = mysql_free_result($result);
  sql_error(mysql_errno(), mysql_error());

  return $return;
}

# sprintf - ����ȭ�� ���ڿ��� ��ȯ��
#           http://www.php.net/manual/function.sprintf.php
function sql_error($errno,$error) {
  global $langs;
  if($errno) {
    $error = sprintf("{$langs['sql_m']}\n\n%s\n",$error);
    print_error($error,280,150,1);
  }
}
?>
