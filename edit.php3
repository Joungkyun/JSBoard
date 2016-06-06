<?
require("include/header.ph");
require("./admin/include/config.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);
$passment = "$langs[w_pass]";

// 패스워드가 없는 게시물이나 수정을 허락치 않을 경우 관리자 패스워드를 사용해야 함
if(!$list[passwd] || !$enable[edit] || !$cenable[edit]) {
  if (!$enable[edit]) $passment = "$langs[e_wpw]";
  else $passment = "$langs[b_apw]";
}

require("html/head.ph");

if($board[notice]) print_notice($board[notice]);

$wrap = form_wrap();

if($list[html]) $html[1] = " CHECKED";
else $html[0] = " CHECKED";

// image menu를 사용할시에 wirte 화면과 list,read 화면의 비율을 맞춤
if ($board[img] && !eregi("%",$board[width])) 
  $board[width] = $board[width]-$icons[size]*2;

echo "
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM METHOD=\"post\" ACTION=\"act.php3\">
<TR>
  <TD BGCOLOR=\"$color[r1_bg]\" width=13%><FONT COLOR=\"$color[r1_fg]\">$langs[w_name]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[name]\" SIZE=\"$size[name]\" MAXLENGTH=\"50\" VALUE=\"$list[name]\"></TD>
  <TD BGCOLOR=\"$color[r2_bg]\" width=35%><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_name_m]</FONT></TD>";

if ($view[email] == "yes" || $list[email]) {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[w_mail]</FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[email]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$list[email]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_mail_m]</FONT></TD>\n";
}

if ($view[url] == "yes"  || $list[url]) {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[url]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$list[url]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_url_m]</FONT></TD>\n";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">HTML</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\">
    <FONT COLOR=\"$color[r2_fg]\">
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"1\"$html[1]>$langs[u_html]
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"0\"$html[0]>$langs[un_html]
    </FONT>
  </TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_html_m]</FONT></TD>";

if($list[bofile]) {
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($list[bofile]);
  $icon = icon_check($tail,$list[bofile]);
  $down_link = check_dnlink($table,$list);
  echo "</TR><TR>\n" .
       "   <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[file]</NOBR></FONT></TD>\n" .
       "   <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\">\n" .
       "   <A HREF=\"$down_link\">\n" .
       "   <IMG SRC=\"images/$icon\" width=16 height=16 border=0 alt=\"$list[bofile]\" align=texttop>\n" .
       "   <FONT COLOR=\"$color[r2_fg]\">$list[bofile]</FONT>\n" .
       "   </A>\n" .
       " <FONT COLOR=\"$color[r2_fg]\">- $hfsize</FONT>\n</TD>";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[titl]</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\" VALUE=\"$list[title]\"></TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
<!-- ---------- 글 내용 ---------- -->
    <TEXTAREA NAME=\"atc[text]\" $wrap ROWS=\"10\" COLS=\"$size[text]\">$list[text]\n\n</TEXTAREA>
<!-- ---------- 글 내용 ---------- -->
  </TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"right\" BGCOLOR=\"$color[r1_bg]\">
    <FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\">
    $langs[w_ment]
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR>
<TR><TD>

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"6\" CELLSPACING=\"0\">
<TR>
  <TD ALIGN=\"center\">
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\">
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" value=\"e\">
    <INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"$table\">
    <INPUT TYPE=\"hidden\" NAME=\"atc[no]\" VALUE=\"$list[no]\">
    $passment: <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"$size[pass]\" MAXLENGTH=\"8\">&nbsp;
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_edit]\">&nbsp;
    <INPUT TYPE=\"reset\" VALUE=\"$langs[b_reset]\">&nbsp;
    <INPUT TYPE=\"button\" onClick=\"history.back();\" VALUE=\"$langs[b_can]\">
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR></FORM></TABLE>\n

<TABLE ALIGN=\"center\" WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\">
<TR>\n";

if ($board[img] == "yes") {
  if ($color[theme]) $themes[img] = get_theme_img($table);
  else $themes[img] = "images";
  echo "<td><nobr>" .
       "<A HREF=\"list.php3?table=$table&page=$page\"><img src=./$themes[img]/list.gif width=$icons[size] height=$icons[size] border=0 alt=\"$langs[cmd_list]\"></a>" .
       "<A HREF=\"javascript:history.back()\"><img src=./$themes[img]/back.gif width=$icons[size] height=$icons[size] border=0 alt=\"$langs[cmd_priv]\"></a>" .
       "</nobr></td>";
} else {
  echo "
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=\"1%\"><A HREF=\"list.php3?table=$table\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=\"1%\"><A HREF=\"javascript:history.back()\"><FONT COLOR=\"$color[n0_fg]\"><NOBR>$langs[cmd_priv]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>\n";
}

echo("</TR>\n</TABLE>\n");

require("html/tail.ph");
?>
