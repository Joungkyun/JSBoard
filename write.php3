<?
require("include/header.ph");
require("./admin/include/config.ph");

if ($board_cookie[name])
  $board_cookie[name] = eregi_replace("[\]","",$board_cookie[name]);

// ���� ������ �����ڿ��Ը� �־��� ��� �н����� üũ
$kind = "write";
enable_write($sadmin[passwd],$admin[passwd],$pcheck,$enable[$kind], $cenable[$kind]);

require("html/head.ph");

if($board[notice]) print_notice($board[notice]);

$wrap = form_wrap();

// image menu�� ����ҽÿ� wirte ȭ��� list,read ȭ���� ������ ����
if ($board[img] && !eregi("%",$board[width])) 
  $board[width] = $board[width]-$icons[size]*2;

echo "
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM METHOD=\"post\" ACTION=\"act.php3\" ENCTYPE=\"multipart/form-data\">
<TR>
  <TD BGCOLOR=\"$color[r1_bg]\" width=13%><FONT COLOR=\"$color[r1_fg]\">$langs[w_name]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[name]\" SIZE=\"$size[name]\" MAXLENGTH=\"50\" VALUE=\"$board_cookie[name]\"></TD>
  <TD BGCOLOR=\"$color[r2_bg]\" width=35%><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_name_m]</FONT></TD>\n";

if($view[email] == "yes") {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[w_mail]</FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[email]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$board_cookie[email]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_mail_m]</FONT></TD>\n";
}

if($view[url] == "yes") {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[url]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$board_cookie[url]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_url_m]</FONT></TD>\n";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[w_pass]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"password\" NAME=\"atc[passwd]\" SIZE=\"$size[pass]\" MAXLENGTH=\"8\"></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_passwd_m]</FONT></TD>
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">HTML</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\">
    <FONT COLOR=\"$color[r2_fg]\">
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"1\">$langs[u_html]
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"0\" CHECKED>$langs[un_html]
    </FONT>
  </TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\">$langs[w_html_m]</FONT></TD>";

if ($upload[yesno] == "yes" && $cupload[yesno] == "yes") {
  echo "</TR><TR>\n".
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[file]</FONT>\n" .
       "<INPUT TYPE=HIDDEN NAME=max_file_size VALUE=\"$upload[maxsize]\"></TD>\n" .
       "<TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\">".
       "<INPUT TYPE=file NAME=userfile SIZE=\"$size[uplo]\" MAXLENGTH=256></TD>";
} else if ($upload[yesno] == "no" && $cupload[yesno] == "yes") {
  echo "</TR><TR>\n".
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[file]</FONT></TD>\n" .
       "<TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><font color=red><b>$langs[upload]</b></font></TD>";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$langs[titl]</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\"></TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
    <TEXTAREA NAME=\"atc[text]\" $wrap ROWS=\"10\" COLS=\"$size[text]\"></TEXTAREA>
  </TD>
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"right\" BGCOLOR=\"$color[r1_bg]\">
    <FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\"> $langs[w_ment]  </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR>
<TR><TD>

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"6\" CELLSPACING=\"0\">
<TR>
  <TD ALIGN=\"center\">
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\">
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" VALUE=\"p\">
    <INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"$table\">
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_send]\">&nbsp;
    <INPUT TYPE=\"reset\" VALUE=\"$langs[b_reset]\">&nbsp;
    <INPUT TYPE=\"button\" onClick=\"history.back()\" VALUE=\"$langs[b_can]\">
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR></FORM></TABLE>

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

echo "</TR>\n</TABLE>\n";

require("html/tail.ph");
?>
