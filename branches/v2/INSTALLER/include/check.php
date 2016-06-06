<?php
# $Id: check.php,v 1.4 2014-03-02 17:11:29 oops Exp $

# // {{{ +-- public inst_pwcheck($pass,$mypass,$msg)
# Password 체크 부분
function inst_pwcheck($pass,$mypass,$msg) {
  global $langs;
  if ($pass != $mypass) {
    echo "<script>\n" .
         "alert('$msg')\n" .
         "document.location='./session.php?mode=logout&langss={$langs['code']}'\n" .
         "</script>".
         "<NOSCRIPT>\n".
         "Error: $msg<BR>\n".
         "If you want to go authentication page, <A HREF=./session.php?mode=logout&langss={$langs['code']}>click here</A>!\n";
         "</NOSCRIPT>\n";
    exit;
  }
}
// }}}

# // {{{ +-- public inst_chk_var($db,$msg)
# DB에서 사용할 변수값 지정 유무 체크
function inst_chk_var($db,$msg) {
  if (!$db) print_error($msg,250,150,1);
}
// }}}

// {{{ +-- public inst_chk_numberic($name,$msg)
function inst_chk_numberic($name,$msg) {
  if (preg_match("/^[0-9]/",$name)) print_error($msg,250,150,1);
}
// }}}

// {{{ +-- public inst_chk_dbname($name,$msg)
function inst_chk_dbname($name,$msg) {
  global $indb, $c;

  $ret = sql_query('SHOW DATABASES', $c);
  while (($r = sql_fetch_row ($ret,$c))) {
	  if ($name == $r[0]) print_error($msg,250,150,1);
  }
}
// }}}

// {{{ +-- public inst_chk_dbuser($name,$msg)
function inst_chk_dbuser($name,$msg) {
  global $langs, $c;
  $check = "select user from user where user = '$name'";
  sql_select_db('mysql', $c);
  $result = sql_query($check, $c);
  $row = sql_fetch_array($result,$c);
  if ($row) print_error($msg,250,150,1);
}
// }}}

// {{{ +-- public inst_check($chk='')
function inst_check($chk='') {
  global $dbinst, $langs;
  inst_chk_var($dbinst['name'],$langs['inst_chk_varn']);
  inst_chk_var($dbinst['user'],$langs['inst_chk_varu']);
  inst_chk_var($dbinst['pass'],$langs['inst_chk_varp']);

  if($chk) {
    inst_chk_numberic($dbinst['name'],$langs['inst_ndb']);
    inst_chk_numberic($dbinst['user'],$langs['inst_udb']);
    inst_chk_dbname($dbinst['name'],$langs['inst_adb']);
    inst_chk_dbuser($dbinst['user'],$langs['inst_cudb']);
  }

  return 1;
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
