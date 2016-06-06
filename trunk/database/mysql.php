<?php
#
# MySQL Basic mapping function
# $Id: mysql.php,v 1.5 2014-02-27 10:51:31 oops Exp $
#

$_extname = 'mysql';

function sql_connect ($sock, $user, $pass, $dbname, $mode = 'w') {
  global $db;

  if ($mode == 'r' )
    print_error ("System Checking NOW !! \n\nSorry, Read only enable.", 250, 130, 1);

  $r = @mysql_connect ($sock, $user, $pass);
  sql_error (mysql_errno (), mysql_error ());

  mysql_select_db ($dbname, $r);
  sql_error (mysql_errno (), mysql_error ());

  if ( $db['type'] == 'mysql41' && is_resource ($r) && $db['charset'] )
    sql_query ("set names {$db['charset']}", $r, 1);

  return $r;
}

function sql_query () {
  $_argno = func_num_args ();
  $_arg = func_get_args ();

  $query = $_arg[0];
  $c     = $_arg[1];

  switch ( $_argno ) {
    case 4:
      if ( is_numeric ($_arg[2]) ) {
        $database = $_arg[3];
        $noerr    = $_arg[2];
      } else {
        $database = $_arg[2];
        $noerr    = $_arg[3];
      }
      break;
    case 3:
      if ( is_numeric ($_arg[2]) ) {
        $database = '';
        $noerr    = $_arg[2];
      } else {
        $database = $_arg[2];
        $noerr    = 0;
      }
      break;
    default:
      $database = '';
      $noerr    = 0;
  }

  if ( $database )
    $r = @mysql_db_query ($database, $query, $c);
  else
    $r = @mysql_query ($query, $c);

  if ( ! $noerr )
    sql_error (mysql_errno (), mysql_error());

  return $r;
}

function sql_result ($_r, $row, $field) {
  if ( is_resource ($_r) ) {
    if ( mysql_num_rows ($_r) )
      $r = mysql_result ($_r, $row, $field);

    sql_error (mysql_errno (), mysql_error ());
  } else
    $r = 0;

  return $r;
}

function sql_free_result ($_r) {
  if ( is_resource ($_r) ) {
    $r = @mysql_free_result ($_r);
    sql_error (mysql_errno (), mysql_error ());
  }

  return $r;
}

function sql_num_rows ($_r) {
  if ( is_resource ($_r) ) {
    $r = @mysql_num_rows ($_r);
    sql_error (mysql_errno(), mysql_error ());
  } else $r = 0;

  return $r;
}

function sql_list_dbs ($c) {
  $r = @mysql_list_dbs ($c);
  sql_error (mysql_errno(), mysql_error ());

  return $r;
}

function sql_fetch_object ($_r) {
  if ( is_resource ($_r) ) {
    $r = @mysql_fetch_object ($_r);
    sql_error (mysql_errno (), mysql_error ());
  } else
    $r = 0;

  return $r;
}

function sql_fetch_array ($_r) {
  if ( is_resource ($_r) ) {
    if ( @mysql_num_rows ($_r) )
      $r = mysql_fetch_array ($_r, MYSQL_ASSOC);

    sql_error (mysql_errno (), mysql_error ());
  } else
    $r = 0;

  return $r;
}

function sql_close ($p) {
  if ( is_resource ($p) )
    @mysql_close ($p);
}

function sql_escape ($c, &$v) {
  if ( function_exists ('get_magic_quotes_gpc') && get_magic_quotes_gpc() )
    return;

  if ( is_array ($v) ) {
    foreach ($v as $key => $val) {
      if ( is_array ($val) ) {
        sql_escape ($c, $v[$key]);
        continue;
      }

      if ( ! is_numeric ($val) )
        $v[$key] = mysql_real_escape_string ($val, $c);
    }

    return;
  }

  if ( ! is_numeric ($v) )
    $v = mysql_real_escape_string ($v, $c);
}

function sql_error ($errno, $error) {
  global $_code, $_;

  if ( $errno ) {
    $error = sprintf ("%s\n\n%s\n", $_('sqlmsg'), $error);
    print_error ($error, 280, 150, 1);
  }
}


#
# SQL Usefull mapping function
#

function exists_database ($c, $db) {
  $_r = sql_list_dbs ($c);

  while ( $r = sql_fetch_object ($_r) ) {
    if ( $r->Database == $db )
      return 1;
  }

  return 0;
}

function exists_dbuser ($c, $user ) {
  $_r = sql_query ("SELECT user FROM user WHERE user = '{$user}'", $c, 'mysql');

  if ( sql_num_rows ($_r) > 0 )
    return 1;

  return 0;
}

function db_table_list ($c, $dbname, $t = '', $chk = '') {
  $list = mysql_list_tables ($dbname);

  # total table number
  $list_num = mysql_num_rows ($list);
  $j = ! $j ? '0' : $j;

  for ( $i=0; $i<$list_num; $i++ ) {
    if ( ! $chk ) {
      # table 이름을 구하여 배열에 저장
      $l[$i] = mysql_tablename ($list, $i);

      # 배열에 저장된 이름중 알파벳별 구분 변수가 있으면 소트된
      # 이름만 다시 배열에 저장
      if ( $t ) {
        if ( preg_match ("/^$t/i", $l[$i]) ) {
          $ll[$j] = $l[$i];
          $j++;
        }
      }
    } else {
      $t = '';
      if ( $chk == mysql_tablename ($list, $i) ) {
        $l = 1;
        break;
      } else $l = 0;
    }
  }

  return $t ? $ll : $l;
}

function field_exist_check ($c, $dbname, $t, $compare) {
  $field = @mysql_list_fields ($dbname, $t);
  sql_error (mysql_errno (), mysql_error ());

  $num = @mysql_num_fields ($field);
  sql_error (mysql_errno (), mysql_error ());

  for ($i = 0; $i < $num; $i++) {
    if ( mysql_field_name ($field, $i) == $compare ) {
      return 1;
    }
  }

  return 0;
}

function compatible_limit ($offset, $num) {
  if ( ! $offset ) $offset = 0;
  return "LIMIT {$offset},{$num}";
}

function get_counter ($c, $table, $period, $sql = '') {
  $query = "SELECT COUNT(1/(date > '{$period}')) as T, COUNT(*) as A FROM {$table} {$sql}";
  $_r = sql_query ($query, $c);
  $r = sql_fetch_array ($_r);

  return $r;
}

function table_lock ($c, $table = '', $offset = 0) {
  if ( $offset )
    sql_query ("LOCK TABLES {$table} WRITE", $c);
  else
    sql_query ('UNLOCK TABLES', $c);
}

function get_like ($o = '', $str = '') {
  $r = $o ? 'REGEXP' : 'LIKE';
  if ( $str ) {
    $r .= $o ? " '{$str}'" : " '%{$str}%'";
  }

  return $r;
}

function korean_area ($t) {
  $h = '0x' . bin2hex ($t[0]) . bin2hex($t[1]);

  if ( $h == '0xb0a1' ) $r['like'] = chr(0xb3).chr(0xaa); # Ga - Na
  if ( $h == '0xb3aa' ) $r['like'] = chr(0xb4).chr(0xd9); # Na - Da
  if ( $h == '0xb4d9' ) $r['like'] = chr(0xb6).chr(0xf3); # Da - La
  if ( $h == '0xb6f3' ) $r['like'] = chr(0xb6).chr(0xb6); # La - Ma
  if ( $h == '0xb8b6' ) $r['like'] = chr(0xb9).chr(0xd9); # Ma - Ba
  if ( $h == '0xb9d9' ) $r['like'] = chr(0xbb).chr(0xe7); # Ba - Sa
  if ( $h == '0xbbe7' ) $r['like'] = chr(0xbe).chr(0xc6); # Sa - Aa
  if ( $h == '0xbec6' ) $r['like'] = chr(0xc0).chr(0xda); # Aa - Ja
  if ( $h == '0xc0da' ) $r['like'] = chr(0xc2).chr(0xf7); # Ja - Cha
  if ( $h == '0xc2f7' ) $r['like'] = chr(0xc4).chr(0xab); # Cha - Ka
  if ( $h == '0xc4ab' ) $r['like'] = chr(0xc5).chr(0xb8); # Ka - Ta
  if ( $h == '0xc5b8' ) $r['like'] = chr(0xc6).chr(0xc4); # Ta - Pa
  if ( $h == '0xc6c4' ) $r['like'] = chr(0xc7).chr(0xcf); # Pa - Ha
  if ( $h == '0xc7cf' ) $r['like'] = chr(0xc9).chr(0xfe); # Ha - hih

  $r = "WHERE binary nid BETWEEN binary '$t' AND binary '{$r['like']}'";

  return $r;
}
?>
