<?php
# $Id: spam_check.ph,v 1.3 2009-11-19 19:10:58 oops Exp $
function get_spam($table, $no) {
  $result = sql_query("SELECT * FROM $table WHERE no = '$no'");
  while($list = sql_fetch_array($result)) {
    if($list[email]) $spam[] = "EM:$list[email]";
    if($list[url]) $spam[] = "HP:$list[url]";
	
    $urls = eregi_replace("(http:\/\/[a-z0-9:&#@=_\?\/~\.\+-]+)", "||\\1||", $list[text]);
    $url  = explode("||", $urls);
    for($co = 0; $co < count($url); $co++) {
      if(eregi("^http:\/\/[a-z0-9:&#@=_\?\/~\.\+-]+", $url[$co])) $spam[] = "HP:$url[$co]";
    }
    $spam[] = "IP:$list[host]";
    $spam[] = eregi_replace("([0-9]+\.[0-9]+\.[0-9]+)\.[0-9]+$", "IP:\\1.0", $list[host]);
  }

  for($i = 0; $i < count($spam); $i++) {
    printf("%s<BR>\n", $spam[$i]);
  }
}
?>
