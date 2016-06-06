<?
require("include/header.ph");
require("./admin/include/config.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);

$warning = "$langs[d_wa]";
// 패스워드가 없는 게시물일  경우 관리자 인증을 거침
if(!$list[passwd] || $list[reyn] || !$enable[delete] || !$cenable[delete]) {
  if (!$enable[delete]) { 
    $adm = "sadmin";
    $warning = "$langs[d_waw]";
  } else if (($enable[delete] && !$cenable[delete]) || $list[reyn] || !$list[passwd]) {
    $adm = "admin";
    $warning = "$langs[d_waa]";
  }
}

require("html/head.ph");

$list[date] = date("Y-m-d H:i:s", $list[date]);
$list[text] = text_nl2br($list[text], $list[html]);
$list[num]  = print_reply($table, $list);

$size = form_size(4);

if($list[bofile]) {
  $deldir  = "./data/$table/$upload[dir]/$list[bcfile]";
  $delfile = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
}

echo "
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM METHOD=\"post\" ACTION=\"act.php3\">
<TR>
  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[d_no]</NOBR></FONT></TD>
  <TD WIDTH=\"64%\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$list[num]</FONT></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[date]</NOBR></FONT></TD>
  <TD WIDTH=\"34%\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$list[date]</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[name]</NOBR></FONT></TD>
  <TD WIDTH=\"64%\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$list[name]</FONT></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[d_ad]</NOBR></FONT></TD>
  <TD WIDTH=\"34%\" BGCOLOR=\"$color[r2_bg]\"><a href=./whois.php3?table=$table&host=$list[host]&window=1><FONT COLOR=\"$color[r2_fg]\">$list[host]</FONT></a></TD>\n";

if ($list[email]) {
  echo "</TR><TR>\n" .
       "  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[w_mail]</NOBR></FONT></TD>\n" .
       "  <TD BGCOLOR=\"$color[r2_bg]\" COLSPAN=\"3\"><FONT COLOR=\"$color[r2_fg]\">$list[email]</FONT></TD>\n";
}

if ($list[url]) {
  echo "</TR><TR>\n" .
       "  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
       "  <TD BGCOLOR=\"$color[r2_bg]\" COLSPAN=\"3\"><FONT COLOR=\"$color[r2_fg]\">$list[url]</FONT></TD>\n";
}

if($list[bofile]) {
  $bofile = "$list[bofile]";
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($bofile);
  $icon = icon_check($tail,$bofile);
    
  echo "</TR><TR>\n" .
       "   <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[file]</NOBR></FONT></TD>\n" .
       "   <TD COLSPAN=\"3\" BGCOLOR=\"$color[r2_bg]\">\n" .
       "   <A HREF=\"./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]\">\n" .
       "   <IMG SRC=\"images/$icon\" width=16 height=16 border=0 alt=\"$bofile\" align=texttop>\n" .
       "   <FONT COLOR=\"$color[r2_fg]\">$list[bofile]</FONT>\n" .
       "   </A>\n" .
       " <FONT COLOR=\"$color[r2_fg]\">- $hfsize</FONT>\n</TD>";
}

echo "
</TR><TR>
  <TD WIDTH=\"1%\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\"><NOBR>$langs[titl]</NOBR></FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\" COLSPAN=\"3\"><FONT COLOR=\"$color[r2_fg]\">$list[title]</FONT></TD>
</TR><TR>
  <TD BGCOLOR=\"$color[r3_bg]\" COLSPAN=\"4\">

<TABLE ALIGN=\"center\" WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\">
<TR>
  <TD>
    <FONT COLOR=\"$color[r3_fg]\">
<!-- ---------- 글 내용 ---------- -->
$list[text]
<!-- ---------- 글 내용 ---------- -->
    </FONT>
  </TD>
</TR>
</TABLE>

  </TD>
</TR><TR>
  <TD COLSPAN=\"4\" ALIGN=\"right\" BGCOLOR=\"$color[r1_bg]\">
    <FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\">
    $warning
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
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" value=\"d\">
    <INPUT TYPE=\"hidden\" NAME=\"o[am]\" value=\"$adm\">
    <INPUT TYPE=\"hidden\" NAME=\"no\" VALUE=\"$no\">
    <INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"$table\">
    <INPUT TYPE=\"hidden\" NAME=\"delete_dir\" VALUE=\"$deldir\">
    <INPUT TYPE=\"hidden\" NAME=\"delete_filename\" VALUE=\"$delfile\">    
    $langs[w_pass]: <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"$size\" MAXLENGTH=\"8\">&nbsp;
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_del]\">&nbsp;
    <INPUT TYPE=\"button\" onClick=\"history.back()\" VALUE=\"$langs[b_can]\">
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

echo "</TR>\n</TABLE>\n";

require("html/tail.ph");
?>
