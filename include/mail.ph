<?
/* mail 보내기 함수 */

function send_mail($no, $bbshome, $mailtoadmin, $mailtowriter, $table, $reno, $name,
                    $email, $reply_writer_email, $url, $webboard_version, $text, $title) {

  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE, $SERVER_NAME, $lang ;
  global $mail_error, $mail_msg_header, $reply_admin_ck ;

  if ($lang == "ko") {
    $time = date("Y/m/d (D) a h시i분");
  } else {
    $time = date("Y/m/d (D) a h : i");
  }

  $time=explode(" ",$time);

  $year=$time[0] ;
  $day=$time[1] ;
  $ampm=$time[2] ;
  $hms=$time[3] ;

  if ($lang == "ko") {
    if ($day == "(Mon)") { $asdf="(월)" ; }
    else if ($day == "(Tue)") { $day="(화)" ; }
    else if ($day == "(Wed)") { $day="(수)" ; }
    else if ($day == "(Thu)") { $day="(목)" ; }
    else if ($day == "(Fri)") { $day="(금)" ; }
    else if ($day == "(Sat)") { $day="(토)" ; }
    else if ($day == "(Sun)") { $day="(일)" ; }
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
    or die("$mail_error");
  }

  if ($mailtoadmin != "") {
    $to = "Board Admin<$mailtoadmin>";
    mail($to, $title, $message, "X-Mailer: PHP/" . phpversion(). "\r\nFrom: JSboard Message <$email>")
    or die("$mail_error");
  }
}
?>
