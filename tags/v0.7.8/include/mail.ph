<?
/* mail ������ �Լ� */

function send_mail($no, $bbshome, $mailtoadmin, $mailtowriter, $table, $reno, $name,
                    $email, $reply_writer_email, $url, $webboard_version, $text, $title) {

  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE, $SERVER_NAME, $lang ;
  global $mail_error, $mail_msg_header, $reply_admin_ck ;

  if ($lang == "ko") {
    $time = date("Y/m/d (D) a h��i��");
  } else {
    $time = date("Y/m/d (D) a h : i");
  }

  $time=explode(" ",$time);

  $year=$time[0] ;
  $day=$time[1] ;
  $ampm=$time[2] ;
  $hms=$time[3] ;

  if ($lang == "ko") {
    if ($day == "(Mon)") { $asdf="(��)" ; }
    else if ($day == "(Tue)") { $day="(ȭ)" ; }
    else if ($day == "(Wed)") { $day="(��)" ; }
    else if ($day == "(Thu)") { $day="(��)" ; }
    else if ($day == "(Fri)") { $day="(��)" ; }
    else if ($day == "(Sat)") { $day="(��)" ; }
    else if ($day == "(Sun)") { $day="(��)" ; }
  }

  $webboard_address =  sprintf("%s%s", $bbshome, "read.php3?table=$table&no=$no");
  if ($reply_admin_ck == "yes") {
    if ($email) {
      $reply_article    =  sprintf("%s%s", $bbshome, "admin/user_admin/mode_auth.php3?type=reply&db=$table&no=$no&origmail=$email");
    } else {
      $reply_article    =  sprintf("%s%s", $bbshome, "admin/user_admin/mode_auth.php3?type=reply&db=$table&no=$no");
    }
  } else {
    $reply_article    =  sprintf("%s%s", $bbshome, "reply.php3?table=$table&no=$no");
  }

  $message ="

$mail_msg_header

�� JSBOARD $tabel message

[ Server Infomation ]------------------------------------------------------
ServerWare	: JSBoard-$webboard_version
Server Name	: $SERVER_NAME
DB Name		: $table
DB Location	: $webboard_address
Reply Article	: $reply_article


[ Article Infomation ]-----------------------------------------------------
�� ��		: $name
Email		: mailto:$email
HomeURL		: $url
�� ��		: $year $day $ampm $hms
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
    or die("$mail_error");
  }

  if ($mailtoadmin != "") {
    $to = "Board Admin<$mailtoadmin>";
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard Message <$email>")
    or die("$mail_error");
  }
}
?>
