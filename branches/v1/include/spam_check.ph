<?php
# $Id: spam_check.ph,v 1.4 2009-11-20 13:45:48 oops Exp $
function get_spam($table, $no) {
  $result = sql_query("SELECT * FROM {$table} WHERE no = '{$no}'");
  while($list = sql_fetch_array($result)) {
    if($list['email']) $spam[] = 'EM:'.$list['email'];
    if($list['url']) $spam[] = 'HP:'.$list['url'];
	
    $urls = preg_replace('!(http://[a-z0-9:&%#@=_\?/~.+-]+)!i', "||\\1||/i", $list['text']);
    $url  = explode("||", $urls);
    for($co = 0; $co < count($url); $co++) {
      if(preg_match('!^http://[a-z0-9:&%#@=_?/~.+-]+!i', $url[$co])) $spam[] = "HP:{$url[$co]}";
    }
    $spam[] = 'IP:'.$list['host'];
    $spam[] = preg_replace('/([0-9]+\.[0-9]+\.[0-9]+)\.[0-9]+$/i', "IP:\\1.0", $list['host']);
  }

  for($i = 0; $i < count($spam); $i++) {
    printf("%s<BR>\n", $spam[$i]);
  }
}
?>
