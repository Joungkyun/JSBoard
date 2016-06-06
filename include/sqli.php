<?php
# MySQLi extension API
# $Id: sqli.php,v 1.2 2014/03/02 17:11:32 oops Exp $
#

// {{{ +-- public sql_connect($server,$user,$pass,$mode='w')
function sql_connect($server,$user,$pass,$mode='w') {
  if ($mode == "r")
    print_error("System Checking NOW !! \n\nSorry, Read only enable.",250,130,1);

  if ( $server[0] == ':' ) {
    $_socket = preg_replace ('/^:/', '', $server);
    $server = 'localhost';
    $_port = 0;
  } else {
    $_socket = '';
    if ( preg_match ('/:/', $server) ) {
      $_sock = explode (':', $server);
      $server = $_sock[0];
      $_port = $_sock[1];
    }
  }

  $r = mysqli_connect ($server, $user, $pass, $dbname, $_port, $_socket);

  sql_error (mysqli_connect_errno (), mysqli_connect_error ());

  //if ( is_object ($r) && $db['charset'] )
  sql_query ('set names utf8', $r, 1);

  return $r;
}
// }}}

// {{{ +-- public sql_select_db($name,$c)
function sql_select_db($name,$c) {
  $return = @mysqli_select_db($c,$name);
  sql_error(mysqli_errno($c), mysqli_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_query($query,$c,$noerr=null)
function sql_query($query,$c,$noerr=null) {
  $return = mysqli_query($c,$query);
  if(!$noerr) sql_error(mysqli_errno($c), mysqli_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_result($result,$row,$field,$c)
function sql_result($result,$row,$field,$c) {
  if (is_object($result)) {
    if (mysqli_num_rows($result)) {
      mysqli_data_seek($result,$row);
      $rr = sql_fetch_array($result,$c);
      $r = $rr[$field];
    }
    sql_error(mysqli_errno($c), mysqli_error($c));
  } else $r = 0;

  return $r;
}
// }}}

// {{{ +-- public sql_fetch_row($result,$c)
function sql_fetch_row($result,$c) {
  if(sql_num_rows($result,$c)) $return = @mysqli_fetch_row($result);
  else $return = 0;

  sql_error(mysqli_errno($c), mysqli_error($c));
  return $return;
}
// }}}

// {{{ +-- public sql_fetch_array($result,$c)
function sql_fetch_array($result,$c) {
  if(sql_num_rows($result,$c)) $return = @mysqli_fetch_array($result);
  else $return = 0;

  sql_error(mysqli_errno($c), mysqli_error($c));
  return $return;
}
// }}}

// {{{ +-- public sql_num_rows($result,$c)
function sql_num_rows($result,$c) {
  $return = @mysqli_num_rows($result);
  sql_error(mysqli_errno($c), mysqli_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_affected_rows($c)
function sql_affected_rows($c) {
  $return = @mysqli_affected_rows($c);
  sql_error(mysqli_errno($c), mysqli_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_free_result($result,$c)
function sql_free_result($result,$c) {
  $return = @mysqli_free_result($result);
  sql_error(mysqli_errno($c), mysqli_error($c));

  return $return;
}
// }}}

// {{{ +-- public sql_escape(&$v, $c)
function sql_escape(&$v, $c) {
  if(version_compare(PHP_VERSION,'5.4.0','<')) {
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
      return;
  }

  if (is_array($v)) {
    foreach ($v as $key => $val) {
      if ( is_array ($val) ) {
        sql_escape ($v[$key],$c);
        continue;
      }

      if (!is_numeric($val))
        $v[$key] = mysqli_real_escape_string ($c,$val);
    }
    return;
  }

  if (!is_numeric($v))
    $v = mysqli_real_escape_string($c,$v);
}
// }}}

// {{{ +-- public sql_close ($c)
function sql_close ($c) {
  if (is_object($c))
    mysqli_close($c);
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
  $sql = 'SHOW TABLES';
  $r = sql_query ($sql, $c);

  $i = 0;
  $j = ! $j ? 0 : $j;

  while ( $row = sql_fetch_row ($r,$c) ) {
    if ( ! $table ) {
      $l[$i] = $row[0];

      if ( $prefix ) {
        if ( preg_match ("/^{$prefix}/i", $row[0]) ) {
          $ll[$j] = $row[0];
          $j++;
        }
      }

      $i++;
    } else {
      $prefix = '';
      if ( $table == $row[0] ) {
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
  $res = sql_query ('DESC ' . $table, $c);

  while (($r = sql_fetch_array($res,$c))) {
    if ($r['Field'] == $compare)
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
