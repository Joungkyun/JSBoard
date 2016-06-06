<?
require("include/header.ph");
require("html/head.ph");

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$agent = get_agent();

$c_time[] = microtime(); // 속도 체크

// 게시판의 전체, 보통, 답장, 오늘 올라온 글 수 등을 가져옴
$count = get_board_info($table);
// 전체 페이지와 현재 페이지에 관련된 정보를 가져옴
$pages = get_page_info($count, $page);

// 오늘 올라온 글이 있는 경우 $str[today]에 문자열을 넣어 목록을 출력할 때
// 출력되도록 함
$msg = count_msg();

if($count[all]) {
  $str[count] = "$msg[count1] ";
  if ($o[at] == 's') $str[count] .= "$msg[count2] ";
  else $str[count] .= "$msg[count3]";
  if ($count[today]) $str[count] .= "$msg[count4]";
} else {
  $str[count] = "$msg[count5]";
}

$str[p_list] = page_list($table, $pages, $count, $board[plist]);
$str[s_form] = search_form($table, $pages);
$str[p_form] = page_form($table, $pages, $color[l0_fg]);

if ($board[img] == "yes") {
  $icons[add] = "<img src=./images/blank.gif width=$icons[size] border=0>";
  if (eregi("%",$board[width])) $icons[td]  = "1%";
  else $icons[td] = $icons[size];
}

// 게시판 목록 제목줄 출력
echo "
<!------ 상단 메뉴 시작 --------->
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>
  <TD VALIGN=bottom><nobr>$icons[add][ <a href=./admin/user_admin/auth.php3?table=$table title=\"$langs[ln_titl]\">admin</a> ]</nobr></TD>
  <TD ALIGN=right VALIGN=bottom>";

// 게시판 목록 상단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
$remote = get_hostname();
if ($board[cmd] == "yes" && $board[img] != "yes") {
  $str[align] = "";
  list_cmd($str);
} else echo("$langs[remote] [ $remote ]$icons[add]");

echo "</TD>
</TR>
</TABLE>
<!------ 상단 메뉴 끝 --------->
<TABLE ALIGN=\"center\" WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>";

// image menu bar 출력
if ($board[img] == "yes") {
  echo "<TD rowspan=2 width=$icons[td] align=right valign=top>";
  img_lmenu($str,$icons[size]);
  echo "</TD>\n";
}

echo "<TD valign=top BGCOLOR=\"$color[l0_bg]\">
<TABLE WIDTH=\"100%\" border=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<TR>
  <TD WIDTH=\"$td_width[1]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\"><NOBR>$langs[no]</NOBR></FONT></TD>
  <TD WIDTH=\"$td_width[2]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\">$langs[titl]</FONT></TD>
  <TD WIDTH=\"$td_width[3]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\">$langs[name]</FONT></TD>\n";
  
if ($upload[yesno] == "yes") {
  if ($cupload[yesno] == "yes")
    echo "  <TD WIDTH=\"$td_width[4]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\">$langs[file]</FONT></TD>";
}

echo "
  <TD WIDTH=\"$td_width[5]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\">$langs[date]</FONT></TD>
  <TD COLSPAN=\"2\" WIDTH=\"$td_width[6]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\"><NOBR>$langs[hit]</NOBR></FONT></TD>
</TR>\n";

get_list($table, $pages);

$c_time[] = microtime(); // 속도 체크
$time = get_microtime($c_time[0], $c_time[1]);

echo "
<TR>
  <TD COLSPAN=\"$colspan\" ALIGN=\"right\" BGCOLOR=\"$color[l1_bg]\">
    <FONT COLOR=\"$color[l1_fg]\" SIZE=\"-1\">$str[count] [ $time sec ]</FONT>
  </TD>
</TR>
</TABLE>

</TD>\n";

// image menu bar 출력
if ($board[img] == "yes") {
  echo "<TD rowspan=2 width=$icons[td] valign=bottom>";
  img_lmenu($str,$icons[size]);
  echo "</TD>\n";
}

echo "</TR>
<TR><TD BGCOLOR=\"$color[l0_bg]\">

<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"6\" ALIGN=\"center\">
<TR>
  <TD VALIGN=\"top\" ROWSPAN=\"2\">$str[s_form]</TD>
  <TD VALIGN=\"top\" ALIGN=\"right\">$str[p_list]</TD>
</TR><TR>
  <TD ALIGN=\"right\" VALIGN=\"bottom\">$str[p_form]</TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n";

if ($board[img] != "yes") {
  // 게시판 목록 하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
  $str[align] = "ALIGN=\"center\"";
  list_cmd($str);
}

require("html/tail.ph");
?>
