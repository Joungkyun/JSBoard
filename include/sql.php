<?php
# MySQL extension API
# $Id: sql.php,v 1.8 2014/03/02 17:11:32 oops Exp $
#

// {{{ +-- public sql_connect($server,$user,$pass,$mode='w')
function sql_connect($server,$user,$pass,$mode='w') {
  if ($mode == "r")
    print_error("System Checking NOW !! \n\nSorry, Read only enable.",250,130,1);

  $return = @mysql_connect($server,$user,$pass);
  sql_error(mysql_errno($return), mysql_error($return));

  sql_query('SET NAMES utf8', $return);

  return $return;
}
// }}}

// {{{ +-- public sql_select_db($name,$c)
function sql_select_db($name,$c) {
  $return = mysql_select_db($name,$c);
  sql_error(mysql_errno($c), mysql_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_query($query,$c,$noerr=null)
function sql_query($query,$c,$noerr=null) {
  $return = mysql_query($query,$c);
  if(!$noerr) sql_error(mysql_errno($c), mysql_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_result($result,$row,$field,$c)
function sql_result($result,$row,$field,$c) {
  if(mysql_num_rows($result)) $return = mysql_result($result,$row,$field);
  else $return = 0;

  sql_error(mysql_errno($c), mysql_error($c));
  return $return;
}
// }}}

// {{{ +-- public sql_fetch_row($result,$c)
function sql_fetch_row($result,$c) {
  if(mysql_num_rows($result)) $return = mysql_fetch_row($result);
  else $return = 0;

  sql_error(mysql_errno($c), mysql_error($c));
  return $return;
}
// }}}

// {{{ +-- public sql_fetch_array($result,$c)
function sql_fetch_array($result,$c) {
  if(mysql_num_rows($result)) $return = mysql_fetch_array($result);
  else $return = 0;

  sql_error(mysql_errno($c), mysql_error($c));
  return $return;
}
// }}}

// {{{ +-- public sql_num_rows($result,$c)
function sql_num_rows($result,$c) {
  $return = mysql_num_rows($result);
  sql_error(mysql_errno($c), mysql_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_affected_rows($c)
function sql_affected_rows($c) {
  $return = mysql_affected_rows($c);
  sql_error(mysql_errno($c), mysql_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_free_result($result,$c)
function sql_free_result($result,$c) {
  $return = mysql_free_result($result);
  sql_error(mysql_errno($c), mysql_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_escape(&$v, $c)
function sql_escape(&$v, $c) {
  if(version_compare(PHP_VERSION,'5.4.0','<')) {
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
      return;
  }

  if (!is_array($v)) {
    $v = @mysql_real_escape_string($v, $c);
    return;
  }

  foreach ($v as $key => $val) {
    if ( is_array ($val) ) {
      sql_escape ($v[$key],$c);
      continue;
    }

    if (!is_numeric($val))
      $v[$key] = @mysql_real_escape_string ($val, $c);
  }
}
// }}}

// {{{ +-- public sql_close ($c)
function sql_close ($c) {
  if (is_resource($c))
    mysql_close($c);
}
// }}}

// {{{ +-- public sql_error($errno,$error)
function sql_error($errno,$error) {
  global $langs;
  if($errno) {
    $error = sprintf("{$langs['sql_m']}\n\n%s\n",$error);
    print_error($error,280,150,1);
  }
}
// }}}

// {{{ +-- public db_table_list ($c, $dbname, $prefix = '', $table = '')
function db_table_list ($c, $dbname, $prefix = '', $table = '') {
  $list = mysql_list_tables ($dbname, $c);

  # total table number
  $list_num = mysql_num_rows ($list);
  $j = ! $j ? '0' : $j;

  for ( $i=0; $i<$list_num; $i++ ) {
    if ( ! $table ) {
      # table 이름을 구하여 배열에 저장
      $l[$i] = mysql_tablename ($list, $i);

      # 배열에 저장된 이름중 알파벳별 구분 변수가 있으면 소트된
      # 이름만 다시 배열에 저장
      if ( $prefix ) {
        if ( preg_match ("/^{$prefix}/i", $l[$i]) ) {
          $ll[$j] = $l[$i];
          $j++;
        }
      }
    } else {
      $prefix = '';
      if ( $table == mysql_tablename ($list, $i) ) {
        $l = 1;
        break;
      } else $l = 0;
    }
  }

  return $prefix ? $ll : $l;
}
// }}}

// {{{ +-- public field_exist_check ($c, $database, $table, $compare)
function field_exist_check ($c, $database, $table, $compare) {
  global $db;

  $field = @mysql_list_fields ($database, $table, $c);
  $num = @mysql_num_fields ($field);

  for ($i = 0; $i < $num; $i++) {
    if ( mysql_field_name ($field, $i) == $compare )
      return true;
  }

  return false;
}
// }}}

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim600: filetype=php et ts=2 sw=2 fdm=marker
 * vim<600: filetype=php et ts=2 sw=2
 */
?>
