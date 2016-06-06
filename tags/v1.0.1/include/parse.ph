<?
// �˻� ������ �Ѿ�� ���� URL�� �ٲ��� (POST -> GET ��ȯ)
//
// trim         - ���ڿ� ������ ���� ���ڸ� ����
//                http://www.php.net/manual/function.trim.php3
// rawurlencode - RFC1738�� �°� URL�� ��ȣȭ
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

// �˻������� �Ѿ�� ���� SQL ���ǹ����� �ٲ�
//
// trim         - ���ڿ� ������ ���� ���ڸ� ����
//                http://www.php.net/manual/function.trim.php3
// rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
//                http://www.php.net/manual/function.rawurldecode.php3
function search2sql($o, $wh = 1) {
  if($o[at] != "s") return;

  $str = rawurldecode($o[ss]); // �˻� ���ڿ��� ��ȣȭ
  $str = trim($str);

  if(strlen(stripslashes($str)) < 3 && !$o[op]) {
    if($o[sc] != "r" && $o[st] != "t")
      print_error("�˻���� �ѱ� 2��, ���� 3�� �̻��̾�� �մϴ�.");
  }


  if(!$o[er]) {
    // %�� SQL ���ǿ��� And �������� ���̹Ƿ� \�� �ٿ��� �Ϲ� �������� ��Ÿ��
    $str = str_replace("%","\%",$str);
    // \%\%�� and �������� �����Ͽ� %�� ����
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
      break; // ����
    case 'w': $sql .= "(date >= $week) AND ";
      break; // �����ϰ�
    case 'm': $sql .= "(date >= $month) AND ";
      break; // �Ѵް�
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

// �˻� ���ڿ� ���̶����� �Լ�
//
// explode      - ���� ���ڿ��� �������� ���ڿ��� ����
//                http://www.php.net/manual/function.explode.php3
// rawurldecode - ��ȣȭ�� URL�� ��ȣȭ
//                http://www.php.net/manual/function.rawurldecode.php3
// trim         - ���ڿ� ������ ���� ���ڸ� ����
//                http://www.php.net/manual/function.trim.php3
// stripslashes -
//                http://www.php.net/manual/function.stripslashes.php3
// quotemeta    -
//                http://www.php.net/manual/function.quotemeta.php3
function search_hl($list) {
  global $board; // �Խ��� �⺻ ���� (config/global.ph)
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
    if ($langs[code] == "ko") $text = eregi_replace("&amp;#","&#",$text); // �ѱ� �����°� ����
    $text = "<PRE>\n$text\n</PRE>";
    $text = auto_link($text);
  }
  return $text;
}


// html ���ÿ� table�� ������ nl2br() �Լ��� �����Ű�� ����
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

// html����� ���� ��� IE���� ������ ���� �ʴ� ���� ǥ���� ������ ���� ����
function ugly_han($text,$html=0) {
  if (!$html) $text = eregi_replace("&amp;#","&#",$text);
  return $text;
}

// ���ڿ��� ������ ���̷� �ڸ��� �Լ�
//
// �ѱ��� �ѹ���Ʈ ������ �߸��� ��츦 ���� �빮�ڰ� ���� ���� ���
// �ҹ��ڿ��� ũ�� ���� ����(1.5?)�� ���� ���ڿ��� �ڸ�
//
// intval - ������ ������ ���� ������
//          http://www.php.net/manual/function.intval.php3
// substr - ���ڿ��� ������ ������ �߶� ������
//          http://www.php.net/manual/function.substr.php3
// chop   - ���ڿ� ���� ���� ������ ����
//          http://www.php.net/manual/function.chop.php3
function cut_string($str, $length) {
  if(strlen($str) <= $length && !eregi("^[a-z]+$", $str))
    return $str;

  for($co = 1; $co <= $length; $co++) {
    if(is_hangul(substr($str, $co - 1, $co))) {
      if($first) { // first ���� ������ �ѱ��� �ι�° ����Ʈ�� ����
        $second = 1;
        $first  = 0;
      } else {     // first ���� ������ �ѱ��� ù��° ����Ʈ�� ����
        $first  = 1;
        $second = 0;
      }
      $hangul = 1;
    } else {
      // �ѱ��� �ƴ� ��� �ѱ��� ���° ����Ʈ���� ��Ÿ���� ���� �ʱ�ȭ
      $first = $second = 0;
      // �빮���� ������ ���
      if(is_alpha(substr($str, $co - 1, $co)) == 2)
        $alpha++;
    }
  }
  // �ѱ��� ù��° ����Ʈ�� �� ������ ���� ���� ���� ������ ���̸� ��
  // ����Ʈ ����
  if($first) $length--;

  // ������ ���̷� ���ڿ��� �ڸ��� ���ڿ� ���� ���� ���ڸ� ������
  // ������ ���� ��� �빮���� ���̸� 1.3���� �Ͽ� �ʰ��� ��ŭ�� ��
  if($hangul) $str = chop(substr($str, 0, $length));
  else $str = chop(substr($str, 0, $length - intval($alpha * 0.5)));

  return $str;
}

// ���� ���뿡 �ִ� URL���� ã�Ƴ��� �ڵ����� ��ũ�� �������ִ� �Լ�
//
// eregi_replace - ���� ǥ������ �̿��� ġȯ (��ҹ��� ����)
//                 http://www.php.net/manual/function.eregi-replace.php3
function auto_link($str) {
  global $color;

  $regex[http] = "(http|https|ftp|telnet|news):\/\/([a-z0-9\-_]+\.[][a-zA-Z0-9:;&#@=_~%\?\/\.\+\-]+)";
  $regex[mail] = "([a-z0-9_\-]+)@([a-z0-9_\-]+\.[a-z0-9\-\._]+)";

  // < �� ���� �±װ� �� �ٿ��� ������ ���� ��� nl2br()���� <BR> �±װ�
  // �پ� ������ ������ ���� ���� ���� �ٱ��� �˻��Ͽ� �̾���
  $str = eregi_replace("<([^<>\n]+)\n([^\n<>]+)>", "<\\1 \\2>", $str);

  // Ư�� ���ڿ� ��ũ�� target ����
  $str = eregi_replace("&(quot|gt|lt)","!\\1",$str);
  $str = eregi_replace("([ ]*)target=[\"'_a-z,A-Z]+","", $str);
  $str = eregi_replace("([ ]+)on([a-z]+)=[\"'_a-z,A-Z\?\.\-_\/()]+","", $str);

  // html���� link ��ȣ
  $str = eregi_replace("<a([ ]+)href=([\"']*)($regex[http])([\"']*)>","<a href=\"\\4_orig://\\5\" target=\"_blank\">", $str);
  $str = eregi_replace("<a([ ]+)href=([\"']*)mailto:($regex[mail])([\"']*)>","<a href=\"mailto:\\4#-#\\5\">", $str);
  $str = eregi_replace("<img([ ]*)src=([\"']*)($regex[http])([\"']*)","<img src=\"\\4_orig://\\5\"",$str);

  // ��ũ�� �ȵ� url�� email address �ڵ���ũ
  $str = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
  $str = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $str);

  // ��ȣ�� ���� ġȯ�� �͵��� ���� 
  $str = eregi_replace("!(quot|gt|lt)","&\\1",$str);
  $str = eregi_replace("http_orig","http", $str);
  $str = eregi_replace("#-#","@",$str);

  // link�� 2�� �������� �̸� �ϳ��� �ٿ��� 
  $str = eregi_replace("(<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>)+<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>","\\1", $str);
  $str = eregi_replace("(<a href=([\"']*)mailto:($regex[mail])([\"']*)>)+<a href=([\"']*)mailto:($regex[mail])([\"']*)>","\\1", $str);
  $str = eregi_replace("</a></a>","</a>",$str);

  return $str;
}

// �����ϰ� ��ũ�� ����� ���� �Լ�
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
