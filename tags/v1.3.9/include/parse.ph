<?
# html����� ���� ��� IE���� ������ ���� �ʴ� ���� ǥ���� ������ ���� ����
function ugly_han($text,$html=0) {
  if (!$html) $text = preg_replace("/&amp;(#|amp)/i","&\\1",$text);
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

# �˻������� �Ѿ�� ���� SQL ���ǹ����� �ٲ�
#
# trim         - ���ڿ� ������ ���� ���ڸ� ����
#                http://www.php.net/manual/function.trim.php
# rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
#                http://www.php.net/manual/function.rawurldecode.php
function search2sql($o, $wh = 1) {
  if($o[at] != "s") return;

  $str = rawurldecode($o[ss]); # �˻� ���ڿ��� ��ȣȭ
  $str = trim($str);

  if(strlen(stripslashes($str)) < 3 && !$o[op]) {
    if($o[sc] != "r" && $o[st] != "t")
      print_error("�˻���� �ѱ� 2��, ���� 3�� �̻��̾�� �մϴ�.");
  }

  if(!$o[er]) {
    # %�� SQL ���ǿ��� And �������� ���̹Ƿ� \�� �ٿ��� �Ϲ� �������� ��Ÿ��
    $str = str_replace("%","\%",$str);
    # \%\%�� and �������� �����Ͽ� %�� ����
    $str = str_replace("\%\%","%",$str);
    $str = addslashes($str);

    if (preg_match("/\"/",$str))
      print_error("<b>[<font color=darkred>\"'</font>]</b>�� ���Ե� �˻���� �˻��ϽǼ� �����ϴ�.");
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

  $sql = $wh ? "WHERE " : "AND ";
  $today = get_date();
  $month = $today - (60 * 60 * 24 * 30);
  $week  = $today - (60 * 60 * 24 * 7);

  switch($o[st]) {
    case 't': $sql .= "(date >= '$today')";
      break; # ����
    case 'w': $sql .= "(date >= '$week') AND ";
      break; # �����ϰ�
    case 'm': $sql .= "(date >= '$month') AND ";
      break; # �Ѵް�
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
  global $board; # �Խ��� �⺻ ���� (config/global.ph)
  global $o;

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

    # regex ���� �浹�Ǵ� ���� escape ó��
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
  if(strlen($s) <= $l && !preg_match("/^[a-z]+$/i", $s))
    return $s;

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
  $agent = get_agent();

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
  $tar[] = "<A HREF=\"act.php?o[at]=ma&target=\\1__at__\\2";

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

# File upload�� ���� �Լ�
#
#
# mkdir            -> directory ����
# is_upload_file   -> upload file�� ���缺 ����
# move_upload_file -> tmp�� upload�Ǿ� �ִ� ������ ���ϴ� ���丮�� ��ġ
# chmod            -> file, direcoty�� ���� ����
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

    # file name�� ������ ���� ��� ���� ����
    $ufile[name] = preg_replace("/ /","",$ufile[name]);

    # file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
    upload_name_chk($ufile[name]);

    # php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
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
