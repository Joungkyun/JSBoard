<?
########################################################################
# JSBoard Pre List v0.4
# Scripted By JoungKyun Kim 2002.07.30
########################################################################

# JSBoard가 설치되어 있는 절대 경로
# 마지막에 /를 붙이면 안됨
$prlist[path] = "/webroot/jsboard-version";

# JSBoard가 설치되어 있는 웹 경로
# 마지막에 /를 붙이면 안됨
$prlist[wpath] = "http://domain.com/jsboard-version";

include_once "$prlist[path]/config/global.ph";
include_once "$prlist[path]/include/error.ph";
include_once "$prlist[path]/include/parse.ph";
include_once "$prlist[path]/include/check.ph";
include_once "$prlist[path]/include/sql.ph";
include_once "$prlist[path]/include/get.ph";
include_once "$prlist[path]/include/print.ph";

print_preview_src(1);

# 글리스트들을 출력하는 design
#   echo 문의 "" 사이에서 디자인을 넣으면 됨
#   단 주의 할것은 따옴표(")는 \" 로 표기를 해야 함
#   $p[link]  -> 글 리스트의 링크
#   $p[name]  -> 글쓴이
#   $p[date]  -> 등록일
#   $p[count] -> 조회수
#
# table tag를 사용하기 위해서는 아래의 prelist() 함수를
# 잘 연계해야함
#
# $prlistTemplate 라는 변수가 정의 되어 있을 경우에는 이 변수의
# 디자인을 이용하여 출력
#
function print_prlist($p) {
  $temp = trim($GLOBALS[prlistTemplate]) ? $GLOBALS[prlistTemplate] : "";
  if ($temp) {
    $src[] = "/P_SUBJECT_/i";
    $des[] = $p[link]; 
    $src[] = "/P_NAME_/i";
    $des[] = $p[name];
    $src[] = "/P_DATE_/i";
    $des[] = $p[date];
    $src[] = "/P_EMAIL_/i";
    $des[] = $p[email];
    $src[] = "/P_REFER_/i";
    $des[] = $p[count];
    if ($p[email]) {
      $src[] = "/P_LNAME_/i";
      $des[] = "<A HREF=mailto:$p[email]>$p[name]</A>";
    } else {
      $src[] = "/P_LNAME_/i";
      $des[] = "$p[name]";
    }

    echo preg_replace($src,$des,$temp)."\n";
  } else {
    echo "$p[link] $p[name] $p[date] $p[count]<BR>\n";
  }
}

# PHP에 대해서 잘 모르신다고 생각하시는 분들은 건드리지 말것!!!
# table 이름
# $limit 글수
# $cut 출력글자수
#
function prelist($t,$limit=3,$cut=30) {
  global $prlist, $db;

  sql_connect($db[server], $db[user], $db[pass]);
  sql_select_db($db[name]);

  $sql = "SELECT * FROM $t ORDER BY date DESC LIMIT $limit";
  $result = sql_query($sql);
  $total = sql_num_rows($result);

  for ($i=0;$i<$total;$i++) {
    mysql_data_seek($result,$i);
    $row = mysql_fetch_array($result);

    $p[no] = $row[no];
    $p[title] = $row[title];

    $p[name] = $row[name];
    $p[date] = date("y.m.d",$row[date]);
    $p[email] = $row[email];
    $p[count] = $row[refer];
    $p[l] = " ".$GLOBALS[prlistOpt];

    $p[preview] = cut_string(htmlspecialchars($row[text]),100);
    $p[preview] = preg_replace("/#|'|\\\\/i","\\\\\\0",$p[preview]);
    $p[preview] = htmlspecialchars(htmlspecialchars($p[preview]));
    $p[preview] = preg_replace("/\r*\n/i","<BR>",$p[preview]);
    $p[preview] = trim(str_replace("&amp;amp;","&amp;",$p[preview]));
    $p[preview] = " onMouseOver=\"drs('$p[preview]'); return true;\" onMouseOut=\"nd(); return true;\"";

    if($cut) {
      if(strlen($p[title]) > $cut)
        { $p[title] = cut_string($p[title],$cut).".."; }
    }

    $p[link] = "<a href=$prlist[wpath]/read.php?table=$t&no=$p[no]$p[l] $p[preview]>$p[title]</a>";

    #리스트 출력
    print_prlist($p);
  }

  sql_free_result($result);
  mysql_close();
}

?>
