<?

/* mail 보내기 함수 2000.1.16 수정 by 김정균 */

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
Email		: mailto:$email
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

  if ($mailtowriter=="yes" && $reply_writer_email != "") {
    $to = $reply_writer_email;
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard Message <$email>")
    or die("mail을 보내지 못했습니다");
  }

  if ($mailtoadmin!="") {
    $to = "Board Admin<$mailtoadmin>";
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard Message <$email>")
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

function sform($size) {
    $agent = g_agent();
    if($agent != "moz") { $size *= 2; }
    echo $size;
}

function sform_echo($size) {
    $agent = g_agent();
    if($agent != "moz") { $size *= 2; }
}

function g_agent($test = "0") {

  $agent = getenv("HTTP_USER_AGENT");

  if (ereg("^Lynx", $agent)) {
      $agent = "lynx";
  } else if (ereg("MS", $agent)) {
      $agent = "msie";
  } else if (ereg("^Moz", $agent)) {
      if (ereg("Linux", $agent)) {
	$agent = "moz";
      } else if (ereg("WinNT", $agent)) {
	if (ereg("\[ko\]", $agent)) {
	  $agent = "moz_nt_ko";
	} else if (ereg("\[en\]", $agent)) {
	  $agent = "moz_nt_en";
	} else { $agent = "moz"; }
      } else if (ereg("Win", $agent)) {
	if (ereg("\[ko\]", $agent)) {
	  $agent = "moz_w_ko";
	} else if (ereg("\[en\]", $agent)) {
	  $agent = "moz_w_en";
	} else { $agent = "moz"; }
      } else { $agent = "moz"; }
  } else { $agent = "unknown"; }
  
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


function auto_link($text) {

  $regex[http] = "(http|https|ftp|telnet|news):\/\/([a-z0-9\-_]+\.[][a-zA-Z0-9:;&#@=_~%\?\/\.\+\-]+)";
  $regex[mail] = "([a-z0-9_\-]+)@([a-z0-9_\-]+\.[a-z0-9\-\._]+)";

  /* 특수 문자와 링크시 target 삭제 */
  $text = eregi_replace("&(quot|gt|lt)","!\\1",$text);
  $text = eregi_replace(" target=[\"'_a-z,A-Z]+","", $text);

  /* html사용시 link 보호 */
  $text = eregi_replace("<a href=([\"']*)($regex[http])([\"']*)>","<a href=\"\\3_orig://\\4\" target=_blank>", $text);
  $text = eregi_replace("<a href=([\"']*)mailto:($regex[mail])([\"']*)>","<a href=\"mailto:\\3#-#\\4\">", $text);
  $text = eregi_replace("<img src=([\"']*)($regex[http])([\"']*)","<img src=\"\\3_orig://\\4\"",$text);

  /* 링크가 안된 url및 email address 자동링크 */
  $text = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $text);
  $text = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $text);

  /* 보호를 위해 치환한 것들을 복구 */
  $text = eregi_replace("!(quot|gt|lt)","&\\1",$text);
  $text = eregi_replace("http_orig","http", $text);
  $text = eregi_replace("#-#","@",$text) ;

  /* link가 2개 겹쳤을때 이를 하나로 줄여줌 */
  $text = eregi_replace("(<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>)+<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>","\\1", $text);
  $text = eregi_replace("(<a href=([\"']*)mailto:($regex[mail])([\"']*)>)+<a href=([\"']*)mailto:($regex[mail])([\"']*)>","\\1", $text);
  $text = eregi_replace("</a></a>","</a>",$text) ;

  return $text;
}

// 문자열에 한글이 포함되어 있는지 검사하는 함수
//
// ord    - 문자의 ASCII 값을 가져옴
//          http://www.php.net/manual/function.ord.php3
function is_hangul($char)
{
    // 특정 문자가 한글의 범위내(0xA1A1 - 0xFEFE)에 있는지 검사
    $char = ord($char);

    if($char >= 0xa1 && $char <= 0xfe)
	return 1;
}

// 알파벳인지 그리고 대문자(0x41 - 0x5a)인지 소문자(0x61 - 0x7a)인지
// 검사하는 함수
//
// ord - 문자의 ASCII 값을 가져옴
//       http://www.php.net/manual/function.ord.php3
function is_alpha($char)
{
    $char = ord($char);

    if($char >= 0x61 && $char <= 0x7a)
	return 1;
    if($char >= 0x41 && $char <= 0x5a)
	return 2;
}

// 문자열을 일정한 길이로 자르는 함수
//
// 한글을 한바이트 단위로 잘르는 경우를 막고 대문자가 많이 쓰인 경우
// 소문자와의 크기 비율 정도(1.5?)에 따라 문자열을 자름
//
// intval - 변수의 정수형 값을 가져옴
//          http://www.php.net/manual/function.intval.php3
// substr - 문자열의 지정된 범위를 잘라서 가져옴
//          http://www.php.net/manual/function.substr.php3
// chop   - 문자열 끝의 공백 문자을 없앰
//          http://www.php.net/manual/function.chop.php3
function cut_string($str, $length)
{
    if(strlen($str) <= $length && !eregi("^[a-z]+$", $str))
	return $str;

    for($co = 1; $co <= $length; $co++) {
	if(is_hangul(substr($str, $co - 1, $co))) {
	    if($first) { // first 값이 있으면 한글의 두번째 바이트로 정의
		$second = 1;
		$first  = 0;
	    } else {     // first 값이 없으면 한글의 첫번째 바이트로 정의
		$first  = 1;
		$second = 0;
	    }
	    $hangul = 1;
	} else {
	    // 한글이 아닐 경우 한글의 몇번째 바이트인지 나타내는 변수 초기화
	    $first = $second = 0;
	    // 대문자의 갯수를 기록
	    if(is_alpha(substr($str, $co - 1, $co)) == 2)
		$alpha++;
	}
    }
    // 한글의 첫번째 바이트일 때 깨지는 것을 막기 위해 지정된 길이를 한
    // 바이트 줄임
    if($first)
	$length--;

    // 지정된 길이로 문자열을 자르고 문자열 끝의 공백 문자를 삭제함
    // 영문만 있을 경우 대문자의 길이를 1.3으로 하여 초과된 만큼을 뺌
    if($hangul)
	$str = chop(substr($str, 0, $length));
    else
	$str = chop(substr($str, 0, $length - intval($alpha * 0.5)));

    return $str;
}

function list_cmd_bar ($page, $l0_bg, $table, $sc_column) {

  global $prev, $next, $apage, $SCRIPT_NAME, $act, $search ;

  echo("<table width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\"><tr>\n");
  if($act == "search") {
      sepa($l0_bg);
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table\"><nobr>전체목록보기</nobr></a></td>\n");
  }
  sepa($l0_bg);
  if($page > 1) {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$prev$search\"><nobr>이전페이지</nobr></a></td>\n");
  } else {
      echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>이전페이지</nobr></font></td>\n");
  }
  sepa($l0_bg);
  echo("<td width=\"1%\" align=\"center\"><a href=\"write.php3?table=$table\"><nobr>글쓰기</nobr></a></td>\n");
  sepa($l0_bg);
  if($page < $apage) {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&page=$next$search\"><nobr>다음페이지</nobr></a></td>");
  } else {
      echo("<td width=\"1%\" align=\"center\"><font color=\"#acacac\"><nobr>다음페이지</nobr></font></td>\n");
  }
  sepa($l0_bg);
  if($sc_column != "today") {
      echo("<td width=\"1%\" align=\"center\"><a href=\"$SCRIPT_NAME?table=$table&act=search&sc_column=today\"><nobr>오늘올라온글</nobr></a></td>\n");
      sepa($l0_bg);
  }
  echo("</tr>\n</table>\n");
}

function read_cmd_bar ($no, $page, $prev, $next, $r0_bg, $act, $table, $passwd, $email) {

  global $search, $SCRIPT_NAME, $reyn ;

    echo("\n<table width=\"1%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\">\n<tr>");
    sepa($r0_bg);
    // 목록
    if($act == "search") {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table$search\"><nobr>목록보기</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><a href=\"list.php3?table=$table&page=$page\"><nobr>목록보기</nobr></a></td>\n");
    }
    sepa($r0_bg);
    if($prev) { // 이전글
	$result  = dquery("SELECT title FROM $table WHERE no = $prev");
	$p_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$prev$search\"><nobr>이전글</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>이전글</nobr></font></td>\n");
    }
    sepa($r0_bg);
    if($next) { // 다음글
	$result  = dquery("SELECT title FROM $table WHERE no = $next");
	$n_title = mysql_result($result, 0, "title");
	echo("<td align=\"center\" width=\"1%\"><a href=\"$SCRIPT_NAME?table=$table&no=$next$search\"><nobr>다음글</nobr></a></td>\n");
    } else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>다음글</nobr></font></td>\n");
    }
    sepa($r0_bg);
    echo("<td align=\"center\" width=\"1%\"><a href=\"write.php3?table=$table\"><nobr>글쓰기</nobr></a></td>\n");
    sepa($r0_bg);

    if($email) {
    echo("<td align=\"center\" width=\"1%\"><a href=\"reply.php3?table=$table&no=$no&page=$page&origmail=$email\"><nobr>답장쓰기</nobr></a></td>\n");
    } else {
    echo("<td align=\"center\" width=\"1%\"><a href=\"reply.php3?table=$table&no=$no&page=$page\"><nobr>답장쓰기</nobr></a></td>\n");
    }
    sepa($r0_bg);

    if($passwd) { // 암호가 있는 경우
	echo("<td align=\"center\" width=\"1%\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><nobr>수정</nobr></a></td>\n");
        sepa($r0_bg);
 	  if(!$reyn) {
	      echo("<td align=\"center\" width=\"1%\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><nobr>삭제</nobr></a></td>\n");
              sepa($r0_bg);
	  }
    }
    else {
	echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>수정</nobr></font></td>\n");
	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"edit.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");
        echo("<td align=\"center\" width=\"1%\"><font color=\"acacac\"><nobr>삭제</nobr></font></td>\n");
	echo("<td width=\"1%\" bgcolor=\"$r0_bg\"><a href=\"delete.php3?table=$table&no=$no&page=$page\"><img src=\"images/n.gif\" alt=\"\" width=\"2\" height=\"10\" border=\"0\"></a></td>");
    }

    echo("</tr>\n</table>\n");
}

?>
