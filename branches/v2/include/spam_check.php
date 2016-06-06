<?php
# $Id: spam_check.php,v 1.5 2014-03-02 17:11:31 oops Exp $

// {{{ +-- public get_spam($table, $no)
function get_spam($table, $no) {
  global $c;
  $result = sql_query("SELECT * FROM $table WHERE no = '$no'",$c);
  while($list = sql_fetch_array($result,$c)) {
    if($list['email']) $spam[] = "EM:{$list['email']}";
    if($list['url']) $spam[] = "HP:{$list['url']}";

    $urls = preg_replace('!(http://[a-z0-9:&#%@=_\?/~.+-]+)!i', "||\\1||/i", $list['text']);
    $url  = explode("||", $urls);
    for($co = 0; $co < count($url); $co++) {
      if(preg_match('!^http://[a-z0-9:&%#@=_\?/~.+-]+!i', $url[$co])) $spam[] = 'HP:'.$url[$co];
    }
    $spam[] = 'IP:'.$list['host'];
    $spam[] = preg_replace("/([0-9]+\.[0-9]+\.[0-9]+)\.[0-9]+$/i", "IP:\\1.0", $list['host']);
  }

  for($i = 0; $i < count($spam); $i++) {
    printf("%s<BR>\n", $spam[$i]);
  }
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
