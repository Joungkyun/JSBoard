<?
########################################################################
# JSBoard Pre List v0.4
# Scripted By JoungKyun Kim 2002.07.30
########################################################################

# JSBoard�� ��ġ�Ǿ� �ִ� ���� ���
# �������� /�� ���̸� �ȵ�
$prlist[path] = "/webroot/jsboard-version";

# JSBoard�� ��ġ�Ǿ� �ִ� �� ���
# �������� /�� ���̸� �ȵ�
$prlist[wpath] = "http://domain.com/jsboard-version";

include_once "$prlist[path]/config/global.ph";
include_once "$prlist[path]/include/error.ph";
include_once "$prlist[path]/include/parse.ph";
include_once "$prlist[path]/include/check.ph";
include_once "$prlist[path]/include/sql.ph";
include_once "$prlist[path]/include/get.ph";
include_once "$prlist[path]/include/print.ph";

print_preview_src(1);

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
# $prlistTemplate ��� ������ ���� �Ǿ� ���� ��쿡�� �� ������
# �������� �̿��Ͽ� ���
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

# PHP�� ���ؼ� �� �𸣽Ŵٰ� �����Ͻô� �е��� �ǵ帮�� ����!!!
# table �̸�
# $limit �ۼ�
# $cut ��±��ڼ�
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

    #����Ʈ ���
    print_prlist($p);
  }

  sql_free_result($result);
  mysql_close();
}

?>
