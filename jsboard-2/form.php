<?
include "./include/header.ph";
if($HTTP_COOKIE_VARS[$cjsboard][id]) { session_start(); }

if($mode == "photo") {
  if(eregi("^(2|3|5)$",$board[mode]) && !session_is_registered("$jsboard"))
    print_error("$langs[login_err]");

  meta_char_check($table,0,1);
  meta_char_check($f[c]);
  meta_char_check($upload[dir]);
  upload_name_chk($f[n]);

  $print[head] = "VIEW ORIGINAL IMAGE";
  $print[body] = "<a href=javascript:window.close()>".
           "<img src=./data/$table/$upload[dir]/$f[c]/$f[n] width=$f[w] height=$f[h] border=0>".
           "</a>\n";
} elseif($mode == "version") {
  $print[head] = "Version Numbering";
  $print[body] = "<TABLE WIDTH=100% HEIGHT=100% WIDTH=0 BORDER=0 ALIGN=CENTER>\n".
                 "<TR><TD ALIGN=center VALIGN=center><FONT ID=en>JSBoard v$board[ver]</FONT></TD></TR>\n".
                 "</TABLE>\n";
}

if($mode) {
  if(!$print[theme]) $print[theme] = "EN-default";
  include "theme/$print[theme]/ext.template";
}
?>
