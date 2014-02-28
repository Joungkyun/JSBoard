<?php
# $Id: replicate.php,v 1.3 2014-02-28 21:37:18 oops Exp $

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
?>
