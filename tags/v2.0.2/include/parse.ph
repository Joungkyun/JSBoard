<?
# html����� ���� ��� IE���� ������ ���� �ʴ� ���� ǥ���� ������ ���� ����
function ugly_han($text,$html=0) {
  if (!$html) $text = preg_replace("/&amp;(#|amp)/i","&\\1",$text);
  else $text = str_replace("&amp;","&",$text);
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

  $url = preg_replace("/(%5C)%5C/i","\\1",$url);
  return $url;
}

# �˻������� �Ѿ�� ���� SQL ���ǹ����� �ٲ�
#
# trim         - ���ڿ� ������ ���� ���ڸ� ����
#                http://www.php.net/manual/function.trim.php
# rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
#                http://www.php.net/manual/function.rawurldecode.php
function search2sql($o, $wh = 1, $join = 0) {
  global $langs;
  if($o[at] != "s" && $o[at] != "d") return;

  $str = rawurldecode($o[ss]); # �˻� ���ڿ��� ��ȣȭ
  $str = trim($str);
  $join = $join ? "tb." : "";

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

    if (preg_match("/\"/",$str)) print_error($langs[nochar],250,150,1);
  } else {
		# ���� ǥ����: �˻�� "[,("�� ���������� "],)"�� ���� ���� ��� üũ
    $chk = preg_replace("/\\\([\]\[()])/i","",$str);
    $chk = preg_replace("/[^\[\]()]/i","",$chk);

    $chkAOpen = strlen(preg_replace("/\]/i","",$chk));
    $chkAClos = strlen(preg_replace("/\[/i","",$chk));
    $chkBOpen = strlen(preg_replace("/\)/i","",$chk));
    $chkBClos = strlen(preg_replace("/\(/i","",$chk));

		if($chkAOpen !== $chkAClos) $str .= "]";
		elseif($chkBOpen !== $chkBClos) $str .= ")";
  }

  if($o[at] == "d") {
    # �˻� �����ڿ� ���� �˻��� �и�
    $src = array("/\\\\\\\\/i","/\\\\\+/i","/\\\\\-/i","/\+/i","/\-/i");
    $tar = array("\\","!pluschar!","!minuschar!","!explode!p!","!explode!m!");
    $strs = preg_replace($src,$tar,$str);
    $strs = str_replace("!pluschar!","+",$strs);
    $strs = str_replace("!minuschar!","-",$strs);
    $strs = explode("!explode",$strs);

    for($i=0;$i<sizeof($strs);$i++) {
      $lenchk = strlen(trim(preg_replace("/!(m|p)!/i","",$strs[$i])));
      if($lenchk < 3) print_error($langs[nsearch],250,150,1);
    }
  }

  $sql = $wh ? "WHERE " : "AND ";

  if($o[at] != "d") {
    $today = get_date();
    $month = $today - (60 * 60 * 24 * 30);
    $week  = $today - (60 * 60 * 24 * 7);

    switch($o[st]) {
      case 't': $sql .= "({$join}date >= $today)";
        break; # ����
      case 'w': $sql .= "({$join}date >= $week) AND ";
        break; # �����ϰ�
      case 'm': $sql .= "({$join}date >= $month) AND ";
        break; # �Ѵް�
    }
  } else {
    $startday = mktime(0,0,0,$o[m1],$o[d1],$o[y1]);
    $endday = mktime(23,59,59,$o[m2],$o[d2],$o[y2]);
    $sql .= "({$join}date BETWEEN $startday AND $endday) AND ";
  }

  if($o[at] != "d") {
    if($o[er]) $str = "REGEXP \"$str\"";
    else $str = "LIKE \"%$str%\"";

    switch($o[sc]) {
      case 'a': $sql .= "({$join}title $str OR {$join}text $str OR {$join}name $str)";
        break;
      case 'c': $sql .= "({$join}text $str)";
        break;
      case 'n': $sql .= "({$join}name $str)";
        break;
      case 't': $sql .= "({$join}title $str)";
        break;
      case 'r': $sql .= "({$join}no = $o[no] OR {$join}reto = $o[no])";
        break;
    }
  } else {
    $likeregex = $o[er] ? "REGEXP" : "LIKE";
    $pchar = !$o[er] ? "%" : "";
    switch($o[sc]) {
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

    $sql = preg_replace("/((title|name|text) (LIKE|REGEXP) \"%?)!p!/i"," AND \\1",$sql);
    $sql = preg_replace("/((title|name|text) (LIKE|REGEXP) \"%?)!m!/i"," OR \\1",$sql);
  }

  return $sql;
}

# �˻� ���ڿ� ���̶����� �Լ�
#
function search_hl($list) {
  global $board ,$o;

  $hl = explode("STR", $board[hl]);
  if(!$o[ss]) return $list;

  $str = rawurldecode($o[ss]);
  $str = trim($str);
  $str = stripslashes($str);

  # ���� ǥ����: �˻�� "[,("�� ���������� "],)"�� ���� ���� ��� üũ
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
  }

  # regex ���� �浹�Ǵ� ���� escape ó��
  $dead = array("/\?|\)|\(|\*|\.|\^|\+|\%/i");
  $live = array("\\\\\\0");
  $str = preg_replace($dead,$live,$str);

  if($o[at] != "d") {
    # %% �˻��� �ʿ� ����
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
  $regex3 = "(HREF|SRC)=([^<>]*)$hl[0]([^<]*)<\/FONT>([^>]*)";

  $src = array("/$regex1/i","/$regex2/i");
  $tar = array("\\1\\2\\3","\\1\\2\\3");
  $tsrc = array("/$regex1/i","/$regex2/i","/$regex3/i");
  $ttar = array("\\1\\2\\3","\\1\\2\\3","\\1=\\2\\3\\4");

  if(!$o[er]) $str = quotemeta($str);

  switch($o[sc]) {
    case 'n':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[name] = preg_replace("/$strs[$i]/i","$hl[0]\\0$hl[1]",$list[name]);
        $list[name] = preg_replace("/$regex2/i","\\1\\2\\3",$list[name]);
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
        while(true) {
          if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list[text]))
            $list[text] = preg_replace($tsrc,$ttar,$list[text]);
          else break;
        }
      }
      break;
    case 'a':
      for($i=0;$i<sizeof($strs);$i++) {
        $strs[$i] = trim($strs[$i]);
        $list[name] = preg_replace("/$strs[$i]/i","$hl[0]\\0$hl[1]",$list[name]);
        $list[name] = preg_replace($src,$tar,$list[name]);

        $list[text] = preg_replace("/$strs[$i]/i", "$hl[0]\\0$hl[1]", $list[text]);
        while(true) {
          if(preg_match("/($regex1)|($regex2)|($regex3)/i",$list[text]))
            $list[text] = preg_replace($tsrc,$ttar,$list[text]);
          else break;
        }

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
                    "/<(\/*(script|style|pre|xmp|base|span|html)[^>]*)>/i","/(=[0-9]+%)&gt;/i");
    $target = array("&lt;\\1","\\1&gt;","deny_\\1","\n","&lt;\\1&gt;","\\1>");
    $text = preg_replace($source,$target,$text);
    if(!preg_match("/<--no-autolink>/i",$text)) $text = auto_link($text);
    else $text = chop(str_replace("<--no-autolink>","",$text));

    $text = preg_replace("/(\n)?<table/i","</PRE><TABLE",$text);
    $text = preg_replace("/<\/table>(\n)?/i","</TABLE><PRE>",$text);
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
               "/<\/*(script|style|title|xmp)>/i","/<(\\?|%)/i","/(\\?|%)>/i","/(=[0-9]+%)&gt;/i",
               "/#\^--ENTER--\^#/i");
  $tar = array("#^--ENTER--^#","","","","","","&lt;\\1","\\1&gt;","\\1>","\n");

  $text = chop(preg_replace($src,$tar,$text));

  return $text;
}

# ���ڿ��� ������ ���̷� �ڸ��� �Լ�
#
# substr - ���ڿ��� ������ ������ �߶� ������
#          http://www.php.net/manual/function.substr.php
function cut_string($s,$l) {
  if(strlen($s) > $l) {
    $s = substr($s,0,$l);
    $s = preg_replace("/(([\x80-\xFE].)*)[\x80-\xFE]?$/","\\1",$s);
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
  $regex[http] = "(http|https|ftp|telnet|news|mms):\/\/(([\xA1-\xFEa-z0-9:_\-]+\.[\xA1-\xFEa-z0-9,:;&#=_~%\[\]?\/.,+\-]+)([.]*[\/a-z0-9\[\]]|=[\xA1-\xFE]+))";
  $regex[mail] = "([\xA1-\xFEa-z0-9_.-]+)@([\xA1-\xFEa-z0-9_-]+\.[\xA1-\xFEa-z0-9._-]*[a-z]{2,3}(\?[\xA1-\xFEa-z0-9=&\?]+)*)";

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
  $src[] = "/((SRC|HREF|BASE|GROUND)[ ]*=[ ]*|[^=]|^)($regex[http])/i";
  $tar[] = "\\1<A HREF=\"\\3\" TARGET=\"_blank\">\\3</a>";
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
  global $table, $board, $rmail, $o, $agent;
  $str = $str ? $str : $url;

  if($agent[br] == "MSIE") {
    $mailTarget = " Target=noPage";
    $mailFrame = "<IFRAME NAME=\"noPage\" SRC=\"\" STYLE=\"display:none;\"></IFRAME>";
  }

  if(check_email($url) && $rmail[uses]) {
    if(preg_match("/^s|d$/i",$o[at]) && ($o[sc] == "n" || $o[sc] == "a")) {
      $strs = preg_replace("/<[^>]+>/i","",$str);
    } else $strs = $str;
    $strs = str_replace("'", "\'", $strs);

    $url = str_replace("@",$rmail[chars],$url);
    $str = "<A HREF=./act.php?o[at]=ma&target=$url ".
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

# File upload�� ���� �Լ�
#
#
# mkdir            -> directory ����
# is_upload_file   -> upload file�� ���缺 ����
# move_upload_file -> tmp�� upload�Ǿ� �ִ� ������ ���ϴ� ���丮�� ��ġ
# chmod            -> file, direcoty�� ���� ����
#
function file_upload($fn,$updir) {
  global $upload, $langs, $table;

  $ufile[name] = $_FILES[$fn][name];
  $ufile[size] = $_FILES[$fn][size];
  $ufile[type] = $_FILES[$fn][type];
  $ufile[tmp_name] = $_FILES[$fn][tmp_name];

  if(is_uploaded_file($ufile[tmp_name]) && $ufile[size] > 0) {
    if ($ufile[size] > $upload[maxsize]) {
      print_error($langs[act_md],250,150,1);
      exit;
    }

    # file name�� ������ ���� ��� ���� ����
    $ufile[name] = str_replace(" ","",urldecode($ufile[name]));

    # file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
    upload_name_chk($ufile[name]);

    # php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
    $fcname[0] = "/\.*$/";
    $fvname[0] = "";
    $fcname[1] = "/\.(ph|inc|php[0-9a-z]*|phtml)$/i";
    $fvname[1] = ".phps";
    $fcname[2] = "/(.*)\.(cgi|pl|sh|html|htm|shtml|vbs)$/i";
    $fvname[2] = "\\1_\\2.phps";

    $f[name] = preg_replace($fcname, $fvname, $f[name]);

    mkdir("data/$table/$upload[dir]/$updir",0755);
    move_uploaded_file($ufile[tmp_name],"data/$table/$upload[dir]/$updir/".$ufile[name]);
    chmod("data/$table/$upload[dir]/$updir/$ufile[name]",0644);

    $up = 1;
  } elseif($ufile[name]) {
    if($ufile[size] == '0') {
      print_error($langs[act_ud],250,150,1);
    } else {
      print_error($langs[act_ed],250,150,1);
    }
    exit;
  }
  if($up) return $ufile;
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
