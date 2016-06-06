<?
# html����� ���� ��� IE���� ������ ���� �ʴ� ���� ǥ���� ������ ���� ����
function ugly_han($text,$html=0) {
  if (!$html) $text = eregi_replace("&amp;(#|amp)","&\\1",$text);
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

    if (eregi("\"",$str))
      print_error("<b>[<font color=darkred>\"'</font>]</b>�� ���Ե� �˻���� �˻��ϽǼ� �����ϴ�.");
  }

  $sql = $wh ? "WHERE " : "AND ";
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
    # �ѱ� �����°� ����
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
  if(strlen($s) <= $l && !eregi("^[a-z]+$", $s))
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
# eregi_replace - ���� ǥ������ �̿��� ġȯ (��ҹ��� ����)
#                 http://www.php.net/manual/function.eregi-replace.php
function auto_link($str) {
  global $color;
  $agent = get_agent();

  $regex[file] = "gz|tgz|tar|gzip|zip|rar|mpeg|mpg|exe|rpm|dep|rm|ram|asf|ace|viv|avi|mid|gif|jpg|png|bmp|eps|mov";
  $regex[http] = "(http|https|ftp|telnet|news):\/\/(([\xA1-\xFEa-z0-9_\-]+\.[][\xA1-\xFEa-z0-9:;&#@=_~%\?\/\.\,\+\-]+)(\/|[\.]*[a-z0-9]))";
  $regex[mail] = "([\xA1-\xFEa-z0-9_\.\-]+)@([\xA1-\xFEa-z0-9_\-]+\.[a-z0-9\-\._\-]+[\.]*[\xA1-\xFEa-z0-9\?=]*)";

  # < �� ���� �±װ� �� �ٿ��� ������ ���� ��� nl2br()���� <BR> �±װ�
  # �پ� ������ ������ ���� ���� ���� �ٱ��� �˻��Ͽ� �̾���
  $str = eregi_replace("<([^<>\n]*)\n([^<>\n]+)\n([^<>\n]*)>", "<\\1\\2\\3>", $str);
  $str = eregi_replace("<([^<>\n]*)\n([^\n<>]*)>", "<\\1\\2>", $str);
  
  # Ư�� ���ڿ� ��ũ�� target ����
  $str = eregi_replace("&(quot|gt|lt)","!\\1",$str);
  
  # html���� link ��ȣ
  $str = eregi_replace("<a[ ]+href=[\"']*($regex[http])[\"']*[ ]?[^>]*>","<a href=\"\\2_orig://\\3\" target=\"_blank\">", $str);
  $str = eregi_replace("<a[ ]+href=[\"']*mailto:($regex[mail])[\"']*>","<a href=\"mailto:\\2#-#\\3\">", $str);
  $str = eregi_replace("<([a-z0-9\"'%= ]+) (background|codebase|src)=[\"']*($regex[http])[\"']*","<\\1 \\2=\"\\4_orig://\\5\"",$str);
  if($agent[br] != "MSIE") $str = eregi_replace("<embed","&lt;embed",$str);
  
  # ��ũ�� �ȵ� url�� email address �ڵ���ũ
  $str = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
  $str = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $str);
  
  # ��ȣ�� ���� ġȯ�� �͵��� ����
  $str = eregi_replace("!(quot|gt|lt)","&\\1",$str);
  $str = eregi_replace("(http|https|ftp|telnet|news)_orig","\\1", $str);
  $str = eregi_replace("#-#","@",$str);
  
  # link�� 2�� �������� �̸� �ϳ��� �ٿ���
  $str = eregi_replace("(<a href=[\"']?($regex[http])[\"']?[^>]*>)(<a href=[\"']?($regex[http])[\"']?[^>]*>)+","\\1", $str);
  $str = eregi_replace("(<a href=[\"']?mailto:($regex[mail])[\"']?>)(<a href=[\"']?mailto:($regex[mail])[\"']?>)+","\\1", $str);
  $str = eregi_replace("</a></a>","</a>",$str);
  $str = eregi_replace("([a-z])(\.)*(\" target=\"_blank)\">","\\1\\3\">",$str);
  $str = eregi_replace("([a-z])(\.)*\">","\\1\">",$str);
  
  # file link�� target �� ����
  $str = eregi_replace("(\.($regex[file])\") target=\"_blank\"","\\1",$str);
  
  return $str;
}

# Email ��ũ�� ����� ���� �Լ�
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
      print_error($langs[act_md]);
      exit;
    }

    # file name�� ������ ���� ��� ���� ����
    $userfile_name = eregi_replace(" ","",$userfile_name);

    # file name�� Ư�� ���ڰ� ���� ��� ��� �ź�
    upload_name_chk($userfile_name);

    # php, cgi, pl file�� upload�ҽÿ��� ������ �Ҽ����� phps, cgis, pls�� filename�� ����
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
