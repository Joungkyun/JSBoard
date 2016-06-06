<?
function replication_mode($db) {
  $db[userver] = $db[userver] ? $db[userver] : $db[server];
  if(!preg_match("/#/",$db[userver])) $db[userver] .= "#w";

  $chk = explode("#",$db[userver]);
  $db[rhost] = $chk[0];
  $db[rmode] = $chk[1];

  return $db;
}

function replication_addrefer($db) {
  global $table,$list,$no;

  if($db[rmode] == "w")
    $c = @sql_connect($db[rhost],$db[user],$db[pass],1);
  
  if($c && get_hostname(0) != $list[host]) {
    @mysql_select_db($db[name],$c);
    mysql_query("UPDATE $table SET refer = refer + 1 WHERE no = '$no'",$c);
    sql_error($mysql_errno,$mysql_error);
    $list[refer]++;
  }
}
?>
