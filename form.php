<HTML>
<?
if (!@file_exists("config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { @include("config/global.ph"); }

if(!$mode && trim($table)) {
  @include("include/lang.ph");
  @include("include/get.ph");
  @include("include/print.ph");
  @include("include/error.ph");
  @include("include/sql.ph");
  @include("include/sendmail.ph");
  @include("include/tableratio.ph");

  $kind = "formmail";
  if($board[notice]) print_notice($board[notice]);

  $wrap = form_wrap();

  // image menu를 사용할시에 wirte 화면과 list,read 화면의 비율을 맞춤
  if ($board[img] && !eregi("%",$board[width])) 
    $board[width] = $board[width]-$icons[size]*2;

  if(trim($table) && trim($no)) $sinfo = get_send_info($table,$no);
  else print_error("Some problem in \$table or \$no value");

  echo "<HEAD>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">
<TITLE>Jsboard $version - [Form Mail]</TITLE>
<STYLE TYPE=\"text/css\">
<!--
A:link, A:visited, A:active { TEXT-DECORATION: none; }
A:hover { TEXT-DECORATION: underline; }
TD { FONT: 9pt $langs[font] }
INPUT {font: 9pt $langs[font]; BACKGROUND-COLOR: $color[bgcol]; COLOR: $color[text]; BORDER:2x solid $color[l1_bg]}
SELECT {font: 9pt $langs[font]; BACKGROUND-COLOR: $color[bgcol]; COLOR: $color[text]; BORDER:1x solid $color[l1_bg] }
TEXTAREA {font: 10pt $langs[font]; BACKGROUND-COLOR: $color[bgcol]; COLOR: $color[text]; BORDER:2x solid $color[l1_bg] }
 #radio {font: 9pt $langs[font]; BACKGROUND-COLOR: $color[bgcol]; COLOR: $color[text]; BORDER:2x solid $color[bgcol] }
 #title {font:20pt $langs[font]; color: echo $color[n0_bg] }
-->
</STYLE>
</HEAD>

<BODY BACKGROUND=\"$color[image]\" BGCOLOR=\"$color[bgcol]\" TEXT=\"$color[text]\" LINK=\"$color[link]\" VLINK=\"$color[vlink]\" ALINK=\"$color[alink]\">

<font id=title><b><li type=disc>JSBoard FormMail Service</b></font>
<p>
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" BGCOLOR=\"$color[r0_bg]\"><TR><TD>
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<FORM METHOD=\"post\" ACTION=\"act.php\" ENCTYPE=\"multipart/form-data\">
<TR>
  <TD BGCOLOR=\"$color[r1_bg]\" width=13%><FONT COLOR=\"$color[r1_fg]\" $board[css]>To</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\">$sinfo[email]</TD>
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>From</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[email]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\" VALUE=\"$board_cookie[email]\"></TD>
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[name]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[name]\" SIZE=\"$size[name]\" MAXLENGTH=\"255\"></TD>
</TR><TR>
  <TD BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\" $board[css]>$langs[titl]</FONT></TD>
  <TD BGCOLOR=\"$color[r2_bg]\"><INPUT TYPE=\"text\" NAME=\"atc[title]\" SIZE=\"$size[titl]\" MAXLENGTH=\"100\"></TD>
</TR><TR>
  <TD COLSPAN=\"2\" ALIGN=\"center\" BGCOLOR=\"$color[r2_bg]\">
    <TEXTAREA NAME=\"atc[text]\" $wrap ROWS=\"10\" COLS=\"$size[text]\"></TEXTAREA>
  </TD>
</TR><TR>
  <TD COLSPAN=\"2\" ALIGN=\"right\" BGCOLOR=\"$color[r1_bg]\">
    <FONT COLOR=\"$color[r1_fg]\" SIZE=\"-1\" $board[css]>Scripted by JoungKyun Kim</FONT>
  </TD>
</TR>
</TABLE>

</TD></TR>
<TR><TD>

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"6\" CELLSPACING=\"0\">
<TR>
  <TD ALIGN=\"center\">
    <FONT SIZE=\"-1\" COLOR=\"$color[l0_fg]\" $board[css]>
    <INPUT TYPE=\"hidden\" NAME=\"o[at]\" VALUE=\"sm\">
    <INPUT TYPE=\"hidden\" NAME=\"atc[to]\" VALUE=\"$sinfo[email]\">
    <INPUT TYPE=\"submit\" VALUE=\"$langs[b_send]\">&nbsp;
    <INPUT TYPE=\"reset\" VALUE=\"$langs[b_reset]\">&nbsp;
    <INPUT TYPE=\"button\" onClick=\"window.close()\" VALUE=\"$langs[b_can]\">
    </FONT>
  </TD>
</TR>
</TABLE>

</TD></TR>
</FORM>
</TABLE>\n";
} else {
  @include("include/error.ph");
  @include("include/check.ph");

  meta_char_check($table,0,1);
  meta_char_check($f[c]);
  meta_char_check($upload[dir]);
  if(!$f[n] || eregi("\.\./",$f[n])) {
    echo "<script>\n".
         "alert('U attempted invalid method in this program!');\n".
         "history.back();\n".
         "</script>\n";
    exit;
  }

  echo "<HEAD>\n".
       "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=$langs[charset]\">\n".
       "<TITLE>JSBoard - VIEW ORIGINAL IMAGE</TITLE>\n".
       "</HEAD>\n".
       "<BODY bgcolor=white leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>\n".
       "<a href=javascript:window.close()>".
       "<img src=./data/$table/$upload[dir]/$f[c]/$f[n] width=$f[w] height=$f[h] border=0>".
       "</a>\n";
}
?>

</BODY>
</HTML>
