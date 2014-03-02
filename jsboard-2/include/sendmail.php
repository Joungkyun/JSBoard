<?php
# mail 보내기 함수 2001.11.30 김정균
# $Id: sendmail.php,v 1.5 2014-03-02 17:11:31 oops Exp $

# 서버상의 smtp daemon 에 의존하지 않고 직접 발송하는 smtp class
#
# 특정 배열로 class 에 전달을 하여 메일을 발송한다. 배열은 아래을 참조한다.
#
# debug -> debug 를 할지 안할지를 결정한다.
# ofhtml -> 웹상에서 사용할지 쉘상에서 사용할지를 결정한다.
# from -> 메일을 발송하는 사람의 메일주소
# to -> 메일을 받을 사람의 메일 주소
# text -> 헤더 내용을 포함한 메일 본문
#
# {{{ +-- class maildaemon
class maildaemon {
  var $failed = 0;

  // {{{ +-- public maildaemon($v)
  function maildaemon($v) {
    $this->debug = $v['debug'];
    $this->ofhtml = $v['ofhtml'];
    if($_SERVER['SERVER_NAME']) $this->helo = $_SERVER['SERVER_NAME'];
    if(!$this->helo || preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i",$this->helo))
      $this->helo = "JSBoardMessage";

    $this->from = $v['from'];
    $this->to   = $v['to'];
    $this->body = $v['text']."\r\n.";
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
  // }}}

  // {{{ +-- public getMX($email)
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
  // }}}

  # // {{{ +-- public debug($str,$t=0,$p=0)
  # 디버그 함수
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
      if(!preg_match("/^(220|221|250|251|354)$/",substr(trim($str),0,3)))
        $this->failed = 1;
    }

    # DEBUG mode -> 모든 메세지 출력
    if($p) {
      if($t) {
        $str = "Conncet ".$this->mx;
        $str .= $this->failed ? " Failed" : " Success";
        $str .= $this->newline."DEBUG: $returnmsg";
      }
      echo "DEBUG: $str".$this->newline;
    }

    # DEBUG 모드가 아닐때, 에러 메세지 출력
    if(!$p && $this->failed) {
      if($this->ofhtml) echo "<SCRIPT>\nalert('$str')\n</SCRIPT>\n";
      else "ERROR: $str\n";
    }
  }
  // }}}

  // {{{ +-- public sockets($option=0)
  function sockets($option=0) {
    switch($option) {
      case "open" :
        $this->sock = @fsockopen($this->mx,25,$this->errno,$this->errstr,30);
        $this->debug($this->sock,1,$this->debug);
        break;
      default :
        if($this->sock) fclose($this->sock);
        break;
    }
  }
  // }}}

  // {{{ +-- public send($str,$chk=0)
  function send($str,$chk=0) {
    if(!$this->failed) {
      if($this->debug) {
        if(preg_match("/\r\n/",trim($str)))
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
  // }}}
}
// }}}

// {{{ +-- public mailcheck($to,$from,$title,$body)
function mailcheck($to,$from,$title,$body) {
  global $langs;
  if(!trim($to)) print_error($langs['mail_to_chk_err'],250,150,1);
  if(!trim($from)) print_error($langs['mail_from_chk_err'],250,150,1);
  if(!trim($title)) print_error($langs['mail_title_chk_err'],250,150,1);
  if(!trim($body)) print_error($langs['mail_body_chk_drr'],250,150,1);
}
// }}}

// {{{ +-- public get_boundary_msg(void)
function get_boundary_msg() {
  $uniqchr = uniqid("");
  $one = strtoupper($uniqchr[0]);
  $two = strtoupper(substr($uniqchr,0,8));
  $three = strtoupper(substr(strrev($uniqchr),0,8));
  return "----=_NextPart_000_000${one}_${two}.${three}";
}
// }}}

// {{{ +-- public generate_mail_id($uid)
function generate_mail_id($uid) {
  $id = date("YmdHis",time());
  mt_srand((float) microtime() * 1000000);
  $randval = mt_rand();
  $id .= $randval."@$uid";
  return $id;
}
// }}}

// {{{ +-- public body_encode_lib($str)
function body_encode_lib($str) {
  $return = base64_encode(trim($str));
  $return = wordwrap($return,60,"\r\n",1);
  return $return;
}
// }}}

// {{{ +-- public html_to_plain_lib($str)
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
// }}}

// {{{ +-- public get_htmltext($rmail,$year,$day,$ampm,$hms,$nofm)
function get_htmltext($rmail,$year,$day,$ampm,$hms,$nofm) {
  global $langs,$color;

  if($nofm) $nofm = auto_link($nofm);
  if($rmail['url']) $homeurl = "HomeURL           : ".auto_link($rmail['url'])."\r\n";
  if($rmail['email']) {
    $rmail['pemail'] = (preg_match("/^nobody@/i",$rmail['email'])) ? "" : $rmail['email'];
    if($rmail['pemail']) {
      $rmail['pemail'] = preg_replace("/{$rmail['pemail']}/i","mailto:<A HREF=mailto:\\0>\\0</A>",$rmail['pemail']);
      $mailurl = "Email             : {$rmail['pemail']}\r\n";
    }
  }
  $addressinfo = $mailurl.$homeurl;
  $rmail['text'] = !$rmail['html'] ? html_to_plain_lib($rmail['text']) : $rmail['text'];
  $servername = strtoupper($_SERVER['SERVER_NAME']);

  $themepath = "theme/{$rmail['theme']}/mail.template";
  $htmltext = file_operate("$themepath","r","can't open $themepath",sizeof($themepath));
  $htmltext = str_replace ("\"", "\\\"", $htmltext);
  eval("\$htmltext = \"$htmltext\";");

  return $htmltext;
}
// }}}

// {{{ +-- public mail_header($to,$from,$title,$mta=0)
function mail_header($to,$from,$title,$mta=0) {
  global $langs,$boundary;

  # mail header 를 작성 
  $boundary = get_boundary_msg();
  $header = "Message-ID: <".generate_mail_id(preg_replace("/@.+$/i","",$to)).">\r\n".
            "From: JSBoard Message <$from>\r\n".
            "MIME-Version: 1.0\r\n";

  if(!$mta) $header .= "Date: ".date ("D, j M Y H:i:s T",time())."\r\n".
                       "To: $to\r\n".
                       "Subject: $title\r\n";

  $header .= "Content-Type: multipart/alternative;\r\n".
             "              boundary=\"$boundary\"\r\n\r\n";

  return $header;
}
// }}}

// {{{ +-- public socketmail($mta,$to,$from,$title,$pbody,$hbody)
function socketmail($mta,$to,$from,$title,$pbody,$hbody) {
  global $langs,$boundary;

  # 빈 문자열 체크
  mailcheck($to,$from,$title,$pbody);

  $title = "=?{$langs['charset']}?B?".trim(base64_encode($title))."?=";
  $title = preg_replace("/[\s]+/i"," ",str_replace("\r\n","\n",$title));
  
  # mail header 를 작성 
  $mail_header = mail_header($to,$from,$title,$mta);

  # body 를 구성
  $body = "This is a multi-part message in MIME format.\r\n".
          "\r\n--$boundary\r\n".
          "Content-Type: text/plain; charset={$langs['charset']}\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib($pbody).
          "\r\n\r\n--$boundary\r\n".
          "Content-Type: text/html; charset={$langs['charset']}\r\n".
          "Content-Transfer-Encoding: base64\r\n\r\n".
          body_encode_lib($hbody).
          "\r\n--$boundary--\r\n";

  $mails['debug'] = 0;
  $mails['ofhtml'] = 0;
  $mails['to'] = $to;
  $mails['from'] = $from;
  $mails['text'] = $mail_header.$body;

  if($mta) {
    ini_set('SMTP','$smtp');
    $body = str_replace("\r\n","\n",$body);
    $mail_header = str_replace("\r\n","\n",$mail_header);
    mail($mails['to'],$title,$body,$mail_header,"-f{$mails['from']}");
  } else {
    new maildaemon($mails);
  }
}
// }}}

// {{{ +-- public sendmail($rmail)
function sendmail($rmail) {
  global $langs;

  if($rmail['smtp']) $rmail['mta'] = $rmail['smtp'];
  $rmail['mta'] = $rmail['mta'] ? $rmail['mta'] : 0;
  $mail_msg_head = $langs['sm_dr'];

  if ($langs['code'] == "ko") $time = date("Y/m/d (D) a h시i분");
  else $time = date("Y/m/d (D) a h:i");

  $time=explode(" ",$time);

  $year=$time[0];
  $day=$time[1];
  $ampm=$time[2];
  $hms=$time[3];

  # 메일에서의 double quote와 single quote 처리
  $rmail['text'] = stripslashes($rmail['text']);
  $rmail['name'] = stripslashes($rmail['name']);
  $rmail['title'] = stripslashes($rmail['title']);
  $rmail['email'] = !trim($rmail['email']) ? "nobody@{$_SERVER['SERVER_NAME']}" : $rmail['email'];
  if(preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i",$_SERVER['SERVER_NAME']))
    $rmail['email'] = "nobody@[".$_SERVER['SERVER_NAME']."]";

  $rmail['pemail'] = (preg_match("/^nobody@/i",$rmail['email'])) ? "" : "mailto:{$rmail['email']}";

  if ($langs['code'] == "ko") {
    if ($day == "(Mon)") $day="(월)";
    else if ($day == "(Tue)") $day="(화)";
    else if ($day == "(Wed)") $day="(수)";
    else if ($day == "(Thu)") $day="(목)";
    else if ($day == "(Fri)") $day="(금)";
    else if ($day == "(Sat)") $day="(토)";
    else if ($day == "(Sun)") $day="(일)";
  } else if ($langs['code'] == "jp") {
    if ($day == "(Mon)") $day="(月)";
    else if ($day == "(Tue)") $day="(火)";
    else if ($day == "(Wed)") $day="(水)";
    else if ($day == "(Thu)") $day="(木)";
    else if ($day == "(Fri)") $day="(金)";
    else if ($day == "(Sat)") $day="(土)";
    else if ($day == "(Sun)") $day="(日)";
  }

  $webboard_address =  sprintf("%s%s",$rmail['path'],"read.php?table={$rmail['table']}&no={$rmail['no']}");
  $reply_article    =  sprintf("%s%s",$rmail['path'],"reply.php?table={$rmail['table']}&no={$rmail['no']}");

  $dbname  = "DB Name           : {$rmail['table']}";
  $dbloca  = "DB Location       : $webboard_address";
  #$repart  = "Reply Article     : $reply_article";
  $nofm    = "\r\n$dbname\r\n$dbloca\r\n$repart";
  $mailurl = "Email             : {$rmail['pemail']}\r\n";
  $homeurl = "HomeURL           : {$rmail['url']}\r\n";

  $message = "\r\n".
             "\r\n".
             "$mail_msg_header\r\n".
             "\r\n".
             "■ JSBOARD {$rmail['table']} message\r\n".
             "\r\n".
             "[ Server Infomation ]------------------------------------------------------\r\n".
             "ServerWare        : JSBoard-{$rmail['version']}\r\n".
             "Server Name       : {$_SERVER['SERVER_NAME']}$nofm\r\n".
             "\r\n".
             "\r\n".
             "[ Article Infomation ]-----------------------------------------------------\r\n".
             "{$langs['u_name']}              : {$rmail['name']}\r\n".
             "$mailurl".
             "$homeurl".
             "{$langs['a_t13']}              : $year $day $ampm $hms\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             html_to_plain_lib($rmail['text'])."\r\n".
             "\r\n".
             "\r\n".
             "\r\n".
             "---------------------------------------------------------------------------\r\n".
             "REMOTE_ADDR : {$_SERVER['REMOTE_ADDR']}\r\n".
             "HTTP_USER_AGENT : {$_SERVER['HTTP_USER_AGENT']}\r\n".
             "HTTP_ACCEPT_LANGUAGE : {$_SERVER['HTTP_ACCEPT_LANGUAGE']}\r\n".
             "---------------------------------------------------------------------------\r\n".
             "\r\n".
             "JSBoard Form mail service - http://jsboard.kldp.net\r\n";

  $htmltext = get_htmltext($rmail,$year,$day,$ampm,$hms,$nofm);

  if ($rmail['user'] && $rmail['reply_orig_email'] && $rmail['email'] != $rmail['toadmin']) {
    socketmail($rmail['mta'],$rmail['reply_orig_email'],$rmail['email'],$rmail['title'],$message,$htmltext);
  }

  if ($rmail['admin'] && $rmail['toadmin'] != "" && $rmail['email'] != $rmail['toadmin']) {
    socketmail($rmail['mta'],$rmail['toadmin'],$rmail['email'],$rmail['title'],$message,$htmltext);
  }
}
// }}}

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim600: filetype=php et ts=2 sw=2 fdm=marker
 * vim<600: filetype=php et ts=2 sw=2
 */
?>
