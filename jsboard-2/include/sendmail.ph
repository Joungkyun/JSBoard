<?php
# mail 보내기 함수 2001.11.30 김정균

function get_send_info($table,$no) {
  global $db;
  $c = sql_connect($db[server],$db[user],$db[pass]);
  sql_select_db($db[name]);
  $r = sql_query("SELECT name,email FROM $table WHERE no = $no");
  $row = sql_fetch_array($r);
  mysql_close($c);

  return $row;
}

function mailcheck($to,$from,$title,$body) {
  global $langs;
  if(!trim($to)) print_error($langs[mail_to_chk_err],250,150,1);
  if(!trim($from)) print_error($langs[mail_from_chk_err],250,150,1);
  if(!trim($title)) print_error($langs[mail_title_chk_err],250,150,1);
  if(!trim($body)) print_error($langs[mail_body_chk_drr],250,150,1);
}

function mail_header($to,$from,$title,$type=0) {
  global $langs;

  # language type 을 결정
  if($langs[code] == "ko") $charbit = "8bit";
  else  $charbit = "7bit";

  # mail header 를 작성 
  if(!$type) {
    $header = "From: JSBoard Message <$from>\r\n".
              "X-Accept-Language: ko,en\r\n".
              "MIME-Version: 1.0\r\n".
              "Content-Type: text/plain; charset=$langs[charset]\r\n".
              "Content-Transfer-Encoding: $charbit\r\n".
              "To: $to\r\n".
              "Subject: $title\n";
  } else {
    $header = "From: JSBoard Message <$from>\r\n".
              "X-Accept-Language: ko,en\r\n".
              "MIME-Version: 1.0\r\n".
              "Content-Type: text/plain; charset=$langs[charset]\r\n".
              "Content-Transfer-Encoding: $charbit\r\n";
  }

  return $header;
}

function phpmail($to,$from,$title,$body) {
  global $langs;

  # 빈 문자열 체크
  mailcheck($to,$from,$title,$body);

  $header = mail_header($to,$from,$title,1);
  mail($to,$title,$body,$header,"-f\"$from\"") or print_notice($langs[mail_send_err]);
}

function socketmail($smtp,$to,$from,$title,$body) {
  global $langs;
  $smtp = !trim($smtp) ? "127.0.0.1" : $smtp;

  # 빈 문자열 체크
  mailcheck($to,$from,$title,$body);
  
  # mail header 를 작성 
  $mail_header = mail_header($to,$from,$title);

  # body 를 구성
  $body = $mail_header.$body;
  $body = str_replace("\n","\r\n",str_replace("\r","",$body));
  
  # smtp port 에 socket 을 연결
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
    fputs($p,"$body\r\n.\r\n");
    fgets($p,512);
    fputs($p,"quit\r\n");
    fgets($p,512);
    fclose($p);
  } else print_notice($langs[mail_send_err]);
}

function sendmail($rmail,$fm=0) {
  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE;
  global $SERVER_NAME, $langs;

  $mail_msg_head = "$langs[sm_dr]";

  if ($langs[code] == "ko") $time = date("Y/m/d (D) a h시i분");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # 메일에서의 double quote와 single quote 처리
  $rmail[text] = stripslashes($rmail[text]);
  $rmail[name] = stripslashes($rmail[name]);
  $rmail[title] = stripslashes($rmail[title]);
  $rmail[email] = !trim($rmail[email]) ? "nobody@$SERVER_NAME" : $rmail[email];
  $rmail[pemail] = (eregi("^nobody@",$rmail[email])) ? "" : "mailto:$rmail[email]";

  if ($langs[code] == "ko") {
    if ($day == "(Mon)") $day="(월)";
    else if ($day == "(Tue)") $day="(화)";
    else if ($day == "(Wed)") $day="(수)";
    else if ($day == "(Thu)") $day="(목)";
    else if ($day == "(Fri)") $day="(금)";
    else if ($day == "(Sat)") $day="(토)";
    else if ($day == "(Sun)") $day="(일)";
  } else if ($langs[code] == "jp") {
    if ($day == "(Mon)") $day="(月)";
    else if ($day == "(Tue)") $day="(火)";
    else if ($day == "(Wed)") $day="(水)";
    else if ($day == "(Thu)") $day="(木)";
    else if ($day == "(Fri)") $day="(金)";
    else if ($day == "(Sat)") $day="(土)";
    else if ($day == "(Sun)") $day="(日)";
  }

  $webboard_address =  sprintf("%s%s",$rmail[path],"read.php?table=$rmail[table]&no=$rmail[no]");
  $reply_article    =  sprintf("%s%s",$rmail[path],"reply.php?table=$rmail[table]&no=$rmail[no]");

  if (!$fm) {
    $dbname  = "DB Name           : $rmail[table]";
    $dbloca  = "DB Location       : $webboard_address";
    $repart  = "Reply Article     : $reply_article";
    $nofm    = "\n$dbname\n$dbloca\n$repart";
    $homeurl = "HomeURL           : $rmail[url]";
  } else {
    $rmail[user] = 1;
    $homeurl = "To                : mailto:$rmail[reply_orig_email]";
  }

  $message = "\r\n".
             "\r\n".
             "$mail_msg_header\r\n".
             "\r\n".
             "■ JSBOARD $rmail[table] message\r\n".
             "\r\n".
             "[ Server Infomation ]------------------------------------------------------\r\n".
             "ServerWare        : JSBoard-$rmail[version]\r\n".
             "Server Name       : $SERVER_NAME$nofm\r\n".
             "\r\n".
             "\r\n".
             "[ Article Infomation ]-----------------------------------------------------\r\n".
             "$langs[u_name]              : $rmail[name]\r\n".
             "Email             : $rmail[pemail]\r\n".
             "$homeurl\r\n".
             "$langs[a_t13]              : $year $day $ampm $hms\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             "$rmail[text]\r\n".
             "\r\n".
             "\r\n".
             "\r\n".
             "---------------------------------------------------------------------------\r\n".
             "REMOTE_ADDR : $REMOTE_ADDR\r\n".
             "HTTP_USER_AGENT : $HTTP_USER_AGENT\r\n".
             "HTTP_ACCEPT_LANGUAGE : $HTTP_ACCEPT_LANGUAGE\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             "JSBoard Form mail service - http://jsboard.kldp.org\r\n";

  if ($rmail[user] && $rmail[reply_orig_email] && $rmail[email] != $rmail[toadmin]) {
    if($rmail[mta]) socketmail($rmail[smtp],$rmail[reply_orig_email],$rmail[email],$rmail[title],$message);
    else phpmail($rmail[reply_orig_email],$rmail[email],$rmail[title],$message);
  }

  if ($rmail[admin] && $rmail[toadmin] != "" && $rmail[email] != $rmail[toadmin]) {
    if($rmail[mta]) socketmail($rmail[smtp],$rmail[toadmin],$rmail[email],$rmail[title],$message);
    else phpmail($rmail[toadmin],$rmail[email],$rmail[title],$message);
  }

}
?>
