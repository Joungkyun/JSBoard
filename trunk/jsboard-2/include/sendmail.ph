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
    $this->debug = $v[debug];
    $this->ofhtml = $v[ofhtml];
    if($_SERVER[SERVER_NAME]) $this->helo = $_SERVER[SERVER_NAME];
    if(!$this->helo || eregi("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$this->helo))
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

    if(checkdnsrr($host,"MX")) {
      if(getmxrr($host,$mx,$weight)) {
        $idx = 0;
        for($i=0;$i<sizeof($mx);$i++) {
          $dest = $dest ? $dest : $weight[$i];
          if($dest > $weight[$i]) {
            $dest = $weight[$i];
            $idx = $i;
          }
        }
      } else return $host;
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
      if(!eregi("^(220|221|250|251|354)$",substr(trim($str),0,3)))
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

      if(eregi("Mail From:",$str) && eregi("exist|require|error",$recvchk) && !$chk) {
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
  if($rmail[url]) $homeurl = "HomeURL           : ".auto_link($rmail[url])."\r\n";
  if($rmail[email]) {
    $rmail[pemail] = (eregi("^nobody@",$rmail[email])) ? "" : $rmail[email];
    if($rmail[pemail]) {
      $rmail[pemail] = preg_replace("/$rmail[pemail]/i","mailto:<A HREF=mailto:\\0>\\0</A>",$rmail[pemail]);
      $mailurl = "Email             : $rmail[pemail]\r\n";
    }
  }
  $addressinfo = $mailurl.$homeurl;
  $rmail[text] = !$rmail[html] ? html_to_plain_lib($rmail[text]) : $rmail[text];
  $servername = strtoupper($_SERVER[SERVER_NAME]);

  $themepath = "theme/$rmail[theme]/mail.template";
  $htmltext = addslashes(file_operate("$themepath","r","can't open $themepath",sizeof($themepath)));
  eval("\$htmltext = \"$htmltext\";");
  $htmltext = stripslashes($htmltext);
  return $htmltext;
}

function mail_header($to,$from,$title) {
  global $langs,$boundary;

  # mail header �� �ۼ� 
  $boundary = get_boundary_msg();
  $header = "From: JSBoard Message <$from>\r\n".
            "MIME-Version: 1.0\r\n".
            "To: $to\r\n".
            "Subject: $title\n".
            "Content-Type: multipart/alternative;\r\n".
            "              boundary=\"$boundary\"\r\n\r\n".
            "This is a multi-part message in MIME format.\r\n\r\n";

  return $header;
}

function socketmail($to,$from,$title,$pbody,$hbody) {
  global $langs,$boundary;

  # �� ���ڿ� üũ
  mailcheck($to,$from,$title,$pbody);

  $title = "=?$langs[charset]?B?".trim(base64_encode($title))."?=";
  $title = eregi_replace("\n[ |\t]*"," ",str_replace("\r\n","\n",$title));
  
  # mail header �� �ۼ� 
  $mail_header = mail_header($to,$from,$title);

  # body �� ����
  $body = str_replace("\n","\r\n",str_replace("\r","",$pbody));
  $body = "--$boundary\r\n".
          "Content-Type: text/plain;\r\n".
          "              charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$pbody).
          "\r\n\r\n--$boundary\r\n".
          "Content-Type: text/html;\r\n".
          "              charset=$langs[charset]\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib(&$hbody).
          "\r\n\r\n--$boundary--\r\n\r\n";

  $mails[debug] = 0;
  $mails[ofhtml] = 0;
  $mails[to] = $to;
  $mails[from] = $from;
  $mails[text] = $mail_header.$body;

  new maildaemon($mails);
}

function sendmail($rmail) {
  global $langs;

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
  $rmail[email] = !trim($rmail[email]) ? "nobody@$_SERVER[SERVER_NAME]" : $rmail[email];
  if(eregi("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$_SERVER[SERVER_NAME]))
    $rmail[email] = "nobody@[".$_SERVER[SERVER_NAME]."]";

  $rmail[pemail] = (eregi("^nobody@",$rmail[email])) ? "" : "mailto:$rmail[email]";

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

  $webboard_address =  sprintf("%s%s",$rmail[path],"read.php?table=$rmail[table]&no=$rmail[no]");
  $reply_article    =  sprintf("%s%s",$rmail[path],"reply.php?table=$rmail[table]&no=$rmail[no]");

  $dbname  = "DB Name           : $rmail[table]";
  $dbloca  = "DB Location       : $webboard_address";
  $repart  = "Reply Article     : $reply_article";
  $nofm    = "\n$dbname\n$dbloca\n$repart";
  $mailurl = "Email             : $rmail[pemail]\r\n";
  $homeurl = "HomeURL           : $rmail[url]\r\n";

  $message = "\r\n".
             "\r\n".
             "$mail_msg_header\r\n".
             "\r\n".
             "�� JSBOARD $rmail[table] message\r\n".
             "\r\n".
             "[ Server Infomation ]------------------------------------------------------\r\n".
             "ServerWare        : JSBoard-$rmail[version]\r\n".
             "Server Name       : $_SERVER[SERVER_NAME]$nofm\r\n".
             "\r\n".
             "\r\n".
             "[ Article Infomation ]-----------------------------------------------------\r\n".
             "$langs[u_name]              : $rmail[name]\r\n".
             "$mailurl".
             "$homeurl".
             "$langs[a_t13]              : $year $day $ampm $hms\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             html_to_plain_lib($rmail[text])."\r\n".
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

  if ($rmail[user] && $rmail[reply_orig_email] && $rmail[email] != $rmail[toadmin]) {
    socketmail($rmail[reply_orig_email],$rmail[email],$rmail[title],&$message,&$htmltext);
  }

  if ($rmail[admin] && $rmail[toadmin] != "" && $rmail[email] != $rmail[toadmin]) {
    socketmail($rmail[toadmin],$rmail[email],$rmail[title],&$message,&$htmltext);
  }
}
?>
