<?
include "include/header.ph";
include "html/head.ph";

if($enable[amark])
  $admin_link = "[ <a href=./admin/user_admin/auth.php?table=$table title=\"$langs[ln_titl]\"><font color=$color[n0_fg]>admin</font></a> ]";

if($table == $enable[security]) {
  include "include/security.ph";
  $board[secwarn] = get_security_info();
  if($board[secwarn] == "warning")
    $admin_link = "[ <a href=security.php?table=$table><font color=$color[n0_fg]>warnning</font></a> ]";
} elseif(!$enable[security]) {
  $langs[sec_error] = str_replace("\n","\\n",$langs[sec_error]);
  echo "<SCRIPT>alert('$langs[sec_error]')</SCRIPT>";
}

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

$agent = get_agent();

# 게시판의 전체, 보통, 답장, 오늘 올라온 글 수 등을 가져옴
$count = get_board_info($table);
# 전체 페이지와 현재 페이지에 관련된 정보를 가져옴
$pages = get_page_info($count, $page);

# 오늘 올라온 글이 있는 경우 $str[today]에 문자열을 넣어 목록을 출력할 때
# 출력되도록 함
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

# 관련글 리스트 출력시 preview 기능 사용할때 필요한 JavaScript 출력
if ($enable[pre]) print_preview_src();

# 게시판 목록 제목줄 출력
echo "
<DIV ALIGN=\"$board[align]\">
<!------ 상단 메뉴 시작 --------->
<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>
  <TD VALIGN=bottom><nobr>$icons[add]$admin_link</nobr></TD>
  <TD ALIGN=right VALIGN=bottom>";

# 게시판 목록 상단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
if ($board[cmd] == "yes" && $board[img] != "yes") {
  $str[align] = "";
  list_cmd($str);
} elseif($enable[dhost]) {
  $list[dhost] = get_hostname($enable[dlook]);
  if($enable[dwho]) 
    $list[hlinked] = "<a href=javascript:new_windows('./whois.php?table=$table&host=$list[dhost]',0,1,0,600,480)>".
                      "<font color=$color[text]>$list[dhost]</font></a>";
  else $list[hlinked] = "<font color=$color[text]>$list[dhost]</font>";
  echo "$langs[remote] [ $list[hlinked] ]$icons[add]";
} else echo "&nbsp;";

echo "</TD>
</TR>
</TABLE>
<!------ 상단 메뉴 끝 --------->
<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">
<TR>";

# image menu bar 출력
if ($board[img] == "yes") {
  echo "<TD rowspan=2 width=$icons[td] align=right valign=top>";
  img_lmenu($str,$icons[size]);
  echo "</TD>\n";
}

echo "<TD valign=top BGCOLOR=\"$color[l0_bg]\">
<TABLE WIDTH=\"100%\" border=\"0\" CELLSPACING=\"1\" CELLPADDING=\"3\">
<TR>
  <TD WIDTH=\"$td_width[1]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\" NOWRAP><FONT COLOR=\"$color[l1_fg]\" $board[css]><NOBR>$langs[no]</NOBR></FONT></TD>
  <TD WIDTH=\"$td_width[2]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\" $board[css]>$langs[titl]</FONT></TD>
  <TD WIDTH=\"$td_width[3]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\" $board[css]>$langs[name]</FONT></TD>\n";
  
if ($upload[yesno] == "yes") {
  if ($cupload[yesno] == "yes")
    echo "  <TD WIDTH=\"$td_width[4]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\" $board[css]>$langs[file]</FONT></TD>";
}

echo "
  <TD WIDTH=\"$td_width[5]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\"><FONT COLOR=\"$color[l1_fg]\" $board[css]>$langs[date]</FONT></TD>
  <TD COLSPAN=\"2\" WIDTH=\"$td_width[6]\" ALIGN=\"center\" BGCOLOR=\"$color[l1_bg]\" NOWRAP><FONT COLOR=\"$color[l1_fg]\" $board[css]><NOBR>$langs[hit]</NOBR></FONT></TD>
</TR>\n";

$c_time[] = microtime(); # 속도 체크

get_list($table, $pages);

$c_time[] = microtime(); # 속도 체크
$time = get_microtime($c_time[0], $c_time[1]);

echo "
<TR>
  <TD COLSPAN=\"$colspan\" ALIGN=\"right\" BGCOLOR=\"$color[l1_bg]\">
    <FONT COLOR=\"$color[l1_fg]\" SIZE=\"-1\" $board[css]>$str[count] [ $time sec ]</FONT>
  </TD>
</TR>
</TABLE>

</TD>\n";

# image menu bar 출력
if ($board[img] == "yes") {
  if($color[bgcol] != $color[l4_bg]) $srowspan = " rowspan=2";
  echo "<TD$srowspan width=$icons[td] valign=bottom>";
  img_lmenu($str,$icons[size]);
  echo "</TD>\n";
}

if($color[bgcol] == $color[l4_bg]) {
  $board[cs] = "0";
  $board[blank] = "<img src=images/blank.gif width=10 height=6 border=0><br>";
} else $board[cs] = "6";

echo "</TR>
<TR><TD BGCOLOR=\"$color[l4_bg]\">
$board[blank]
<TABLE WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"$board[cs]\" ALIGN=\"center\">
<TR>
  <TD VALIGN=\"top\" ROWSPAN=\"2\">$str[s_form]</TD>
  <TD VALIGN=\"top\" ALIGN=\"right\">$str[p_list]</TD>
</TR><TR>
  <TD ALIGN=\"right\" VALIGN=\"bottom\">$str[p_form]</TD>
</TR>
</TABLE>
</TD>";

if($board[img] == "yes" && $color[bgcol] == $color[l4_bg]) echo "\n<TD>&nbsp;</TD>\n";

echo "</TR></TABLE>\n".
     "<TABLE WIDTH=\"$board[width]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n".
     "<TR><td>\n";

if ($board[img] != "yes") {
  # 게시판 목록 하단에 다음, 이전 페이지, 글쓰기 등의 링크를 출력
  $str[align] = "ALIGN=\"center\"";
  list_cmd($str);
}

echo "</TD></TR>\n".
     "</TABLE>\n</DIV>\n";

include "html/tail.ph";
?>
