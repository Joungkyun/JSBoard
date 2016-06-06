<?php
# $Id: parse.php,v 1.21 2014/03/02 17:11:31 oops Exp $

# // {{{ +-- public ugly_han($text,$html=0)
# html사용을 안할 경우 IE에서 문법에 맞지 않는 글자 표현시 깨지는 것을 수정
function ugly_han($text,$html=0) {
  if (!$html) $text = preg_replace("/&amp;(#|amp)/i","&\\1",$text);
  else $text = str_replace("&amp;","&",$text);
  return $text;
}
// }}}

# // {{{ +-- public search2url($o, $method = "get")
# 검색 폼에서 넘어온 값을 URL로 바꿔줌 (POST -> GET 전환)
#
# trim         - 문자열 양쪽의 공백 문자를 없앰
#                http://www.php.net/manual/function.trim.php
# rawurlencode - RFC1738에 맞게 URL을 암호화
#                http://www.php.net/manual/function.rawurlencode.php
function search2url($o, $method = "get") {
  if($o['at'] != "s" && $o['at'] != "d") return;

  $str = trim($o['ss']);
  $str = stripslashes($str);

  unset($o['go']);

  for($i = 0; $i < count($o); $i++) {
    $key   = key($o);
    $value = current($o);

    if($method == "get") {
      $value = rawurlencode($value);
      $url  .= "&amp;o[$key]=$value";
    } else $url  .= "\n<INPUT TYPE=\"hidden\" NAME=\"o[$key]\" VALUE=\"$value\">";

    next($o);
  }

  $url = preg_replace("/(%5C)%5C/i","\\1",$url);
  return $url;
}
// }}}

# // {{{ +-- public search2sql($o, $wh = 1, $join = 0)
# 검색폼에서 넘어온 값을 SQL 질의문으로 바꿈
#
# trim         - 문자열 양쪽의 공백 문자를 없앰
#                http://www.php.net/manual/function.trim.php
# rawurldecode - 암호화된 URL를 복호화
#                http://www.php.net/manual/function.rawurldecode.php
function search2sql($o, $wh = 1, $join = 0) {
  global $langs;
  if($o['at'] != "s" && $o['at'] != "d") return;

  $str = rawurldecode($o['ss']); # 검색 문자열을 복호화
  $str = trim($str);
  $join = $join ? "tb." : "";

  if(strlen(stripslashes($str)) < 3 && !$o['op']) {
    if($o['sc'] != "r" && $o['st'] != "t")
      print_error($langs['nsearch'],250,150,1);
  }

  if(!$o['er']) {
    # %는 SQL 질의에서 And 연산으로 쓰이므로 \를 붙여서 일반 문자임을 나타냄
    $str = str_replace("%","\%",$str);
    if($o['at'] != "d") {
      # \%\%를 and 연산으로 간주하여 %로 수정
      $str = str_replace("\%\%","%",$str);
    }
    $str = addslashes($str);

    if (preg_match("/[\"']/",$str)) print_error($langs['nochar'],250,150,1);
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

  if($o['at'] == "d") {
    # 검색 연산자에 의해 검색어 분리
    $src = array("/\\\\\\\\/i","/\\\\\+/i","/\\\\\-/i","/\+/i","/\-/i");
    $tar = array("\\","!pluschar!","!minuschar!","!explode!p!","!explode!m!");
    $strs = preg_replace($src,$tar,$str);
    $strs = str_replace("!pluschar!","+",$strs);
    $strs = str_replace("!minuschar!","-",$strs);
    $strs = explode("!explode",$strs);

    for($i=0;$i<sizeof($strs);$i++) {
      $lenchk = strlen(trim(preg_replace("/!(m|p)!/i","",$strs[$i])));
      if($lenchk < 3) print_error($langs['nsearch'],250,150,1);
    }
  }

  $sql = $wh ? "WHERE " : "AND ";

  if($o['at'] != "d") {
    $today = get_date();
    $month = $today - (60 * 60 * 24 * 30);
    $week  = $today - (60 * 60 * 24 * 7);

    switch($o['st']) {
      case 't': $sql .= "({$join}date >= $today)";
        break; # 오늘
      case 'w': $sql .= "({$join}date >= $week) AND ";
        break; # 일주일간
      case 'm': $sql .= "({$join}date >= $month) AND ";
        break; # 한달간
    }
  } else {
    $startday = mktime(0,0,0,$o['m1'],$o['d1'],$o['y1']);
    $endday = mktime(23,59,59,$o['m2'],$o['d2'],$o['y2']);
    $sql .= "({$join}date BETWEEN $startday AND $endday) AND ";
  }

  if($o['at'] != "d") {
    if($o['er']) $str = "REGEXP \"$str\"";
    else $str = "LIKE \"%$str%\"";

    switch($o['sc']) {
      case 'a': $sql .= "({$join}title $str OR {$join}text $str OR {$join}name $str)";
        break;
      case 'c': $sql .= "({$join}text $str)";
        break;
      case 'n': $sql .= "({$join}name $str)";
        break;
      case 't': $sql .= "({$join}title $str)";
        break;
      case 'r': $sql .= "({$join}no = {$o['no']} OR {$join}reto = {$o['no']})";
        break;
    }
  } else {
    $likeregex = $o['er'] ? "REGEXP" : "LIKE";
    $pchar = !$o['er'] ? "%" : "";
    switch($o['sc']) {
      case 'a':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltitle .= "{$join}title $strs[$i]";
          $sqltext .= "{$join}text $strs[$i]";
          $sqlname .= "{$join}name $strs[$i]";
        }
        $sql .= "(($sqltitle) OR ($sqltext) OR ($sqlname))";
        break;
      case 'c':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltext .= "{$join}text $strs[$i]";
        }
        $sql .= "($sqltext)";
        break;
      case 'n':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqlname .= "{$join}name $strs[$i]";
        }
        $sql .= "($sqlname)";
        break;
      case 't':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltitle .= "{$join}title $strs[$i]";
        }
        $sql .= "($sqltitle)";
        break;
    }

    $sql = preg_replace("/((tb\.)?(title|name|text) (LIKE|REGEXP) \"%?)!p![ ]*/i"," AND \\1",$sql);
    $sql = preg_replace("/((tb\.)?(title|name|text) (LIKE|REGEXP) \"%?)!m![ ]*/i"," OR \\1",$sql);
  }

  return $sql;
}
// }}}

# // {{{ +-- public search_hl($list)
# 검색 문자열 하이라이팅 함수
#
function search_hl($list) {
  global $board ,$o;

  $hl = explode("STR", $board['hl']);
  if(!$o['ss']) return $list;

  $str = rawurldecode($o['ss']);
  $str = trim($str);
  $str = stripslashes($str);

  # 정규 표현식: 검색어가 "[,("로 시작했지만 "],)"로 닫지 않은 경우 체크
  if ($o['er']) {
    $chk = preg_replace("/\\\([\]\[()])/i","",$str);
    $chk = preg_replace("/[^\[\]()]/i","",$chk);

    $chkAOpen = strlen(preg_replace("/\]/i","",$chk));
    $chkAClos = strlen(preg_replace("/\[/i","",$chk));
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk));
    $chkBClos = strlen(preg_replace("/\(/i","",$chk));

    if($chkAOpen !== $chkAClos) {
       $str .= "]";
       $o['ss'] .= "]";
    } elseif($chkBOpen !== $chkBClos) {
       $str .= ")";
       $o['ss'] .= ")";
    }
  }

  # regex 에서 충돌되는 문자 escape 처리
  $dead = array("/\?|\)|\(|\*|\.|\^|\+|\%/i");
  $live = array("\\\\\\0");
  $str = preg_replace($dead,$live,$str);

  if($o['at'] != "d") {
    # %% 검색시 필요 조건
    $strs = explode("%%",str_replace("/","\/",$str));
  } else {
    $src = array("/\\\\\\\\/i","/\\\\\+/i","/\\\\\-/i","/\+/i","/\-/i","/\//i");
    $tar = array("\\","!pluschar!","!minuschar!","!explode!","!explode!","\/");
    $strs = preg_replace($src,$tar,$str);
    $strs = str_replace("!pluschar!","+",$strs);
    $strs = str_replace("!minuschar!","-",$strs);
    $strs = explode("!explode!",$strs);
  }

  $regex1 = "(<\/?)<FONT[^>]+>([^<]+)<\/FONT>([^>]*>)";
  $regex2 = "(<\/?FONT[^<>]+)<FONT[^>]+>([^<]+)<\/FONT>([^>]*>)";
  $regex3 = "(HREF|SRC)=([^<>]*){$hl[0]}([^<]*)<\/FONT>([^>]*)";

  $src = array("/$regex1/i","/$regex2/i");
  $tar = array("\\1\\2\\3","\\1\\2\\3");
  $tsrc = array("/$regex1/i","/$regex2/i","/$regex3/i");
  $ttar = array("\\1\\2\\3","\\1\\2\\3","\\1=\\2\\3\\4");

  if(!$o['er']) $str = checkquote($str);

  switch($o['sc']) {
    case 'n':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim(checkquote($strs[$i]));
        $list['name'] = preg_replace("/{$strs[$i]}/i","{$hl[0]}\\0{$hl[1]}",$list['name']);
        $list['name'] = preg_replace("/$regex2/i","\\1\\2\\3",$list['name']);
      }
      break;
    case 't':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim(checkquote($strs[$i]));
        $list['title'] = preg_replace("/{$strs[$i]}/i","{$hl[0]}\\0{$hl[1]}",$list['title']);
        $list['title'] = preg_replace($src,$tar,$list['title']);
      }
      break;
    case 'c':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim(checkquote($strs[$i]));
        $list['text'] = preg_replace("/{$strs[$i]}/i", "{$hl[0]}\\0{$hl[1]}", $list['text']);
        while(true) {
          if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list['text']))
            $list['text'] = preg_replace($tsrc,$ttar,$list['text']);
          else break;
        }
      }
      break;
    case 'a':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim(checkquote($strs[$i]));
        $list['name'] = preg_replace("/{$strs[$i]}/i","{$hl[0]}\\0{$hl[1]}",$list['name']);
        $list['name'] = preg_replace($src,$tar,$list['name']);

        $list['text'] = preg_replace("/{$strs[$i]}/i", "{$hl[0]}\\0{$hl[1]}", $list['text']);
        while(true) {
          if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list['text']))
            $list['text'] = preg_replace($tsrc,$ttar,$list['text']);
          else break;
        }

        $list['title'] = preg_replace("/{$strs[$i]}/i", "{$hl[0]}\\0{$hl[1]}", $list['title']);
        $list['title'] = preg_replace($src,$tar,$list['title']);
      }
      break;
  }

  return $list;
}
// }}}

// {{{ +-- public quote_len ($buf)
function quote_len ($buf) {
  $no = 0;
  preg_match_all ('/\[\/?quote[^\]]+\]/', $buf, $matches);
  foreach ($matches[0] as $v)
    $no += strlen (trim ($v));
  return $no;
}
// }}}

// {{{ +-- public wordwrap_js (&$buf, $len = 80)
function wordwrap_js (&$buf, $len = 80) {
  $tol = &$GLOBALS['langs']['code'];
  $buf = unhtmlspecialchars ($buf);

  if ($tol == 'ko') {
    if (($ss = iconv ('utf-8', 'cp949', $buf)) !== false)
      $buf = $ss;
  }

  $_buf = split ("\r?\n", $buf);
  $size = count ($_buf);
  $buf = '';

  for ( $i=0; $i<$size; $i++ ) {
    $_buf[$i] = rtrim ($_buf[$i]);
    $_bufs = preg_replace ('/\[(\/)?quote[^\]]*\]/i', '', $_buf[$i]);
    $_bufs_size = strlen ($_bufs);

    if ( $_bufs_size > $len ) {
      if ( ord ($_bufs[$len - 1]) & 0x80 ) {
        $z = strlen(preg_replace ('/[\x00-\x7F]/', '', substr ($_bufs, 0, $len)));
        $cut = ( $z % 2 ) ? $len - 1 : $len;
      } else
        $cut = $len;

      $_bufs = substr ($_buf[$i], 0, $cut);
      $_bufsno = quote_len ($_bufs);
      $cut += $_bufsno;
      $buf .= substr ($_buf[$i], 0, $cut) . "\n";

      if ( preg_match ('/^(: )+/', $_buf[$i], $matches) ) {
        $next = $matches[0] . substr ($_buf[$i], $cut);
        if ( ! strncmp ($matches[0], $_buf[$i+1], strlen ($matches[0])) )
          $_buf[$i+1] = $next . ' ' .  preg_replace ('/^(: )+/', '', $_buf[$i+1]);
        else
          $buf .= $next . "\n";
      } else {
        if ( preg_match ('/^[\s]*$/', $_buf[$i+1]) ) {
          $next = substr ($_buf[$i], $cut);
          if ( strlen ($next) > $len ) {
            $_buf[$i] = $next;
            $i--;
          } else
            $buf .= $next . "\n";
        } else
          $_buf[$i+1] = substr ($_buf[$i], $cut) . ' ' . $_buf[$i+1];
      }
    } else
      $buf .= $_buf[$i] . "\n";
  }
  $buf = htmlspecialchars ($buf, ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');

  if ($tol == 'ko') {
    if (($ss = iconv ('cp949', 'utf-8', $buf)) !== false)
      $buf = $ss;
  }
}
// }}}

// {{{ +-- public js_htmlcode(&$buf)
function js_htmlcode(&$buf) {
  global $enable, $agent;

  if(!is_object($enable['tag']))
    return;

  foreach($enable['tag'] as $v) {
    if($v == 'code')
      continue;
    $reg .= $v . '|';
  }
  $reg = preg_replace ('/\|$/', '', $reg);
  $reg = "!\[(/?({$reg}))\]!i";

  $buf = preg_replace ($reg, '<\\1>', $buf);
  unset($reg);
  $reg[] = "/\[code\][\r\n]*/i";
  $reg[] = "/[\r\n]*\[\/code\]/i";
  $reg[] = '/^[: ]*: <li/m';
  if ( $agent['br'] == "MSIE" || $agent['tx'] ) {
    $conv[] = '<div class="jsCodeBlock"><pre>';
    $conv[] = '<pre></div>';
  } else {
    $conv[] = '\\1<div class="jsCodeBlock" style="white-space: pre;">';
    $conv[] = '</div>';
  }
  $conv[] = '<li';
  $buf = preg_replace ($reg, $conv, $buf);
}
// }}}

// {{{ +-- public new_read_format(&$buf)
function new_read_format(&$buf) {
  global $enable, $board;

  $buf = preg_replace ("/\r?\n/", "\n", $buf);
  if(!is_object($enable['tag']))
    return;

  $req_code = array ('[code]', '[table]', '[ul]', '[ol]');
  foreach ($enable['tag'] as $v) {
    $v = trim($v);
    switch ($v) {
      case 'code' :
      case 'table' :
      case 'ul' :
      case 'ol' :
        break;
      default :
        if (preg_match('/:$/', $v))
          $req_code[] = '[' . $v . ']';
    }
  }

  $buf_r = block_devided($buf, $req_code);

  if(!is_array($buf_r) || count($buf_r) < 1) {
    $buf = "<pre>{$buf}</pre>";
    return;
  }

  $buf = '';
  foreach($buf_r as $v) {
    $block = false;
    if(preg_match('/^[:\s]*(\[[^\]]+\])/', $v, $matches))
      if(array_search($matches[1], $req_code) !== FALSE)
        $block = true;

    if(!$block) {
      wordwrap_js($v, $board['wwrap']);
      $v = preg_replace ("/\n$/", '', $v);
      $buf .= "<pre>{$v}</pre>";
    } else
      $buf .= $v;
  }
  js_htmlcode ($buf);
}
// }}}

// {{{ +-- public text_nl2br(&$text, $html)
function text_nl2br(&$text, $html) {
  global $langs;
  if($html == 1) {
    $source = array("/<(\?|%)/i","/(\?|%)>/i","/<img .*src=[a-z0-9\"']*script:[^>]+>/i","/\r\n/",
                    "/<(\/*(script|style|pre|xmp|xml|base|span|html)[^>]*)>/i","/(=[0-9]+%)&gt;/i");
    $target = array("&lt;\\1","\\1&gt;","","\n","&lt;\\1&gt;","\\1>");
    $text = preg_replace($source,$target,$text);
    if(!preg_match("/<--no-autolink>/i",$text)) $text = auto_link($text);
    else $text = chop(str_replace("<--no-autolink>","",$text));

    $text = preg_replace("/(\n)?<table/i","</PRE><TABLE",$text);
    $text = preg_replace("/<\/table>(\n)?/i","</TABLE><PRE>",$text);
    $text = !$text ? "No Contents" : $text;
    $text = "<pre>$text</pre>";
  } else {
    $text = htmlspecialchars($text);
    # 한글 깨지는것 보정
    if ($langs['code'] == "ko") $text = ugly_han($text);
    if ($html)
      new_read_format($text);
    else
      $text = "<pre>\n$text\n</pre>";
    $text = auto_link($text);
  }
}
// }}}

// {{{ +-- public delete_tag(&$var)
function delete_tag(&$var) {
  if ( $var['html'] != 1 )
    return;

  $src = array("/\n/i","/<html.*<body[^>]*>/i","/<\/body.*<\/html>.*/i",
               "/<\/*(div|span|layer|body|html|head|meta|input|select|option|form)[^>]*>/i",
               "/<(style|script|title).*<\/(style|script|title)>/i",
               "/<\/*(script|style|title|xmp|xml)>/i","/<(\\?|%)/i","/(\\?|%)>/i","/(=[0-9]+%)&gt;/i",
               "/#\^--ENTER--\^#/i");
  $tar = array("#^--ENTER--^#","","","","","","&lt;\\1","\\1&gt;","\\1>","\n");

  $var['text'] = chop(preg_replace($src,$tar,$var['text']));
}
// }}}

# // {{{ +-- public cut_string($s,$l)
# 문자열을 일정한 길이로 자르는 함수
#
# substr - 문자열의 지정된 범위를 잘라서 가져옴
#          utf-8의 경우 3byte이기 때문에 영어가 섞여 있을
#          경우 글자수 길이 처리가 조금 곤란하므로 euc-kr
#          로 전환해서 자르는 꽁수를 부린다.
#          http://www.php.net/manual/function.substr.php
function cut_string($s,$l) {
  $tol = &$GLOBALS['langs']['code'];
  if ($tol == 'ko') {
    if (($ss = iconv ('utf-8', 'cp949', $s)) !== false)
      $s = $ss;
  }

  if(strlen($s) > $l) {
    $s = substr($s,0,$l);
    if ($tol)
      $s = preg_replace("/(([\x80-\xFE].)*)[\x80-\xFE]?$/","\\1",$s);
  }

  if ($tol == 'ko') {
    if (($ss = iconv ('cp949', 'utf-8', $s)) !== false)
      $s = $ss;
  }
  return $s;
}
// }}}

# // {{{ +-- public auto_link($str)
# 문서 내용에 있는 URL들을 찾아내어 자동으로 링크를 구성해주는 함수
#
# preg_replace  - 펄 형식의 정규표현식을 이용한 치환
#                 http://www.php.net/manual/function.preg-replace.php
function auto_link($str) {
  global $agent,$rmail,$print;

  $uhan = '\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}';
  $ehan = '\xA1-\xFE';
  $regex['file'] = 'gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov';
  $regex['file'] = "(\.({$regex['file']})\") TARGET=\"_blank\"";
  $regex['http'] = "(http|https|ftp|telnet|news|mms):\/\/(([{$uhan}a-z0-9:_\-]+\.[{$uhan}a-z0-9,:;&#=_~%\[\]?\/.,+\-]+)([.]*[\/a-z0-9\[\]]|=[{$uhan}]+))";
  $regex['mail'] = "([{$uhan}a-z0-9_.-]+)@([{$uhan}a-z0-9_-]+\.[{$uhan}a-z0-9._-]*[a-z]{2,3}(\?[{$uhan}a-z0-9=&\?]+)*)";

  # &lt; 로 시작해서 3줄뒤에 &gt; 가 나올 경우와
  # IMG tag 와 A tag 의 경우 링크가 여러줄에 걸쳐 이루어져 있을 경우
  # 이를 한줄로 합침 (합치면서 부가 옵션들은 모두 삭제함)
  $src[] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i";
  $tar[] = "<\\1\\2\\3>";
  $src[] = "/<([^<>\n]*)\n([^\n<>]*)>/i";
  $tar[] = "<\\1\\2>";
  $src[] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*({$regex['http']}|mailto:{$regex['mail']})[^>]*>/ui";
  $tar[] = "<\\1 \\2=\"\\3\">";

  # email 형식이나 URL 에 포함될 경우 URL 보호를 위해 @ 을 치환
  $src[] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i";
  $tar[] = "\\1://\\2_HTTPAT_\\3";

  # 특수 문자를 치환 및 html사용시 link 보호
  $src[] = "/&(quot|gt|lt)/i";
  $tar[] = "!\\1";
  $src[] = "/<a([^>]*)href=[\"' ]*({$regex['http']})[\"']*[^>]*>/ui";
  $tar[] = "<A\\1href=\"\\3_orig://\\4\" TARGET=\"_blank\">";
  $src[] = "/href=[\"' ]*mailto:({$regex['mail']})[\"']*>/ui";
  $tar[] = "href=\"mailto:\\2#-#\\3\">";
  $src[] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*({$regex['http']})[\"']*/ui";
  $tar[] = "<\\1\\2=\"\\4_orig://\\5\"";

  # 링크가 안된 url및 email address 자동링크
  $src[] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)({$regex['http']})/ui";
  $tar[] = "\\1<a href=\"\\3\" target=\"_blank\">\\3</a>";
  $src[] = "/({$regex['mail']})/ui";
  $tar[] = "<a href=\"mailto:\\1\">\\1</a>";
  $src[] = "/<a href=[^>]+>(<a href=[^>]+>)/i";
  $tar[] = "\\1";
  $src[] = "/<\/a><\/a>/i";
  $tar[] = "</a>";

  # 보호를 위해 치환한 것들을 복구
  $src[] = "/!(quot|gt|lt)/i";
  $tar[] = "&\\1";
  $src[] = "/(http|https|ftp|telnet|news|mms)_orig/i";
  $tar[] = "\\1";
  $src[] = "'#-#'";
  $tar[] = "@";
  $src[] = "/{$regex['file']}/ui";
  $tar[] = "\\1";

  # email 주소를 변형시킴
  $src[] = "/mailto:[ ]*{$regex['mail']}/ui";
  $tar[] = "javascript:sendform('\\1','\\2','');";
  $src[] = "/{$regex['mail']}/ui";
  #$tar[] = "\\1<img src=\"./images/at.gif\" width=9 height=13 border=0 alt='at'>\\2";
  #$tar[] = "\\1<img src=\"./theme/{$print['theme']}/img/at.gif\" width=9 height=13 border=0 alt='at'>\\2";
  $tar[] = "\\1&#0064;\\2";
  $src[] = "/<</";
  $tar[] = "&lt;<";
  $src[] = "/>>/";
  $tar[] = ">&gt;";

  # email 주소를 변형한 뒤 URL 속의 @ 을 복구
  $src[] = "/_HTTPAT_/";
  $tar[] = "@";

  # 이미지에 보더값 0 을 삽입
  $src[] = "/<(img src=\"[^\"]+\")>/i";
  $tar[] = "<\\1 border=0>";

  # IE 가 아닌 경우 embed tag 를 삭제함
  if($agent['br'] != "MSIE" && $agent['br'] != 'Firefox') {
    $src[] = "/<embed/i";
    $tar[] = "&lt;embed";
  }

  $str = preg_replace($src,$tar,$str);
  return $str;
}
// }}}

# // {{{ +-- public url_link($url, $str = "", $no = 0)
# Email 링크를 만들기 위한 함수
function url_link($url, $str = "", $no = 0) {
  global $table, $board, $rmail, $o, $agent;
  $str = $str ? $str : $url;

  if($agent['br'] == "MSIE") {
    $mailTarget = " Target=noPage";
    $mailFrame = "<IFRAME NAME=\"noPage\" SRC=\"\" STYLE=\"display:none;\"></IFRAME>";
  }

  if(check_email($url) && $rmail['uses']) {
    if(preg_match("/^s|d$/i",$o['at']) && ($o['sc'] == "n" || $o['sc'] == "a")) {
      $strs = preg_replace("/<[^>]+>/i","",$str);
    } else $strs = $str;
    $strs = str_replace("'", "\'", $strs);

    $url = str_replace("@",$rmail['chars'],$url);
    $str = "<A HREF=./act.php?o[at]=ma&amp;target=$url ".
           "onMouseOut=\"window.status=''; return true;\" ".
           "onMouseOver=\"window.status='Send mail to $strs'; return true;\"{$mailTarget}>$str</A>{$mailFrame}";
  } else if(check_url($url)) {
    $str = "<A HREF=\"$url\" target=\"_blank\">$str</A>";
  } else {
    if($str == $url) $str = "";
    $str = "$str";
  }

  return $str;
}
// }}}

# // {{{ +-- public file_upload($fn,$updir)
# File upload를 위한 함수
#
#
# mkdir            -> directory 생성
# is_upload_file   -> upload file의 존재성 여부
# move_upload_file -> tmp로 upload되어 있는 파일을 원하는 디레토리에 위치
# chmod            -> file, direcoty의 권한 변경
#
function file_upload($fn,$updir) {
  global $upload, $langs, $table;

  $ufile['name'] = $_FILES[$fn]['name'];
  $ufile['size'] = $_FILES[$fn]['size'];
  $ufile['type'] = $_FILES[$fn]['type'];
  $ufile['tmp_name'] = $_FILES[$fn]['tmp_name'];

  if(is_uploaded_file($ufile['tmp_name']) && $ufile['size'] > 0) {
    if ($ufile['size'] > $upload['maxsize']) {
      print_error($langs['act_md'],250,150,1);
      exit;
    }

    # file name에 공백이 있을 경우 공백 삭제
    $ufile['name'] = str_replace(" ","",urldecode($ufile['name']));

    # file name에 특수 문자가 있을 경우 등록 거부
    upload_name_chk($ufile['name']);

    # php, cgi, pl file을 upload할시에는 실행을 할수없게 phps, cgis, pls로 filename을 수정
    $_parseName = explode ('.', $ufile['name']);
    $_parsePart = count ($_parseName);

    $ufile['name'] = '';
    for ( $i=0; $i<$_parsePart; $i++ ) {
      $_sep = ( $i && $i != $_parsePart - 1 ) ? '_' : '.';
      if ( ! $i ) $_sep = "";
      $ufile['name'] .= "$_sep{$_parseName[$i]}";
    }

    $fcname[0] = "/\.*$/";
    $fvname[0] = "";
    $fcname[1] = "/\.(ph|inc|php[0-9a-z]*|phtml)$/i";
    $fvname[1] = ".phps";
    $fcname[2] = "/(.*)\.(cgi|pl|sh|html|htm|shtml|vbs)$/i";
    $fvname[2] = "\\1_\\2.phps";
    $fcname[3] = "/(_tar)\.(gz|bz2)$/i";
    $fvname[3] = ".\\1.\\2";

    $ufile['name'] = preg_replace($fcname, $fvname, $ufile['name']);

    mkdir("data/$table/{$upload['dir']}/$updir",0755);
    move_uploaded_file($ufile['tmp_name'],"data/$table/{$upload['dir']}/$updir/".$ufile['name']);
    chmod("data/$table/{$upload['dir']}/$updir/{$ufile['name']}",0644);

    $up = 1;
  } elseif($ufile['name']) {
    if($ufile['size'] == '0') {
      print_error($langs['act_ud'],250,150,1);
    } else {
      print_error($langs['act_ed'],250,150,1);
    }
    exit;
  }
  if($up) return $ufile;
}
// }}}

# // {{{ +-- public unhtmlspecialchars($t)
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
// }}}

# // {{{ +-- public conv_emoticon(&$str, $opt=0)
# Emoticon 변환 함수
function conv_emoticon(&$str, $opt=0) {
  if (!$opt) return $str;

  $src[] = "/\^\^|\^\.\^/";
  $con[] = "<IMG SRC=./emoticon/icon1.gif BORDER=0 ALT='emoticon'>";
  $src[] = '/([^0-9a-z])T\.T([^0-9a-z])/i';
  $con[] = "\\1<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/\?\.\?/';
  $con[] = "<IMG SRC=./emoticon/icon3.gif BORDER=0 ALT='emoticon'>";
  $src[] = '/([^0-9a-z]):-?(\(|<)([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon4.gif BORDER=0 ALT='emoticon'>\\3";
  $src[] = '/([^0-9a-z])(:-?(\)|>)|n\.n)([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon5.gif BORDER=0 ALT='emoticon'>\\4";
  $src[] = '/([^0-9])0\.0([^0-9])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon6.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/([^0-9a-z])O\.O([^0-9a-z])/i';
  $con[] = "\\1<IMG SRC=./emoticon/icon6.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/-\.?-V/';
  $con[] = "<IMG SRC=./emoticon/icon7.gif BORDER=0 ALT='emoticon'>";
  $src[] = '/([^0-9a-z])(-_-|-\.-)([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon8.gif BORDER=0 ALT='emoticon'>\\3";
  $src[] = '/-0-|^0^|-O-|^O^/';
  $con[] = "<IMG SRC=./emoticon/icon9.gif BORDER=0 ALT='emoticon'>";
  $src[] = '/([^0-9a-z]):-?D([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon10.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/([^0-9a-z]);-?\)([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon11.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/([^0-9a-z])\^_\^([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon12.gif BORDER=0 ALT='emoticon'>\\2";
  $src[] = '/([^0-9a-z]):-P|:P([^0-9a-z])/';
  $con[] = "\\1<IMG SRC=./emoticon/icon14.gif BORDER=0 ALT='emoticon'>\\2";

  $str = preg_replace($src, $con, $str);
  $str = str_replace("ㅜ.ㅜ", "<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>", $str);
  $str = str_replace("ㅠ.ㅠ", "<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>", $str);
  $str = str_replace("ㅠ_ㅠ", "<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>", $str);
  $str = str_replace("ㅜㅜ", "<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>", $str);
  $str = str_replace("ㅠㅠ", "<IMG SRC=./emoticon/icon2.gif BORDER=0 ALT='emoticon'>", $str);
}
// }}}

// {{{ +-- public checkquote ( $str )
function checkquote ( $str ) {
  $str = preg_quote ($str);
  $str = str_replace ("\\\\/", "\\/", $str);

  return $str;
}
// }}}

// {{{ +-- public parse_referer (void)
function parse_referer () {
  $referer = parse_url ($_SERVER['HTTP_REFERER']);
  $referer['basename'] = basename ($referer['path']);

  if ( ! is_array ($referer) )
    return;

  parse_str ($referer['query'], $ref);

  if ( ! is_array ($ref) )
    return;

  return array_merge ($ref, $referer);
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
