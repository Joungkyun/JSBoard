<?
// 문자열에 한글이 포함되어 있는지 검사하는 함수
//
// ord    - 문자의 ASCII 값을 가져옴
//          http://www.php.net/manual/function.ord.php3
function is_hangul($char) {
  // 특정 문자가 한글의 범위내(0xA1A1 - 0xFEFE)에 있는지 검사
  $char = ord($char);

  if($char >= 0xa1 && $char <= 0xfe)
    return 1;
}

// 알파벳인지 그리고 대문자(0x41 - 0x5a)인지 소문자(0x61 - 0x7a)인지
// 검사하는 함수
//
// ord - 문자의 ASCII 값을 가져옴
//       http://www.php.net/manual/function.ord.php3
function is_alpha($char) {
  $char = ord($char);

  if($char >= 0x61 && $char <= 0x7a)
    return 1;
  if($char >= 0x41 && $char <= 0x5a)
    return 2;
}

// URL이 정확한 것인지 검사하는 함수
//
// eregi         - 정규 표현식을 이용한 검사 (대소문자 무시)
//                 http://www.php.net/manual/function.eregi.php3
// eregi_replace - 정규 표현식을 이용한 치환 (대소문자 무시)
//                 http://www.php.net/manual/function.eregi-replace.php3
function check_url($url) {
  $url = trim($url);

  // 프로토콜(http://, ftp://...)을 나타내는 부분이 없을 때 기본값으로
  // http://를 붙임
  if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url))
    $url = eregi_replace("^", "http://", $url);

  if(!eregi("(http|https|ftp|telnet|news):\/\/[a-z0-9-]+\.[][a-zA-Z0-9:&#@=_~%\-\?\/\.\+]+", $url))
    return;
    
  return $url;
}

// E-MAIL 주소가 정확한 것인지 검사하는 함수
//
// eregi - 정규 표현식을 이용한 검사 (대소문자 무시)
//         http://www.php.net/manual/function.eregi.php3
function check_email($email) {
  $url = trim($email);

  if(!eregi("^[a-z0-9_-]+@[a-z0-9-]+\.[a-z0-9-]+", $email))
    return;
    
  return $email;
}

// 패스워드 비교 함수
//
// crpyt - 문자열을 DES로 암호화함
//         http://www.php.net/manual/function.crypt.php3
function check_passwd($table, $no, $passwd) {
  global $sadmin, $admin;

  if ($table && $no) {
    $result = sql_query("SELECT passwd FROM $table WHERE no = $no");
    $crypt  = sql_result($result, 0, "passwd");
    sql_free_result($result);
  }

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(crypt($passwd, $crypt) == $crypt || $sadmin[passwd] == $spasswd || $admin[passwd] == $upasswd) {
    return 1;
  }
}

// 스팸 검사 함수
function check_spam($str, $spam_list = "config/spam_list.txt") {
  // $spam_list에 지정된 파일을 읽기 전용(r)으로 읽음
  $fp = fopen($spam_list, "r");
  // $spam_list에 지정된 파일의 크기만큼 내용을 읽어 $fr에 저장
  $fr = fread($fp, filesize($spam_list));
  fclose($fp);

  // $fr의 값을 \n 문자로 구분하여 $list 배열에 저장
  $list = explode("\n", $fr);

  // $list 배열의 갯수 만큼 for문을 돌려 $spam_list 파일에 지정되어 있던
  // 문자열들과 일치하는 문자열이 $spam_str에 있는지 검사함, 있을 경우
  // 스팸으로 판단하고 정수형 1을 반환함, 없을 경우는 0을 반환함
  for($co = 0; $co < count($list); $co++) {
    $list[$co] = eregi_replace("(\r|\n)","",$list[$co]);
    if($list[$co] && eregi($list[$co], $str)) {
      return 1;
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

// 쓰기권한 체크 함수
//
function enable_write($super,$user,$check,$ena, $cena = 1) {
  global $kind, $table, $no, $wcheck, $langs;

  if($no) $nom = "&no=$no";
  if ($wcheck) {
    header("Location: auth_ext.php3?table=$table&ena=$ena&cena=$cena&kind=$kind$nom");
    die;
  }

  $pucheck = crypt($check,$user);
  $pscheck = crypt($check,$super);

  $selffile ="auth_ext.php3?table=$table&kind=$kind$nom";
  if (!$ena) $user_m = "$langs[chk_a]";
  $ment = "$langs[chk_wa]";

  if (!$ena) {
    if($super != $pscheck) missmatch_passwd($selffile,$ment);
  } else if($ena && !$cena) {
    if($super != $pscheck && $user != $pucheck) missmatch_passwd($selffile,$ment);
  }
}

// upload file 미리보기를 위한 file type 검사
// substr - 문자열의 일부분을 반환한다.
// strchr - 문자열이 마지막으로 나타나는 위치를 구한다
//
function check_filetype($filetype) {
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}

function icon_check($tail,$fn) {
  if ($tail == "rm" || $tail == "doc" || $tail == "exe" || $tail == "hwp" || $tail == "mp3" || $tail == "mov" || $tail == "txt") $icon = "$tail.gif";
  else if ($tail == "zip" || $tail == "arj" || $tail == "gz" || $tail == "lha" || $tail == "rar" || $tail == "tar" || $tail == "tgz") $icon = "comp.gif";
  else if ($tail == "php" || $tail == "php3" || $tail == "phps") {
    if (eregi("(_htm|_cgi|_pl|_shtm|_sh)",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if ($tail == "avi" || $tail == "mpg" || $tail == "mpeg") $icon = "mpeg.gif";
  else if ($tail == "jpg" || $tail == "gif" || $tail == "bmp" || $tail == "psd") $icon = "pic.gif";
  else if ($tail == "ppt" || $tail == "xls") $icon = "doc.gif";
  else if ($tail == "ra" || $tail == "ram") $icon = "ra.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (eregi("(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch)$",$list[bofile])) {
    $dn = "act.php3?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
  } else {
    if ($list[bfsize] < 51200) $dn = "act.php3?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
    else $dn = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  }
  return $dn;
}

?>
