<?
if( eregi("list.php3",$page_value)) {
  $span = " rowspan=2" ;
} else if ( eregi("read.php3",$page_value)) {
  $span = "" ;
}
?>
<table width="<? echo $width ?>" border="0" cellpadding="1" align="center">
<tr>
<td<? echo $span ?>>
<font size="2" color="<? echo $l0_fg ?>">
<form method="post" action="encode.php3?table=<? echo $table ?>">
<input type="hidden" name="act" value="search">
<img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
<select name="sc_column">
<option value="title" selected><? echo $select_subj ?>
<option value="text"><? echo $select_text ?>
<option value="name"><? echo $select_writer ?>
<option value="all"><? echo $select_all ?>
</select>
<input type="text" name="sc_string" size="<? sform(10) ?>" maxlength="50">
<input type="submit" value="<? echo $serach_act ?>">
<br><img src="images/n.gif" width="10" height="5" alt="" border="0"><br>
</font>
</td>
<?
if( eregi("list.php3",$page_value)) {
  echo("<td valign=\"top\" align=\"right\">\n" .
       "<font size=\"2\">\n");

  $article = article_message($acount,$tcount,$lang,$act) ;
  if($act != "reply" && $act != "relate" && $act != "search") {
    echo "<font size=-1 color=$l0_fg>$article&nbsp;</font>" ;
  } else if($act == "search") {
    echo "<font size=-1 color=$l0_fg>$article&nbsp;</font>" ;
  }
  echo("</font>\n" .
       "</td>\n" .
       "</tr><tr>\n" .
       "<td align=right><font size=-1>" ) ;
  nlist($page) ;
  echo("&nbsp;</font></td>\n</tr></table>");

} else if ( eregi("read.php3",$page_value)) {
  echo("<td valign=\"top\" align=\"right\">\n" .
       "<font size=\"2\" color=\"$r1_fg\"><b>Writer's IP</b>: $host<br><b>SQL Time</b>: [ $sqltime sec ]</font>" .
       "</td>\n" .
       "</tr></table>");
}
?>
