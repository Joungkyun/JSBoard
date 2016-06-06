<?
include "include/header.ph";
include "./admin/include/config.ph";

if ($board_cookie[name])
  $board_cookie[name] = eregi_replace("[\]","",$board_cookie[name]);

# 쓰기 권한을 관리자에게만 주었을 경우 패스워드 체크
$kind = "write";
# 전체 관리자 password 가 존재할 경우 $pcheck 값으로 변환
if($adminsession) $pcheck = $adminsession;
enable_write($sadmin[passwd],$admin[passwd],$pcheck,$enable[$kind], $cenable[$kind]);

include "html/head.ph";

if($board[notice]) print_notice($board[notice]);

# Browser가 Lynx일때 multim form 삭제
$agent = get_agent();
if($agent[br] == "LYNX") $board[formtype] = "";
else $board[formtype] = " ENCTYPE=\"multipart/form-data\"";

# TEXTAREA의 wrap option check
$wrap = form_wrap();

# image menu를 사용할시에 wirte 화면과 list,read 화면의 비율을 맞춤
if ($board[img] == "yes" && !eregi("%",$board[width])) 
  $board[width] = $board[width]-$icons[size]*2;
else $size[text] += 4;

echo "
<DIV ALIGN=$board[align]>
<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM NAME=writep METHOD=\"post\" ACTION=\"act.php\"$board[formtype]>
<TR>
  <TD BGCOLOR=\"$color[r1_bg]\" width=13%><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[w_name]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[name]\" SIZE=\"$size[name]\" MAXLENGTH=\"50\" VALUE=\"$board_cookie[name]\"></TD>
  <TD BGCOLOR=\"$color[r2_bg]\" width=35%><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_name_m]</FONT></TD>\n";

if($view[email] == "yes") {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[w_mail]</FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[email]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$board_cookie[email]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_mail_m]</FONT></TD>\n";
}

if($view[url] == "yes") {
  echo "</TR><TR>\n" .
       "<TD BGCOLOR=\"$color[r1_bg]\" NOWRAP><FONT COLOR=\"$color[r1_fg]\" $board[css]><NOBR>$langs[ln_url]</NOBR></FONT></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[url]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$board_cookie[url]\"></TD>\n" .
       "<TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_url_m]</FONT></TD>\n";
}

if(!$pcheck && !$adminsession || $enable[write]) {
  echo "</TR><TR>\n".
       "  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[w_pass]</FONT></TD>\n".
       "  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"password\" NAME=\"atc[passwd]\" SIZE=\"$size[pass]\" MAXLENGTH=\"8\" STYLE=\"font: 10px tahoma\"></TD>\n".
       "  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_passwd_m]</FONT></TD>\n";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>HTML</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\">
    <FONT COLOR=\"$color[r2_fg]\" $board[css]>
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"1\">$langs[u_html]
    <INPUT TYPE=\"radio\" NAME=\"atc[html]\" VALUE=\"0\" CHECKED>$langs[un_html]
    </FONT>
  </TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><FONT SIZE=\"-1\" COLOR=\"$color[r2_fg]\" $board[css]>$langs[w_html_m]</FONT></TD>";

if ($upload[yesno] == "yes" && $cupload[yesno] == "yes" && $agent[br] != "LYNX") {
  echo "</TR><TR>\n".
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[file]</FONT>\n" .
       "<INPUT TYPE=HIDDEN NAME=max_file_size VALUE=\"$upload[maxsize]\"></TD>\n" .
       "<TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\">".
       "<INPUT TYPE=file NAME=userfile SIZE=\"$size[uplo]\" MAXLENGTH=256></TD>";
} else if ($upload[yesno] == "no" && $cupload[yesno] == "yes") {
  echo "</TR><TR>\n".
       "<TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[file]</FONT></TD>\n" .
       "<TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><font color=red><b>$langs[upload]</b></font></TD>";
}

echo "
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[titl]</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\"></TD>";

if (eregi("MSIE",$agent[br]) || $agent[br] == "MOZL6") {
  $orig_option = " onClick=fresize(0)";
  echo "
</TR><TR>
  <TD COLSPAN=\"2\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>Textarea Size Operation</FONT></TD>
  <TD ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">

<SCRIPT LANGUAGE=JavaScript>
<!--
function fresize(value) {
if (value == 0) {
  document.writep.wpost.cols  = $size[text];
  document.writep.wpost.rows  = 10;
}
if (value == 1) document.writep.wpost.cols += 5;
if (value == 2) document.writep.wpost.rows += 5;
}
// -->
</SCRIPT>\n";

  # 언어 코드에 따라 버튼을 text 로 처리 할것인지 이미지로 처리할 것인지를 결정
  form_size_button($langs[code]);

  echo "  </TD>";
}

echo "
</TR><TR>
  <TD COLSPAN=\"3\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
    <TEXTAREA NAME=\"wpost\" $wrap[op] ROWS=\"10\" COLS=\"$size[text]\"></TEXTAREA>
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
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" VALUE=\"p\">
    <INPUT TYPE=\"hidden\" NAME=\"table\" VALUE=\"$table\">
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_send]\">&nbsp;
    <INPUT TYPE=\"reset\" VALUE=\"$langs[b_reset]\"$orig_option>&nbsp;
    <INPUT TYPE=\"button\" onClick=\"history.back()\" VALUE=\"$langs[b_can]\">
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR></FORM>
</TABLE>

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
  <TD WIDTH=1% BGCOLOR=#800000><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=1% NOWRAP><A HREF=\"list.php?table=$table\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_list]</NOBR></FONT></A></TD>
  <TD WIDTH=1% BGCOLOR=\"#800000\"><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>
  <TD WIDTH=1% NOWRAP><A HREF=\"javascript:history.back()\"><FONT COLOR=\"$color[n0_fg]\" $board[css]><NOBR>$langs[cmd_priv]</NOBR></FONT></A></TD>
  <TD WIDTH=1% BGCOLOR=#800000><IMG SRC=\"images/n.gif\" WIDTH=\"1\" HEIGHT=\"1\" ALT=\"|\"></TD>\n";
}

echo "</TR>\n</TABLE>\n</TD></TR>\n</TABLE>\n</DIV>\n";

include "html/tail.ph";
?>
