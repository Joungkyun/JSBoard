<?php
include "./include/header.php";
$nocopy = 0;

if ( $mode == 'photo' ) {
  $nocopy = 1;
  if ( preg_match ('/^(2|3|5)$/', $board['mode']) && ! session_is_registered ($jsboard) )
    print_error ($_('login_err'));

  meta_char_check ($table, 0, 1);
  meta_char_check ($f['c']);
  meta_char_check ($upload['dir']);
  upload_name_chk ($f['n']);

  $f['n'] = urlencode ($f['n']);
  $print['head'] = "VIEW ORIGINAL IMAGE";
  $print['body'] = "<a href=\"javascript:window.close()\">".
           "<img alt=\"\" src=\"./data/$table/{$upload['dir']}/{$f['c']}/{$f['n']}\" ".
           "width=\"{$f['w']}\" height=\"{$f['h']}\" border=0>".
           "</a>\n";
} elseif ( $mode == 'version' ) {
  $print['head'] = "Version Numbering";
  $print['body'] = "<table width=\"100%\" border=0 align=\"center\" style=\"height: 100%;\">\n".
                 "<tr><td class=\"versionprint\">-= [ JSBoard v{$board['ver']} ] =-</td></tr>\n".
                 "</table>\n";
}

if ( $mode ) {
  if ( ! $print['theme'] )
    $print['theme'] = "EN-default";

  meta_char_check ($print['theme'], 1, 1);
  $bodyType = 'ext';
  include "theme/{$print['theme']}/index.template";
}
?>
