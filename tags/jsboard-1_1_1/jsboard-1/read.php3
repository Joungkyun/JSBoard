<?
require("include/header.ph");
require("html/head.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$c_time[] = microtime(); // 속도 체크

if($num && !$no) {
    $num = get_article($table, $num, "no", "num");
    $no = $num[no];
}

$list = get_article($table, $no);
$page = get_current_page($table, $list[idx]); // 글이 위치한 페이지를 가져옴
$pos  = get_pos($table, $list[idx]); // 다음, 이전 글 번호를 가져옴

$list[num]  = print_reply($table, $list);
$list[date] = date("Y-m-d H:i:s", $list[date]);
$list[text] = text_nl2br($list[text], $list[html]);

$list       = search_hl($list);

if ($o[sc]) $list[text] = eregi_replace("<a href=(.*)<font color=#000000><b><u>(.*)</u></b></font>(.*) target=(.*)>","<a href=\\1\\2\\3 target=\\4>",$list[text]);

if($list[email])
  $list[name] = url_link($list[email], $list[name], $color[r3_fg], $no);

if($list[url]) {
  if(eregi("^http://", $list[url]))
    $list[name] .= " [" . url_link($list[url], "$langs[ln_url]", $color[r3_fg]) . "]";
  else
    $list[name] .= " [" . url_link("http://$list[url]", "$langs[ln_url]", $color[r3_fg]) . "]";
}


$str[s_form] = search_form($table, $pages);
$str[p_form] = page_form($table, $pages, $color[r0_fg]);
$str[sepa]   = separator($color[n0_fg]);

$c_time[] = microtime(); // 속도 체크
$time = get_microtime($c_time[0], $c_time[1]);

if ($board[img] == "yes") {
  $icons[add] = "<img src=./images/blank.gif width=$icons[size] border=0>";
  if (eregi("%",$board[width])) $icons[td]  = "1%";
  else $icons[td] = $icons[size];
}

echo "
<!------ 상단 메뉴 시작 --------->
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>
  <TD VALIGN=bottom><nobr>$icons[add][ <a href=./admin/user_admin/auth.php3?table=$table title=\"$langs[ln_titl]\">admin</a> ]</nobr></TD>
  <TD ALIGN=right VALIGN=bottom>";

// 게시판 읽기 페이지 상단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
$remote = get_hostname();
if ($board[cmd] == "yes" && $board[img] != "yes") {
  $str[align] = "";
  read_cmd($str);
} else
  echo("$langs[writerad] [ <a href=./whois.php3?table=$table&host=$list[host]&window=1><font color=$color[text]>$list[host]</font></a> ]$icons[add]");

echo "
</TD>
</TR>
</TABLE>
<!------ 상단 메뉴 끝 --------->

<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>";

// image menu bar 출력
if ($board[img] == "yes") {
  echo "<TD rowspan=2 width=$icons[td] align=right valign=top>";
  img_rmenu($str,$icons[size]);
  echo "</TD>\n";
}

echo "<TD valign=top BGCOLOR=\"$color[r0_bg]\">
<TABLE WIDTH=\"100%\" border=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<TR>
  <TD COLSPAN=\"3\" BGCOLOR=\"$color[r1_bg]\"><FONT COLOR=\"$color[r1_fg]\">$list[num]: $list[title]</FONT></TD>
</TR><TR>
  <TD WIDTH=\"$readp[name]\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$langs[name]: $list[name]</FONT></TD>
  <TD WIDTH=\"$readp[date]\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$langs[date]: $list[date]</FONT></TD>
  <TD WIDTH=\"$readp[read]\" BGCOLOR=\"$color[r2_bg]\"><FONT COLOR=\"$color[r2_fg]\">$langs[hit]: $list[refer]</FONT></TD>\n";

if($list[bofile]) {
  $hfsize = human_fsize($list[bfsize]);
  $tail = check_filetype($list[bofile]);
  $fileicon = icon_check($tail,$list[bofile]);
  $down_link = check_dnlink($table,$list);

  echo "</TR><TR>\n" .
       "   <TD COLSPAN=\"3\" BGCOLOR=\"$color[r4_bg]\">\n" .
       "   <A HREF=\"$down_link\">\n" .
       "   <IMG SRC=\"images/$fileicon\" width=16 height=16 border=0 alt=\"$list[bofile]\" align=texttop>\n" .
       "   <FONT COLOR=\"$color[r4_fg]\">$list[bofile]</FONT>\n" .
       "   </A>\n" .
       " <FONT COLOR=\"$color[r2_fg]\">- $hfsize</FONT>\n</TD>";
}

echo "
</TR><TR>
  <TD COLSPAN=\"3\" BGCOLOR=\"$color[r3_bg]\">

<TABLE ALIGN=\"center\" WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"4\" CELLPADDING=\"0\">
<TR>
  <TD valign=top height=60>
    <FONT COLOR=\"$color[r3_fg]\">\n";

if ($list[bofile]) {
  $tail = check_filetype($list[bofile]);
  $preview = viewfile($tail);
}

echo "
$preview[up]
<!-- =============================== 글내용 =============================== -->
$list[text]
<!-- =============================== 글내용 =============================== -->
$preview[down]\n";

echo "
    </FONT>
  </TD>
</TR>
</TABLE>\n\n";

# 글읽기에서 관련글 리스트 출력
if($enable[re_list] && ($list[reto] || $list[reyn])) article_reply_list($table,$pages);

echo "  </TD>
</TR>
</TABLE>

</TD>\n";

// image menu bar 출력
if ($board[img] == "yes") {
  echo "<TD rowspan=2 width=$icons[td] valign=bottom>";
  img_rmenu($str,$icons[size]);
  echo "</TD>\n";
}

echo "</TR>
<TR><TD BGCOLOR=\"$color[r0_bg]\">

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"6\" ALIGN=\"center\">
<TR>
  <TD VALIGN=\"top\" ROWSPAN=\"2\">
$str[s_form]
  </TD><TD ALIGN=\"right\">
    <FONT SIZE=\"-1\" COLOR=\"$color[r0_fg]\">[ $time sec ]</FONT>
  </TD>
</TR><TR>
  <TD ALIGN=\"right\" VALIGN=\"bottom\">
$str[p_form]
  </TD>
</TR>
</TABLE>
</TD></TR>
</TABLE>\n";

if ($board[img] != "yes") {
  // 게시판 읽기 페이지 하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
  $str[align] = "ALIGN=\"center\"";
  read_cmd($str);
}

if ($remote != $list[host]) sql_query("UPDATE $table SET refer = refer + 1 WHERE no = $no");

require("html/tail.ph");
?>
