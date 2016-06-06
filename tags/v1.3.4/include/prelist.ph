<?
########################################################################
# JSBoard Pre List v0.2
# Scripted By JoungKyun Kim 2000.10.11
########################################################################

# JSBoard�� ��ġ�Ǿ� �ִ� ���� ���
# �������� /�� ���̸� �ȵ�
$prlist[path] = "/home/httpd/html/jsboard";

# JSBoard�� ��ġ�Ǿ� �ִ� �� ���
# �������� /�� ���̸� �ȵ�
$prlist[wpath] = "http://www.domain.com/jsboard";

# �۸���Ʈ���� ����ϴ� design
#   echo ���� "" ���̿��� �������� ������ ��
#   �� ���� �Ұ��� ����ǥ(")�� \" �� ǥ�⸦ �ؾ� ��
#   $p[link]  -> �� ����Ʈ�� ��ũ
#   $p[name]  -> �۾���
#   $p[date]  -> �����
#   $p[count] -> ��ȸ��
#
# table tag�� ����ϱ� ���ؼ��� �Ʒ��� prelist() �Լ���
# �� �����ؾ���
#
function print_prlist($p) {
  global $prlist;
  $p[date] = $p[date] ? "[$p[date]]" : "";
  if($prlist[type] == "main") {
    $p[link] = eregi_replace("\[����\]|\[TIP\]","",$p[link]);
    echo "<Li type=disc>$p[link] $p[name] $p[date] $p[count]<br>\n";
  } elseif ($prlist[type] == "proftpd") {
    echo "$p[link] $p[name] $p[date] $p[count]<br>\n";
  } else {
    echo "$p[link] $p[name] - $p[date] $p[count]<br>\n";
  }
}


# PHP�� ���ؼ� �� �𸣽Ŵٰ� �����Ͻô� �е��� �ǵ帮�� ����!!!
# table �̸�
# inc include ����
# $limit �ۼ�
# $cut ��±��ڼ�
# $cn �̸� ���
# $cd ����� ���
# $ce �̸��� ���
# $cc ��ȸ�� ���
# $l ��ũ�� �ɼ� (��: target onClick ���)
#
function prelist($t,$inc,$limit=3,$cut=30,$cn=0,$cd=0,$ce=0,$cc=0,$l=0) {
  global $prlist;

  include "$prlist[path]/config/global.ph";
  if($inc) {
    include "$prlist[path]/include/error.ph";
    include "$prlist[path]/include/parse.ph";
    include "$prlist[path]/include/check.ph";
    include "$prlist[path]/include/sql.ph";
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

    #����Ʈ ���
    print_prlist($p);
  }

  sql_free_result($result);
  mysql_close();
}

?>
