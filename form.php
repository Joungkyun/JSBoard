<HTML>
<?
# $Id: form.php,v 1.8 2009-11-20 13:56:38 oops Exp $
include_once "include/print.ph";
# register_globals 옵션의 영향을 받지 않기 위한 함수
parse_query_str();

if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include_once "config/global.ph"; }

if($mode == "photo") {
  include_once "include/lang.ph";
  include_once "include/get.ph";
  include_once "include/error.ph";
  include_once "include/check.ph";

  meta_char_check($table,0,1);
  meta_char_check($f[c]);
  meta_char_check($upload[dir]);
  upload_name_chk($f[n]);

  $f[n] = urlencode($f[n]);
  $pr[head] = "VIEW ORIGINAL IMAGE";
  $pr[body] = "<a href=javascript:window.close()>".
           "<img src=./data/$table/$upload[dir]/$f[c]/$f[n] width=$f[w] height=$f[h] border=0>".
           "</a>\n";
} elseif($mode == "version") {
  include_once "include/version.ph";
  $pr[head] = "Version Numbering";
  $pr[body] = "JSBoard v$board[ver]";
}

echo "<HEAD>\n".
     "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">\n".
     "<TITLE>JSBoard - $pr[head]</TITLE>\n".
     "</HEAD>\n".
     "<BODY bgcolor=white leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n".
     "$pr[body]";
?>

</BODY>
</HTML>
