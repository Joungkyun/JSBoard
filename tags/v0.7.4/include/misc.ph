<?
/* ������ �������� */
function get_next($no)
{
    global $table, $act;
    global $sc_column, $sc_string;

    if($sc_string) {
	if ($sc_column == "all") {
	    $sc_sql = "title LIKE \"%$sc_string%\" OR name LIKE \"%$sc_string%\" OR text LIKE \"%$sc_string%\"";
	} else {
	    $sc_sql = "$sc_column LIKE \"%$sc_string%\"";
	}
    }

    $result = mysql_query("SELECT reyn, reno FROM $table WHERE no = $no");
    $reyn   = mysql_result($result, 0, "reyn");
    $reno   = mysql_result($result, 0, "reno");

    if (!$reyn && !$reno) {
	if ($sc_sql) {
	    $result = mysql_query("SELECT IF(reto > 0, reto, no) AS no FROM $table WHERE no < $no AND ($sc_sql) GROUP BY no DESC LIMIT 0, 1");
	} else {
	    $result = mysql_query("SELECT no FROM $table WHERE no < $no AND reno = 0 ORDER BY no DESC LIMIT 0, 1");
	}
    } else if ($reyn) {
	$result = mysql_query("SELECT no FROM $table WHERE reno = $no ORDER BY no DESC LIMIT 0, 1");
    } else if ($reno) {
	do {
	    if ($sc_sql && !$reno) {
		$result = mysql_query("SELECT IF(reto > 0, reto, no) AS no FROM $table WHERE ((reto != 0 OR no < $no) AND reto < $no) AND ($sc_sql) GROUP BY no DESC LIMIT 0, 1");
	    } else {
		$result = mysql_query("SELECT no FROM $table WHERE reno = $reno AND no < $no ORDER BY no DESC LIMIT 0, 1");
	    }
	    if (!mysql_num_rows($result)) {
		$result = mysql_query("SELECT no, reyn, reno FROM $table WHERE no = $reno");
		if (mysql_num_rows($result)) {
		    $no     = mysql_result($result, 0, "no");
		    $reyn   = mysql_result($result, 0, "reyn");
		    $reno   = mysql_result($result, 0, "reno");
		} else {
		    $exit = 1;
		}
	    } else {
		$exit = 1;
	    }
	} while (!$exit);
    }
    if (mysql_num_rows($result)) {
	return mysql_result($result, 0, "no");
    } 
}

/* ������ �������� */
function get_prev($no)
{
    global $table, $act;
    global $sc_column, $sc_string;

    if($sc_string) {
	if ($sc_column == "all") {
	    $sc_sql = "title LIKE \"%$sc_string%\" OR name LIKE \"%$sc_string%\" OR text LIKE \"%$sc_string%\"";
	} else {
	    $sc_sql = "$sc_column LIKE \"%$sc_string%\"";
	}
    }

    $result = mysql_query("SELECT reno FROM $table WHERE no = $no");
    $reno   = mysql_result($result, 0, "reno");
    if ($reno) {
	$result = mysql_query("SELECT no, reyn, reno FROM $table WHERE reno = $reno AND no > $no LIMIT 0, 1");
	if (!mysql_num_rows($result)) {
	    return $reno;
	}
    } else {
	if ($sc_sql) {
	    $result = mysql_query("SELECT IF(reto > 0, reto, no) AS no FROM $table WHERE no > $no AND (reto = 0 OR reto > $no) AND ($sc_sql) GROUP BY no LIMIT 0, 1");
	    if(mysql_num_rows($result)) {
		$no = mysql_result($result, 0, "no");
		$result = mysql_query("SELECT no, reyn, reno FROM $table WHERE no = $no");
	    }
	} else {
	    $result = mysql_query("SELECT no, reyn, reno FROM $table WHERE no > $no AND reno = 0 LIMIT 0, 1");
	}
    }
    if (!mysql_num_rows($result)) {
	return;
    }
    $no   = mysql_result($result, 0, "no");
    $reyn = mysql_result($result, 0, "reyn");
    if (!$reyn) {
	return $no;
    } else {
	do {
	    $result = mysql_query("SELECT no, reyn FROM $table WHERE reno = $no AND no > $no LIMIT 0, 1");
	    if(!mysql_num_rows($result)) {
		return $no;
	    }
	    $reyn = mysql_result($result, 0, "reyn");
	    $no   = mysql_result($result, 0, "no");
	} while ($reyn);
	return $no;
    }
}

/* ������ �������� */
function get_page($no)
{
    global $table, $pern;

    $result    = mysql_query("SELECT COUNT(*) FROM $table");
    $all_count = mysql_result($result, 0, "COUNT(*)");
    $result    = mysql_query("SELECT COUNT(*) FROM $table WHERE reno > 0");
    $re_count  = mysql_result($result, 0, "COUNT(*)");
    $count     = $all_count - $re_count;

    $result = mysql_query("SELECT reto, reyn FROM $table WHERE no = $no");
    $reto   = mysql_result($result, 0, "reto");

    if (!$reto) {
	$result = mysql_query("SELECT COUNT(*) FROM $table WHERE no <= $no AND reno < 1");
    } else {
	$result = mysql_query("SELECT COUNT(*) FROM $table WHERE no <= $reto AND reno < 1");
    }
    $count_no = mysql_result($result, 0, "COUNT(*)");
    $page     = intval(($count - $count_no) / $pern) + 1;

    return $page;
}

function sform($size)
{
    $agent = g_agent();
    if($agent != "moz") {
	$size *= 2;
    }
    echo $size;
}


function sepa($bg)
{
    echo("<td width=\"1%\" bgcolor=\"$bg\"><img src=\"images/n.gif\" width=\"2\" height=\"1\" alt=\"\"></td>\n");
}

function auto_link($text) {

  $regex[http] = "(http|https|ftp|telnet|news):\/\/([a-z0-9\-_]+\.[][a-zA-Z0-9:;&#@=_~%\?\/\.\+\-]+)";
  $regex[mail] = "([a-z0-9_\-]+)@([a-z0-9_\-]+\.[a-z0-9\-\._]+)";

  // Ư�� ���ڿ� ��ũ�� target ����
  $text = eregi_replace("&(quot|gt|lt)","!\\1",$text);
  $text = eregi_replace(" target=[\"'_a-z,A-Z]+","", $text);

  // html���� link ��ȣ
  $text = eregi_replace("<a href=([\"']*)($regex[http])([\"']*)>","<a href=\"\\3_orig://\\4\" target=_blank>", $text);
  $text = eregi_replace("<a href=([\"']*)mailto:($regex[mail])([\"']*)>","<a href=\"mailto:\\3#-#\\4\">", $text);
  $text = eregi_replace("<img src=([\"']*)($regex[http])([\"']*)","<img src=\"\\3_orig://\\4\"",$text);

  // ��ũ�� �ȵ� url�� email address �ڵ���ũ
  $text = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $text);
  $text = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $text);

  // ��ȣ�� ���� ġȯ�� �͵��� ���� 
  $text = eregi_replace("!(quot|gt|lt)","&\\1",$text);
  $text = eregi_replace("http_orig","http", $text);
  $text = eregi_replace("#-#","@",$text) ;

  // link�� 2�� �������� �̸� �ϳ��� �ٿ��� 
  $text = eregi_replace("(<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>)+<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>","\\1", $text);
  $text = eregi_replace("(<a href=([\"']*)mailto:($regex[mail])([\"']*)>)+<a href=([\"']*)mailto:($regex[mail])([\"']*)>","\\1", $text);
  $text = eregi_replace("</a></a>","</a>",$text) ;

  return $text;
}

// ���ڿ��� ������ ���̷� �ڸ��� �Լ�
//
// �ѱ��� �ѹ���Ʈ ������ �߸��� ��츦 ���� �빮�ڰ� ���� ���� ���
// �ҹ��ڿ��� ũ�� ���� ����(1.5?)�� ���� ���ڿ��� �ڸ�
//
// intval - ������ ������ ���� ������
//          http://www.php.net/manual/function.intval.php3
// substr - ���ڿ��� ������ ������ �߶� ������
//          http://www.php.net/manual/function.substr.php3
// chop   - ���ڿ� ���� ���� ������ ����
//          http://www.php.net/manual/function.chop.php3
function cut_string($str, $length)
{
    if(strlen($str) <= $length && !eregi("^[a-z]+$", $str))
	return $str;

    for($co = 1; $co <= $length; $co++) {
	if(is_hangul(substr($str, $co - 1, $co))) {
	    if($first) { // first ���� ������ �ѱ��� �ι�° ����Ʈ�� ����
		$second = 1;
		$first  = 0;
	    } else {     // first ���� ������ �ѱ��� ù��° ����Ʈ�� ����
		$first  = 1;
		$second = 0;
	    }
	    $hangul = 1;
	} else {
	    // �ѱ��� �ƴ� ��� �ѱ��� ���° ����Ʈ���� ��Ÿ���� ���� �ʱ�ȭ
	    $first = $second = 0;
	    // �빮���� ������ ���
	    if(is_alpha(substr($str, $co - 1, $co)) == 2)
		$alpha++;
	}
    }
    // �ѱ��� ù��° ����Ʈ�� �� ������ ���� ���� ���� ������ ���̸� ��
    // ����Ʈ ����
    if($first)
	$length--;

    // ������ ���̷� ���ڿ��� �ڸ��� ���ڿ� ���� ���� ���ڸ� ������
    // ������ ���� ��� �빮���� ���̸� 1.3���� �Ͽ� �ʰ��� ��ŭ�� ��
    if($hangul)
	$str = chop(substr($str, 0, $length));
    else
	$str = chop(substr($str, 0, $length - intval($alpha * 0.5)));

    return $str;
}
?>