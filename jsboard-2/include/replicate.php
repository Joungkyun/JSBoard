<?php
# $Id: replicate.php,v 1.4 2014-03-02 17:11:31 oops Exp $

function replication_mode($db) {
  $db['userver'] = $db['userver'] ? $db['userver'] : $db['server'];
  if(!preg_match("/#/",$db['userver'])) $db['userver'] .= "#w";

  $chk = explode("#",$db['userver']);
  $db['rhost'] = $chk[0];
  $db['rmode'] = $chk[1];

  return $db;
}

function replication_addrefer($db) {
  global $table,$list,$no;

  if($db['rmode'] == "w")
    $conn = @sql_connect($db['rhost'],$db['user'],$db['pass'],$db['rmode']);
  
  if($c && get_hostname(0) != $list['host']) {
    @sql_select_db($db['name'],$conn);
    sql_query("UPDATE $table SET refer = refer + 1 WHERE no = '$no'",$conn);
    $list['refer']++;
  }

  if ($conn)
	sql_close($conn);
}

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
