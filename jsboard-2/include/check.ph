<?
# table 이름에 meta character 가 포함되어 있는지 검사하는 함수
# $name -> 변수값
# $i    -> null 이라도 상관없을 경우 1
# $t    -> table 이름 검사시 1
#
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error(" $name Value Name Missing! You must specify a value",250,150,1);
  if ($t && !eregi("^[a-zA-Z]",$name)) print_error("$name Value must start with an alphabet",250,150,1);
  if (eregi("[^a-z0-9_\-]",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters",250,150,1);
  if ($t && eregi("^as$",$name)) print_error("Cat't use table name as &quot;as&quot;",250,150,1);
}

# 로그인에 사용되는 Password 비교 함수
#
function compare_pass($l) {
  global $langs,$edb;
  $nocrypt = $edb[crypts] ? 1 : "";
  $r = get_authinfo($l[id],$nocrypt);

  if($nocrypt) {
    if (crypt($r[passwd],$l[pass]) != $l[pass]) print_pwerror($langs[ua_pw_c]);
  } else {
    if ($r[passwd] != $l[pass]) print_pwerror($langs[ua_pw_c]);
  }
}

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

# URL이 정확한 것인지 검사하는 함수
#
# eregi         - 정규 표현식을 이용한 검사 (대소문자 무시)
#                 http://www.php.net/manual/function.eregi.php
# eregi_replace - 정규 표현식을 이용한 치환 (대소문자 무시)
#                 http://www.php.net/manual/function.eregi-replace.php
function check_url($url) {
  $url = trim($url);

  # 프로토콜(http://, ftp://...)을 나타내는 부분이 없을 때 기본값으로
  # http://를 붙임
  if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url))
    $url = eregi_replace("^", "http://", $url);

  if(!eregi("(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[][\xA1-\xFEa-zA-Z0-9:&#@=_~%?\/.+-]+$", $url))
    return;
    
  return $url;
}

# E-MAIL 주소가 정확한 것인지 검사하는 함수
#
# eregi - 정규 표현식을 이용한 검사 (대소문자 무시)
#         http://www.php.net/manual/function.eregi.php
# gethostbynamel - 호스트 이름으로 ip 를 얻어옴
#          http://www.php.net/manual/function.gethostbynamel.php
# checkdnsrr - 인터넷 호스트 네임이나 IP 어드레스에 대응되는 DNS 레코드를 체크함
#          http://www.php.net/manual/function.checkdnsrr.php
function check_email($email,$hchk=0) {
  $url = trim($email);
  if($hchk) {
    $host = explode("@",$url);
    if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$", $url)) {
      if(checkdnsrr($host[1],"MX") || gethostbynamel($host[1])) return $url;
      else return;
    }
  } else {
    if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$", $url)) return $url;
    else return;
  }
}

# 패스워드 비교 함수
#
# crpyt - 문자열을 DES로 암호화함
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table,$no,$passwd) {
  global $jsboard, $board;
  if($board[mode] && session_is_registered("$jsboard")) $sql_field = "name";
  else $sql_field = "passwd";

  $passwd = !trim($passwd) ? "null passwd" : $passwd;

  if ($table && $no) {
    $result = sql_query("SELECT $sql_field FROM $table WHERE no = $no");
    $r[chk] = sql_result($result,0,"$sql_field");
    sql_free_result($result);
  }

  if (session_is_registered("$jsboard")) {
    if($_SESSION[$jsboard][id] == $r[chk]) $chk = 1;
    if($_SESSION[$jsboard][pos] == 1) $chk = 2;
  }

  if(!$chk) {
    if(crypt($passwd,$r[chk]) == $r[chk]) $chk = 1;
  }

  if(!$chk || $chk == 1) {
    # 게시판 관리자 패스워드
    $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$board[ad]'");
    $r[ad] = sql_result($result,0,"passwd");
    sql_free_result($result);

    # 게시판 관리자가 존재하지 않을 경우를 대비
    $r[ad] = !$r[ad] ? "null admin" : $r[ad];

    # 전체 관리자 패스워드
    $result = sql_query("SELECT passwd FROM userdb WHERE position = 1");
    $r[su] = sql_result($result,0,"passwd");
    sql_free_result($result);

    if($r[ad] == crypt($passwd,$r[ad])) $chk = 2;
    elseif($r[su] == crypt($passwd,$r[su])) $chk =2;
  }

  if($chk) return $chk;
}

# 인증 확인 함수
#
function check_auth($user,$chk) {
  if(crypt($user,$chk) == $chk) return 1;
}

# 스팸 검사 함수
#
function check_spam($str, $spam_list = "config/spam_list.txt") {
  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # PHP 4.1 에서 제공하는 mbstring 함수 지원 여부를 체크한다.
  if(extension_loaded("mbstring")) $mbt = 1;

  # mbstring 함수를 지원하면 문자열을 UTF-8 로 변환한다.
  $str = $mbt ? mb_convert_encoding($str,"UTF-8") : $str;

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
    $list[$co] = str_replace("/","\/",$list[$co]);

    # mbstring 을 지원하면 필터링 키워드를 UTF-8 로 변환
    $list[$co] = $mbt ? mb_convert_encoding(trim($list[$co]),"UTF-8") : $list[$co];

    if(preg_match("/$list[$co]/i", $str)) {
      return 1;
      break;
    }
  }

  return 0;
}

function check_net($ipaddr, $network, $netmask) {
  $net[ip] = explode(".", $ipaddr);
  $net[nw] = explode(".", $network);
  $net[nm] = explode(".", $netmask);

  for($co = 0; $co < count($net[ip]); $co++) {
    $nw[ip] = sprintf("%x", $net[ip][$co]);
    $nw[nw] = sprintf("%x", $net[nw][$co]);
    $nw[nm] = sprintf("%x", $net[nm][$co]);

    $mask1 .= $nw[nw] & $nw[nm];
    $mask2 .= $nw[ip] & $nw[nm];
  }
  if($mask1 == $mask2)
    return 1;
}

# upload file 미리보기를 위한 file type 검사
# substr - 문자열의 일부분을 반환한다.
# strchr - 문자열이 마지막으로 나타나는 위치를 구한다
#
function check_filetype($filetype) {
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}

function icon_check($t,$fn) {
  if (eregi("^(hwp|mov|txt)$",$t)) $icon = "$t.gif";
  else if (eregi("^(exe|com)$",$t)) $icon = "exe.gif";
  else if (eregi("^(zip|arj|gz|lha|rar|tar|tgz|ace)$",$t)) $icon = "comp.gif";
  else if (eregi("^(php|php3|phps|vbs)$",$t)) {
    if (eregi("(_htm|_cgi|_pl|_shtm|_sh)",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if (eregi("^(avi|mpg|mpeg|asf|swf|wmv)$",$t)) $icon = "mpeg.gif";
  else if (eregi("^(jpg|gif|bmp|psd|png)$",$t)) $icon = "pic.gif";
  else if (eregi("^(ppt|xls|doc)$",$t)) $icon = "doc.gif";
  else if (eregi("^(ra|ram|rm)$",$t)) $icon = "ra.gif";
  else if (eregi("^(mp3|mp2|wav|mid)$",$t)) $icon = "mp3.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (eregi("(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch|\.vbs|\.ppt|\.xls)$",$list[bofile])) {
    $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
  } else {
    if ($list[bfsize] < 51200) $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
    else $dn = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  }
  return $dn;
}

# upload file 이름에 특수 문자가 들어 가 있는지 검사
#
function upload_name_chk($f) {
  global $langs;

  if(!trim($f)) print_error($langs[act_de],250,150,1);

  # file 이름에서 특수문자가 있으면 에러 출력
  if (eregi("[^\xA1-\xFEa-z0-9._\-]|\.\.",urldecode($f))) {
    print_error($langs[act_de],250,150,1);
    exit;
  }
}

# 비정상적인 접근을 막기 위한 체크 함수.
# referer 값과 rmail[bbs] 변수에 지정한 값이 동일할 경우에만
# 허락됨
#
function check_location($n=0) {
  global $board, $langs, $agent;

  if($n && $agent[br] != "LYNX") {
    $board[referer] = $_SERVER[HTTP_REFERER];

    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,$board[referer]));
    $l = gethostbyname(preg_replace($sre,$tre,$board[path]));

    if ($r != $l) {
      print_error("$langs[chk_lo]",250,150,1);
      return 0;
    } else return 1;
  } else return 1;
}

# IIS(isapi) 인지 아닌지 판단 함수
function check_iis() {
  if(php_sapi_name() == "isapi") return 1;
  else return 0;
}

# 윈도우용 php 인지 아닌지를 판단.
# 윈도우용 php 일 경우 turn 를 반환
function check_windows() {
  if(eregi("Windows",php_uname())) return 1;
  else return 0;
}

# TABLE 을 정확하게 사용했는지 체크하는 함수
# str  -> 체크할 문자열
# rep  -> 1 일 경우 에러메시지 대신 결과를 echo 함
#
function check_htmltable($str,$rep='') {
  global $langs;

  # table tag 들만 나두고 모두 삭제
  $s[] = "/>[^<]*</i";
  $s[] = "/#|@/i";
  $s[] = "/<(\/?(TABLE|TR|TD|TH|IFRAME))[^>]*>/i";
  $s[] = "/^[^#]*/i";
  $s[] = "/(TABLEEMARK@)[^#]*(#TABLESMARK)/i";
  $s[] = "/<[^>]*>/i";
  $s[] = "/#TABLESMARK#/i";
  $s[] = "/@TABLEEMARK@/i";
  $s[] = "/(\r?\n)+/i";

  $d[] = ">\n<";
  $d[] = "";
  $d[] = "\n#TABLESMARK#\\1@TABLEEMARK@\n";
  $d[] = "";
  $d[] = "\\1\\2";
  $d[] = "";
  $d[] = "\n<";
  $d[] = ">\n";
  $d[] = "\n";

  $str = trim(preg_replace($s,$d,$str));

  # table tag 들만 남은 것을 \n 을 기준으로 배열로 받음
  $ary = explode("\n",$str);

  for($i=0;$i<sizeof($ary);$i++) {
    if(strtoupper($ary[$i]) == "<TABLE>") $opentable++;
    elseif(strtoupper($ary[$i]) == "</TABLE>") $clstable++;
    elseif(strtoupper($ary[$i]) == "<TH>") $openth++;
    elseif(strtoupper($ary[$i]) == "</TH>") $clsth++;
    elseif(strtoupper($ary[$i]) == "<TR>") $opentr++;
    elseif(strtoupper($ary[$i]) == "</TR>") $clstr++;
    elseif(strtoupper($ary[$i]) == "<TD>") $opentd++;
    elseif(strtoupper($ary[$i]) == "</TD>") $clstd++;
    elseif(strtoupper($ary[$i]) == "<IFRAME>") $openif++;
    elseif(strtoupper($ary[$i]) == "</IFRAME>") $clsif++;
  }

  if(!$rep) {
    if($opentable != $clstable) $msg = $langs[chk_tb]."\n";
    if($openth != $clsth) $msg .= $langs[chk_th]."\n";
    if($opentr != $clstr) $msg .= $langs[chk_tr]."\n";
    if($opentd != $clstd) $msg .= $langs[chk_td]."\n";
    if($openif != $clsif) $msg .= $langs[chk_if]."\n";

    $msg = $msg ? $langs[chk_ta]."\n\nError Check:\n".trim($msg)."\n" : "";

    if($msg) print_error($msg,"","",1);
  } else {
    echo "\n##  TABLE CHECK RESULT\n".
         "##  ----------------------------------------------------------------------\n".
         "##  OPEN  TABLE  TAG : $opentable\n".
         "##  CLOSE TABLE  TAG : $clstable\n##  \n".

         "##  OPEN  TH     TAG : $openth\n".
         "##  CLOSE TH     TAG : $clsth\n##  \n".

         "##  OPEN  TR     TAG : $opentr\n".
         "##  CLOSE TR     TAG : $clstr\n##  \n".

         "##  OPEN  TD     TAG : $opentd\n".
         "##  CLOSE TD     TAG : $clstd\n\n".

         "##  OPEN  IFRAME TAG : $openif\n".
         "##  CLOSE IFRAME TAG : $clsif\n\n";
  }
}

# hyper link 를 통한 접근을 제어
# m -> 0 : ips 에 등록된 Ip 에서의 링크만 허락
#      1 : ips 에 등록된 Ip 에서의 링크만 막음
#
function check_dhyper($c=0,$wips='',$m=0,$ips='') {
  global $langs;

  # $c 설정이 있고, list read from write page 에서만 체크
  if($c && preg_match("/(list|read|form|write)\.php/i",$_SERVER[PHP_SELF])) {
    # global.ph 에 $board[dhyper] 가 정의 되어 있으면 체크 목록을 합침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # 레퍼럴이 존재하지 않거나 ips 변수가 없으면 체크 중지
    if(!trim($ips) || !$_SERVER[HTTP_REFERER]) return;

    # 레퍼럴에서 서버 이름만 추출
    preg_match("/^(http:\/\/)?([^\/]+)/i",$_SERVER[HTTP_REFERER],$chks);
    # 추출한 이름의 ip 를 구함
    $chk = gethostbyname($chks[2]);

    # chk 가 자신과 동일하면 체크 중지
    if($chk == $_SERVER[SERVER_ADDR]) return;

    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$chk)) $val = 1;
    }

    switch($m) {
      case '1' :
        if($val) print_error($langs[chk_hy],250,250,1);
        break;
      default:
        if(!$val) print_error($langs[chk_hy],250,250,1);
        break;
    }
  }
}

# IP blocking 함수
#
function check_access($c=0,$wips='',$ips='') {
  global $langs;

  if($c) {
    # global.ph 에 $board[ipbl] 이 존재하면 함침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # 원격 접속지가 존재하지 않거나 ips 변수가 없거나, 접속지가 자신이라면 체크 중지
    if(!trim($ips) || !$_SERVER[REMOTE_ADDR] || $_SERVER[REMOTE_ADDR] == $_SERVER[SERVE_ADDR]) return;

    # spoofing 체크
    $ipchk = explode(".",$_SERVER[REMOTE_ADDR]);
    for($j=1;$j<4;$j++) $ipchk[$j] = $ipchk[$j] ? $ipchk[$j] : 0;
    # 각 자리수 마다 255 보다 크면 ip 주소를 벋어나므로 체크 
    if($ipchk[0] > 255 || $ipchk[1] > 255 || $ipchk[2] > 255 || $ipchk[3] > 255)
      print_error($langs[chk_sp],250,250,1);
 
    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$_SERVER[REMOTE_ADDR])) $val = 1;
    }

    if($val) print_error($langs[chk_bl],250,250,1);
  }
}
?>
