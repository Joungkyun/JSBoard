<?
# This flie applied under GPL License
session_start();
$path[type] = "admin";
if (!file_exists("../config/global.ph")) {
  echo "<script>\nalert('Don\'t exist global\\nconfiguration file');\n" .
       "history.back();\nexit;\n</script>\n";
} else { include("../config/global.ph"); }
if ($color[theme]) { include("../config/default.themes"); }

include("../include/lang.ph");
include("../include/check.ph");
include("../include/error.ph");
include("../include/get.ph");
include("../include/sql.ph");
include("include/check.ph");
include("include/config.ph");

// password 비교함수 - admin/include/check.ph
compare_pass($sadmin,$login);

if(!$table) print_error($table_err);
include("../data/$table/config.ph");
if ($color[theme]) { include("../data/$table/default.themes"); }

require("include/html_ahead.ph");

echo "<table width=100% border=0 align=center>\n" .
     "<tr align=center><td bgcolor=$color[r1_bg]><font id=title>JSBoard [ $table DB ] Statistics</font></td></tr>\n\n" .
     "<tr align=center><td>\n" .
     "<!----------------- 통계 시작 ------------------->\n\n<p><br>";

sql_connect($db[server], $db[user], $db[pass]);
sql_select_db($db[name]);

function get_stat($table, $interval)
{
    global $debug;
    $debug = 1;

    if($interval == 0) $intv = 0;
    else $intv = time() - $interval;

    // 전체 글 갯수
    $result     = sql_query("SELECT COUNT(*) FROM $table WHERE date > $intv");
    $count[all] = sql_result($result, 0, "COUNT(*)");
    sql_free_result($result);

    // 답장 글 갯수
    $result     = sql_query("SELECT COUNT(*) FROM $table WHERE date > $intv AND reno != 0");
    $count[rep] = sql_result($result, 0, "COUNT(*)");
    sql_free_result($result);

    // 보통 글 갯수
    $count[nor] = $count[all] - $count[rep];

    // 처음 글
    $result       = sql_query("SELECT * FROM $table WHERE date > $intv ORDER BY no LIMIT 0, 1");
    $article[min] = sql_fetch_array($result);
    sql_free_result($result);

    // 마지막 글
    $result       = sql_query("SELECT * FROM $table WHERE date > $intv ORDER BY no DESC LIMIT 0, 1");
    $article[max] = sql_fetch_array($result);
    sql_free_result($result);

    if($interval) {
	$article[time] =  $interval;
    } else {
	$article[time] =  $article[max][date] - $article[min][date];
    }

    // 최고 조회수
    $result     = sql_query("SELECT MAX(refer) FROM $table WHERE date > $intv");
    $refer[max] = sql_result($result, 0, "MAX(refer)");
    sql_free_result($result);

    // 최고 조회수 글 번호
    if($refer[max]) {
	$result     = sql_query("SELECT no FROM $table WHERE refer = $refer[max] AND date > $intv");
	$refer[mno] = sql_result($result, 0, "no");
	sql_free_result($result);
    }

    // 최저 조회수
    $result     = sql_query("SELECT MIN(refer) FROM $table WHERE date > $intv");
    $refer[min] = sql_fetch_array($result, 0, "MIN(refer)");
    sql_free_result($result);

    // 조회수 합계
    $result       = sql_query("SELECT SUM(refer) FROM $table WHERE date > $intv");
    $refer[total] = sql_result($result, 0, "SUM(refer)");
    sql_free_result($result);
    
    // 평균 조회수
    if($count[all]) {
	$refer[avg] = intval($refer[total] / $count[all]);
    }

/*
    // 글쓴이
    $result = sql_query("SELECT COUNT(*) AS COUNT, name FROM $table WHERE date > $intv GROUP BY name ORDER BY COUNT DESC LIMIT 0, 4");
    while($list = sql_fetch_array($result)) {
	$name[] = $list;
    }
    sql_free_result($result);
*/

    $stat[count] = $count;
    $stat[refer] = $refer;
    $stat[artic] = $article;
    $stat[name]  = $name;

    return $stat;
}

function display_stat($stat, $title)
{
    global $table, $color;

    $count   = $stat[count];
    $refer   = $stat[refer];
    $article = $stat[artic];
    $name    = $stat[name];

/*
    $str[name]  = sprintf("
<TABLE WIDTH=\"250\" BGCOLOR=\"$color[l0_bg]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"#2a2a2a\"><FONT COLOR=\"$color[l1_fg]\">글쓴이</FONT></TD>
  <TD COLSPAN=\"2\" BGCOLOR=\"#4a4a4a\"><FONT COLOR=\"$color[l1_fg]\">글 수</FONT></TD>");
for($i = 0; $i < 4; $i++) {
    if($count[all] == 0) {
	$per[nam] = 0;
    } else {
	$per[nam] = 100 * ($name[$i][COUNT] / $count[all]);
    }

    $str[name] .= sprintf("
</TR><TR>
  <TD WIDTH=\"40%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR>%s</NOBR></TD>
  <TD ALIGN=\"right\" WIDTH=\"30%%\" BGCOLOR=\"$color[l2_bg]\">%d 개</TD>
  <TD ALIGN=\"right\" WIDTH=\"20%%\" BGCOLOR=\"$color[l2_bg]\"><NOBR>%0.2f%%</NOBR></TD>", $name[$i][name], $name[$i][COUNT], $per[nam]);
}
    $str[name] .= sprintf(" 
</TR>
</TABLE>
</TD></TR></TABLE>\n");
*/

    if($count[all] == 0) {
	$per[nor] = 0;
	$per[rep] = 0;
	$per[avg] = 0;
    } else {
	$per[nor] = 100 * ($count[nor] / $count[all]);
	$per[rep] = 100 * ($count[rep] / $count[all]);
	$per[avg] = $refer[total] / $count[all];
    }

    if($count[nor] == 0) {
	$per[per] = $per[rep] * $count[rep];
    } else {
	$per[per] = 100 * ($count[rep] / $count[nor]);
    }

    $str[count] = sprintf("
<TABLE WIDTH=\"200\" BGCOLOR=\"$color[l0_bg]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"$color[l1_bg]\" COLSPAN=\"3\"><FONT COLOR=\"$color[l1_fg]\">글 수</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">보통</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%d 개</FONT></TD>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><NOBR><FONT COLOR=\"$color[l2_fg]\">%0.2f%%</FONT></NOBR></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">답장</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%d 개</FONT></TD>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><NOBR><FONT COLOR=\"$color[l2_fg]\">%0.2f%%</FONT></NOBR></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">답장률</FONT></NOBR></TD>
  <TD COLSPAN=\"2\" ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f%%</FONT></TD>
</TR>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">합계</FONT></NOBR></TD>
  <TD COLSPAN=\"2\" ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%d 개</FONT></TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n",
    $count[nor], $per[nor], $count[rep], $per[rep], $per[per], $count[all]);

    if (!$count[all] || !$article[time]) {
      $for[year] = 0;
      $for[month] = 0;
      $for[day] = 0;
      $for[hour] = 0;
    } else {
      $for[year] = $count[all] / ($article[time] / ((60*60*24*30*12) + (60*60*24*5)));
      $for[month] = $count[all] / ($article[time] / (60*60*24*30));
      $for[day] = $count[all] / ($article[time] / (60*60*24));
      $for[hour] = $count[all] / ($article[time] / (60*60));
    }

    $str[avg] = sprintf("
<TABLE WIDTH=\"150\" BGCOLOR=\"$color[l0_bg]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"$color[l1_bg]\" COLSPAN=\"2\"><FONT COLOR=\"$color[l1_fg]\">평균 글 수</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><FONT COLOR=\"$color[l3_fg]\">年</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f 개</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><FONT COLOR=\"$color[l3_fg]\">月</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f 개</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><FONT COLOR=\"$color[l3_fg]\">日</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f 개</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><FONT COLOR=\"$color[l3_fg]\">侍</FONT></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f 개</FONT></TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n", 
    $for[year],$for[month],$for[day],$for[hour]);

    $str[refer] = sprintf("
<TABLE WIDTH=\"150\" BGCOLOR=\"$color[l0_bg]\" BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"0\"><TR><TD>
<TABLE WIDTH=\"100%%\" BORDER=\"0\" CELLSPACING=\"1\" CELLPADDING=\"4\">
<TR>
  <TD BGCOLOR=\"$color[l1_bg]\" COLSPAN=\"2\"><FONT COLOR=\"$color[l1_fg]\">조회수</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">최고</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%d 번</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">글번호</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><A HREF=\"../read.php3?table=%s&no=%d\" target=_blank><FONT COLOR=\"$color[l2_fg]\">%d 번 글</FONT></A></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">평균</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%0.2f 번</FONT></TD>
</TR><TR>
  <TD WIDTH=\"1%%\" ALIGN=\"right\" BGCOLOR=\"$color[l3_bg]\"><NOBR><FONT COLOR=\"$color[l3_fg]\">합계</FONT></NOBR></TD>
  <TD ALIGN=\"right\" BGCOLOR=\"$color[l2_bg]\"><FONT COLOR=\"$color[l2_fg]\">%d 번</TD>
</TR>
</TABLE>
</TD></TR></TABLE>\n", 
    $refer[max],$table, $refer[mno], $refer[mno],$per[avg],$refer[total]);

    printf("
<TABLE WIDTH=\"1%%\" BORDER=\"0\" CELLSPACING=\"3\" CELLPADDING=\"0\">
<TR>
  <TD VALIGN=\"top\" WIDTH=\"1\"><FONT COLOR=\"$color[n0_fg]\">$title</FONT></TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
  <TD VALIGN=\"top\" WIDTH=\"1%%\">%s</TD>
</TR>
</TABLE>\n", $str[count], $str[refer], $str[avg]);
}

// 주별
$stat = get_stat($table, 60*60*24*7);
display_stat($stat,"최 근 한 주");

// 월별
$stat = get_stat($table, 60*60*24*30);
display_stat($stat,"최 근 한 달");

// 반년별
$stat = get_stat($table, 60*60*24*30*6);
display_stat($stat,"최 근 반 년");

// 년별
$stat = get_stat($table, (60*60*24*30*12) + (60*60*24*5));
display_stat($stat,"최 근 일 년");

// 전체
$stat = get_stat($table, 0);
display_stat($stat,"전 체");

echo "\n<br>\n</td></tr>\n\n" .
     "<tr align=center><td bgcolor=$color[r1_bg]>\n<font color=$color[text]>\n" .
     "Copyright by <a href=http://jsboard.kldp.org><font color=$color[link]>JSBoard Open Project</font></a><br>\n" .
     "and all right reserved\n</font>\n</td></tr>\n</table>\n";

require("include/html_atail.ph");
?>
