<?
function get_hostname()
{
    $host  = getenv('REMOTE_ADDR');
    return $host;
}

/* �н����� �� �Լ� */
function check_passwd($passwd, $no)
{
    global $table;

    $result  = mysql_query("SELECT passwd FROM $table WHERE no = $no");
    $cpasswd = mysql_result($result, 0, "passwd");
    
    if (crypt($passwd, $cpasswd) == $cpasswd) {
	return 1;
    }
}

/* URL üũ �Լ� */
function check_url($url)
{
    if (!eregi("[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9\-\.]+.*", $url)) {
	return;
    }
    /* �ѱ��� ���ԵǾ����� üũ */
    for($i = 1; $i <= strlen($url); $i++) {
	if ((Ord(substr("$url", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }
    $url = eregi_replace("^http.*://", "", $url);
    $url = eregi_replace("^", "http://", $url);

    return $url;
}

/* �̸��� �ּ� üũ �Լ� */
function check_email($email)
{
    if (!eregi("^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]", $email)) {
	return;
    }
    /* �ѱ��� ���ԵǾ����� üũ */
    for($i = 1; $i <= strlen($email); $i++) {
	if ((Ord(substr("$email", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }

    return $email;
}

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

    $result    = mysql_query("SELECT COUNT(no) FROM $table");
    $all_count = mysql_result($result, 0, "COUNT(no)");
    $result    = mysql_query("SELECT COUNT(no) FROM $table WHERE reno > 0");
    $re_count  = mysql_result($result, 0, "COUNT(no)");
    $count     = $all_count - $re_count;

    $result = mysql_query("SELECT reto, reyn FROM $table WHERE no = $no");
    $reto   = mysql_result($result, 0, "reto");

    if (!$reto) {
	$result = mysql_query("SELECT COUNT(no) FROM $table WHERE no <= $no AND reno < 1");
    } else {
	$result = mysql_query("SELECT COUNT(no) FROM $table WHERE no <= $reto AND reno < 1");
    }
    $count_no = mysql_result($result, 0, "COUNT(no)");
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

function g_agent($test = "0")
{
  $agent = getenv("HTTP_USER_AGENT");

  if (ereg("^Lynx", $agent)) {
      $agent = "lynx";
  } else if (ereg("MS", $agent)) {
      $agent = "msie";
  } else if (ereg("^Moz", $agent)) {
      if (ereg("Linux", $agent)) {
	$agent = "moz";
      } else if (ereg("Win", $agent)) {
	if (ereg("\[ko\]", $agent)) {
	  $agent = "moz_w_ko";
	} else {
	  $agent = "moz";
	}
      } else {
	$agent = "moz";
      }
  } else {
      $agent = "unknown";
  }
  
  if ($test) {
    if ($test == $agent) {
      return 1;
    } else {
      return 0;
    }
  }

  return $agent;
}

function sepa($bg)
{
    echo("<td width=\"1%\" bgcolor=\"$bg\"><img src=\"images/n.gif\" width=\"2\" height=\"1\" alt=\"\"></td>\n");
}

// �������� �����ֽ� ������ ũ�⸦ ����ϴ� �κ��� �־ ����� �б� ���� ����ϴ� ���
function human_readable($byte)
{
    if($byte >= 1024 * 1024 * 1024) {
	$byte /= 1024 * 1024 * 1024;
	$suff = "GB";
    } else if($byte >= 1024 * 1024) {
	$byte /= 1024 * 1024;
	$suff = "MB";
    } else if($byte >= 1024) {
	$byte /= 1024;
	$suff = "KB";
    } else {
	$suff = "B";
    }

    if($byte == 0) {
	$str = "0";
    } else if($byte >= 10) {
	$str = sprintf("%4.0f", $byte);
    } else {
	$str = sprintf("%4.1f", $byte);
    }
    
    $str .= sprintf(" %s", $suff);

    return $str;
}

?>
