<?php
$__db = $_db['type'] ? $_db['type'] : $db['type'];
$_based = dirname ($_SERVER['PHP_SELF']);

if ( $prlist['path'] ) {
  $_locate = $prlist['path'];
} else if ( preg_match ('/\/admin$/', $_based) ) {
  $_locate = "..";
} else if ( preg_match ('/user_admin$/', $_based) ) {
  $_locate = "../..";
} else {
  $_locate = ".";
}

if ( $db['char'] && $__db == 'pgsql' || $__db == 'mysql41' ) {
  require_once "{$_locate}/database/charset.php";
  $db['charset'] = $_char[$__db][$_code];
} else $db['charset'] = '';

switch ($__db) {
  case 'pgsql' :
    require_once "{$_locate}/database/pgsql.php";
    break;
  case 'sqlite' :
    require_once "{$_locate}/database/sqlite.php";
    break;
  default:
    if ( extension_loaded ('mysqli') && $__db == 'mysql41' ) {
      require_once "{$_locate}/database/mysqli.php";
    } else {
      require_once "{$_locate}/database/mysql.php";
    }
}
?>
