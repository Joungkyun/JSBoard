<?
# table �̸��� meta character �� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
# $name -> ������
# $i    -> null �̶� ������� ��� 1
# $t    -> table �̸� �˻�� 1
#
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error(" $name Value Name Missing! You must specify a value",250,150,1);
  if ($t && !eregi("^[a-zA-Z]",$name)) print_error("$name Value must start with an alphabet",250,150,1);
  if (eregi("[^a-z0-9_\-]",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters",250,150,1);
  if ($t && eregi("^as$",$name)) print_error("Cat't use table name as &quot;as&quot;",250,150,1);
}

# �α��ο� ���Ǵ� Password �� �Լ�
#
function compare_pass($l) {
  global $langs;
  $r = get_authinfo($l[id]);
  if ($r[passwd] != $l[pass]) print_pwerror($langs[ua_pw_c]);
}

# ���ڿ��� �ѱ��� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
#
# ord    - ������ ASCII ���� ������
#          http://www.php.net/manual/function.ord.php
function is_hangul($char) {
  # Ư�� ���ڰ� �ѱ��� ������(0xA1A1 - 0xFEFE)�� �ִ��� �˻�
  $char = ord($char);

  if($char >= 0xa1 && $char <= 0xfe)
    return 1;
}

# ���ĺ����� �׸��� �빮��(0x41 - 0x5a)���� �ҹ���(0x61 - 0x7a)����
# �˻��ϴ� �Լ�
#
# ord - ������ ASCII ���� ������
#       http://www.php.net/manual/function.ord.php
function is_alpha($char) {
  $char = ord($char);

  if($char >= 0x61 && $char <= 0x7a)
    return 1;
  if($char >= 0x41 && $char <= 0x5a)
    return 2;
}

# URL�� ��Ȯ�� ������ �˻��ϴ� �Լ�
#
# eregi         - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
#                 http://www.php.net/manual/function.eregi.php
# eregi_replace - ���� ǥ������ �̿��� ġȯ (��ҹ��� ����)
#                 http://www.php.net/manual/function.eregi-replace.php
function check_url($url) {
  $url = trim($url);

  # ��������(http://, ftp://...)�� ��Ÿ���� �κ��� ���� �� �⺻������
  # http://�� ����
  if(!eregi("^(http://|https://|ftp://|telnet://|news://)", $url))
    $url = eregi_replace("^", "http://", $url);

  if(!eregi("(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[][\xA1-\xFEa-zA-Z0-9:&#@=_~%\?\/\.\+-]+$", $url))
    return;
    
  return $url;
}

# E-MAIL �ּҰ� ��Ȯ�� ������ �˻��ϴ� �Լ�
#
# eregi - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
#         http://www.php.net/manual/function.eregi.php
function check_email($email) {
  $url = trim($email);
  if(!eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9\._-]+$", $url))
    return;
    
  return $url;
}

# �н����� �� �Լ�
#
# crpyt - ���ڿ��� DES�� ��ȣȭ��
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table,$no,$passwd) {
  global $jsboard, ${$jsboard}, $board;
  if($board[mode] && session_is_registered("$jsboard")) $sql_field = "name";
  else $sql_field = "passwd";

  $passwd = !trim($passwd) ? "null passwd" : $passwd;

  if ($table && $no) {
    $result = sql_query("SELECT $sql_field FROM $table WHERE no = $no");
    $r[chk] = sql_result($result,0,"$sql_field");
    sql_free_result($result);
  }

  if (session_is_registered("$jsboard")) {
    if(${$jsboard}[id] == $r[chk]) $chk = 1;
    if(${$jsboard}[pos] == 1) $chk = 2;
  }

  if(!$chk) {
    if(crypt($passwd,$r[chk]) == $r[chk]) $chk = 1;
  }

  if(!$chk || $chk == 1) {
    # �Խ��� ������ �н�����
    $result = sql_query("SELECT passwd FROM userdb WHERE nid = '$board[ad]'");
    $r[ad] = sql_result($result,0,"passwd");
    sql_free_result($result);

    # �Խ��� �����ڰ� �������� ���� ��츦 ���
    $r[ad] = !$r[ad] ? "null admin" : $r[ad];

    # ��ü ������ �н�����
    $result = sql_query("SELECT passwd FROM userdb WHERE position = 1");
    $r[su] = sql_result($result,0,"passwd");
    sql_free_result($result);

    if($r[ad] == crypt($passwd,$r[ad])) $chk = 2;
    elseif($r[su] == crypt($passwd,$r[su])) $chk =2;
  }

  if($chk) return $chk;
}

# ���� Ȯ�� �Լ�
#
function check_auth($user,$chk) {
  if(crypt($user,$chk) == $chk) return 1;
}

# ���� �˻� �Լ�
function check_spam($str, $spam_list = "config/spam_list.txt") {
  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # $list �迭�� ���� ��ŭ for���� ���� $spam_list ���Ͽ� �����Ǿ� �ִ�
  # ���ڿ���� ��ġ�ϴ� ���ڿ��� $spam_str�� �ִ��� �˻���, ���� ���
  # �������� �Ǵ��ϰ� ������ 1�� ��ȯ��, ���� ���� 0�� ��ȯ��
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

# upload file �̸����⸦ ���� file type �˻�
# substr - ���ڿ��� �Ϻκ��� ��ȯ�Ѵ�.
# strchr - ���ڿ��� ���������� ��Ÿ���� ��ġ�� ���Ѵ�
#
function check_filetype($filetype) {
  $tail = substr( strrchr($filetype, "."), 1 );
  $tail = strtolower($tail);
  return $tail;
}

function icon_check($t,$fn) {
  if (eregi("^(hwp|mov|txt)$",$t)) $icon = "$t.gif";
  else if (eregi("^(exe|com)$",$t)) $icon = "exe.gif";
  else if (eregi("^(zip|arj|gz|lha|rar|tar|tgz|ace)$",$t)) $icon = "comp.gif";
  else if (eregi("^(php|php3|phps|vbs)$",$t)) {
    if (eregi("(_htm|_cgi|_pl|_shtm|_sh)",$fn)) $icon = "html.gif";
    else $icon = "php.gif";
  } else if (eregi("^(avi|mpg|mpeg|asf|swf|wmv)$",$t)) $icon = "mpeg.gif";
  else if (eregi("^(jpg|gif|bmp|psd|png)$",$t)) $icon = "pic.gif";
  else if (eregi("^(ppt|xls|doc)$",$t)) $icon = "doc.gif";
  else if (eregi("^(ra|ram|rm)$",$t)) $icon = "ra.gif";
  else if (eregi("^(mp3|mp2|wav|mid)$",$t)) $icon = "mp3.gif";
  else $icon = "file.gif";

  return $icon;
}

function check_dnlink($table,$list) {
  global $upload;
  if (eregi("(\.phps|\.txt|\.gif|\.jpg|\.png|\.html|\.php|\.php3|\.phtml|\.sh|\.jsp|\.asp|\.htm|\.cgi|\.doc|\.hwp|\.pdf|\.rpm|\.patch|\.vbs|\.ppt|\.xls)$",$list[bofile])) {
    $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
  } else {
    if ($list[bfsize] < 51200) $dn = "act.php?o[at]=dn&dn[tb]=$table&dn[cd]=$list[bcfile]&dn[name]=$list[bofile]";
    else $dn = "./data/$table/$upload[dir]/$list[bcfile]/$list[bofile]";
  }
  return $dn;
}

# upload file �̸��� Ư�� ���ڰ� ��� �� �ִ��� �˻�
#
function upload_name_chk($f) {
  global $langs;

  if(!trim($f)) print_error($langs[act_de],250,150,1);

  # file �̸����� Ư�����ڰ� ������ ���� ���
  if (eregi("[^\xA1-\xFEa-z0-9\._\-]|\.\.",urldecode($f))) {
    print_error($langs[act_de],250,150,1);
    exit;
  }
}

# ���������� ������ ���� ���� üũ �Լ�.
# referer ���� rmail[bbs] ������ ������ ���� ������ ��쿡��
# �����
#
function check_location($n=0) {
  global $board, $langs, $agent;

  if($n && $agent[br] != "LYNX") {
    $board[referer] = check_iis() ? $GLOBALS[HTTP_REFERER] : getenv("HTTP_REFERER");

    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,$board[referer]));
    $l = gethostbyname(preg_replace($sre,$tre,$board[path]));

    if ($r != $l) {
      print_error("$langs[chk_lo]",250,150,1);
      return 0;
    } else return 1;
  } else return 1;
}

# IIS(isapi) ���� �ƴ��� �Ǵ� �Լ�
function check_iis() {
  if(php_sapi_name() == "isapi") return 1;
  else return 0;
}
?>