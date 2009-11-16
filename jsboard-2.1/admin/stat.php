<?php
# This flie applied under GPL License
# $Id: stat.php,v 1.3 2009-11-16 21:52:46 oops Exp $
if ( preg_match ('/user_admin/', $_SERVER['HTTP_REFERER']) ) $path['type'] = "user_admin";
else $path['type'] = "admin";
require_once './include/admin_head.php';

if ( ! session_is_registered ($jsboard) && $_SESSION[$jsboard]['pos'] != 1 )
  print_error ($_('login_err'));

htmlhead();
?>

<table width="100%" border=0 cellpadding=10 cellspacing=0>
<tr><td class="gbtitle">
JSBoard [ <?=$table?> DB ] Statistics
</td></tr>

<tr align="center"><td>
<!-- ========================= 통계 시작        ========================= -->

<br>

<?
$c = sql_connect($db['server'], $db['user'], $db['pass'], $db['name']);

function get_stat ($table, $interval) {
    global $debug, $c, $db;
    $debug = 1;

    $intv = ( $interval == 0) ? 0 : time () - $interval;

    # 전체 글 갯수
    $result = sql_query ("SELECT COUNT(*) as co FROM $table WHERE date > '$intv'", $c);
    $count['all'] = sql_result ($result, 0, 'co');
    sql_free_result ($result);

    # 답장 글 갯수
    $result = sql_query ("SELECT COUNT(*) as co FROM $table WHERE date > '$intv' AND reno != 0", $c);
    $count['rep'] = sql_result ($result, 0, 'co');
    sql_free_result ($result);

    # 보통 글 갯수
    $count['nor'] = $count['all'] - $count['rep'];

    # 처음 글
    $result = sql_query ("SELECT * FROM $table WHERE date > '$intv' ORDER BY no LIMIT 1", $c);
    $article['min'] = sql_fetch_array ($result);
    sql_free_result ($result);

    # 마지막 글
    $result = sql_query ("SELECT * FROM $table WHERE date > '$intv' ORDER BY no DESC LIMIT 1", $c);
    $article['max'] = sql_fetch_array ($result);
    sql_free_result ($result);

    if($interval) $article['time'] =  $interval;
    else $article['time'] =  $article['max']['date'] - $article['min']['date'];

    # 최고 조회수
    $result = sql_query ("SELECT MAX(refer) as maxref FROM $table WHERE date > '$intv'", $c);
    $refer['max'] = sql_result ($result, 0, 'maxref');
    sql_free_result ($result);

    # 최고 조회수 글 번호
    if ( $refer['max'] ) {
      $result = sql_query ("SELECT no FROM $table WHERE refer = '{$refer['max']}' AND date > '$intv'", $c);
      $refer['mno'] = sql_result ($result, 0, 'no');
      sql_free_result ($result);
    }

    # 최저 조회수
    $result = sql_query("SELECT MIN(refer) as min FROM $table WHERE date > '$intv'", $c);
    $refer['min'] = sql_fetch_array($result, 0, 'min');
    sql_free_result($result);

    # 조회수 합계
    $result = sql_query("SELECT SUM(refer) as sum FROM $table WHERE date > '$intv'", $c);
    $refer['total'] = sql_result($result, 0, 'sum');
    sql_free_result($result);
    
    # 평균 조회수
    if($count['all']) $refer['avg'] = intval($refer['total'] / $count['all']);

    $stat['count'] = $count;
    $stat['refer'] = $refer;
    $stat['artic'] = $article;
    $stat['name']  = $name;

    return $stat;
}

function display_stat ($stat, $title) {
  global $table, $_;

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

  $per['per'] = ( $count['nor'] == 0) ? $per['rep'] * $count['rep'] : 100 * ($count['rep'] / $count['nor']);

  $str['count'] = sprintf ("
<table width=\"200\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"st_tableguide\"><tr><td>
<table width=\"100%%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
<tr>
  <td colspan=\"3\" class=\"st_td_value\" style=\"text-align: left\">" . $_('st_ar_no') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_pub') . "</td>
  <td class=\"st_td_value\">%d " . $_('st_ea') . "</td>
  <td width=\"1%%\" class=\"st_td_value\">%0.2f%%</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_rep') . "</td>
  <td align=\"right\" class=\"st_td_value\">%d " . $_('st_ea') . "</td>
  <td width=\"1%%\" class=\"st_td_value\">%0.2f%%</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_rep') . $_('st_per') . "</td>
  <td colspan=\"2\" class=\"st_td_value\">%0.2f%%</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_tot') . "</td>
  <td colspan=\"2\" class=\"st_td_value\">%d " . $_('st_ea') . "</td>
</tr>
</table>
</td></tr></table>\n",
  $count['nor'], $per['nor'], $count['rep'], $per['rep'], $per['per'], $count['all']);

  if ( ! $count['all'] || !$article['time'] ) {
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

  $str['avg'] = sprintf ("
<table width=\"150\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"st_tableguide\"><tr><td>
<table width=\"100%%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
<tr>
  <td colspan=\"2\" class=\"st_td_value\" style=\"text-align: left\">" . $_('st_a_ar_no') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_year') . "</td>
  <td class=\"st_td_value\">%0.2f " . $_('st_ea') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_mon') . "</td>
  <td class=\"st_td_value\">%0.2f " . $_('st_ea') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_day') . "</td>
  <td class=\"st_td_value\">%0.2f " . $_('st_ea') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_hour') . "</td>
  <td class=\"st_td_value\">%0.2f " . $_('st_ea') . "</td>
</tr>
</table>
</td></tr></table>\n", 
  $for['year'], $for['month'], $for['day'], $for['hour']);

  $str['refer'] = sprintf ("
<table width=\"150\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"st_tableguide\"><tr><td>
<table width=\"100%%\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
<tr>
  <td colspan=\"2\" class=\"st_td_value\" style=\"text-align: left\">" . $_('st_read') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_max') . "</td>
  <td class=\"st_td_value\">%d " . $_('st_read_no') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_no') . "</td>
  <td class=\"st_td_value\"><a href=\"../read.php?table=%s&amp;no=%d\" target=_blank>%d " . $_('st_read_no_ar') . "</A></td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_ever') . "</td>
  <td class=\"st_td_value\">%0.2f " . $_('st_read_no') . "</td>
</tr><tr>
  <td width=\"1%%\" class=\"st_td_title\">" . $_('st_tot') . "</td>
  <td class=\"st_td_value\">%d " . $_('st_read_no') . "</td>
</tr>
</table>
</td></tr></table>\n", 
  $refer['max'], $table, $refer['mno'], $refer['mno'], $per['avg'], $refer['total']);

  printf("
<table width=\"1%%\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\">
<tr>
  <td style=\"vertical-align: top; font-weight: bold;\" width=\"1\">{$title}</td>
  <td style=\"vertical-align: top;\" width=\"1%%\">%s</td>
  <td style=\"vertical-align: top;\" width=\"1%%\">%s</td>
  <td style=\"vertical-align: top;\" width=\"1%%\">%s</td>
</tr>
</table>\n", $str['count'], $str['refer'], $str['avg']);
}

# 주별
$stat = get_stat ($table, 60*60*24*7);
display_stat ($stat, $_('st_lweek'));

# 월별
$stat = get_stat ($table, 60*60*24*30);
display_stat ($stat, $_('st_lmonth'));

# 반년별
$stat = get_stat ($table, 60*60*24*30*6);
display_stat ($stat, $_('st_lhalfyear'));

# 년별
$stat = get_stat ($table, (60*60*24*30*12) + (60*60*24*5));
display_stat ($stat, $_('st_lyear'));

# 전체
$stat = get_stat ($table, 0);
display_stat ($stat, $_('st_ltot'));

echo "\n<br>\n</td></tr>\n\n".
     "<tr><td class=\"fieldtitle\">\n".
     "Copyright by <a href=\"http://jsboard.kldp.net\"><span class=\"fieldtitle\">JSBoard Open Project</span></a><br>\n".
     "and all right reserved\n</td></tr>\n</table>\n";

htmltail();
?>
