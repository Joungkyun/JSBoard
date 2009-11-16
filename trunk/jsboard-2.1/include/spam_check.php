<?php
# $Id: spam_check.php,v 1.3 2009-11-16 21:52:47 oops Exp $

function get_spam($table, $no) {
  global $c, $db;
  $result = sql_query("SELECT * FROM $table WHERE no = '$no'", $c);
  while($list = sql_fetch_array($result)) {
    if($list['email']) $spam[] = "EM:{$list['email']}";
    if($list['url']) $spam[] = "HP:{$list['url']}";

    $urls = preg_replace("/(http:\/\/[a-z0-9:&#@=_\?\/~\.\+-]+)", "||\\1||/i", $list['text']);
    $url  = explode("||", $urls);
    for($co = 0; $co < count($url); $co++) {
      if(preg_match("/^http:\/\/[a-z0-9:&#@=_\?\/~\.\+-]+/i", $url[$co])) $spam[] = "HP:$url[$co]";
    }
    $spam[] = "IP:{$list['host']}";
    $spam[] = preg_replace("/([0-9]+\.[0-9]+\.[0-9]+)\.[0-9]+$/i", "IP:\\1.0", $list['host']);
  }

  for($i = 0; $i < count($spam); $i++) {
    printf("%s<BR>\n", $spam[$i]);
  }
}
?>
