<?
// 검색 폼에서 넘어온 값을 URL로 바꿔줌 (POST -> GET 전환)
//
// trim         - 문자열 양쪽의 공백 문자를 없앰
//                http://www.php.net/manual/function.trim.php3
// rawurlencode - RFC1738에 맞게 URL을 암호화
//                http://www.php.net/manual/function.rawurlencode.php3
function search2url($o, $method = "get") {
  if($o[at] != "s")
    return;

  $str = trim($o[ss]);
  $str = stripslashes($str);

  for($i = 0; $i < count($o); $i++) {
    $key   = key($o);
    $value = current($o);

    if($method == "get") {
      $value = rawurlencode($value);
      $url  .= "&o[$key]=$value";
    } else $url  .= "\n<INPUT TYPE=\"hidden\" NAME=\"o[$key]\" VALUE=\"$value\">";

    next($o);
  }

  return $url;
}

// 검색폼에서 넘어온 값을 SQL 질의문으로 바꿈
//
// trim         - 문자열 양쪽의 공백 문자를 없앰
//                http://www.php.net/manual/function.trim.php3
// rawurldecode - 암호화된 URL를 복호화
//                http://www.php.net/manual/function.rawurldecode.php3
function search2sql($o, $wh = 1) {
  if($o[at] != "s") return;

  $str = rawurldecode($o[ss]); // 검색 문자열을 복호화
  $str = trim($str);

  if(strlen(stripslashes($str)) < 3 && !$o[op]) {
    if($o[sc] != "r" && $o[st] != "t")
      print_error("검색어는 한글 2자, 영문 3자 이상이어야 합니다.");
  }


  if(!$o[er]) {
    // %는 SQL 질의에서 And 연산으로 쓰이므로 \를 붙여서 일반 문자임을 나타냄
    $str = str_replace("%","\%",$str);
    // \%\%를 and 연산으로 간주하여 %로 수정
    $str = str_replace("\%\%","%",$str);
    $str = addslashes($str);

    if (eregi("\"",$str))
        print_error("<b>[<font color=darkred>\"'</font>]</b>가 포함된 검색어는 검색하실수 없습니다.");
  }

  $sql = $wh ? "WHERE " : "AND ";
  $today = get_date();
  $month = $today - (60 * 60 * 24 * 30);
  $week  = $today - (60 * 60 * 24 * 7);

  switch($o[st]) {
    case 't': $sql .= "(date >= $today)";
      break; // 오늘
    case 'w': $sql .= "(date >= $week) AND ";
      break; // 일주일간
    case 'm': $sql .= "(date >= $month) AND ";
      break; // 한달간
  }

  if($o[er]) $str = "REGEXP \"$str\"";
  else $str = "LIKE \"%$str%\"";

  switch($o[sc]) {
    case 'a': $sql .= "(title $str OR text $str)";
      break;
    case 'c': $sql .= "(text $str)";
      break;
    case 'n': $sql .= "(name $str)";
      break;
    case 't': $sql .= "(title $str)";
      break;
    case 'r': $sql .= "(no = $o[no] OR reto = $o[no])";
      break;
  }

  return $sql;
}

// 검색 문자열 하이라이팅 함수
//
// explode      - 구분 문자열을 기준으로 문자열을 나눔
//                http://www.php.net/manual/function.explode.php3
// rawurldecode - 암호화된 URL을 복호화
//                http://www.php.net/manual/function.rawurldecode.php3
// trim         - 문자열 양쪽의 공백 문자를 없앰
//                http://www.php.net/manual/function.trim.php3
// stripslashes -
//                http://www.php.net/manual/function.stripslashes.php3
// quotemeta    -
//                http://www.php.net/manual/function.quotemeta.php3
function search_hl($list) {
  global $board; // 게시판 기본 설정 (config/global.ph)
  global $o;

  $hl = explode("STR", $board[hl]);

  if(!$o[ss]) return $list;

  $str = rawurldecode($o[ss]);
  $str = trim($str);
  $str = stripslashes($str);

  if(!$o[er]) $str = quotemeta($str);

  switch($o[sc]) {
    case 'n':
      $list[name] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[name]);
      break;
    case 't':
      $list[title] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[title]);
      break;
    case 'c':
      $list[text] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[text]);
      $list[text] = eregi_replace("<a href=([^<]*)<font color=#000000><b><u>([^<]*)</u></b></font>([^>]*)>","<a href=\\1\\2\\3>",$list[text]);
      break;
    case 'a':
      $list[text] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[text]);
      $list[title] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[title]);
      $list[text] = eregi_replace("<a href=([^<]*)<font color=#000000><b><u>([^<]*)</u></b></font>([^>]*)>","<a href=\\1\\2\\3>",$list[text]);
      break;
  }

  return $list;
}

function text_nl2br($text, $html) {
  global $langs;
  if($html) {
    $text = eregi_replace("<\\?(.*)\\?>", "&lt;?\\1?&gt;", $text);
    $text = eregi_replace("<script([^>]*)>(.*)</script>", "&lt;script\\1&gt;\\2&lt;/script&gt;", $text);
    $text = ereg_replace("\r\n", "\n", $text);
    $text = remove_tbbr($text);
    $text = auto_link($text);
    $text = ereg_replace("\n", "\r\n", $text);
    $text = nl2br($text);
    $text = ereg_replace("<br>\n", "<BR>", $text);
  } else {
    $text = htmlspecialchars($text);
    if ($langs[code] == "ko") $text = eregi_replace("&amp;#","&#",$text); // 한글 깨지는것 보정
    $text = "<PRE>\n$text\n</PRE>";
    $text = auto_link($text);
  }
  return $text;
}


// html 사용시에 table이 있으면 nl2br() 함수를 적용시키지 않음
function remove_tbbr($text) {
  $text = eregi_replace("(\n)*<tr([^>]*)>(\n)*","<tr\\2>",$text);
  $text = eregi_replace("(\n)*<td([^>]*)>(\n)*","<td\\2>",$text);
  $text = eregi_replace("(\n)*</table>(\n)*","</table>",$text);
  $text = eregi_replace("(\n)*([a-z&\; ])*(<[\/]*(tab|tr|td|li|ul|ol))","\\3",$text);

  return $text;
}

function delete_tag($text) {

  $text = eregi_replace("<\\?(.*)\\?>", "", $text);
  $text = eregi_replace("<html(.*)<body([^>]*)>","",$text);
  $text = eregi_replace("</body(.*)</html>","",$text);
  $text = eregi_replace("<(\/)*(div|layer|body|html|head|meta)[^>]*>","",$text);
  $text = eregi_replace("<(style|script|title)(.*)</(style|script|title)>","",$text);
  $text = eregi_replace("<[/]*(script|style|title)>","",$text);
  $text = trim($text);

  return $text;

}

// html사용을 안할 경우 IE에서 문법에 맞지 않는 글자 표현시 깨지는 것을 수정
function ugly_han($text,$html=0) {
  if (!$html) $text = eregi_replace("&amp;#","&#",$text);
  return $text;
}

// 문자열을 일정한 길이로 자르는 함수
//
// 한글을 한바이트 단위로 잘르는 경우를 막고 대문자가 많이 쓰인 경우
// 소문자와의 크기 비율 정도(1.5?)에 따라 문자열을 자름
//
// intval - 변수의 정수형 값을 가져옴
//          http://www.php.net/manual/function.intval.php3
// substr - 문자열의 지정된 범위를 잘라서 가져옴
//          http://www.php.net/manual/function.substr.php3
// chop   - 문자열 끝의 공백 문자을 없앰
//          http://www.php.net/manual/function.chop.php3
function cut_string($str, $length) {
  if(strlen($str) <= $length && !eregi("^[a-z]+$", $str))
    return $str;

  for($co = 1; $co <= $length; $co++) {
    if(is_hangul(substr($str, $co - 1, $co))) {
      if($first) { // first 값이 있으면 한글의 두번째 바이트로 정의
        $second = 1;
        $first  = 0;
      } else {     // first 값이 없으면 한글의 첫번째 바이트로 정의
        $first  = 1;
        $second = 0;
      }
      $hangul = 1;
    } else {
      // 한글이 아닐 경우 한글의 몇번째 바이트인지 나타내는 변수 초기화
      $first = $second = 0;
      // 대문자의 갯수를 기록
      if(is_alpha(substr($str, $co - 1, $co)) == 2)
        $alpha++;
    }
  }
  // 한글의 첫번째 바이트일 때 깨지는 것을 막기 위해 지정된 길이를 한
  // 바이트 줄임
  if($first) $length--;

  // 지정된 길이로 문자열을 자르고 문자열 끝의 공백 문자를 삭제함
  // 영문만 있을 경우 대문자의 길이를 1.3으로 하여 초과된 만큼을 뺌
  if($hangul) $str = chop(substr($str, 0, $length));
  else $str = chop(substr($str, 0, $length - intval($alpha * 0.5)));

  return $str;
}

// 문서 내용에 있는 URL들을 찾아내어 자동으로 링크를 구성해주는 함수
//
// eregi_replace - 정규 표현식을 이용한 치환 (대소문자 무시)
//                 http://www.php.net/manual/function.eregi-replace.php3
function auto_link($str) {
  global $color;

  $regex[http] = "(http|https|ftp|telnet|news):\/\/([a-z0-9\-_]+\.[][a-zA-Z0-9:;&#@=_~%\?\/\.\+\-]+)";
  $regex[mail] = "([a-z0-9_\-]+)@([a-z0-9_\-]+\.[a-z0-9\-\._]+)";

  // < 로 열린 태그가 그 줄에서 닫히지 않을 경우 nl2br()에서 <BR> 태그가
  // 붙어 깨지는 문제를 막기 위해 다음 줄까지 검사하여 이어줌
  $str = eregi_replace("<([^<>\n]+)\n([^\n<>]+)>", "<\\1 \\2>", $str);

  // 특수 문자와 링크시 target 삭제
  $str = eregi_replace("&(quot|gt|lt)","!\\1",$str);
  $str = eregi_replace("([ ]*)target=[\"'_a-z,A-Z]+","", $str);
  $str = eregi_replace("([ ]+)on([a-z]+)=[\"'_a-z,A-Z\?\.\-_\/()]+","", $str);

  // html사용시 link 보호
  $str = eregi_replace("<a([ ]+)href=([\"']*)($regex[http])([\"']*)>","<a href=\"\\4_orig://\\5\" target=\"_blank\">", $str);
  $str = eregi_replace("<a([ ]+)href=([\"']*)mailto:($regex[mail])([\"']*)>","<a href=\"mailto:\\4#-#\\5\">", $str);
  $str = eregi_replace("<img([ ]*)src=([\"']*)($regex[http])([\"']*)","<img src=\"\\4_orig://\\5\"",$str);

  // 링크가 안된 url및 email address 자동링크
  $str = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
  $str = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $str);

  // 보호를 위해 치환한 것들을 복구 
  $str = eregi_replace("!(quot|gt|lt)","&\\1",$str);
  $str = eregi_replace("http_orig","http", $str);
  $str = eregi_replace("#-#","@",$str);

  // link가 2개 겹쳤을때 이를 하나로 줄여줌 
  $str = eregi_replace("(<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>)+<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>","\\1", $str);
  $str = eregi_replace("(<a href=([\"']*)mailto:($regex[mail])([\"']*)>)+<a href=([\"']*)mailto:($regex[mail])([\"']*)>","\\1", $str);
  $str = eregi_replace("</a></a>","</a>",$str);

  return $str;
}

// 간편하게 링크를 만들기 위한 함수
function url_link($url, $str, $color) {
  if(check_email($url)) {
    $str = "<A HREF=\"mailto:$url\"><FONT COLOR=\"$color\">$str</FONT></A>";
  } else if(check_url($url)) {
    $str = "<A HREF=\"$url\" target=\"_blank\"><FONT COLOR=\"$color\">$str</FONT></A>";
  } else {
    $str = "<FONT COLOR=\"$color\">$str</FONT>";
  }

  return $str;
}
?>
