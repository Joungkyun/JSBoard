<?
# html����� ���� ��� IE���� ������ ���� �ʴ� ���� ǥ���� ������ ���� ����
function ugly_han($text,$html=0) {
  if (!$html) $text = eregi_replace("&amp;(#|amp)","&\\1",$text);
  $text = str_replace("&amp;","&",$text);
  return $text;
}

# �˻� ������ �Ѿ�� ���� URL�� �ٲ��� (POST -> GET ��ȯ)
#
# trim         - ���ڿ� ������ ���� ���ڸ� ����
#                http://www.php.net/manual/function.trim.php
# rawurlencode - RFC1738�� �°� URL�� ��ȣȭ
#                http://www.php.net/manual/function.rawurlencode.php
function search2url($o, $method = "get") {
  if($o[at] != "s" && $o[at] != "d") return;

  $str = trim($o[ss]);
  $str = stripslashes($str);

  unset($o[go]);

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

# �˻������� �Ѿ�� ���� SQL ���ǹ����� �ٲ�
#
# trim         - ���ڿ� ������ ���� ���ڸ� ����
#                http://www.php.net/manual/function.trim.php
# rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
#                http://www.php.net/manual/function.rawurldecode.php
function search2sql($o, $wh = 1) {
  global $langs;
  if($o[at] != "s" && $o[at] != "d") return;

  $str = rawurldecode($o[ss]); # �˻� ���ڿ��� ��ȣȭ
  $str = trim($str);

  if(strlen(stripslashes($str)) < 3 && !$o[op]) {
    if($o[sc] != "r" && $o[st] != "t")
      print_error($langs[nsearch],250,150,1);
  }

  if(!$o[er]) {
    # %�� SQL ���ǿ��� And �������� ���̹Ƿ� \�� �ٿ��� �Ϲ� �������� ��Ÿ��
    $str = str_replace("%","\%",$str);
    if($o[at] != "d") {
      # \%\%�� and �������� �����Ͽ� %�� ����
      $str = str_replace("\%\%","%",$str);
    }
    $str = addslashes($str);

    if (eregi("\"",$str)) print_error($langs[nochar],250,150,1);
  }


  if($o[at] == "d") {
    # �˻� �����ڿ� ���� �˻��� �и�
    $src = array("/\\\\\\\\/i","/\\\\\+/i","/\\\\\-/i","/\+/i","/\-/i");
    $tar = array("\\","!pluschar!","!minuschar!","!explode!p!","!explode!m!");
    $strs = preg_replace($src,$tar,$str);
    $strs = str_replace("!pluschar!","+",$strs);
    $strs = str_replace("!minuschar!","-",$strs);
    $strs = explode("!explode",$strs);
  }

  $sql = $wh ? "WHERE " : "AND ";

  if($o[at] != "d") {
    $today = get_date();
    $month = $today - (60 * 60 * 24 * 30);
    $week  = $today - (60 * 60 * 24 * 7);

    switch($o[st]) {
      case 't': $sql .= "(date >= $today)";
        break; # ����
      case 'w': $sql .= "(date >= $week) AND ";
        break; # �����ϰ�
      case 'm': $sql .= "(date >= $month) AND ";
        break; # �Ѵް�
    }
  } else {
    $startday = mktime(0,0,0,$o[m1],$o[d1],$o[y1]);
    $endday = mktime(11,59,59,$o[m2],$o[d2],$o[y2]);
    $sql .= "(date BETWEEN $startday AND $endday) AND ";
  }

  if($o[at] != "d") {
    if($o[er]) $str = "REGEXP \"$str\"";
    else $str = "LIKE \"%$str%\"";

    switch($o[sc]) {
      case 'a': $sql .= "(title $str OR text $str OR name $str)";
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
  } else {
    $likeregex = $o[er] ? "REGEXP" : "LIKE";
    $pchar = !$o[er] ? "%" : "";
    switch($o[sc]) {
      case 'a':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltitle .= "title $strs[$i]";
          $sqltext .= "text $strs[$i]";
          $sqlname .= "name $strs[$i]";
        }
        $sql .= "(($sqltitle) OR ($sqltext) OR ($sqlname))";
        break;
      case 'c':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltext .= "text $strs[$i]";
        }
        $sql .= "($sqltext)";
        break;
      case 'n':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqlname .= "name $strs[$i]";
        }
        $sql .= "($sqlname)";
        break;
      case 't':
        for($i=0;$i<sizeof($strs);$i++) {
          $strs[$i] = "$likeregex \"$pchar".trim($strs[$i])."$pchar\"";
          $sqltitle .= "title $strs[$i]";
        }
        $sql .= "($sqltitle)";
        break;
    }

    $sql = eregi_replace("((title|name|text) (LIKE|REGEXP) \"%?)!p!"," AND \\1",$sql);
    $sql = eregi_replace("((title|name|text) (LIKE|REGEXP) \"%?)!m!"," OR \\1",$sql);
  }

  return $sql;
}

# �˻� ���ڿ� ���̶����� �Լ�
#
# explode      - ���� ���ڿ��� �������� ���ڿ��� ����
#                http://www.php.net/manual/function.explode.php
# rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
#                http://www.php.net/manual/function.rawurldecode.php
# trim         - ���ڿ� ������ ���� ���ڸ� ����
#                http://www.php.net/manual/function.trim.php
# stripslashes -
#                http://www.php.net/manual/function.stripslashes.php
# quotemeta    -
#                http://www.php.net/manual/function.quotemeta.php
function search_hl($list) {
  global $board ,$o;

  $hl = explode("STR", $board[hl]);
  if(!$o[ss]) return $list;

  $str = rawurldecode($o[ss]);
  $str = trim($str);
  $str = stripslashes($str);

  if($o[at] != "d") {
    # %% �˻��� �ʿ� ����
    $strs = explode("%%",str_replace("/","\/",$str));
  } else {
    $src = array("/\\\\\\\\/i","/\\\\\+/i","/\\\\\-/i","/\+/i","/\-/i");
    $tar = array("\\","!pluschar!","!minuschar!","!explode!","!explode!");
    $strs = preg_replace($src,$tar,$str);
    $strs = str_replace("!pluschar!","+",$strs);
    $strs = str_replace("!minuschar!","-",$strs);
    $strs = explode("!explode!",$strs);
  }
  $regex1 = "/(<\/?)<FONT[^>]+>([^<]+)<\/FONT>([^>]*>)/i";
  $regex2 = "/(<\/?FONT[^<>]+)<FONT[^>]+>([^<]+)<\/FONT>([^>]*>)/i";
  $regex3 = "/(HREF|SRC)=([^<]*)$hl[0]([^<]*)<\/FONT>([^>]*)/i";

  $src = array($regex1,$regex2);
  $tar = array("\\1\\2\\3","\\1\\2\\3");
  $tsrc = array($regex1,$regex2,$regex3);
  $ttar = array("\\1\\2\\3","\\1\\2\\3","\\1=\\2\\3\\4");

  if(!$o[er]) $str = quotemeta($str);

  switch($o[sc]) {
    case 'n':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[name] = preg_replace("/$strs[$i]/i","$hl[0]\\0$hl[1]",$list[name]);
        $list[name] = preg_replace($regex2,"\\1\\2\\3",$list[name]);
      }
      break;
    case 't':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[title] = preg_replace("/$strs[$i]/i","$hl[0]\\0$hl[1]",$list[title]);
        $list[title] = preg_replace($src,$tar,$list[title]);
      }
      break;
    case 'c':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[text] = preg_replace("/$strs[$i]/i", "$hl[0]\\0$hl[1]", $list[text]);
        $list[text] = preg_replace($tsrc,$ttar,$list[text]);
      }
      break;
    case 'a':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[name] = preg_replace("/$strs[$i]/i","$hl[0]\\0$hl[1]",$list[name]);
        $list[name] = preg_replace($src,$tar,$list[name]);

        $list[text] = preg_replace("/$strs[$i]/i", "$hl[0]\\0$hl[1]", $list[text]);
        $list[text] = preg_replace($tsrc,$ttar,$list[text]);

        $list[title] = preg_replace("/$strs[$i]/i", "$hl[0]\\0$hl[1]", $list[title]);
        $list[title] = preg_replace($src,$tar,$list[title]);
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
    if(!eregi("<--no-autolink>",$text)) $text = auto_link($text);
    else $text = chop(str_replace("<--no-autolink>","",$text));

    $text = eregi_replace("(\n)?<table","</PRE><TABLE",$text);
    $text = eregi_replace("</table>(\n)?","</TABLE><PRE>",$text);
    $text = !$text ? "No Contents" : $text;
    $text = "<PRE>$text</PRE>";
  } else {
    $text = htmlspecialchars($text);
    # �ѱ� �����°� ����
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

# ���ڿ��� ������ ���̷� �ڸ��� �Լ�
#
# �ѱ��� �ѹ���Ʈ ������ �߸��� ��츦 ���� �빮�ڰ� ���� ���� ���
# �ҹ��ڿ��� ũ�� ���� ����(1.5?)�� ���� ���ڿ��� �ڸ�
#
# intval - ������ ������ ���� ������
#          http://www.php.net/manual/function.intval.php
# substr - ���ڿ��� ������ ������ �߶� ������
#          http://www.php.net/manual/function.substr.php
# chop   - ���ڿ� ���� ���� ������ ����
#          http://www.php.net/manual/function.chop.php
function cut_string($s, $l) {
  if(strlen($s) <= $l && !eregi("^[a-z]+$", $s)) return $s; 
  for($i = $l; $i >=1; $i--) {
      # ���������� �ѱ� byte���� ����.
      if(is_hangul($s[$i-1])) $hangul++;
      else break;
  }
  
  if ($hangul) {
      # byte���� Ȧ���̸�, �ѱ��� ù��° ����Ʈ�̴�.
      # �ѱ��� ù��° ����Ʈ�� �� ������ ���� ���� ���� ������ ���̸� ��
      # ����Ʈ ����
      if ($hangul%2) $l--;
      
      $s = chop(substr($s, 0, $l));
  }
  else { # ���ڿ��� ���� �ѱ��� �ƴ� ���
      for($i = 1; $i <= $l; $i++) {
          # �빮���� ������ ���
          if(is_alpha($s[$i-1]) == 2) $alpha++;
          # ������ �ѱ��� ��Ÿ�� ��ġ ���
          if(is_hangul($s[$i-1])) $last_han=$i;
      }
      
      # ������ ���̷� ���ڿ��� �ڸ��� ���ڿ� ���� ���� ���ڸ� ������
      # �빮���� ���̴� 1.3���� ����Ѵ�. ���ڿ� �������� ���� ���ڿ��� 
      # ������ ��ü ���̺��� ũ�� �ʰ��� ��ŭ ����.
      $capitals = intval($alpha * 0.5);
      if ( ($l-$last_han) <= $capitals) $capitals=0;
      $s = chop(substr($s, 0, $l - $capitals));
  }

  return $s;
}

# ���� ���뿡 �ִ� URL���� ã�Ƴ��� �ڵ����� ��ũ�� �������ִ� �Լ�
#
# preg_replace  - �� ������ ����ǥ������ �̿��� ġȯ
#                 http://www.php.net/manual/function.preg-replace.php
function auto_link($str) {
  global $agent,$rmail;

  $regex[file] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
  $regex[file] = "(\.($regex[file])\") TARGET=\"_blank\"";
  $regex[http] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9:;&#=_~%\[\]\?\/\.\,\+\-]+)(\/|[\.]*[\xA1-\xFEa-z0-9\[\]]))";
  $regex[mail] = "([\xA1-\xFEa-z0-9_\.\-]+)@([\xA1-\xFEa-z0-9_\-]+\.[\xA1-\xFEa-z0-9\-\._\-]+[\.]*[a-z0-9]\??[\xA1-\xFEa-z0-9=]*)";

  # &lt; �� �����ؼ� 3�ٵڿ� &gt; �� ���� ����
  # IMG tag �� A tag �� ��� ��ũ�� �����ٿ� ���� �̷���� ���� ���
  # �̸� ���ٷ� ��ħ (��ġ�鼭 �ΰ� �ɼǵ��� ��� ������)
  $src[] = "/<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>/i";
  $tar[] = "<\\1\\2\\3>";
  $src[] = "/<([^<>\n]*)\n([^\n<>]*)>/i";
  $tar[] = "<\\1\\2>";
  $src[] = "/<(A|IMG)[^>]*(HREF|SRC)[^=]*=[ '\"\n]*($regex[http]|mailto:$regex[mail])[^>]*>/i";
  $tar[] = "<\\1 \\2=\"\\3\">";

  # email �����̳� URL �� ���Ե� ��� URL ��ȣ�� ���� @ �� ġȯ
  $src[] = "/(http|https|ftp|telnet|news|mms):\/\/([^ \n@]+)@/i";
  $tar[] = "\\1://\\2_HTTPAT_\\3";

  # Ư�� ���ڸ� ġȯ �� html���� link ��ȣ
  $src[] = "/&(quot|gt|lt)/i";
  $tar[] = "!\\1";
  $src[] = "/<a([^>]*)href=[\"' ]*($regex[http])[\"']*[^>]*>/i";
  $tar[] = "<A\\1HREF=\"\\3_orig://\\4\" TARGET=\"_blank\">";
  $src[] = "/href=[\"' ]*mailto:($regex[mail])[\"']*>/i";
  $tar[] = "HREF=\"mailto:\\2#-#\\3\">";
  $src[] = "/<([^>]*)(background|codebase|src)[ \n]*=[\n\"' ]*($regex[http])[\"']*/i";
  $tar[] = "<\\1\\2=\"\\4_orig://\\5\"";

  # ��ũ�� �ȵ� url�� email address �ڵ���ũ
  $src[] = "/(SRC=|HREF=|[^=]|^)($regex[http])/i";
  $tar[] = "\\1<A HREF=\"\\2\" TARGET=\"_blank\">\\2</a>";
  $src[] = "/($regex[mail])/i";
  $tar[] = "<A HREF=\"mailto:\\1\">\\1</a>";
  $src[] = "/<A HREF=[^>]+>(<A HREF=[^>]+>)/i";
  $tar[] = "\\1";
  $src[] = "/<\/A><\/A>/i";
  $tar[] = "</A>";

  # ��ȣ�� ���� ġȯ�� �͵��� ����
  $src[] = "/!(quot|gt|lt)/i";
  $tar[] = "&\\1";
  $src[] = "/(http|https|ftp|telnet|news|mms)_orig/i";
  $tar[] = "\\1";
  $src[] = "'#-#'";
  $tar[] = "@";
  $src[] = "/$regex[file]/i";
  $tar[] = "\\1";

  # email �ּҸ� ������Ŵ
  $src[] = "/$regex[mail]/i";
  $tar[] = "\\1 at \\2";
  $src[] = "/<A HREF=\"mailto:([^ ]+) at ([^\">]+)/i";
  $tar[] = "<A HREF=\"act.php?o[at]=ma&target=\\1$rmail[chars]\\2";

  # email �ּҸ� ������ �� URL ���� @ �� ����
  $src[] = "/_HTTPAT_/";
  $tar[] = "@";

  # �̹����� ������ 0 �� ����
  $src[] = "/<(IMG SRC=\"[^\"]+\")>/i";
  $tar[] = "<\\1 BORDER=0>";

  # IE �� �ƴ� ��� embed tag �� ������
  if($agent[br] != "MSIE") {
    $src[] = "/<embed/i";
    $tar[] = "&lt;embed";
  }

  $str = preg_replace($src,$tar,$str);
  return $str;
}

# Email ��ũ�� ����� ���� �Լ�
function url_link($url, $str = "", $no = 0) {
  global $table, $board, $rmail, $o;
  $str = $str ? $str : $url;

  if(check_email($url) && $rmail[uses]) {
    if(eregi("^s|d$",$o[at]) && ($o[sc] == "n" || $o[sc] == "a")) {
      $strs = preg_replace("/<[^>]+>/i","",$str);
    } else $strs = $str;

    $url = str_replace("@",$rmail[chars],$url);
    $str = "<A HREF=./act.php?o[at]=ma&target=$url ".
           "onMouseOut=\"window.status=''; return true;\" ".
           "onMouseOver=\"window.status='Send mail to $strs'; return true;\">$str</A>";
  } else if(check_url($url)) {
    $str = "<A HREF=\"$url\" target=\"_blank\">$str</A>";
  } else {
    if($str == $url) $str = "";
    $str = "$str";
  }

  return $str;
}

# File upload�� ���� �Լ�
#
#
# mkdir            -> directory ����
# is_upload_file   -> upload file�� ���缺 ����
# move_upload_file -> tmp�� upload�Ǿ� �ִ� ������ ���ϴ� ���丮�� ��ġ
# chmod            -> file, direcoty�� ���� ����
#
function file_upload($updir) {
  global $userfile_size, $userfile, $userfile_name;
  global $upload, $langs, $table;

  if(is_uploaded_file($userfile)) {
    if ($userfile_size > $upload[maxsize]) {
      print_error($langs[act_md],250,150,1);
      exit;
    }

    # file name�� ������ ���� ��� ���� ����
    $userfile_name = eregi_replace(" ","",$userfile_name);

    # file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
    upload_name_chk($userfile_name);

    # php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
    $userfile_name = eregi_replace("[\.]*$","",$userfile_name);
    $userfile_name = eregi_replace(".(ph|inc|php[0-9a-z]*|phtml)$",".phps", $userfile_name);
    $userfile_name = eregi_replace("(.*)\.(cgi|pl|sh|html|htm|shtml|vbs|jsp)$", "\\1_\\2.phps", $userfile_name);

    mkdir("data/$table/$upload[dir]/$updir",0755);
    move_uploaded_file($userfile,"data/$table/$upload[dir]/$updir/$userfile_name");
    chmod("data/$table/$upload[dir]/$updir/$userfile_name",0644);

    $up = 1;
  } elseif($userfile_name) {
    if($userfile_size == '0') {
      print_error($langs[act_ud],250,150,1);
    } else {
      print_error($langs[act_ed],250,150,1);
    }
    exit;
  }
  return $up;
}

# HTML entry�� Ư�� Ư�� ���ڷ� ��ȯ
# (htmlspecialchars �Լ��� ���Լ�)
#
# get_html_translation_table - htmlspecialchars()�� htmlentities() �Լ�����
#                              ����ϴ� ��ȯ ���̺��� �迭�� ��ȯ
# array_flip                 - �迭 ������ ������ �ݴ�� 
#
function unhtmlspecialchars($t) {
  $tr = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  $t = strtr(str_replace("&#039;","'",$t),$tr);
  $t = strtr(str_replace("&amp;","&",$t),$tr);

  return $t;
}
?>
