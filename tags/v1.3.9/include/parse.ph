<?
# html사용을 안할 경우 IE에서 문법에 맞지 않는 글자 표현시 깨지는 것을 수정
function ugly_han($text,$html=0) {
  if (!$html) $text = preg_replace("/&amp;(#|amp)/i","&\\1",$text);
  $text = str_replace("&amp;","&",$text);
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

  $url = preg_replace("/(%5C)%5C/i","\\1",$url);
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

    if (preg_match("/\"/",$str))
      print_error("<b>[<font color=darkred>\"'</font>]</b>가 포함된 검색어는 검색하실수 없습니다.");
  } else {
    # 정규 표현식: 검색어가 "[,("로 시작했지만 "],)"로 닫지 않은 경우 체크 
    $chk = preg_replace("/\\\([\]\[()])/i","",$str); 
    $chk = preg_replace("/[^\[\]()]/i","",$chk); 
    
    $chkAOpen = strlen(preg_replace("/\]/i","",$chk)); 
    $chkAClos = strlen(preg_replace("/\[/i","",$chk)); 
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk)); 
    $chkBClos = strlen(preg_replace("/\(/i","",$chk)); 
    
    if($chkAOpen !== $chkAClos) $str .= "]"; 
    elseif($chkBOpen !== $chkBClos) $str .= ")"; 
  }

  $sql = $wh ? "WHERE " : "AND ";
  $today = get_date();
  $month = $today - (60 * 60 * 24 * 30);
  $week  = $today - (60 * 60 * 24 * 7);

  switch($o[st]) {
    case 't': $sql .= "(date >= '$today')";
      break; # 오늘
    case 'w': $sql .= "(date >= '$week') AND ";
      break; # 일주일간
    case 'm': $sql .= "(date >= '$month') AND ";
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
    case 'r': $sql .= "(no = '$o[no]' OR reto = '$o[no]')";
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

  # 정규 표현식: 검색어가 "[,("로 시작했지만 "],)"로 닫지 않은 경우 체크
  if ($o[er]) {
    $chk = preg_replace("/\\\([\]\[()])/i","",$str);
    $chk = preg_replace("/[^\[\]()]/i","",$chk);
  
    $chkAOpen = strlen(preg_replace("/\]/i","",$chk));
    $chkAClos = strlen(preg_replace("/\[/i","",$chk));
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk));
    $chkBClos = strlen(preg_replace("/\(/i","",$chk));

    if($chkAOpen !== $chkAClos) {
       $str .= "]";
       $o[ss] .= "]";
    } elseif($chkBOpen !== $chkBClos) {
       $str .= ")";
       $o[ss] .= ")";
    }

    # regex 에서 충돌되는 문자 escape 처리
    $dead = array("/\?|\)|\(|\*|\.|\^|\+|\%/i");
    $live = array("\\\\\\0");
    $str = preg_replace($dead,$live,$str);
  } else $str = quotemeta($str);

  $str = str_replace("/","\/",$str);

  $hlreg[0] = $hl[0];
  $hlreg[1] = str_replace("/","\/",str_replace("\/","/",$hl[1]));

  $regex1 = "(<\/?)$hlreg[0]([^<]+)$hlreg[1]([^>]*>)";
  $regex2 = "(<\/?FONT[^<>]+)$hlreg[0]([^<]+)$hlreg[1]([^>]*>)";
  $regex3 = "(HREF|SRC)=([^<>]*)$hl[0]([^<]*)<\/U><\/B><\/FONT>([^>]*)";

  $src = array("/$regex1/i","/$regex2/i");
  $tar = array("\\1\\2\\3","\\1\\2\\3");
  $tsrc = array("/$regex1/i","/$regex2/i","/$regex3/i");
  $ttar = array("\\1\\2\\3","\\1\\2\\3","\\1=\\2\\3\\4");

  switch($o[sc]) {
    case 'n':
      $list[name] = preg_replace("/$str/i","$hl[0]\\0$hl[1]",$list[name]);
      break;
    case 't':
      $list[title] = preg_replace("/$str/i","$hl[0]\\0$hl[1]",$list[title]);
      break;
    case 'c':
      $list[text] = preg_replace("/$str/i","$hl[0]\\0$hl[1]",$list[text]);
      while(true) {
        if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list[text]))
          $list[text] = preg_replace($tsrc,$ttar,$list[text]);
        else break;
      }
      break;
    case 'a':
      $list[title] = preg_replace("/$str/i","$hl[0]\\0$hl[1]",$list[title]);
      $list[text] = preg_replace("/$str/i","$hl[0]\\0$hl[1]",$list[text]);
      while(true) {
        if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list[text]))
          $list[text] = preg_replace($tsrc,$ttar,$list[text]);
        else break;
      }
      break;
  }
     
  return $list;
}

function text_nl2br($text, $html) {
  global $langs;
  if($html) {
    $source = array("/<(\?|%)/i","/(\?|%)>/i","/([a-z0-9]*script:)/i","/\r\n/",
                    "/<(\/*(script|style|pre|xmp|base|span|html)[^>]*)>/i");
    $target = array("&lt;\\1","\\1&gt;","deny_\\1","\n","&lt;\\1&gt;");
    $text = preg_replace($source,$target,$text);
    if(!preg_match("/<--no-autolink>/i",$text)) $text = auto_link($text);
    else $text = chop(str_replace("<--no-autolink>","",$text));

    $text = eregi_replace("(\n)?<table","</PRE><TABLE",$text);
    $text = eregi_replace("</table>(\n)?","</TABLE><PRE>",$text);
    $text = !$text ? "No Contents" : $text;
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
  $src = array("/\n/i","/<html.*<body[^>]*>/i","/<\/body.*<\/html>.*/i",
               "/<\/*(div|span|layer|body|html|head|meta|input|select|option|form)[^>]*>/i",
               "/<(style|script|title).*<\/(style|script|title)>/i",
               "/<\/*(script|style|title|xmp)>/i","/<(\\?|%)/i","/(\\?|%)>/i",
               "/#\^--ENTER--\^#/i");
  $tar = array("#^--ENTER--^#","","","","","","&lt;\\1","\\1&gt;","\n");

  $text = chop(preg_replace($src,$tar,$text));

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
  if(strlen($s) <= $l && !preg_match("/^[a-z]+$/i", $s))
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
# preg_replace  - 펄 형식의 정규표현식을 이용한 치환
#                 http://www.php.net/manual/function.preg-replace.php
function auto_link($str) {
  $agent = get_agent();

  $regex[file] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
  $regex[file] = "(\.($regex[file])\") TARGET=\"_blank\"";
  $regex[http] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9,:;&#=_~%\[\]?\/.,+\-]+)([.]*[\/a-z0-9\[\]]|=[\xA1-\xFE]+))";
  $regex[mail] = "([\xA1-\xFEa-z0-9_.-]+)@([\xA1-\xFEa-z0-9_-]+\.[\xA1-\xFEa-z0-9._-]*[a-z]{2,3}(\?[\xA1-\xFEa-z0-9=&\?]+)*)";

  # &lt; 로 시작해서 3줄뒤에 &gt; 가 나올 경우와
  # IMG tag 와 A tag 의 경우 링크가 여러줄에 걸쳐 이루어져 있을 경우
  # 이를 한줄로 합침 (합치면서 부가 옵션들은 모두 삭제함)
  $src[] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i";
  $tar[] = "<\\1\\2\\3>";
  $src[] = "/<([^<>\n]*)\n([^\n<>]*)>/i";
  $tar[] = "<\\1\\2>";
  $src[] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*($regex[http]|mailto:$regex[mail])[^>]*>/i";
  $tar[] = "<\\1 \\2=\"\\3\">";

  # email 형식이나 URL 에 포함될 경우 URL 보호를 위해 @ 을 치환
  $src[] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i";
  $tar[] = "\\1://\\2_HTTPAT_\\3";

  # 특수 문자를 치환 및 html사용시 link 보호
  $src[] = "/&(quot|gt|lt)/i";
  $tar[] = "!\\1";
  $src[] = "/<a([^>]*)href=[\"' ]*($regex[http])[\"']*[^>]*>/i";
  $tar[] = "<A\\1HREF=\"\\3_orig://\\4\" TARGET=\"_blank\">";
  $src[] = "/href=[\"' ]*mailto:($regex[mail])[\"']*>/i";
  $tar[] = "HREF=\"mailto:\\2#-#\\3\">";
  $src[] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*($regex[http])[\"']*/i";
  $tar[] = "<\\1\\2=\"\\4_orig://\\5\"";

  # 링크가 안된 url및 email address 자동링크
  $src[] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)($regex[http])/i";
  $tar[] = "\\1<A HREF=\"\\3\" TARGET=\"_blank\">\\3</a>";
  $src[] = "/($regex[mail])/i";
  $tar[] = "<A HREF=\"mailto:\\1\">\\1</a>";
  $src[] = "/<A HREF=[^>]+>(<A HREF=[^>]+>)/i";
  $tar[] = "\\1";
  $src[] = "/<\/A><\/A>/i";
  $tar[] = "</A>";

  # 보호를 위해 치환한 것들을 복구
  $src[] = "/!(quot|gt|lt)/i";
  $tar[] = "&\\1";
  $src[] = "/(http|https|ftp|telnet|news|mms)_orig/i";
  $tar[] = "\\1";
  $src[] = "'#-#'";
  $tar[] = "@";
  $src[] = "/$regex[file]/i";
  $tar[] = "\\1";

  # email 주소를 변형시킴
  $src[] = "/$regex[mail]/i";
  $tar[] = "\\1 at \\2";
  $src[] = "/<A HREF=\"mailto:([^ ]+) at ([^\">]+)/i";
  $tar[] = "<A HREF=\"act.php?o[at]=ma&target=\\1__at__\\2";

  # email 주소를 변형한 뒤 URL 속의 @ 을 복구
  $src[] = "/_HTTPAT_/";
  $tar[] = "@";

  # 이미지에 보더값 0 을 삽입
  $src[] = "/<(IMG SRC=\"[^\"]+\")>/i";
  $tar[] = "<\\1 BORDER=0>";

  # IE 가 아닌 경우 embed tag 를 삭제함
  if($agent[br] != "MSIE") {
    $src[] = "/<embed/i";
    $tar[] = "&lt;embed";
  }

  $str = preg_replace($src,$tar,$str);
  return $str;
}

# Email 링크를 만들기 위한 함수
function url_link($url, $str, $color, $no = 0) {
  global $table, $board, $rmail;

  if(check_email($url) && $rmail[uses] = "yes") {
    $url = str_replace("@","__at__",$url);
    $str = "<A HREF=./act.php?o[at]=ma&target=$url>$str</A>";
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
function file_upload($fn,$updir) {
  global $HTTP_POST_FILES, $upload, $langs, $table;

  $ufile[name] = $HTTP_POST_FILES[$fn][name];
  $ufile[size] = $HTTP_POST_FILES[$fn][size];
  $ufile[type] = $HTTP_POST_FILES[$fn][type];
  $ufile[tmp_name] = $HTTP_POST_FILES[$fn][tmp_name];

  if(is_uploaded_file($ufile[tmp_name])) {
    if ($ufile[size] > $upload[maxsize]) {
      print_error($langs[act_md]);
      exit;
    }

    # file name에 공백이 있을 경우 공백 삭제
    $ufile[name] = preg_replace("/ /","",$ufile[name]);

    # file name에 특수 문자가 있을 경우 등록 거부
    upload_name_chk($ufile[name]);

    # php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
    $f[name] = eregi_replace("[.]*$","",$f[name]);
    $f[name] = eregi_replace(".(ph|inc|php[0-9a-z]*|phtml)$",".phps", $f[name]);
    $f[name] = eregi_replace("(.*)\.(cgi|pl|sh|html|htm|shtml|vbs)$", "\\1_\\2.phps", $f[name]);

    mkdir("data/$table/$upload[dir]/$updir",0755);
    move_uploaded_file($ufile[tmp_name],"data/$table/$upload[dir]/$updir/$ufile[name]");
    chmod("data/$table/$upload[dir]/$updir/$ufile[name]",0644);

    $up = 1;
  } elseif($ufile[name]) {
    if($ufile[size] == '0') {
      print_error($langs[act_ud]);
    } else {
      print_error($langs[act_ed]);
    }
    exit;
  }

  if($up) return $ufile;
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
