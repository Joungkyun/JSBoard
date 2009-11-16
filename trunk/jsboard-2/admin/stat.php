<?php
# This flie applied under GPL License
if(preg_match("/user_admin/",$_SERVER['HTTP_REFERER'])) $path['type'] = "user_admin";
else $path['type'] = "admin";
include "./include/admin_head.php";

if(!session_is_registered("$jsboard") && $_SESSION[$jsboard]['pos'] != 1)
  print_error($langs['login_err']);

# table 을 체크한다.
table_name_check($table);

if($path['type'] != "admin") {
  include "../data/$table/config.php";
}
table_name_check($print['theme']);
include "../theme/{$print['theme']}/config.php";
include "../include/lang.php";

require "include/html_ahead.php";

echo "<table width=100% border=0 align=center>\n".
     "<tr align=center><td bgcolor={$color['t_bg']}>\n".
     "<font style=\"font-family:tahoma;font-size:22px;color:{$color['t_fg']};font-weight:bold\">\n".
     "JSBoard [ $table DB ] Statistics</font>\n".
     "</td></tr>\n\n".
     "<tr align=center><td>\n".
     "<!----------------- 통계 시작 ------------------->\n\n<p><br>";

sql_connect($db['server'],$db['user'],$db['pass']);
sql_select_db($db['name']);

function get_stat($table, $interval) {
    global $debug;
    $debug = 1;

    if($interval == 0) $intv = 0;
    else $intv = time() - $interval;

    # 전체 글 갯수
    $result = sql_query("SELECT COUNT(*) FROM $table WHERE date > '$intv'");
    $count['all'] = sql_result($result,0,"COUNT(*)");
    sql_free_result($result);

    # 답장 글 갯수
    $result = sql_query("SELECT COUNT(*) FROM $table WHERE date > '$intv' AND reno != 0");
    $count['rep'] = sql_result($result,0,"COUNT(*)");
    sql_free_result($result);

    # 보통 글 갯수
    $count['nor'] = $count['all'] - $count['rep'];

    # 처음 글
    $result = sql_query("SELECT * FROM $table WHERE date > '$intv' ORDER BY no LIMIT 0, 1");
    $article['min'] = sql_fetch_array($result);
    sql_free_result($result);

    # 마지막 글
    $result = sql_query("SELECT * FROM $table WHERE date > '$intv' ORDER BY no DESC LIMIT 0, 1");
    $article['max'] = sql_fetch_array($result);
    sql_free_result($result);

    if($interval) $article['time'] =  $interval;
    else $article['time'] =  $article['max']['date'] - $article['min']['date'];

    # 최고 조회수
    $result = sql_query("SELECT MAX(refer) FROM $table WHERE date > '$intv'");
    $refer['max'] = sql_result($result,0,"MAX(refer)");
    sql_free_result($result);

    # 최고 조회수 글 번호
    if($refer['max']) {
      $result = sql_query("SELECT no FROM $table WHERE refer = '{$refer['max']}' AND date > '$intv'");
      $refer['mno'] = sql_result($result,0,"no");
      sql_free_result($result);
    }

    # 최저 조회수
    $result = sql_query("SELECT MIN(refer) FROM $table WHERE date > '$intv'");
    $refer['min'] = sql_fetch_array($result,0,"MIN(refer)");
    sql_free_result($result);

    # 조회수 합계
    $result = sql_query("SELECT SUM(refer) FROM $table WHERE date > '$intv'");
    $refer['total'] = sql_result($result,0,"SUM(refer)");
    sql_free_result($result);
    
    # 평균 조회수
    if($count['all']) $refer['avg'] = intval($refer['total'] / $count['all']);

    $stat['count'] = $count;
    $stat['refer'] = $refer;
    $stat['artic'] = $article;
    $stat['name']  = $name;

    return $stat;
}

function display_stat($stat, $title) {
  global $table, $color, $langs;

  $count   = $stat['count'];
  $refer   = $stat['refer'];
  $article = $stat['artic'];
  $name    = $stat['name'];

  if($count['all'] == 0) {
    $per['nor'] = 0;
    $per['rep'] = 0;
    $per['avg'] = 0;
  } else {
    $per['nor'] = 100 * ($count['nor'] / $count['all']);
    $per['rep'] = 100 * ($count['rep'] / $count['all']);
    $per['avg'] = $refer['total'] / $count['all'];
  }

  if($count['nor'] == 0) $per['per'] = $per['rep'] * $count['rep'];
  else $per['per'] = 100 * ($count['rep'] / $count['nor']);

    $str['count'] = sprintf("
<TABLE WIDTH=\"200\" BGCOLOR=\"{$color['m_bg']}\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"{$color['d_bg']}\" COLSPAN=\"3\"><FONT COLOR=\"{$color['d_fg']}\">{$langs['st_ar_no']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_pub']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_ea']}</FONT></TD>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><NOBR><FONT COLOR=\"{$color['d_fg']}\">%0.2f%%</FONT></NOBR></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_rep']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_ea']}</FONT></TD>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><NOBR><FONT COLOR=\"{$color['d_fg']}\">%0.2f%%</FONT></NOBR></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_rep']}{$langs['st_per']}</FONT></NOBR></TD>
  <TD COLSPAN=\"2\" ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f%%</FONT></TD>
</TR>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_tot']}</FONT></NOBR></TD>
  <TD COLSPAN=\"2\" ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_ea']}</FONT></TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n",
  $count['nor'],$per['nor'],$count['rep'],$per['rep'],$per['per'],$count['all']);

  if (!$count['all'] || !$article['time']) {
    $for['year'] = 0;
    $for['month'] = 0;
    $for['day'] = 0;
    $for['hour'] = 0;
  } else {
    $for['year'] = $count['all'] / ($article['time'] / ((60*60*24*30*12) + (60*60*24*5)));
    $for['month'] = $count['all'] / ($article['time'] / (60*60*24*30));
    $for['day'] = $count['all'] / ($article['time'] / (60*60*24));
    $for['hour'] = $count['all'] / ($article['time'] / (60*60));
  }

  $str['avg'] = sprintf("
<TABLE WIDTH=\"150\" BGCOLOR=\"{$color['m_bg']}\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"{$color['d_bg']}\" COLSPAN=\"2\"><FONT COLOR=\"{$color['d_fg']}\">{$langs['st_a_ar_no']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_year']}</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f {$langs['st_ea']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_mon']}</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f {$langs['st_ea']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_day']}</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f {$langs['st_ea']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_hour']}</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f {$langs['st_ea']}</FONT></TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n", 
  $for['year'],$for['month'],$for['day'],$for['hour']);

  $str['refer'] = sprintf("
<TABLE WIDTH=\"150\" BGCOLOR=\"{$color['m_bg']}\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"{$color['d_bg']}\" COLSPAN=\"2\"><FONT COLOR=\"{$color['d_fg']}\">{$langs['st_read']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_max']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_read_no']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_no']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><A HREF=\"../read.php?table=%s&no=%d\" target=_blank><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_read_no_ar']}</FONT></A></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_ever']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%0.2f {$langs['st_read_no']}</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"{$color['m_bg']}\"><NOBR><FONT COLOR=\"{$color['m_fg']}\">{$langs['st_tot']}</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"{$color['d_bg']}\"><FONT COLOR=\"{$color['d_fg']}\">%d {$langs['st_read_no']}</TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n", 
  $refer['max'],$table,$refer['mno'],$refer['mno'],$per['avg'],$refer['total']);

  printf("
<TABLE WIDTH=\"1%%\" BORDER=\"0\" CELLSPACING=\"3\" CELLPADDING=\"0\">
<TR>
  <TD VALIGN=\"top\" WIDTH=\"1\"><FONT COLOR=\"{$color['text']}\">$title</FONT></TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
</TR>
</TABLE>\n",$str['count'],$str['refer'],$str['avg']);
}

# 주별
$stat = get_stat($table, 60*60*24*7);
display_stat($stat,$langs['st_lweek']);

# 월별
$stat = get_stat($table, 60*60*24*30);
display_stat($stat,$langs['st_lmonth']);

# 반년별
$stat = get_stat($table, 60*60*24*30*6);
display_stat($stat,$langs['st_lhalfyear']);

# 년별
$stat = get_stat($table, (60*60*24*30*12) + (60*60*24*5));
display_stat($stat,$langs['st_lyear']);

# 전체
$stat = get_stat($table, 0);
display_stat($stat,$langs['st_ltot']);

echo "\n<br>\n</td></tr>\n\n".
     "<tr align=center><td bgcolor={$color['t_bg']}>\n<font color={$color['t_fg']}>\n".
     "Copyright by <a href=http://jsboard.kldp.net><font color={$color['t_fg']}>JSBoard Open Project</font></a><br>\n".
     "and all right reserved\n</font>\n</td></tr>\n</table>\n";

require("include/html_atail.php");
?>
