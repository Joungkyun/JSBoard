<?php
# mail ������ �Լ� 2001.11.30 ������

# �������� smtp daemon �� �������� �ʰ� ���� �߼��ϴ� smtp class
#
# Ư�� �迭�� class �� ������ �Ͽ� ������ �߼��Ѵ�. �迭�� �Ʒ��� �����Ѵ�.
#
# debug -> debug �� ���� �������� �����Ѵ�.
# ofhtml -> ���󿡼� ������� ���󿡼� ��������� �����Ѵ�.
# from -> ������ �߼��ϴ� ����� �����ּ�
# to -> ������ ���� ����� ���� �ּ�
# text -> ��� ������ ������ ���� ����
#
class maildaemon {
  var $failed = 0;

  function maildaemon($v) {
    global $SERVER_NAME;
    $this->debug = $v[debug];
    $this->ofhtml = $v[ofhtml];
    if($SERVER_NAME) $this->helo = $SERVER_NAME;
    if(!$this->helo || preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i",$this->helo))
      $this->helo = "JSBoardMessage";

    $this->from = $v[from];
    $this->to   = $v[to];
    $this->body = $v[text]."\r\n.";
    $this->newline = $this->ofhtml ? "<br>\n" : "\n";

    $this->mx = $this->getMX($this->to);

    if($this->debug) {
      echo "DEBUG: ".$this->mx." start".$this->newline;
      echo "################################################################".$this->newline;
    }
    $this->sockets("open");
    $this->send("HELO ".$this->helo);
    $this->send("MAIL FROM: <".$this->from.">");
    $this->send("RCPT TO: <".$this->to.">");
    $this->send("data");
    $this->send($this->body);
    $this->send("quit");
    $this->sockets("close");
  }

  function getMX($email) {
    $dev = explode("@",$email);
    $account = $dev[0];
    $host = $dev[1];

    if(checkdnsrr($host,"MX") && getmxrr($host,$mx,$weight)) {
      $idx = 0;
      for($i=0;$i<sizeof($mx);$i++) {
        $dest = $dest ? $dest : $weight[$i];
        if($dest > $weight[$i]) {
          $dest = $weight[$i];
          $idx = $i;
        }
      }
    } else return $host;
    return $mx[$idx];
  }

  # ����� �Լ�
  #  $t -> 1 (debug of socket open,close)
  #        0 (regular smtp message)
  #  $p -> 1 (print detail debug)
  # 
  # return 1 -> success
  # return 0 -> failed
  #
  function debug($str,$t=0,$p=0) {
    if($t) {
      if(!$str) $this->failed = 1;
      if($this->sock) $returnmsg = trim(fgets($this->sock,1024));
    } else {
      if(!preg_match("/^(220|221|250|251|354)$/i",substr(trim($str),0,3)))
        $this->failed = 1;
    }

    # DEBUG mode -> ��� �޼��� ���
    if($p) {
      if($t) {
        $str = "Conncet ".$this->mx;
        $str .= $this->failed ? " Failed" : " Success";
        $str .= $this->newline."DEBUG: $returnmsg";
      }
      echo "DEBUG: $str".$this->newline;
    }

    # DEBUG ��尡 �ƴҶ�, ���� �޼��� ���
    if(!$p && $this->failed) {
      if($this->ofhtml) echo "<SCRIPT>\nalert('$str')\n</SCRIPT>\n";
      else "ERROR: $str\n";
    }
  }

  function sockets($option=0) {
    switch($option) {
      case "open" :
        $this->sock = @fsockopen($this->mx,25,&$this->errno,&$this->errstr,30);
        $this->debug($this->sock,1,$this->debug);
        break;
      default :
        if($this->sock) fclose($this->sock);
        break;
    }
  }

  function send($str,$chk=0) {
    if(!$this->failed) {
      if($this->debug) {
        if(eregi("\r\n",trim($str)))
          $str_debug = trim(str_replace("\r\n","\r\n       ",$str));
        else $str_debug = $str;
      }
      fputs($this->sock,"$str\r\n");
      $recv = trim(fgets($this->sock,1024));
      $recvchk = $recv;
      $this->debug($recv,0,$this->debug);

      if(preg_match("/Mail From:/i",$str) && preg_match("/exist|require|error/i",$recvchk) && !$chk) {
        $this->failed = 0;
        $this->send("MAIL FROM: <".$this->to.">",1);
      }
    }
  }
}

function mailcheck($to,$from,$title,$body) {
  global $langs;
  if(!trim($to)) print_error($langs[mail_to_chk_err],250,150,1);
  if(!trim($from)) print_error($langs[mail_from_chk_err],250,150,1);
  if(!trim($title)) print_error($langs[mail_title_chk_err],250,150,1);
  if(!trim($body)) print_error($langs[mail_body_chk_drr],250,150,1);
}

function generate_mail_id($uid) {
  $id = date("YmdHis", time());
  mt_srand ((float) microtime() * 1000000);
  $randval = mt_rand();
  $id .= $randval. "@$uid";
  return $id;
}

function mail_header($to,$from,$title,$mta='') {
  global $langs;

  # language type �� ����
  if($langs[code] == "ko") $charbit = "8bit";
  else  $charbit = "7bit";

  # mail header �� �ۼ� 
  $header = "Message-ID: <".generate_mail_id(preg_replace("/@.+$/i","",$to)).">\r\n".
            "From: JSBoard Message <$from>\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-Type: text/plain; charset=$langs[charset]\r\n".
            "Content-Transfer-Encoding: $charbit\r\n";
  if(!$mta) $header .= "Date: ".date ("D, j M Y H:i:s T",time())."\r\n".
                       "To: $to\r\n".
                       "Subject: $title\r\n\r\n";

  return $header;
}

function socketmail($to,$from,$title,$body,$smtp='') {
  global $langs;

  # �� ���ڿ� üũ
  mailcheck($to,$from,$title,$body);
  
  # mail header �� �ۼ� 
  $mail_header = mail_header($to,$from,$title,"$smtp");

  # body �� ����
  $bodies = $mail_header.$body;
  $bodies = str_replace("\r","",$bodies);

  $mails[debug] = 0;
  $mails[ofhtml] = 0;
  $mails[to] = $to;
  $mails[from] = $from;
  $mails[text] = $bodies;
 
  if($smtp) {
    ini_set("SMTP","$smtp");
    if(check_phpver("4.0.4"))
      mail($mails[to],$title,$body,$mail_header,"-f$mails[from]");
    else
      mail($mails[to],$title,$body,$mail_header);
  } else {
    new maildaemon($mails);
  }
}

function sendmail($rmail,$fm=0) {
  global $REMOTE_ADDR, $HTTP_USER_AGENT, $HTTP_ACCEPT_LANGUAGE;
  global $SERVER_NAME, $langs;

  $rmail[smtp] = $rmail[smtp] ? $rmail[smtp] : 0;
  $mail_msg_head = "$langs[sm_dr]";

  if ($langs[code] == "ko") $time = date("Y/m/d (D) a h��i��");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # ���Ͽ����� double quote�� single quote ó��
  $rmail[text] = stripslashes($rmail[text]);
  $rmail[name] = stripslashes($rmail[name]);
  $rmail[title] = stripslashes($rmail[title]);
  $rmail[email] = !trim($rmail[email]) ? "nobody@$SERVER_NAME" : $rmail[email];
  if(preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i",$SERVER_NAME))
    $rmail[email] = "nobody@[".$SERVER_NAME."]";
  $rmail[pemail] = (preg_match("/^nobody@/i",$rmail[email])) ? "" : "mailto:$rmail[email]";

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

  $dbname  = "DB Name           : $rmail[table]";
  $dbloca  = "DB Location       : $webboard_address";
  #$repart  = "Reply Article     : $reply_article";
  $nofm    = "\r\n$dbname\r\n$dbloca\r\n$repart";
  $mailurl = "Email             : $rmail[pemail]";
  $homeurl = "HomeURL           : $rmail[url]";

  $message = "\r\n".
             "\r\n".
             "$mail_msg_header\r\n".
             "\r\n".
             "�� JSBOARD $rmail[table] message\r\n".
             "\r\n".
             "[ Server Infomation ]------------------------------------------------------\r\n".
             "ServerWare        : JSBoard-$rmail[version]\r\n".
             "Server Name       : $SERVER_NAME$nofm\r\n".
             "\r\n".
             "\r\n".
             "[ Article Infomation ]-----------------------------------------------------\r\n".
             "�� ��             : $rmail[name]\r\n".
             "Email             : $rmail[pemail]\r\n".
             "$homeurl\r\n".
             "�� ��             : $year $day $ampm $hms\r\n".
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
             "JSBoard Form mail service\r\n";

  if ($rmail[user] == "yes" && $rmail[reply_orig_email] && $rmail[email] != $rmail[reply_orig_email]) {
    socketmail($rmail[reply_orig_email],$rmail[email],$rmail[title],$message,$rmail[smtp]);
  }

  if ($rmail[admin] == "yes" && $rmail[toadmin] != "" && $rmail[email] != $rmail[toadmin]) {
    socketmail($rmail[toadmin],$rmail[email],$rmail[title],$message,$rmail[smtp]);
  }
}
?>
