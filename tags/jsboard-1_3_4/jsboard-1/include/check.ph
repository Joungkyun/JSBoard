<?
# table �̸��� meta character �� ���ԵǾ� �ִ��� �˻��ϴ� �Լ�
# $name -> ������
# $i    -> null �̶� ������� ��� 1
# $t    -> table �̸� �˻�� 1
#
function meta_char_check($name,$i=0,$t=0) {
  if (!$i && !trim($name))  print_error(" $name Value Name Missing! You must specify a value");
  if ($t && !eregi("^[a-zA-Z]",$name)) print_error("$name Value must start with an alphabet");
  if (eregi("[^a-z0-9_\-]",$name)) print_error("Can't use special characters except alphabat, numberlic , _, - charcters");
  if ($t && eregi("^as$",$name)) print_error("Cat't use table name as &quot;as&quot;");
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
function check_passwd($table, $no, $passwd) {
  global $sadmin, $admin;

  if ($table && $no) {
    $result = sql_query("SELECT passwd FROM $table WHERE no = $no");
    $crypt  = sql_result($result, 0, "passwd");
    sql_free_result($result);
  }

  $spasswd = crypt($passwd,$sadmin[passwd]);
  $upasswd = crypt($passwd,$admin[passwd]);

  if(crypt($passwd, $crypt) == $crypt || $sadmin[passwd] == $spasswd || $admin[passwd] == $upasswd) {
    return 1;
  }
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

# ��� ������ ������ üũ
# file() - file�� �о� ���پ� �迭�� ����
#
function chk_spam_browser($file = "config/allow_browser.txt") {
  $agent_env = getenv("HTTP_USER_AGENT");

  if(@file_exists($file)) {
    $br = file($file);
    for($i=0;$i<sizeof($br);$i++) {
      $br[$i] = trim($br[$i]);
      if (eregi($br[$i],$agent_env)) {
        return 1;
        break;
      }
    }
    return 0;
  } else return 1;
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

# ������� üũ �Լ�
#
function enable_write($super,$user,$check,$ena, $cena = 1) {
  global $kind, $table, $no, $wcheck, $langs, $page, $adminsession;

  if($no) $nom = "&no=$no";
  $page = $page? "&page=$page" : "";

  if ($wcheck && !$adminsession && !$check) {
    SetCookie("pcheck","",0);
    header("Location: auth_ext.php?table=$table&ena=$ena&cena=$cena&kind=$kind$page$nom");
    die;
  }

  if($adminsession) {
    $pscheck = $adminsession;
  } else {
    $pucheck = crypt($check,$user);
    $pscheck = crypt($check,$super);
  }

  $selffile ="act.php?o[at]=se&o[se]=logout&table=$table&kind=$kind$nom";
  if (!$ena) $user_m = "$langs[chk_a]";
  $ment = "$langs[chk_wa]";

  if (!$ena) {
    if($super != $pscheck) missmatch_passwd($selffile,$ment);
  } else if($ena && !$cena) {
    if($super != $pscheck && $user != $pucheck) missmatch_passwd($selffile,$ment);
  }
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
  } else if (eregi("^(avi|mpg|mpeg|asf|swf)$",$t)) $icon = "mpeg.gif";
  else if (eregi("^(jpg|gif|bmp|psd)$",$t)) $icon = "pic.gif";
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

  if(!trim($f)) print_error($langs[act_de]);

  # file �̸����� Ư�����ڰ� ������ ���� ���
  if (eregi("[^\xA1-\xFEa-z0-9\._\-]|\.\.",urldecode($f))) {
    print_error($langs[act_de]);
    exit;
  }
}

# ���������� ������ ���� ���� üũ �Լ�.
# referer ���� rmail[bbs] ������ ������ ���� ������ ��쿡��
# �����
#
function check_location($n=0) {
  global $board, $langs;

  if($n) {
    $sre[] = "/http[s]?:\/\/([^\/]+)\/.*/i";
    $tre[] = "\\1";
    $sre[] = "/:[0-9]+/i";
    $tre[] = "";

    $r = gethostbyname(preg_replace($sre,$tre,getenv("HTTP_REFERER")));
    $l = gethostbyname(preg_replace($sre,$tre,$rmail[bbshome]));

    if ($r != $l) {
      print_error("$langs[chk_lo]");
      return 0;
    } else return 1;
  } else return 1;
}
?>
