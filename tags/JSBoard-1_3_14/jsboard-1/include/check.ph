<?
# table 이름에 meta character 가 포함되어 있는지 검사하는 함수
# $name -> 변수값
# $i    -> null 이라도 상관없을 경우 1
# $t    -> table 이름 검사시 1
#
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error(" $name Value Name Missing! You must specify a value");
  if ($t && !preg_match("/^[a-z]/i",$name)) print_error("$name Value must start with an alphabet");
  if (preg_match("/[^a-z0-9_-]/i",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters");
  if ($t && preg_match("/^as$/i",$name)) print_error("Cat't use table name as &quot;as&quot;");
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

  if(!eregi("(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[][\xA1-\xFEa-zA-Z0-9,:&#@=_~%?\/.+-]+$", $url))
    return;

  return $url;
}

# E-MAIL 주소가 정확한 것인지 검사하는 함수
#
# eregi - 정규 표현식을 이용한 검사 (대소문자 무시)
#         http://www.php.net/manual/function.eregi.php
function check_email($url) {
  if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$",trim($url))) return $url;
  else return;
}

# 패스워드 비교 함수
#
# crpyt - 문자열을 DES로 암호화함
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table, $no, $passwd) {
  global $sadmin, $admin;

  if ($table && $no) {
    $result = sql_query("SELECT passwd FROM $table WHERE no = '$no'");
    $crypt  = sql_result($result, 0, "passwd");
    sql_free_result($result);
  }

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(crypt($passwd, $crypt) == $crypt || $sadmin[passwd] == $spasswd || $admin[passwd] == $upasswd) {
    return 1;
  }
}

# 스팸 검사 함수
function check_spam($str, $spam_list = "config/spam_list.txt") {
  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # $list 배열의 갯수 만큼 for문을 돌려 $spam_list 파일에 지정되어 있던
  # 문자열들과 일치하는 문자열이 $spam_str에 있는지 검사함, 있을 경우
  # 스팸으로 판단하고 정수형 1을 반환함, 없을 경우는 0을 반환함
  for($co = 0; $co < count($list); $co++) {
    $list[$co] = trim($list[$co]);
    if($list[$co] && eregi($list[$co], $str)) {
      return 1;
    }
  }
  return 0;
}

# 등록 가능한 브라우져 체크
# file() - file을 읽어 한줄씩 배열로 받음
#
function chk_spam_browser($file = "config/allow_browser.txt") {
  global $HTTP_SERVER_VARS;
  $agent_env = $HTTP_SERVER_VARS["HTTP_USER_AGENT"];

  if(@file_exists($file)) {
    $br = file($file);
    for($i=0;$i<sizeof($br);$i++) {
      $br[$i] = trim($br[$i]);
      if (eregi($br[$i],$agent_env)) {
        return 1;
        break;
      }
    }
    return 0;
  } else return 1;
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

# 쓰기권한 체크 함수
#
function enable_write($super,$user,$check,$ena, $cena = 1) {
  global $kind, $table, $no, $wcheck, $langs, $page, $adminsession;

  if($no) $nom = "&no=$no";
  $page = $page? "&page=$page" : "";

  if ($wcheck && !$adminsession && !$check) {
    SetCookie("pcheck","",0);
    header("Location: auth_ext.php?table=$table&ena=$ena&cena=$cena&kind=$kind$page$nom");
    die;
  }

  if($adminsession) {
    $pscheck = $adminsession;
  } else {
    $pucheck = crypt($check,$user);
    $pscheck = crypt($check,$super);
  }

  $selffile ="act.php?o[at]=se&o[se]=logout&table=$table&kind=$kind$nom";
  if (!$ena) $user_m = "$langs[chk_a]";
  $ment = "$langs[chk_wa]";

  if (!$ena) {
    if($super != $pscheck) missmatch_passwd($selffile,$ment);
  } else if($ena && !$cena) {
    if($super != $pscheck && $user != $pucheck) missmatch_passwd($selffile,$ment);
  }
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
  if (preg_match("/^(hwp|mov|txt)$/i",$t)) $icon = "$t.gif";
  else if (preg_match("/^(exe|com)$/i",$t)) $icon = "exe.gif";
  else if (preg_match("/^(zip|arj|gz|lha|rar|tar|tgz|ace)$/i",$t)) $icon = "comp.gif";
  else if (preg_match("/^(php|php3|phps|vbs)$/i",$t)) {
    if (preg_match("/(_htm|_cgi|_pl|_shtm|_sh)/i",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if (preg_match("/^(avi|mpg|mpeg|asf|swf)$/i",$t)) $icon = "mpeg.gif";
  else if (preg_match("/^(jpg|gif|bmp|psd|png)$/i",$t)) $icon = "pic.gif";
  else if (preg_match("/^(ppt|xls|doc)$/i",$t)) $icon = "doc.gif";
  else if (preg_match("/^(ra|ram|rm)$/i",$t)) $icon = "ra.gif";
  else if (preg_match("/^(mp3|mp2|wav|mid)$/i",$t)) $icon = "mp3.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (preg_match("/(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch|\.vbs|\.ppt|\.xls)$/i",$list[bofile])) {
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

  if(!trim($f)) print_error($langs[act_de]);

  # file 이름에서 특수문자가 있으면 에러 출력
  # 한자/한글은 허락함
  if ( preg_replace ("/[\w\d._\-]|[\xB0-\xC8\xCA-\xFD][\xA1-\xFE]/",'', urldecode ($f)) ) {
    print_error($langs[act_de]);
    exit;
  }

  # hidden file 이나 multiple dot 허락하지 않음
  if ( preg_match ("/^\.|\.\.+/", urldecode ($f)) ) {
    print_error($langs[act_de]);
    exit;
  }
}

# 비정상적인 접근을 막기 위한 체크 함수.
# referer 값과 rmail[bbs] 변수에 지정한 값이 동일할 경우에만
# 허락됨
#
function check_location($n=0) {
  global $board, $langs, $HTTP_SERVER_VARS;

  if($n) {
    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,$HTTP_SERVER_VARS["HTTP_REFERER"]));
    $l = gethostbyname(preg_replace($sre,$tre,$rmail[bbshome]));

    if ($r != $l) {
      print_error("$langs[chk_lo]");
      return 0;
    } else return 1;
  } else return 1;
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

    if($msg) print_error($msg);
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

# PHP 의 버전을 체크하는 함수.
# php 버젼이 4.1 보다 낮을 경우 사용
# version => 비교할 버전
# v       => 1 (현재 버젼이 version 보다 낮을 경우 참)
#            0 (현재 버젼이 version 보다 높을 경우 참)
#
function check_phpver($version,$v=0) {
  $phpversion = phpversion();
  if(preg_match("/pl[0-9]/i",$version))
    $ver_pl = preg_replace("/[0-9.]+pl([0-9]+).*/i","\\1",$version);
  $ver = explode(".",preg_replace("/([0-9.]+)[^0-9\.].*/i","\\1",$version));

  if(preg_match("/pl[0-9]/i",$phpversion))
    $chk_pl = preg_replace("/[0-9.]+pl([0-9]+).*/i","\\1",$phpversion);
  $chk = explode(".",preg_replace("/([0-9.]+)[^0-9.].*/i","\\1",$phpversion));

  if($v) {
    # 현재 버전이 비교할 버전보다 낮을 경우 참
    if($ver[0] > $chk[0]) return 1;
    elseif ($ver[0] == $chk[0]) {
      if($ver[1] > $chk[1]) return 1;
      elseif ($ver[1] == $chk[1]) {
        if($ver[2] > $chk[2]) return 1;
        elseif ($ver[2] == $chk[2]) {
          if($ver_pl > $chk_pl) return 1;
          else return 0;
        } else return 0;
      } else return 0;
    } else return 0;
  } else {
    # 현재 버전이 비교할 버젼보다 높을 경우 참
    if($ver[0] < $chk[0]) return 1;
    elseif ($ver[0] == $chk[0]) {
      if($ver[1] < $chk[1]) return 1;
      elseif ($ver[1] == $chk[1]) {
        if($ver[2] < $chk[2]) return 1;
        elseif ($ver[2] == $chk[2]) {
          if($ver_pl < $chk_pl) return 1;
          else return 0;
        } else return 0;
      } else return 0;
    } else return 0;
  }
}

function check_access($c=0,$wips='',$ips='') {
  global $HTTP_SERVER_VARS, $langs;

  if($c) {
    # global.ph 에 $board[ipbl] 이 존재하면 함침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # 원격 접속지가 존재하지 않거나 ips 변수가 없거나, 접속지가 자신이라면 체크 중지
    if(!trim($ips) || !$HTTP_SERVER_VARS[REMOTE_ADDR] ||
       $HTTP_SERVER_VARS[REMOTE_ADDR] == $HTTP_SERVER_VARS[SERVE_ADDR]) return;

    # spoofing 체크
    $ipchk = explode(".",$HTTP_SERVER_VARS[REMOTE_ADDR]);
    for($j=1;$j<4;$j++) $ipchk[$j] = $ipchk[$j] ? $ipchk[$j] : 0;
    # 각 자리수 마다 255 보다 크면 ip 주소를 벋어나므로 체크 
    if($ipchk[0] > 255 || $ipchk[1] > 255 || $ipchk[2] > 255 || $ipchk[3] > 255)
      print_error($langs[chk_sp]);

    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$HTTP_SERVER_VARS[REMOTE_ADDR])) $val = 1;
    }

    if($val) print_error($langs[chk_bl]);
  }
}

# hyper link 를 통한 접근을 제어
# m -> 0 : ips 에 등록된 Ip 에서의 링크만 허락
#      1 : ips 에 등록된 Ip 에서의 링크만 막음
#
function check_dhyper($c=0,$am=0,$wips='',$m=0,$ips='') {
  global $langs;

  # $c 설정이 있고, list read from write page 에서만 체크
  if($c && preg_match("/(list|read|form|write)\.php/i",$_SERVER[PHP_SELF])) {
    # global.ph 에 $board[dhyper] 가 정의 되어 있으면 체크 목록을 합침
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # $i = 0 -> 전체 어드민에서의 설정 체크
    # $i = 1 -> 게시판 어드민에서의 설정 체크
    for($i=0;$i<2;$i++) {
      if($i === 0 && !trim($wips)) continue;
      $ips_value = ($i === 0) ? $wips : $ips;
      $m_value = ($i === 0) ? $am : $m;

      # 레퍼럴이 존재하지 않거나 ips_value 변수가 없으면 체크 중지
      if(!trim($ips_value) || !$_SERVER[HTTP_REFERER]) return;

      # 레퍼럴에서 서버 이름만 추출
      preg_match("/^(http:\/\/)?([^\/]+)/i",$_SERVER[HTTP_REFERER],$chks);
      # 추출한 이름의 ip 를 구함
      $chk = gethostbyname($chks[2]);

      # chk 가 자신과 동일하면 체크 중지
      if($chk == $_SERVER[SERVER_ADDR]) return;

      $addr = explode(";",$ips_value);
      for($j=0;$j<sizeof($addr);$j++) {
        if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$j])) $addr[$j] .= ".";
        $addr[$j] = str_replace(".","\.",$addr[$j]);
        if(preg_match("/^$addr[$j]/i",$chk)) $val = 1;
      }
      switch($m_value) {
        case '1' :
          if($val) print_error($langs[chk_hy]);
          break;
        default:
          if(!$val) print_error($langs[chk_hy]);
          break;
      }
    }
  }
}

# spam 등록기 체크 함수
function check_spamer($anti,$wkey) {
  global $langs,$o;

  if($o[at] == "p" || $o[at] == "r") {
    if(!$anti || !preg_match("/^[0-9]+:[0-9]+:[0-9]+$/i",$anti)) print_error($langs[chk_an]);
    if($wkey != get_spam_value($anti)) print_error($langs[chk_sp]);
  }
}
?>
