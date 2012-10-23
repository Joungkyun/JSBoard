<?php
#
# PosgreSQL Basic mapping function
# $Id: pgsql.php,v 1.3 2012-10-23 16:12:03 oops Exp $
#

$_extname = 'pgsql';

function sql_connect ($sock, $user, $pass, $dbname, $mode = 'w') {
  global $phperr, $db;

  if ($mode == 'r' )
    print_error ("System Checking NOW !! \n\nSorry, Read only enable.", 250, 130, 1);

  if ( preg_match ('/:/', $sock) ) {
    $_sock = explode (':', $sock);
    $sock  = $_sock[0];
    $_port = " port={$_sock[1]}";
  } else $_port = '';

  $php_errormsg = '';
  $r = @pg_connect ("host={$sock}{$_port} user={$user} password={$pass} dbname={$dbname}");
  $phperr = $php_errormsg;

  //$_err = ( pg_connection_status ($r) !== PGSQL_CONNECTION_OK ) ? 1 : 0;
  sql_error ($php_errormsg);

  if ( is_resource ($r) && $db['charset'] )
    sql_query ("set client_encoding to {$db['charset']}", $r, 1);
	//pg_set_client_encoding ($r, $db['charset']);

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
        $database = '';
        $noerr    = $_arg[2];
      } else {
        $database = '';
        $noerr    = $_arg[3];
      }
      break;
    case 3:
      if ( is_numeric ($_arg[2]) ) {
        $database = '';
        $noerr    = $_arg[2];
      } else {
        $database = '';
        $noerr    = 0;
      }
      break;
    default:
      $database = '';
      $noerr    = 0;
  }

  $r = @pg_query ($c, $query);

  if ( ! $noerr && ! $r )
    sql_error (@pg_last_error ());

  return $r;
}

function sql_result ($_r, $row, $field) {
  if ( is_resource ($_r) ) {
    if ( @pg_num_rows ($_r) ) {
      $rr = @pg_fetch_assoc ($_r, $row);
      $r  = $rr[$field];
    }

    sql_error (@pg_last_error ());
  } else $r = 0;

  return $r;
}

function sql_fetch_array($_r) {
  if ( is_resource ($_r) ) {
    if ( @pg_num_rows ($_r) )
      $r = @pg_fetch_array ($_r);

    sql_error (@pg_last_error ());
  } else $r = 0;

  return $r;
}

function sql_free_result ($_r) {
  if ( is_resource ($_r) ) {
    $r = @pg_free_result($_r);
    sql_error (@pg_last_error ());
  } else $r = 0;

  return $r;
}

function sql_num_rows ($_r) {
  if ( is_resource ($_r) ) {
    $r = @pg_num_rows ($_r);
    sql_error (@pg_last_error ());
  } else $r = 0;

  return $r;
}

function sql_close ($p) {
  if ( is_resource ($p) ) {
    pg_close ($p);
  }
}

function sql_escase ($c, &$v) {
  if ( function_exists ('get_magic_quotes_gpc') && get_magic_quotes_gpc() )
    return;

  $func = ( function_exists ('pg_escape_literal' ) ?
          'pg_escape_literal' : 'pg_escape_string';

  if ( is_array ($v) ) {
    foreach ($v as $key => $val) {
      if ( ! is_numeric ($val) )
        $v[$key] = $func ($c, $val);
    }

    return;
  }

  if ( ! is_numeric ($v) )
    $v = $func ($c, $v);
}

function sql_error ($error) {
  global $_;

  if ( $error ) {
    $error = sprintf("%s\n\n%s\n", $_('sqlmsg'), $error);
    print_error ($error, 280, 150, 1);
  }
}

#
# SQL Usefull mapping function
#

function exists_database ($c, $db) {
  $sql = "SELECT datname FROM pg_database WHERE datname = '{$db}'";
  $_r = sql_query ($sql, $c);

  if ( sql_num_rows ($_r) > 0 )
    return 1;

  return 0;
}

function exists_dbuser ($c, $user) {
  $_r = sql_query ("SELECT usename FROM pg_user WHERE usename = '{$user}'", $c);

  if ( sql_num_rows ($_r) > 0 )
    return 1;

  return 0;
}

function db_table_list ($c, $dbname, $t = '', $chk = '') {
  global $db;

  $sql = "SELECT tablename FROM pg_tables WHERE tableowner = '{$db['user']}'";
  $list = sql_query ($sql, $c);

  # total table number
  $list_num = sql_num_rows ($list);
  $j = ! $j ? '0' : $j;

  for ( $i=0; $i<$list_num; $i++ ) {
    if ( ! $chk ) {
      # table 이름을 구하여 배열에 저장
      list ($l[$i]) = pg_fetch_row ($list, $i);

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
      list ($testtablename) = pg_fetch_row ($list, $i);
      if ( $chk == $testtablename ) {
        $l = 1;
        break;
      } else $l = 0;
    }
  }

  return $t ? $ll : $l;
}

function field_exist_check ($c, $dbname, $t, $compare) {
  $sql = "SELECT a.attname FROM pg_attribute a, pg_class b" .
         " WHERE b.relname = '{$t}' AND a.attrelid = b.oid" .
         "   AND a.attname = '{$compare}'";

  return sql_num_rows (sql_query ($sql, $c));
}

function compatible_limit ($offset, $num) {
  if ( ! $offset )
    return "LIMIT $num";

  return "OFFSET $offset LIMIT $num";
}

function get_counter ($c, $table, $period, $sql = '') {
  $query = "SELECT count(*) as A FROM {$table} {$sql}";
  $_r = sql_query ($query, $c);
  $r['A'] = sql_result ($_r, 0, 'A');
  sql_free_result ($_r);

  $_sql = $sql ? "$sql AND " : 'WHERE ';
  $_sql .= "date > '{$period}'";
  $query = "SELECT count(*) as A FROM {$table} {$_sql}";

  $_r = sql_query ($query, $c);
  $r['T'] = sql_result ($_r, 0, 'T');
  sql_free_result ($_r);

  return $r;
}

function table_lock ($c, $table, $offset = 0) {
  return 0;
}

function get_like ($o = '', $str = '') {
  $r = '~*';
  if ( $str ) {
    $r .= " '{$str}'";
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

  $r = "WHERE nid BETWEEN '$t' AND '{$r['like']}'";

  return $r;
}
?>
