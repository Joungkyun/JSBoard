<?
# html사용을 안할 경우 IE에서 문법에 맞지 않는 글자 표현시 깨지는 것을 수정
function ugly_han($text,$html=0) {
  if (!$html) $text = eregi_replace("&amp;(#|amp)","&\\1",$text);
  return $text;
}

# 검색 폼에서 넘어온 값을 URL로 바꿔줌 (POST -> GET 전환)
#
# trim         - 문자열 양쪽의 공백 문자를 없앰
#                http://www.php.net/manual/function.trim.php
# rawurlencode - RFC1738에 맞게 URL을 암호화
#                http://www.php.net/manual/function.rawurlencode.php
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

# 검색폼에서 넘어온 값을 SQL 질의문으로 바꿈
#
# trim         - 문자열 양쪽의 공백 문자를 없앰
#                http://www.php.net/manual/function.trim.php
# rawurldecode - 암호화된 URL를 복호화
#                http://www.php.net/manual/function.rawurldecode.php
function search2sql($o, $wh = 1) {
  if($o[at] != "s") return;

  $str = rawurldecode($o[ss]); # 검색 문자열을 복호화
  $str = trim($str);

  if(strlen(stripslashes($str)) < 3 && !$o[op]) {
    if($o[sc] != "r" && $o[st] != "t")
      print_error("검색어는 한글 2자, 영문 3자 이상이어야 합니다.");
  }

  if(!$o[er]) {
    # %는 SQL 질의에서 And 연산으로 쓰이므로 \를 붙여서 일반 문자임을 나타냄
    $str = str_replace("%","\%",$str);
    # \%\%를 and 연산으로 간주하여 %로 수정
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
      break; # 오늘
    case 'w': $sql .= "(date >= $week) AND ";
      break; # 일주일간
    case 'm': $sql .= "(date >= $month) AND ";
      break; # 한달간
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

# 검색 문자열 하이라이팅 함수
#
# explode      - 구분 문자열을 기준으로 문자열을 나눔
#                http://www.php.net/manual/function.explode.php
# rawurldecode - 암호화된 URL을 복호화
#                http://www.php.net/manual/function.rawurldecode.php
# trim         - 문자열 양쪽의 공백 문자를 없앰
#                http://www.php.net/manual/function.trim.php
# stripslashes -
#                http://www.php.net/manual/function.stripslashes.php
# quotemeta    -
#                http://www.php.net/manual/function.quotemeta.php
function search_hl($list) {
  global $board; # 게시판 기본 설정 (config/global.ph)
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
      $list[text] = eregi_replace("<a href=([^<]*)$hl[0]([^<]*)$hl[1]([^>]*)>","<a href=\\1\\2\\3>",$list[text]);
      break;
    case 'a':
      $list[text] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[text]);
      $list[title] = eregi_replace($str, "$hl[0]\\0$hl[1]", $list[title]);
      $list[text] = eregi_replace("<a href=([^<]*)$hl[0]([^<]*)$hl[1]([^>]*)>","<a href=\\1\\2\\3>",$list[text]);
      break;
  }

  return $list;
}

function text_nl2br($text, $html) {
  global $langs;
  if($html) {
    $text = eregi_replace("<(\\?|%)(.*)(\\?|%)>", "&lt;\\1\\2\\3?&gt;", $text);
    $text = eregi_replace("<([/]*)(pre|xmp|base)[^>]*>","",$text);
    $text = eregi_replace("([a-z0-9]*script:)","deny_\\1",$text);
    $text = ereg_replace("\r\n", "\n", $text);

    if(!eregi("<--no-autolink>",$text)) $text = auto_link($text);
    else $text = chop(str_replace("<--no-autolink>","",$text));

    $text = eregi_replace("(\n)?<table","</PRE><TABLE",$text);
    $text = eregi_replace("</table>(\n)?","</TABLE><PRE>",$text);
    $text = "<PRE>$text</PRE>";
  } else {
    $text = htmlspecialchars($text);
    # 한글 깨지는것 보정
    if ($langs[code] == "ko") $text = ugly_han($text);
    $text = "<PRE>\n$text\n</PRE>";
    $text = auto_link($text);
  }
  return $text;
}

function delete_tag($text) {
  $text = eregi_replace("<html(.*)<body([^>]*)>","",$text);
  $text = eregi_replace("</body(.*)</html>(.*)","",$text);
  $text = eregi_replace("<[/]*(div|layer|body|html|head|meta|form|input|select|textarea|base)[^>]*>","",$text);
  $text = eregi_replace("<(style|script|title)(.*)</(style|script|title)>","",$text);
  $text = eregi_replace("<[/]*(script|style|title|xmp)>","",$text);
  $text = eregi_replace("([a-z0-9]*script:)","deny_\\1",$text);
  $text = eregi_replace("<(\\?|%)","&lt;\\1",$text);
  $text = eregi_replace("(\\?|%)>","\\1&gt;",$text);
  $text = chop($text);

  return $text;
}

# 문자열을 일정한 길이로 자르는 함수
#
# 한글을 한바이트 단위로 잘르는 경우를 막고 대문자가 많이 쓰인 경우
# 소문자와의 크기 비율 정도(1.5?)에 따라 문자열을 자름
#
# intval - 변수의 정수형 값을 가져옴
#          http://www.php.net/manual/function.intval.php
# substr - 문자열의 지정된 범위를 잘라서 가져옴
#          http://www.php.net/manual/function.substr.php
# chop   - 문자열 끝의 공백 문자을 없앰
#          http://www.php.net/manual/function.chop.php
function cut_string($s, $l) {
  if(strlen($s) <= $l && !eregi("^[a-z]+$", $s))
    return $s;

  for($i = $l; $i >=1; $i--) {
      # 끝에서부터 한글 byte수를 센다.
      if(is_hangul($s[$i-1])) $hangul++;
      else break;
  }
  
  if ($hangul) {
      # byte수가 홀수이면, 한글의 첫번째 바이트이다.
      # 한글의 첫번째 바이트일 때 깨지는 것을 막기 위해 지정된 길이를 한
      # 바이트 줄임
      if ($hangul%2) $l--;
      
      $s = chop(substr($s, 0, $l));
  }
  else { # 문자열의 끝이 한글이 아닐 경우
      for($i = 1; $i <= $l; $i++) {
          # 대문자의 갯수를 기록
          if(is_alpha($s[$i-1]) == 2) $alpha++;
          # 마지막 한글이 나타난 위치 기록
          if(is_hangul($s[$i-1])) $last_han=$i;
      }
      
      # 지정된 길이로 문자열을 자르고 문자열 끝의 공백 문자를 삭제함
      # 대문자의 길이는 1.3으로 계산한다. 문자열 마지막의 영문 문자열이 
      # 빼야할 전체 길이보다 크면 초과된 만큼 뺀다.
      $capitals = intval($alpha * 0.5);
      if ( ($l-$last_han) <= $capitals) $capitals=0;
      $s = chop(substr($s, 0, $l - $capitals));
  }

  return $s;
}

# 문서 내용에 있는 URL들을 찾아내어 자동으로 링크를 구성해주는 함수
#
# eregi_replace - 정규 표현식을 이용한 치환 (대소문자 무시)
#                 http://www.php.net/manual/function.eregi-replace.php
function auto_link($str) {
  global $color;
  $agent = get_agent();

  $regex[file] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
  $regex[http] = "(http|https|ftp|telnet|news):\/\/(([\xA1-\xFEa-z0-9_\-]+\.[][\xA1-\xFEa-z0-9:;&#@=_~%\?\/\.\,\+\-]+)(\/|[\.]*[a-z0-9]))";
  $regex[mail] = "([\xA1-\xFEa-z0-9_\.\-]+)@([\xA1-\xFEa-z0-9_\-]+\.[a-z0-9\-\._\-]+[\.]*[\xA1-\xFEa-z0-9\?=]*)";

  # < 로 열린 태그가 그 줄에서 닫히지 않을 경우 nl2br()에서 <BR> 태그가
  # 붙어 깨지는 문제를 막기 위해 다음 줄까지 검사하여 이어줌
  $str = eregi_replace("<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>", "<\\1\\2\\3>", $str);
  $str = eregi_replace("<([^<>\n]*)\n([^\n<>]*)>", "<\\1\\2>", $str);
  
  # 특수 문자와 링크시 target 삭제
  $str = eregi_replace("&(quot|gt|lt)","!\\1",$str);
  
  # html사용시 link 보호
  $str = eregi_replace("<a[ ]+href=[\"']*($regex[http])[\"']*[ ]?[^>]*>","<a href=\"\\2_orig://\\3\" target=\"_blank\">", $str);
  $str = eregi_replace("<a[ ]+href=[\"']*mailto:($regex[mail])[\"']*>","<a href=\"mailto:\\2#-#\\3\">", $str);
  $str = eregi_replace("<([a-z0-9\"'%= ]+) (background|codebase|src)=[\"']*($regex[http])[\"']*","<\\1 \\2=\"\\4_orig://\\5\"",$str);
  if($agent[br] != "MSIE") $str = eregi_replace("<embed","&lt;embed",$str);
  
  # 링크가 안된 url및 email address 자동링크
  $str = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
  $str = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $str);
  
  # 보호를 위해 치환한 것들을 복구
  $str = eregi_replace("!(quot|gt|lt)","&\\1",$str);
  $str = eregi_replace("(http|https|ftp|telnet|news)_orig","\\1", $str);
  $str = eregi_replace("#-#","@",$str);
  
  # link가 2개 겹쳤을때 이를 하나로 줄여줌
  $str = eregi_replace("(<a href=[\"']?($regex[http])[\"']?[^>]*>)(<a href=[\"']?($regex[http])[\"']?[^>]*>)+","\\1", $str);
  $str = eregi_replace("(<a href=[\"']?mailto:($regex[mail])[\"']?>)(<a href=[\"']?mailto:($regex[mail])[\"']?>)+","\\1", $str);
  $str = eregi_replace("</a></a>","</a>",$str);
  $str = eregi_replace("([a-z])(\.)*(\" target=\"_blank)\">","\\1\\3\">",$str);
  $str = eregi_replace("([a-z])(\.)*\">","\\1\">",$str);
  
  # file link시 target 을 삭제
  $str = eregi_replace("(\.($regex[file])\") target=\"_blank\"","\\1",$str);
  
  return $str;
}

# Email 링크를 만들기 위한 함수
function url_link($url, $str, $color, $no = 0) {
  global $table, $board, $rmail;

  if(check_email($url)) {
    if($board[mchk] && $rmail[uses] == "yes") {
      $board[fwidth] = eregi("%",$board[width]) ? "550" : $board[width];
      $str = "<A HREF=javascript:new_windows('form.php?table=$table&no=$no','form',0,0,$board[fwidth],420)><FONT COLOR=\"$color\">$str</FONT></A>";
    } else {
      $url = str_replace(".","DENY.SPAM",$url);
      $str = "<A HREF=mailto:$url><FONT COLOR=\"$color\">$str</FONT></A>";
    }
  } else if(check_url($url)) {
    $str = "<A HREF=\"$url\" target=\"_blank\"><FONT COLOR=\"$color\">$str</FONT></A>";
  } else {
    $str = "<FONT COLOR=\"$color\">$str</FONT>";
  }

  return $str;
}

# File upload를 위한 함수
#
#
# mkdir            -> directory 생성
# is_upload_file   -> upload file의 존재성 여부
# move_upload_file -> tmp로 upload되어 있는 파일을 원하는 디레토리에 위치
# chmod            -> file, direcoty의 권한 변경
#
function file_upload($updir) {
  global $userfile_size, $userfile, $userfile_name;
  global $upload, $langs, $table;

  if(is_uploaded_file($userfile)) {
    if ($userfile_size > $upload[maxsize]) {
      print_error($langs[act_md]);
      exit;
    }

    # file name에 공백이 있을 경우 공백 삭제
    $userfile_name = eregi_replace(" ","",$userfile_name);

    # file name에 특수 문자가 있을 경우 등록 거부
    upload_name_chk($userfile_name);

    # php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
    $userfile_name = eregi_replace("[\.]*$","",$userfile_name);
    $userfile_name = eregi_replace(".(ph|inc|php[0-9a-z]*|phtml)$",".phps", $userfile_name);
    $userfile_name = eregi_replace("(.*)\.(cgi|pl|sh|html|htm|shtml|vbs)$", "\\1_\\2.phps", $userfile_name);

    mkdir("data/$table/$upload[dir]/$updir",0755);
    move_uploaded_file($userfile,"data/$table/$upload[dir]/$updir/$userfile_name");
    chmod("data/$table/$upload[dir]/$updir/$userfile_name",0644);

    $up = 1;
  } elseif($userfile_name) {
    if($userfile_size == '0') {
      print_error($langs[act_ud]);
    } else {
      print_error($langs[act_ed]);
    }
    exit;
  }
  return $up;
}

# HTML entry를 특수 특수 문자로 변환
# (htmlspecialchars 함수의 역함수)
#
# get_html_translation_table - htmlspecialchars()와 htmlentities() 함수에서
#                              사용하는 변환 테이블을 배열로 반환
# array_flip                 - 배열 값들의 순서를 반대로 
#
function unhtmlspecialchars($t) {
  $tr = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  $t = strtr(str_replace("&#039;","'",$t),$tr);
  $t = strtr(str_replace("&amp;","&",$t),$tr);

  return $t;
}
?>
