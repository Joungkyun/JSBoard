<?
########################################################################
# JSBoard Pre List v0.2
# Scripted By JoungKyun Kim 2000.12.10
########################################################################

# JSBoard가 설치되어 있는 절대 경로
# 마지막에 /를 붙이면 안됨
$prlist[path] = "/webroot/jsboard-version";

# JSBoard가 설치되어 있는 웹 경로
# 마지막에 /를 붙이면 안됨
$prlist[wpath] = "http://domain.com/jsboard-version";

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
function print_prlist($p) {
  echo "$p[link] $p[name] $p[date] $p[count]<br>\n";
}

# PHP에 대해서 잘 모르신다고 생각하시는 분들은 건드리지 말것!!!
# table 이름
# inc include 여부
# $limit 글수
# $cut 출력글자수
# $cn 이름 출력
# $cd 등록일 출력
# $ce 이메일 출력
# $cc 조회수 출력
# $l 링크의 옵션 (예: target onClick 등등)
#
function prelist($t,$inc,$limit=3,$cut=30,$cn=0,$cd=0,$ce=0,$cc=0,$l=0) {
  global $prlist;

  include "$prlist[path]/config/global.ph";
  if($inc) {
    include "$prlist[path]/include/error.ph";
    include "$prlist[path]/include/parse.ph";
    include "$prlist[path]/include/check.ph";
    include "$prlist[path]/include/sql.ph";
    include "$prlist[path]/include/get.ph";
  }

  sql_connect($db[server], $db[user], $db[pass]);
  sql_select_db($db[name]);

  $sql = "SELECT * FROM $t ORDER BY date DESC LIMIT $limit";
  $result = sql_query($sql);
  $total = sql_num_rows($result);

  for ($i=0;$i<$total;$i++) {
    mysql_data_seek($result,$i);
    $row = mysql_fetch_array($result);

    $p[l] = $l ? " $l" : "";
    $p[no] = $row[no];
    $p[title] = $row[title];
    $p[name] = $cn ? $row[name] : "";
    $p[date] = $cd ? date("y.m.d",$row[date]) : "";
    $p[date] = $p[date] ? "- $p[date]" : "";
    $p[email] = $ce ? $row[email] : "";
    $p[count] = $cc ? $row[refer] : "";
    $p[preview] = cut_string(htmlspecialchars($row[text]),100);

    if($p[email] && $p[name]) 
      $p[name] = "<a href=mailto:$p[email]>$p[name]</a>";

    if($cut) {
      if(strlen($p[title]) > $cut)
        { $p[title] = cut_string($p[title],$cut).".."; }
    }

    $p[link] = "<a href=$prlist[wpath]/read.php?table=$t&no=$p[no]$p[l] title=\"$p[preview]\">$p[title]</a>";

    #리스트 출력
    print_prlist($p);
  }

  sql_free_result($result);
  mysql_close();
}

?>
