<?php
# mail ∫∏≥ª±‚ «‘ºˆ 2001.11.30 ±Ë¡§±’

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

function get_boundary_msg() {
  $uniqchr = uniqid("");
  $one = strtoupper($uniqchr[0]);
  $two = strtoupper(substr($uniqchr,0,8));
  $three = strtoupper(substr(strrev($uniqchr),0,8));
  return "---=_NextPart_000_000${one}_${two}.${three}";
}

function body_encode_lib($str) {
  $return = base64_encode($str);
  $len = strlen($return);
  $chk = intval($len/60);

  for($i=1;$i<$chk+1;$i++) {
    if($i < 2) $no = $i*60-1;
    else {
      $pl = $pl + 2;
      $no = $i*60-1+$pl;
    }
    $return = substr_replace($return,"$return[$no]\r\n",$no,1);
  }

  return $return;
}

function html_to_plain_lib($str) {
  $src[] = "/\n|\r\n/i";
  $des[] = "||ENTER||";
  $src[] = "/^.*<BODY[^>]*>/i";
  $des[] = "";
  $src[] = "/<\/BODY>.*$/i";
  $des[] = "";
  $src[] = "/\|\|ENTER\|\|/i";
  $des[] = "\r\n";
  $str = preg_replace($src,$des,$str);

  return $str;
}

function get_htmltext($rmail,$year,$day,$ampm,$hms,$nofm) {
  global $langs,$color;

  if($nofm) $nofm = auto_link(&$nofm);
  if($rmail[url]) $homeurl = "HomeURL           : ".auto_link($rmail[url]);
  if($rmail[email]) {
    $rmail[pemail] = (eregi("^nobody@",$rmail[email])) ? "" : $rmail[email];
    $rmail[pemail] = preg_replace("/$rmail[pemail]/i","mailto:<A HREF=mailto:\\0>\\0</A>",$rmail[pemail]);
  }
  $rmail[text] = !$rmail[html] ? html_to_plain_lib($rmail[text]) : $rmail[text];
  $servername = strtoupper($_SERVER[SERVER_NAME]);

  $themepath = "theme/$rmail[theme]/mail.template";
  $htmltext = addslashes(file_operate("$themepath","r","can't open $themepath",sizeof($themepath)));
  eval("\$htmltext = \"$htmltext\";");
  $htmltext = stripslashes($htmltext);
  return $htmltext;
}

function mail_header($to,$from,$title,$type=0) {
  global $langs,$boundary;

  # mail header ∏¶ ¿€º∫ 
  $header = "From: JSBoard Message <$from>\r\n".
            "MIME-Version: 1.0\r\n".
            "X-Accept-Language: ko,en\r\n";

  if(!$type) {
    $header .= "To: $to\r\n".
               "Subject: $title\n";
  }

  $boundary = get_boundary_msg();
  $header .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n\r\n".
             "This is a multi-part message in MIME format.\r\n";

  return $header;
}

function phpmail($to,$from,$title,$pbody,$hbody) {
  global $langs,$boundary;

  # ∫Û πÆ¿⁄ø≠ √º≈©
  mailcheck($to,$from,$title,$pbody);

  $title = "=?$langs[charset]?B?".trim(base64_encode($title))."?=";
  $title = eregi_replace("\n[ |\t]*"," ",str_replace("\r\n","\n",$title));

  $header = mail_header($to,$from,$title,1);

  $body = "--$boundary\r\n".
          "Content-Type: text/plain; charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$pbody).
          "\r\n\r\n--$boundary\r\n".
          "Content-Type: text/html; charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$hbody).
          "\r\n\r\n--$boundary--\r\n\r\n";

  mail($to,$title,$body,$header,"-f$from") or print_notice($langs[mail_send_err]);
}

function socketmail($smtp,$to,$from,$title,$pbody,$hbody) {
  global $langs,$boundary;
  $smtp = !trim($smtp) ? "127.0.0.1" : $smtp;

  # ∫Û πÆ¿⁄ø≠ √º≈©
  mailcheck($to,$from,$title,$pbody);

  $title = "=?$langs[charset]?B?".trim(base64_encode($title))."?=";
  $title = eregi_replace("\n[ |\t]*"," ",str_replace("\r\n","\n",$title));
  
  # mail header ∏¶ ¿€º∫ 
  $mail_header = mail_header($to,$from,$title);

  # body ∏¶ ±∏º∫
  $body = str_replace("\n","\r\n",str_replace("\r","",$pbody));
  $body = "--$boundary\r\n".
          "Content-Type: text/plain; charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$pbody).
          "\r\n\r\n--$boundary\r\n".
          "Content-Type: html/text; charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$hbody).
          "\r\n\r\n--$boundary--\r\n\r\n";

  $body = $mail_header.$body;
  
  # smtp port ø° socket ¿ª ø¨∞·
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

function sendmail($rmail) {
  global $langs;

  $mail_msg_head = "$langs[sm_dr]";

  if ($langs[code] == "ko") $time = date("Y/m/d (D) a hΩ√i∫–");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # ∏ﬁ¿œø°º≠¿« double quoteøÕ single quote √≥∏Æ
  $rmail[text] = stripslashes($rmail[text]);
  $rmail[name] = stripslashes($rmail[name]);
  $rmail[title] = stripslashes($rmail[title]);
  $rmail[email] = !trim($rmail[email]) ? "nobody@$_SERVER[SERVER_NAME]" : $rmail[email];
  $rmail[pemail] = (eregi("^nobody@",$rmail[email])) ? "" : "mailto:$rmail[email]";

  if ($langs[code] == "ko") {
    if ($day == "(Mon)") $day="(ø˘)";
    else if ($day == "(Tue)") $day="(»≠)";
    else if ($day == "(Wed)") $day="(ºˆ)";
    else if ($day == "(Thu)") $day="(∏Ò)";
    else if ($day == "(Fri)") $day="(±›)";
    else if ($day == "(Sat)") $day="(≈‰)";
    else if ($day == "(Sun)") $day="(¿œ)";
  } else if ($langs[code] == "jp") {
    if ($day == "(Mon)") $day="(Í≈)";
    else if ($day == "(Tue)") $day="(˚˝)";
    else if ($day == "(Wed)") $day="(‚©)";
    else if ($day == "(Thu)") $day="(Ÿ )";
    else if ($day == "(Fri)") $day="(–›)";
    else if ($day == "(Sat)") $day="(˜œ)";
    else if ($day == "(Sun)") $day="(ÏÌ)";
  }

  $webboard_address =  sprintf("%s%s",$rmail[path],"read.php?table=$rmail[table]&no=$rmail[no]");
  $reply_article    =  sprintf("%s%s",$rmail[path],"reply.php?table=$rmail[table]&no=$rmail[no]");

  $dbname  = "DB Name           : $rmail[table]";
  $dbloca  = "DB Location       : $webboard_address";
  $repart  = "Reply Article     : $reply_article";
  $nofm    = "\n$dbname\n$dbloca\n$repart";
  $homeurl = "HomeURL           : $rmail[url]";

  $message = "\r\n".
             "\r\n".
             "$mail_msg_header\r\n".
             "\r\n".
             "°· JSBOARD $rmail[table] message\r\n".
             "\r\n".
             "[ Server Infomation ]------------------------------------------------------\r\n".
             "ServerWare        : JSBoard-$rmail[version]\r\n".
             "Server Name       : $_SERVER[SERVER_NAME]$nofm\r\n".
             "\r\n".
             "\r\n".
             "[ Article Infomation ]-----------------------------------------------------\r\n".
             "$langs[u_name]              : $rmail[name]\r\n".
             "Email             : $rmail[pemail]\r\n".
             "$homeurl\r\n".
             "$langs[a_t13]              : $year $day $ampm $hms\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             html_to_plain_lib($plainbody)."\r\n".
             "\r\n".
             "\r\n".
             "\r\n".
             "---------------------------------------------------------------------------\r\n".
             "REMOTE_ADDR : $_SERVER[REMOTE_ADDR]\r\n".
             "HTTP_USER_AGENT : $_SERVER[HTTP_USER_AGENT]\r\n".
             "HTTP_ACCEPT_LANGUAGE : $_SERVER[HTTP_ACCEPT_LANGUAGE]\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             "JSBoard Form mail service - http://jsboard.kldp.org\r\n";

  $htmltext = get_htmltext($rmail,$year,$day,$ampm,$hms,$nofm);
  #echo $htmltext;
  #exit;

  if ($rmail[user] && $rmail[reply_orig_email] && $rmail[email] != $rmail[toadmin]) {
    if($rmail[mta]) socketmail($rmail[smtp],$rmail[reply_orig_email],$rmail[email],$rmail[title],&$message,&$htmltext);
    else phpmail($rmail[reply_orig_email],$rmail[email],$rmail[title],&$message,&$htmltext);
  }

  if ($rmail[admin] && $rmail[toadmin] != "" && $rmail[email] != $rmail[toadmin]) {
    if($rmail[mta]) socketmail($rmail[smtp],$rmail[toadmin],$rmail[email],$rmail[title],&$message,&$htmltext);
    else phpmail($rmail[toadmin],$rmail[email],$rmail[title],&$message,&$htmltext);
  }

}
?>
