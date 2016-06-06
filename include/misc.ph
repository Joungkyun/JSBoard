<?

/* mail 보내기 함수 */

function send_mail($no, $bbshome, $mailtoadmin, $mailtowriter, $table, $reno, $name,
                    $email, $reply_writer_email, $url, $webboard_version, $text, $title) {

  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE, $SERVER_NAME, $HOST_NAME ;

  $mail_msg_header = "이 메일은 $table 게시판에서 온 메일입니다. Reply 하지 마십시오." ;

  $time = date("Y/m/d (D) a h시i분");
  $time=explode(" ",$time);

  $year=$time[0] ;
  $day=$time[1] ;
  $ampm=$time[2] ;
  $hms=$time[3] ;

  if ($day == "(Mon)") { $asdf="(월)" ; }
  else if ($day == "(Tue)") { $day="(화)" ; }
  else if ($day == "(Wed)") { $day="(수)" ; }
  else if ($day == "(Thu)") { $day="(목)" ; }
  else if ($day == "(Fri)") { $day="(금)" ; }
  else if ($day == "(Sat)") { $day="(토)" ; }
  else if ($day == "(Sun)") { $day="(일)" ; }

    $webboard_address =  sprintf("%s%s", $bbshome, "read.php3?table=$table&no=$no");
    $reply_article    =  sprintf("%s%s", $bbshome, "reply.php3?table=$table&no=$no");

    $message ="

$mail_msg_header

■ JSBOARD $tabel message

[ Server Infomation ]------------------------------------------------------
ServerWare	: JSBoard-$webboard_version
Server Name	: $SERVER_NAME
DB Name		: $table
DB Location	: $webboard_address
Reply Article	: $reply_article


[ Article Infomation ]-----------------------------------------------------
이 름		: $name
Email		: mailto:$reply_writer_email
HomeURL		: $url
일 시		: $year $day $ampm $hms
---------------------------------------------------------------------------

$text



---------------------------------------------------------------------------
REMOTE_ADDR : $REMOTE_ADDR
HTTP_USER_AGENT : $HTTP_USER_AGENT
HTTP_ACCEPT_LANGUAGE : $HTTP_ACCEPT_LANGUAGE
---------------------------------------------------------------------------

OOPS Form mail service - http://www.oops.org
Scripted by JoungKyun Kim <admin@oops.org>
";

  if ($mailtowriter=="yes" && $email != "") {
    $to = $email;
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard@$HOST_NAME")
    or die("mail을 보내지 못했습니다");
  }

  if ($mailtoadmin!="") {
    $to = $mailtoadmin;
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard@$HOST_NAME")
    or die("mail을 보내지 못했습니다");
  }
}

function get_hostname()
{
    $host  = getenv('REMOTE_ADDR');
    return $host;
}

/* 패스워드 비교 함수 */
function check_passwd($passwd, $no)
{
    global $table;

    $result  = mysql_query("SELECT passwd FROM $table WHERE no = $no");
    $cpasswd = mysql_result($result, 0, "passwd");
    
    if (crypt($passwd, $cpasswd) == $cpasswd) {
	return 1;
    }
}

/* URL 체크 함수 */
function check_url($url)
{
    if (!eregi("[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9\-\.]+.*", $url)) {
	return;
    }
    /* 한글이 포함되었는지 체크 */
    for($i = 1; $i <= strlen($url); $i++) {
	if ((Ord(substr("$url", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }
    $url = eregi_replace("^http.*://", "", $url);
    $url = eregi_replace("^", "http://", $url);

    return $url;
}

/* 이메일 주소 체크 함수 */
function check_email($email)
{
    if (!eregi("^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]", $email)) {
	return;
    }
    /* 한글이 포함되었는지 체크 */
    for($i = 1; $i <= strlen($email); $i++) {
	if ((Ord(substr("$email", $i - 1, $i)) & 0x80)) {
	    return;
	}
    }

    return $email;
}

/* 다음글 가져오기 */
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

/* 이전글 가져오기 */
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

/* 페이지 가져오기 */
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

function sform_echo($size)
{
    $agent = g_agent();
    if($agent != "moz") {
	$size *= 2;
    }
//    echo $size;
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
	} else if (ereg("\[en\]", $agent)) {
	  $agent = "moz_w_en";
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

?>
