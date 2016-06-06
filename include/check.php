<?php
# $Id: check.php,v 1.29 2014/03/02 17:11:31 oops Exp $
# table 이름에 meta character 가 포함되어 있는지 검사하는 함수
# $name -> 변수값
# $i    -> null 이라도 상관없을 경우 1
# $t    -> table 이름 검사시 1
#
// {{{ +-- public meta_char_check($name,$i=0,$t=0)
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error("Table Value Name Missing! You must specify a value",250,150,1);
  if ($t && !preg_match("/^[a-z]/i",$name)) print_error("$name Value must start with an alphabet",250,150,1);
  if (preg_match("/[^a-z0-9_-]/i",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters",250,150,1);
  if ($t && preg_match("/^as$/i",$name)) print_error("Cat't use table name as &quot;as&quot;",250,150,1);
}
// }}}

# // {{{ +-- public compare_pass($l)
# 로그인에 사용되는 Password 비교 함수
#
function compare_pass($l) {
  global $langs,$edb;
  $r = get_authinfo($l['id'],$edb['crypts']);

  if($edb['uses'] && $edb['crypts']) {
    if (crypt($r['passwd'],$l['pass']) != $l['pass']) print_pwerror($langs['ua_pw_c']);
  } else {
    if ($r['passwd'] != $l['pass']) print_pwerror($langs['ua_pw_c']);
  }
}
// }}}

# // {{{ +-- public is_hangul($char)
# 문자열에 한글이 포함되어 있는지 검사하는 함수
#
# ord    - 문자의 ASCII 값을 가져옴
#          http://www.php.net/manual/function.ord.php
function is_hangul($char) {
  # 특정 문자가 한글의 범위내(0xA1A1 - 0xFEFE)에 있는지 검사
  $char = ord($char);

  if($char >= 0xa1 && $char <= 0xfe)
    return 1;
}
// }}}

# // {{{ +-- public is_alpha($char)
# 알파벳인지 그리고 대문자(0x41 - 0x5a)인지 소문자(0x61 - 0x7a)인지
# 검사하는 함수
#
# ord - 문자의 ASCII 값을 가져옴
#       http://www.php.net/manual/function.ord.php
function is_alpha($char) {
  $char = ord($char);

  if($char >= 0x61 && $char <= 0x7a)
    return 1;
  if($char >= 0x41 && $char <= 0x5a)
    return 2;
}
// }}}

# // {{{ +-- public check_url($url)
# URL이 정확한 것인지 검사하는 함수
#
function check_url($url) {
  $url = trim($url);

  # 프로토콜(http://, ftp://...)을 나타내는 부분이 없을 때 기본값으로
  # http://를 붙임
  if(!preg_match("/^(http|https|ftp|telnet|news):\/\//i", $url))
    $url = "http://$url";

  if(!preg_match("/(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[\xA1-\xFEa-zA-Z0-9,:&#@=_~%?\/.+-]+$/i", $url))
    return;
    
  return $url;
}
// }}}

# // {{{ +-- public check_email($email,$hchk=0)
# E-MAIL 주소가 정확한 것인지 검사하는 함수
#
# gethostbynamel - 호스트 이름으로 ip 를 얻어옴
#          http://www.php.net/manual/function.gethostbynamel.php
# checkdnsrr - 인터넷 호스트 네임이나 IP 어드레스에 대응되는 DNS 레코드를 체크함
#          http://www.php.net/manual/function.checkdnsrr.php
function check_email($email,$hchk=0) {
  $url = trim($email);
  if($hchk) {
    $host = explode("@",$url);
    if(preg_match("/^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$/i", $url)) {
      if(!check_windows() || version_compare('5.3.0',js_wrapper('phpversion'),'>=')) {
	   if(checkdnsrr($host[1],"MX") || gethostbynamel($host[1])) return $url;
   	   else return;
      } else {
	   if(gethostbynamel($host[1])) return $url;
   	   else return;
      }
    }
  } else {
    if(preg_match("/^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$/i", $url)) return $url;
    else return;
  }
}
// }}}

# // {{{ +-- public check_passwd($table,$no,$passwd)
# 패스워드 비교 함수
#
# crpyt - 문자열을 DES로 암호화함
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table,$no,$passwd) {
  global $jsboard, $board, $o, $c;
  if($board['mode'] && isset($_SESSION[$jsboard])) $sql_field = "name";
  else $sql_field = "passwd";

  $passwd = !trim($passwd) ? "null passwd" : $passwd;

  $table = ($table && $o['at'] == "c_del") ? $table."_comm" : $table;

  if ($table && $no) {
    $result = sql_query("SELECT $sql_field FROM $table WHERE no = '$no'",$c);
    $r['chk'] = sql_result($result,0,$sql_field,$c);
    sql_free_result($result,$c);
  }

  if (isset($_SESSION[$jsboard])) {
    if($_SESSION[$jsboard]['id'] == $r['chk']) $chk = 1;
    if($_SESSION[$jsboard]['pos'] == 1) $chk = 2;
  }

  if(!$chk) {
    if(crypt($passwd,$r['chk']) == $r['chk']) $chk = 1;
  }

  if(!$chk || $chk == 1) {
    # 전체 관리자 패스워드
    $result = sql_query('SELECT passwd FROM userdb WHERE position = 1',$c);
    $r['su'] = sql_result($result,0,'passwd',$c);
    sql_free_result($result,$c);

    if($r['su'] == crypt($passwd,$r['su'])) $chk =2;

    if($chk != 2) {
      $arrayadm = explode(";",$board['ad']);
      for($i=0;$i<sizeof($arrayadm);$i++) {
        # 게시판 관리자 패스워드
        $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$arrayadm[$i]'",$c);
        $r['ad'] = sql_result($result,0,'passwd',$c);
        sql_free_result($result,$c);

        # 게시판 관리자가 존재하지 않을 경우를 대비
        $r['ad'] = !$r['ad'] ? "null admin" : $r['ad'];

        if($r['ad'] == crypt($passwd,$r['ad'])) {
          $chk = 2;
          break;
        }
      }
    }
  }

  if($chk) return $chk;
}
// }}}

# // {{{ +-- public check_auth($user,$chk)
# 인증 확인 함수
#
function check_auth($user,$chk) {
  if(crypt($user,$chk) == $chk) return 1;
}
// }}}

# // {{{ +-- public check_spam($str, $spam_list = "config/spam_list.txt")
# 스팸 검사 함수
#
function check_spam($str, $spam_list = "config/spam_list.txt") {
  global $langs;
  $mbext = 0;

  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # php versin check
  if ( function_exists ("version_compare") ) {
    if ( version_compare ("4.3.0", PHP_VERSION, '<=') ) {
      $mbext = 1;
    }
  }

  # PHP 4.1 에서 제공하는 mbstring 함수 지원 여부를 체크한다.
  if(extension_loaded("mbstring")) $mbt = 1;

  # mbstring 함수를 지원하면 문자열을 UTF-8 로 변환한다.
  if ( $mbt ) {
    if ( preg_match ("/EUC-KR|EUC-JP|SHIFT_JIS|SHIFT-JIS/i", $langs['charset']) ) {
      if ( $mbext ) {
        $str = mb_convert_encoding ($str, "UTF-8", $langs['charset']);
      } else {
        $str = mb_convert_encoding ($str, "UTF-8");
      }
    }
  }

  # $list 배열의 갯수 만큼 for문을 돌려 $spam_list 파일에 지정되어 있던
  # 문자열들과 일치하는 문자열이 $spam_str에 있는지 검사함, 있을 경우
  # 스팸으로 판단하고 정수형 1을 반환함, 없을 경우는 0을 반환함
  for($co = 0; $co < count($list); $co++) {
    # 2byte 가 안되는 키워드는 무조건 무시
    if(strlen($list[$co]) < 2) continue;

    # mbstirng 함수가 지원하지 않을 경우에는 한글 2자 이상 영문 3자 이상부터 지원
    if(!$mbt && strlen($list[$co]) < 3) continue;

    # 공백라인 이거나 처음이 # 로 시작하는 라인은 무시
    if(preg_match("/^#|^$/i",trim($list[$co]))) continue;

    # preg 함수를 사용하기 위해 / 문자를 \/ 로 치환
    $list[$co] = trim (str_replace("/","\/",$list[$co]));

    # mbstring 을 지원하면 필터링 키워드를 UTF-8 로 변환
    if ( $mbt ) {
      if ( preg_match ("/EUC-KR|EUC-JP|SHIFT_JIS|SHIFT-JIS/i", $langs['charset']) ) {
        if ( $mbext ) {
          $list[$co] = mb_convert_encoding ($list[$co], "UTF-8", $langs['charset']);
        } else {
          $list[$co] = mb_convert_encoding ($list[$co], "UTF-8");
        }
      }
    }

    if(preg_match("/$list[$co]/i", $str, $spam_string)) {
      if ( $mbt ) {
        if ( preg_match ("/EUC-KR|EUC-JP|SHIFT_JIS|SHIFT-JIS/i", $langs['charset']) ) {
          if ( $mbext ) {
            $spamstr_t = mb_convert_encoding ($spam_string[0], $langs['charset'], "UTF-8");
          } else {
            $spamstr_t = "Unsupported";
          }
        } else $spamstr_t = $spam_string[0];
      } else {
        $spamstr_t = $spam_string[0];
      }

      $spamstr_t = htmlentities ($spamstr_t);
      $GLOBALS['spamstr'] = "\nDetected: {$spamstr_t}";
      return 1;
      break;
    }
  }

  return 0;
}
// }}}

// {{{ +-- public check_net($ipaddr, $network, $netmask)
function check_net($ipaddr, $network, $netmask) {
  $net['ip'] = explode(".", $ipaddr);
  $net['nw'] = explode(".", $network);
  $net['nm'] = explode(".", $netmask);

  for($co = 0; $co < count($net['ip']); $co++) {
    $nw['ip'] = sprintf("%x", $net['ip'][$co]);
    $nw['nw'] = sprintf("%x", $net['nw'][$co]);
    $nw['nm'] = sprintf("%x", $net['nm'][$co]);

    $mask1 .= $nw['nw'] & $nw['nm'];
    $mask2 .= $nw['ip'] & $nw['nm'];
  }
  if($mask1 == $mask2)
    return 1;
}
// }}}

# // {{{ +-- public check_filetype($filetype)
# upload file 미리보기를 위한 file type 검사
# substr - 문자열의 일부분을 반환한다.
# strchr - 문자열이 마지막으로 나타나는 위치를 구한다
#
function check_filetype($filetype) {
  $filetype = preg_replace("/\.$/", "", $filetype);
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}
// }}}

// {{{ +-- public icon_check($t,$fn)
function icon_check($t,$fn) {
  if (preg_match("/^(hwp|mov|txt)$/i",$t)) $icon = "$t.gif";
  else if (preg_match("/^(exe|com)$/i",$t)) $icon = "exe.gif";
  else if (preg_match("/^(zip|arj|gz|lha|rar|tar|tgz|ace)$/i",$t)) $icon = "comp.gif";
  else if (preg_match("/^(php|php3|phps|vbs)$/i",$t)) {
    if (preg_match("/_htm|_cgi|_pl|_shtm|_sh/i",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if (preg_match("/^(avi|mpg|mpeg|asf|swf|wmv)$/i",$t)) $icon = "mpeg.gif";
  else if (preg_match("/^(jpg|gif|bmp|psd|png)$/i",$t)) $icon = "pic.gif";
  else if (preg_match("/^(ppt|xls|doc)$/i",$t)) $icon = "doc.gif";
  else if (preg_match("/^(ra|ram|rm)$/i",$t)) $icon = "ra.gif";
  else if (preg_match("/^(mp3|mp2|wav|mid)$/i",$t)) $icon = "mp3.gif";
  else $icon = "file.gif";

  return $icon;
}
// }}}

// {{{ +-- public check_dnlink($table,$list)
function check_dnlink($table,$list) {
  global $upload, $cupload;

  $list['bofile'] = rawurlencode ($list['bofile']);
  if(!$cupload['dnlink']) {
    if (preg_match("/(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch|\.vbs|\.ppt|\.xls)$/i",$list['bofile'])) {
      $dn = "act.php?o[at]=dn&amp;dn[tb]=$table&amp;dn[cd]={$list['bcfile']}&amp;dn[name]={$list['bofile']}";
    } else {
      if ($list['bfsize'] < 51200) $dn = "act.php?o[at]=dn&amp;dn[tb]=$table&amp;dn[cd]={$list['bcfile']}&amp;dn[name]={$list['bofile']}";
      else $dn = "./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}";
    }
  } else {
    $dn = "./data/$table/{$upload['dir']}/{$list['bcfile']}/{$list['bofile']}";
  }
  return $dn;
}
// }}}

# // {{{ +-- public upload_name_chk($f)
# upload file 이름에 특수 문자가 들어 가 있는지 검사
#
function upload_name_chk($f) {
  global $langs;

  if(!trim($f)) print_error($langs['act_de'],250,150,1);

  # file 이름에서 특수문자가 있으면 에러 출력
  # 한글 영역과 한자 영역은 허락 함
  if ( preg_replace ("/[\w\d._\-]|[\xB0-\xC8\xCA-\xFD][\xA1-\xFE]/",'', urldecode ($f)) ) {
    print_error($langs['act_de'],250,150,1);
    exit;
  }

  # hidden file 이나 multiple dot 허락하지 않음
  if ( preg_match ("/^\.|\.\.+/", urldecode ($f)) ) {
    print_error($langs['act_de'], 250, 150, 1);
    exit;
  }
}
// }}}

# // {{{ +-- public check_location($n=0)
# 비정상적인 접근을 막기 위한 체크 함수.
# referer 값과 rmail['bbs'] 변수에 지정한 값이 동일할 경우에만
# 허락됨
#
function check_location($n=0) {
  global $board, $langs, $agent;

  if($n && !$agent['tx']) {
    $board['referer'] = $_SERVER['HTTP_REFERER'];

    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,$board['referer']));
    $l = gethostbyname(preg_replace($sre,$tre,$board['path']));

    if ($r != $l) {
      print_error("{$langs['chk_lo']}",250,150,1);
      return 0;
    } else return 1;
  } else return 1;
}
// }}}

# // {{{ +-- public check_iis(void)
# IIS(isapi) 인지 아닌지 판단 함수
function check_iis() {
  if(php_sapi_name() == "isapi") return 1;
  else return 0;
}
// }}}

# // {{{ +-- public check_windows(void)
# 윈도우용 php 인지 아닌지를 판단.
# 윈도우용 php 일 경우 turn 를 반환
function check_windows() {
  if(preg_match("/Windows/i",js_wrapper('php_uname'))) return 1;
  else return 0;
}
// }}}

# // {{{ +-- public check_htmltable($str)
# TABLE 을 정확하게 사용했는지 체크하는 함수
#
function check_htmltable($str) {
  global $langs;

  if(!preg_match(';</?TABLE[^>]*>;i',$str)) return;

  $from = array(';[\d]+;',     // [0-9]+ to ''
                ';<(/?)(TABLE|TH|TR|TD)[^>]*>;i', // to '<\\1\\2>'
                ';<TABLE>;i',  // to 1
                ';<TR>;i',     // to 2
                ';<TH>;i',     // to 3
                ';<TD>;i',     // to 4
                ';</TD>;i',    // to 94
                ';</TH>;i',    // to 93
                ';</TR>;i',    // to 92
                ';</TABLE>;i', // to 91
                ';[\D]+;'      // [^0-9]+ to ''
                );

  $to = array('','<\\1\\2>',1,2,3,4,94,93,92,91,'');
  $check = preg_replace($from,$to,$str);

  if(strlen($check)%3) {
    print_error($langs['chk_ta'], 250, 150, 1);
  }
  if(!preg_match('/^12(3|4).+9291$/',$check)) {
    print_error($langs['chk_tb'], 250, 150, 1);
  }

  while(preg_match('/([\d])9\1/',$check))
  { $check = preg_replace('/([\d])9\1/','',$check); }

  if($check) {
    print_error("TABLE, TH, TR, TD array(open/close) TAG error", 250, 150, 1);
  }
}
// }}}

# // {{{ +-- public check_iframe($str)
# IFRAME 을 정확하게 사용했는지 체크하는 함수
#
function check_iframe($str) {
  global $langs;

  if (!preg_match(';</?iframe[^>]*>;i', $str)) return 0;

  $from = array(';@;',';#;',';<iframe[^>]*>;i',';</iframe[^>]*>;i',';[^@#];',';@#;');
  $to   = array('','','@','#','','');
  $check = preg_replace($from, $to, $str);

  if ($check) {
    print_error($langs['chk_if'], 250, 150, 1);
  }
}
// }}}

# // {{{ +-- public check_dhyper($c=0,$am=0,$wips='',$m=0,$ips='')
# hyper link 를 통한 접근을 제어
# m -> 0 : ips 에 등록된 Ip 에서의 링크만 허락
#      1 : ips 에 등록된 Ip 에서의 링크만 막음
#
function check_dhyper($c=0,$am=0,$wips='',$m=0,$ips='') {
  global $langs;

  # $c 설정이 있고, list read from write page 에서만 체크
  if($c && preg_match("/(list|read|form|write)\.php/i",$_SERVER['PHP_SELF'])) {
    # global.php 에 $board['dhyper'] 가 정의 되어 있으면 체크 목록을 합침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # $i = 0 -> 전체 어드민에서의 설정 체크
    # $i = 1 -> 게시판 어드민에서의 설정 체크
    for($i=0;$i<2;$i++) {
      if($i === 0 && !trim($wips)) continue;
      $ips_value = ($i === 0) ? $wips : $ips;
      $m_value = ($i === 0) ? $am : $m;

      # 레퍼럴이 존재하지 않거나 ips_value 변수가 없으면 체크 중지
      if(!trim($ips_value) || !$_SERVER['HTTP_REFERER']) return;

      # 레퍼럴에서 서버 이름만 추출
      preg_match("/^(http:\/\/)?([^\/]+)/i",$_SERVER['HTTP_REFERER'],$chks);
      # 추출한 이름의 ip 를 구함
      $chk = gethostbyname($chks[2]);

      # chk 가 자신과 동일하면 체크 중지
      if($chk == $_SERVER['SERVER_ADDR']) return;

      $addr = explode(";",$ips_value);
      for($j=0;$j<sizeof($addr);$j++) {
        if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$j])) $addr[$j] .= ".";
        $addr[$j] = str_replace(".","\.",$addr[$j]);
        if(preg_match("/^$addr[$j]/i",$chk)) $val = 1;
      }
      switch($m_value) {
        case '1' :
          if($val) print_error($langs['chk_hy'],250,250,1);
          break;
        default:
          if(!$val) print_error($langs['chk_hy'],250,250,1);
          break;
      }
    }
  }
}
// }}}

# // {{{ +-- public check_access($c=0,$wips='',$ips='')
# IP blocking 함수
#
function check_access($c=0,$wips='',$ips='') {
  global $langs;

  if($c) {
    # global.php 에 $board['ipbl'] 이 존재하면 함침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # 원격 접속지가 존재하지 않거나 ips 변수가 없거나, 접속지가 자신이라면 체크 중지
    if(!trim($ips) || !$_SERVER['REMOTE_ADDR'] || $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVE_ADDR']) return;

    # spoofing 체크
    $ipchk = explode(".",$_SERVER['REMOTE_ADDR']);
    for($j=1;$j<4;$j++) $ipchk[$j] = $ipchk[$j] ? $ipchk[$j] : 0;
    # 각 자리수 마다 255 보다 크면 ip 주소를 벋어나므로 체크 
    if($ipchk[0] > 255 || $ipchk[1] > 255 || $ipchk[2] > 255 || $ipchk[3] > 255)
      print_error($langs['chk_sp'],250,250,1);
 
    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$_SERVER['REMOTE_ADDR'])) $val = 1;
    }

    if($val) print_error($langs['chk_bl'],250,250,1);
  }
}
// }}}

# // {{{ +-- public check_rw_referer ($referer, $type)
# spam 등록기 체크 함수
function check_rw_referer ($referer, $type) {
  #if ($type != 'reply' && $type != 'write')
  #  return 0;

  if (preg_match("!{$type}\.php!", $referer))
    return 0;

  return 1;
}
// }}}

// {{{ +-- public check_proxy(void)
function check_proxy() {
  foreach($_POST as $k => $v) {
    switch($k) {
      case 'HTTP_VIA':
      case 'HTTP_CLIENT_IP':
      case 'HTTP_PROXY':
      case 'HTTP_SP_HOST':
      case 'HTTP_COMING_FROM':
      case 'HTTP_X_COMING_FROM':
      case 'HTTP_FORWARDED':
      case 'HTTP_X_FORWARDED':
      case 'HTTP_FORWARDED_FOR':
      case 'HTTP_X_FORWARDED_FOR':
        return 1;
        break;
    }
  }
  return 0;
}
// }}}

// {{{ +-- public check_rw_method ($agent)
function check_rw_method ($agent) {
  # if text browser, pass
  if($agent['tx'])
    return 0;

  # only accept POST method
  if($_SERVER['REQUEST_METHOD'] != 'POST')
    return 1;
 
  # only accept HTTP/1.1 protocol
  if($_SERVER['SERVER_PROTOCOL'] != 'HTTP/1.1') {
    # if case proxy server, accept
    if(check_proxy())
      return 0;
    return 2;
  }

  return 0;
}
// }}}

// {{{ +-- public check_spamer($v)
function check_spamer($v) {
  global $langs,$o, $board;
  if($o['at'] != 'write' && $o['at'] != 'reply')
    return;

  if($v['goaway'])
    print_error($langs['chk_rp'],250,250,1);

  if (check_rw_referer($_SERVER['HTTP_REFERER'],$o['at']))
    print_error($langs['chk_rp'],250,250,1);

  $url = parse_url ($board['path']);
  $reg = preg_quote ($url['host']);
  if ( preg_match ('!www\x5c\.!', $reg) )
    $reg = preg_replace ('!www\x5c\.!', '(\0)?', $reg);
  else
    $reg = '(www\.)?' . $reg;
  $reg = '!^http[s]?://' . $reg . '!';

  if ( ! preg_match ($reg, $_SERVER['HTTP_REFERER']) )
    print_error($langs['chk_rp'],250,250,1);

  if (check_rw_method ($v['agent']))
    print_error($langs['chk_rp'],250,250,1);
}
// }}}

#// {{{ +-- public init_htmltag (void)
#
# html code 뒤에 ':' 가 붙어 있으면 block 을 유지 시켜 줘야 하는
# 태그로 인식한다. (wordwrap 이 되면 안되는 code)
#
function init_htmltag () {
  global $enable;

  if ( ! $eanble['tag'] )
    $enable['tag'] = 'b,i,u,ul,ol,li,span,font,table,tr,td';

  $enable['tag'] .= ',code';
  $p = preg_replace ('/[\s]/', '', $enable['tag']);
  $p = explode (',', $p);
  $enable['tag'] = (object) $p;
}
// }}}

// {{{ +-- public check_utf8_conv ($charset)
function check_utf8_conv ($charset) {
  if ( preg_match ('/^utf[-]?8$/i', $charset) )
    return false;

  if ( ! extension_loaded ('iconv') )
    return false;

  return true;
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
