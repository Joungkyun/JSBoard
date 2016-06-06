<?php
/* mail ������ �Լ� 2000.1.16 ������ */

function sendmail($rmail) {

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

  $webboard_address =  sprintf("%s%s",$rmail[bbshome],"read.php3?table=$rmail[table]&no=$rmail[no]");
  $reply_article    =  sprintf("%s%s",$rmail[bbshome],"reply.php3?table=$rmail[table]&no=$rmail[no]");

  $message ="

$mail_msg_header

�� JSBOARD $rmail[table] message

[ Server Infomation ]------------------------------------------------------
ServerWare	: JSBoard-$rmail[version]
Server Name	: $SERVER_NAME
DB Name		: $rmail[table]
DB Location	: $webboard_address
Reply Article	: $reply_article


[ Article Infomation ]-----------------------------------------------------
�� ��		: $rmail[name]
Email		: mailto:$rmail[email]
HomeURL		: $rmail[url]
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

  if ($rmail[user] == "yes" && $rmail[reply_orig_email] != "") {
    $to = $rmail[reply_orig_email];
    mail($to, $rmail[title], $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSBoard Message <$rmail[email]>")
    or die("$mail_error");
  }

  if ($rmail[admin] == "yes" && $rmail[toadmin] != "") {
    $to = $rmail[toadmin];
    mail($to, $rmail[title], $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSBoard Message <$rmail[email]>")
    or die("$mail_error");
  }

}
?>
