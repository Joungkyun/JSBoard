<?php
/* mail ������ �Լ� 2000.10.4 ������ */

function sendmail($rmail,$fm=0) {

  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE, $SERVER_NAME, $langs;
  global $mail_error, $mail_msg_header;

  $mail_msg_head = "$langs[sm_dr]";
  $mail_error = "$langs[sm_ds]";

  if ($langs[code] == "ko") $time = date("Y/m/d (D) a h��i��");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # ���� ������ ",' ó��
  $rmail[text] = eregi_replace("\\\\(\"|')","\\1",$rmail[text]);

  if ($langs[code] == "ko") {
    if ($day == "(Mon)") $day="(��)";
    else if ($day == "(Tue)") $day="(ȭ)";
    else if ($day == "(Wed)") $day="(��)";
    else if ($day == "(Thu)") $day="(��)";
    else if ($day == "(Fri)") $day="(��)";
    else if ($day == "(Sat)") $day="(��)";
    else if ($day == "(Sun)") $day="(��)";
  } else if ($langs[code] == "jp") {
    if ($day == "(Mon)") $day="(��)";
    else if ($day == "(Tue)") $day="(��)";
    else if ($day == "(Wed)") $day="(�)";
    else if ($day == "(Thu)") $day="(��)";
    else if ($day == "(Fri)") $day="(��)";
    else if ($day == "(Sat)") $day="(��)";
    else if ($day == "(Sun)") $day="(��)";
  }

  $webboard_address =  sprintf("%s%s",$rmail[bbshome],"read.php?table=$rmail[table]&no=$rmail[no]");
  $reply_article    =  sprintf("%s%s",$rmail[bbshome],"reply.php?table=$rmail[table]&no=$rmail[no]");

  if (!$fm) {
    $dbname  = "DB Name         : $rmail[table]";
    $dbloca  = "DB Location     : $webboard_address";
    $repart  = "Reply Article   : $reply_article";
    $nofm    = "\n$dbname\n$dbloca\n$repart";
    $homeurl = "HomeURL		: $rmail[url]";
  } else {
    $rmail[user] = "yes";
    $homeurl = "To		: mailto:$rmail[reply_orig_email]";
  }

  $message ="

$mail_msg_header

�� JSBOARD $rmail[table] message

[ Server Infomation ]------------------------------------------------------
ServerWare	: JSBoard-$rmail[version]
Server Name	: $SERVER_NAME$nofm


[ Article Infomation ]-----------------------------------------------------
�� ��		: $rmail[name]
Email		: mailto:$rmail[email]
$homeurl
�� ��		: $year $day $ampm $hms
---------------------------------------------------------------------------

$rmail[text]



---------------------------------------------------------------------------
REMOTE_ADDR : $REMOTE_ADDR
HTTP_USER_AGENT : $HTTP_USER_AGENT
HTTP_ACCEPT_LANGUAGE : $HTTP_ACCEPT_LANGUAGE
---------------------------------------------------------------------------

OOPS Form mail service - http://www.oops.org
Scripted by JoungKyun Kim <admin@oops.org>
";

  if ($rmail[user] == "yes" && $rmail[reply_orig_email]) {
    $to = $rmail[reply_orig_email];
    if(!$rmail[email]) $rmail[email] = "nomail@jsboard.agent"; 
    mail($to, $rmail[title], $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSBoard Message\r\nReply-To: $rmail[email]")
    or die("$mail_error");
  }

  if ($rmail[admin] == "yes" && $rmail[toadmin] != "") {
    $to = $rmail[toadmin];
    if(!$rmail[email]) $rmail[email] = "nomail@jsboard.agent";
    mail($to, $rmail[title], $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSBoard Message\r\nReply-To: $rmail[email]")
    or die("$mail_error");
  }

}

function get_send_info($table,$no) {
  global $db;
  $c = sql_connect($db[host],$db[user],$db[pass]);
  sql_select_db($db[name]);
  $r = sql_query("SELECT email FROM $table WHERE no = $no");
  $row = sql_fetch_array($r);
  mysql_close($c);

  return $row;
}

?>
