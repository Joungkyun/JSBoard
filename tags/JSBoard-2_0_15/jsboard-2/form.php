<?php
# $Id: form.php,v 1.12 2009-11-19 05:29:49 oops Exp $
include "./include/header.php";

if($mode == "photo") {
  if(preg_match("/^(2|3|5)$/",$board['mode']) && !session_is_registered("$jsboard"))
    print_error("{$langs['login_err']}");

  meta_char_check($table,0,1);
  meta_char_check($f['c']);
  meta_char_check($upload['dir']);
  upload_name_chk($f['n']);

  $f['n'] = urlencode($f['n']);
  $print['head'] = "VIEW ORIGINAL IMAGE";
  $print['body'] = "<a href=\"javascript:window.close()\">".
           "<img src=\"./data/$table/{$upload['dir']}/{$f['c']}/{$f['n']}\" width={$f['w']} height={$f['h']} border=0>".
           "</a>\n";
} elseif($mode == "version") {
  $print['head'] = "Version Numbering";
  $print['body'] = "<TABLE WIDTH=\"100%\" WIDTH=0 BORDER=0 ALIGN=\"CENTER\" STYLE=\"height: 100%\">\n".
                 "<TR><TD ALIGN=\"center\" VALIGN=\"center\"><FONT CLASS=\"en\">JSBoard v{$board['ver']}</FONT></TD></TR>\n".
                 "</TABLE>\n";
}

if($mode) {
  if(!$print['theme']) $print['theme'] = "EN-default";

  meta_char_check($print['theme'], 1, 1);
  include "theme/{$print['theme']}/ext.template";
}
?>
