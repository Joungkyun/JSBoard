<?
@include("include/header.ph");
@include("./admin/include/config.ph");
@include("html/head.ph");

# upload[dir] �� mata character ���� ���� üũ
meta_char_check($upload[dir]);

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);
$list[text] = eregi_replace("&([a-z]+);","&amp;\\1;",$list[text]);
$list[text] = str_replace("<!","&lt;!",$list[text]);

$passment = "$langs[w_pass]";

// �н����尡 ���� �Խù��̳� ������ ���ġ ���� ��� ������ �н����带 ����ؾ� ��
if(!$list[passwd] || !$enable[edit] || !$cenable[edit]) {
  if (!$enable[edit]) $passment = "$langs[e_wpw]";
  else $passment = "$langs[b_apw]";
}

if($board[notice]) print_notice($board[notice]);

$wrap = form_wrap();

if($list[html]) $html[1] = " CHECKED";
else $html[0] = " CHECKED";

// image menu�� ����ҽÿ� wirte ȭ��� list,read ȭ���� ������ ����
if ($board[img] == "yes" && !eregi("%",$board[width])) 
  $board[width] = $board[width]-$icons[size]*2;
else $size[text] += 4;

echo "
<DIV ALIGN=\"$board[align]\">
<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM METHOD=\"post\" ACTION=\"act.php\">
<TR>
  <TD BGCOLOR=\"$color[r1_bg]\" width=13%><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[w_name]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[name]\" SIZE=\"$size[name]\" MAXLENGTH=\"50\" VALUE=\"$list[name]\"></TD>
  <TD BGCOLOR=\"$color[r2_bg]\" width=35%><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_name_m]</FONT></TD>";

if ($view[email] == "yes" || $list[email]) {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[w_mail]</FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[email]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$list[email]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_mail_m]</FONT></TD>\n";
}

if ($view[url] == "yes"  || $list[url]) {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[url]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$list[url]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_url_m]</FONT></TD>\n";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>HTML</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\">
    <FONT COLOR=\"$color[r2_fg]\" $board[css]>
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"1\"$html[1]>$langs[u_html]
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"0\"$html[0]>$langs[un_html]
    </FONT>
  </TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_html_m]</FONT></TD>";

if($list[bofile]) {
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($list[bofile]);
  $icon = icon_check($tail,$list[bofile]);
  $down_link = check_dnlink($table,$list);

  echo "</TR><TR>\n" .
       "   <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]><NOBR>$langs[file]</NOBR></FONT></TD>\n" .
       "   <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\">\n" .
       "   <A HREF=\"$down_link\">\n" .
       "   <IMG SRC=\"images/$icon\" width=16 height=16 border=0 alt=\"$list[bofile]\" align=texttop>\n" .
       "   <FONT COLOR=\"$color[r2_fg]\" $board[css]>$list[bofile]</FONT>\n" .
       "   </A>\n" .
       " <FONT COLOR=\"$color[r2_fg]\" $board[css]>- $hfsize</FONT>\n</TD>";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[titl]</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\" VALUE=\"$list[title]\"></TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
<!-- ---------- �� ���� ---------- -->
    <TEXTAREA NAME=\"atc[text]\" $wrap[op] ROWS=\"10\" COLS=\"$size[text]\">$list[text]\n\n</TEXTAREA>
<!-- ---------- �� ���� ---------- -->
  </TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"right\" BGCOLOR=\"$color[r1_bg]\">
    <FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\" $board[css]>
    $wrap[ment]
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR>
<TR><TD>

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"6\" CELLSPACING=\"0\">
<TR>
  <TD ALIGN=\"center\">
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\" $board[css]>
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

<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR><TD>
<TABLE ALIGN=\"center\" WIDTH=\"1%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\">
<TR>\n";

if ($board[img] == "yes") {
  if ($color[theme]) $themes[img] = get_theme_img($table);
  else $themes[img] = "images";
  echo "<td><nobr>" .
       "<A HREF=\"list.php?table=$table&page=$page\"><img src=./$themes[img]/list.gif width=$icons[size] height=$icons[size] border=0 alt=\"$langs[cmd_list]\"></a>" .
       "<A HREF=\"javascript:history.back()\"><img src=./$themes[img]/back.gif width=$icons[size] height=$icons[size] border=0 alt=\"$langs[cmd_priv]\"></a>" .
       "</nobr></td>";
} else {
  echo "
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=\"1%\"><A HREF=\"list.php?table=$table\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=\"1%\"><A HREF=\"javascript:history.back()\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_priv]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>\n";
}

echo("</TR>\n</TABLE>\n</TD></TR>\n</TABLE>\n</DIV>");

@include("html/tail.ph");
?>