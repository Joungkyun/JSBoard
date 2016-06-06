<?
include "include/header.ph";
include "./admin/include/config.ph";
include "html/head.ph";
include_once "include/tableratio.ph";

# upload[dir] 에 mata character 포함 여부 체크
meta_char_check($upload[dir]);

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$list = get_article($table, $no);
$passment = $langs[w_pass];

# 패스워드가 없는 게시물이나 수정을 허락치 않을 경우 관리자 패스워드를 사용해야 함
if(!$list[passwd] || !$enable[edit] || !$cenable[edit]) {
  if (!$enable[edit]) $passment = "$langs[e_wpw]";
  else $passment = "$langs[b_apw]";
}

if(!$adminsession || $cenable[edit]) {
  if(!$adminsession) {
    $passment = "$passment: <INPUT TYPE=\"password\" NAME=\"passwd\" SIZE=\"$size[pass]\" \n".
                "MAXLENGTH=\"8\" STYLE=\"font: 10px tahoma\">&nbsp;";
  } else $passment = "";
} else $passment = "";

if($board[notice]) print_notice($board[notice]);

$wrap = form_wrap();

if($list[html]) $html[1] = " CHECKED";
else $html[0] = " CHECKED";

# Browser가 Lynx일때 multim form 삭제
if($agent[br] == "LYNX") $board[formtype] = "";
else $board[formtype] = " ENCTYPE=\"multipart/form-data\"";

# image menu를 사용할시에 wirte 화면과 list,read 화면의 비율을 맞춤
if ($board[img] == "yes" && !preg_match("/%/",$board[width])) 
  $board[width] = $board[width]-$icons[size]*2;
else $size[text] += 4;

echo "
<DIV ALIGN=\"$board[align]\">
<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM NAME=editp METHOD=\"post\" ACTION=\"act.php\"$board[formtype]>
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
       "<TD BGCOLOR=\"$color[r1_bg]\" NOWRAP><FONT COLOR=\"$color[r1_fg]\" $board[css]><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
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
       "   <TD BGCOLOR=\"$color[r1_bg]\" NOWRAP><FONT COLOR=\"$color[r1_fg]\" $board[css]><NOBR>$langs[file]</NOBR></FONT></TD>\n" .
       "   <TD BGCOLOR=\"$color[r2_bg]\">\n" .
       "   <A HREF=\"$down_link\">\n" .
       "   <IMG SRC=\"images/$icon\" width=16 height=16 border=0 alt=\"$list[bofile]\" align=texttop>\n" .
       "   <FONT COLOR=\"$color[r2_fg]\" $board[css]>$list[bofile]</FONT>\n" .
       "   </A>\n" .
       "   <FONT COLOR=\"$color[r2_fg]\" $board[css]>- $hfsize</FONT>\n</TD>\n".
       "   <TD BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\" $board[css]>$langs[fdel]</FONT>\n".
       "   <INPUT TYPE=CHECKBOX NAME=atc[fdel] VALUE=1>\n".
       "   <INPUT TYPE=HIDDEN NAME=atc[fdelname] VALUE=\"$list[bofile]\">\n".
       "   <INPUT TYPE=HIDDEN NAME=atc[fdeldir] VALUE=\"$list[bcfile]\"></TD>\n";
}

if($upload[yesno] == "yes" && $cupload[yesno] == "yes" && $agent[br] != "LYNX") {
  echo "</TR><TR>\n".
       "   <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[fmod]</FONT></TD>\n".
       "   <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\">\n".
       "   <INPUT TYPE=file NAME=userfile SIZE=\"$size[uplo]\" MAXLENGTH=256>\n".
       "   <INPUT TYPE=HIDDEN NAME=max_file_size VALUE=\"$upload[maxsize]\"></TD>\n";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[titl]</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\" VALUE=\"$list[title]\"></TD>";

if (preg_match("/MSIE/i",$agent[br]) || $agent[br] == "MOZL6") {
  $orig_option = " onClick=fresize(0)";
  echo "
</TR><TR>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>Textarea size config</FONT></TD>
  <TD ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">

<SCRIPT LANGUAGE=JavaScript>
<!--
function fresize(value) {
if (value == 0) {
  document.editp.epost.cols  = $size[text];
  document.editp.epost.rows  = 10;
}
if (value == 1) document.editp.epost.cols += 5;
if (value == 2) document.editp.epost.rows += 5;
}
// -->
</SCRIPT>\n";

  # 언어 코드에 따라 버튼을 text 로 처리 할것인지 이미지로 처리할 것인지를 결정
  form_size_button();

  echo "  </TD>";
}

echo "
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
<!-- ---------- 글 내용 ---------- -->
    <TEXTAREA NAME=\"epost\" $wrap[op] ROWS=\"10\" COLS=\"$size[text]\">$list[text]\n\n</TEXTAREA>
<!-- ---------- 글 내용 ---------- -->
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
    $passment
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_edit]\">&nbsp;
    <INPUT TYPE=\"reset\" VALUE=\"$langs[b_reset]\"$orig_option>&nbsp;
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
  <TD WIDTH=\"1%\" NOWRAP><A HREF=\"list.php?table=$table\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=\"1%\" NOWRAP><A HREF=\"javascript:history.back()\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_priv]</NOBR></FONT></A></TD>
  <TD WIDTH=\"1%\" BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>\n";
}

echo "</TR>\n</TABLE>\n</TD></TR>\n</TABLE>\n</DIV>";

include "html/tail.ph";
?>
