<?php
/* mail º¸³»±â ÇÔ¼ö 2000.11.5 ±èÁ¤±Õ */

function get_send_info($table,$no) {
  global $db;
  $c = sql_connect($db[server],$db[user],$db[pass]);
  sql_select_db($db[name]);
  $r = sql_query("SELECT email FROM $table WHERE no = $no");
  $row = sql_fetch_array($r);
  mysql_close($c);

  return $row;
}

function mailcheck($to,$from,$title,$body) {
  global $langs;
  if(!trim($to)) print_error($langs[mail_to_chk_err]);
  if(!trim($from)) print_error($langs[mail_from_chk_err]);
  if(!trim($title)) print_error($langs[mail_title_chk_err]);
  if(!trim($body)) print_error($langs[mail_body_chk_drr]);
}

function socketmail($smtp='',$to,$from,$title,$body,$lang) {
  global $langs;
  $smtp = !trim($smtp) ? "127.0.0.1" : $smtp;

  # ºó ¹®ÀÚ¿­ Ã¼Å©
  mailcheck($to,$from,$title,$body);

  # language type À» °áÁ¤
  if($lang == "ko") { $charset = "EUC-KR"; $charbit = "8bit"; }
  else  { $cahrset = "ISO-8859-1"; $charbit = "7bit"; }

  # mail header ¸¦ ÀÛ¼º
  $mail_header = "From: JSBoard Message<nouser@jsboard.kldp.org>\n".
                 "Organization: JSBoard Open Project\n".
                 "User-Agent: JSBoard Mail System\n".
                 "X-Accept-Language: ko,en\n".
                 "MIME-Version: 1.0\n".
                 "Content-Type: text/plain; charset=$charset\n".
                 "Content-Transfer-Encoding: $charbit\n".
                 "To: $to\n".
                 "Reply-To: $from\n".
                 "Return-Path: $from\n".
                 "Subject: $title";

  $body = $mail_header.$body;
  $body = str_replace("\n","\r\n",str_replace("\r","",$body));

  # smtp port ¿¡ socket À» ¿¬°á
  $p = fsockopen($smtp,25,&$errno,&$errstr);
  if ($p) {
    fputs($p,"HELO $smtp\r\n");
    fgets($p,512);
    fputs($p,"MAIL From: $from\r\n");
    fgets($p,512);
    fputs($p,"RCPT To: $to\r\n");
    fgets($p,512);
    fputs($p,"data\r\n");
    fgets($p,512);
    fputs($p,"$body\n.\r\n");
    fgets($p,512);
    fputs($p,"quit\r\n");
    fgets($p,512);
    fclose($p);
  } else print_notice($langs[mail_send_err]);
}

function sendmail($rmail,$fm=0) {

  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE, $SERVER_NAME, $langs;
  global $mail_error, $mail_msg_header;

  $mail_msg_head = "$langs[sm_dr]";
  $mail_error = "$langs[sm_ds]";

  if ($langs[code] == "ko") $time = date("Y/m/d (D) a h½ÃiºÐ");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # ¸ÞÀÏ¿¡¼­ÀÇ double quote¿Í single quote Ã³¸®
  $rmail[text] = stripslashes($rmail[text]);
  $rmail[name] = stripslashes($rmail[name]);
  $rmail[title] = stripslashes($rmail[title]);

  if ($langs[code] == "ko") {
    if ($day == "(Mon)") $day="(¿ù)";
    else if ($day == "(Tue)") $day="(È­)";
    else if ($day == "(Wed)") $day="(¼ö)";
    else if ($day == "(Thu)") $day="(¸ñ)";
    else if ($day == "(Fri)") $day="(±Ý)";
    else if ($day == "(Sat)") $day="(Åä)";
    else if ($day == "(Sun)") $day="(ÀÏ)";
  } else if ($langs[code] == "jp") {
    if ($day == "(Mon)") $day="(êÅ)";
    else if ($day == "(Tue)") $day="(ûý)";
    else if ($day == "(Wed)") $day="(â©)";
    else if ($day == "(Thu)") $day="(ÙÊ)";
    else if ($day == "(Fri)") $day="(ÐÝ)";
    else if ($day == "(Sat)") $day="(÷Ï)";
    else if ($day == "(Sun)") $day="(ìí)";
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

¡á JSBOARD $rmail[table] message

[ Server Infomation ]------------------------------------------------------
ServerWare	: JSBoard-$rmail[version]
Server Name	: $SERVER_NAME$nofm


[ Article Infomation ]-----------------------------------------------------
ÀÌ ¸§		: $rmail[name]
Email		: mailto:$rmail[email]
$homeurl
ÀÏ ½Ã		: $year $day $ampm $hms
---------------------------------------------------------------------------

$rmail[text]



---------------------------------------------------------------------------
REMOTE_ADDR : $REMOTE_ADDR
HTTP_USER_AGENT : $HTTP_USER_AGENT
HTTP_ACCEPT_LANGUAGE : $HTTP_ACCEPT_LANGUAGE
---------------------------------------------------------------------------

OOPS Form mail service - http://www.oops.org
Scripted by JoungKyun Kim
";

  if ($rmail[user] == "yes" && $rmail[reply_orig_email]) {
    $to = $rmail[reply_orig_email];
    if(!$rmail[email]) $rmail[email] = "nomail@jsboard.agent"; 
    socketmail($rmail[smtp],$rmail[reply_orig_email],$rmail[email],$rmail[title],$message,$langs[code]);
  }

  if ($rmail[admin] == "yes" && $rmail[toadmin] != "") {
    $to = $rmail[toadmin];
    if(!$rmail[email]) $rmail[email] = "nomail@jsboard.agent";
    socketmail($rmail[smtp],$rmail[toadmin],$rmail[email],$rmail[title],$message,$langs[code]);
  }

}

?>
