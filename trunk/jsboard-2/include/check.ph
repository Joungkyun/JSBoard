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
  global $langs,$edb;
  $nocrypt = $edb[crypts] ? 1 : "";
  $r = get_authinfo($l[id],$nocrypt);

  if($nocrypt) {
    if (crypt($r[passwd],$l[pass]) != $l[pass]) print_pwerror($langs[ua_pw_c]);
  } else {
    if ($r[passwd] != $l[pass]) print_pwerror($langs[ua_pw_c]);
  }
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

  if(!eregi("(http|https|ftp|telnet|news):\/\/[\xA1-\xFEa-z0-9-]+\.[][\xA1-\xFEa-zA-Z0-9:&#@=_~%?\/.+-]+$", $url))
    return;
    
  return $url;
}

# E-MAIL �ּҰ� ��Ȯ�� ������ �˻��ϴ� �Լ�
#
# eregi - ���� ǥ������ �̿��� �˻� (��ҹ��� ����)
#         http://www.php.net/manual/function.eregi.php
# gethostbynamel - ȣ��Ʈ �̸����� ip �� ����
#          http://www.php.net/manual/function.gethostbynamel.php
# checkdnsrr - ���ͳ� ȣ��Ʈ �����̳� IP ��巹���� �����Ǵ� DNS ���ڵ带 üũ��
#          http://www.php.net/manual/function.checkdnsrr.php
function check_email($email,$hchk=0) {
  $url = trim($email);
  if($hchk) {
    $host = explode("@",$url);
    if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$", $url)) {
      if(checkdnsrr($host[1],"MX") || gethostbynamel($host[1])) return $url;
      else return;
    }
  } else {
    if(eregi("^[\xA1-\xFEa-z0-9_-]+@[\xA1-\xFEa-z0-9_-]+\.[a-z0-9._-]+$", $url)) return $url;
    else return;
  }
}

# �н����� �� �Լ�
#
# crpyt - ���ڿ��� DES�� ��ȣȭ��
#         http://www.php.net/manual/function.crypt.php
function check_passwd($table,$no,$passwd) {
  global $jsboard, $board;
  if($board[mode] && session_is_registered("$jsboard")) $sql_field = "name";
  else $sql_field = "passwd";

  $passwd = !trim($passwd) ? "null passwd" : $passwd;

  if ($table && $no) {
    $result = sql_query("SELECT $sql_field FROM $table WHERE no = $no");
    $r[chk] = sql_result($result,0,"$sql_field");
    sql_free_result($result);
  }

  if (session_is_registered("$jsboard")) {
    if($_SESSION[$jsboard][id] == $r[chk]) $chk = 1;
    if($_SESSION[$jsboard][pos] == 1) $chk = 2;
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
#
function check_spam($str, $spam_list = "config/spam_list.txt") {
  $open_fail = "Don't open spam list file";
  $list = file_operate($spam_list,"r",$open_fail,0,1);

  # PHP 4.1 ���� �����ϴ� mbstring �Լ� ���� ���θ� üũ�Ѵ�.
  if(extension_loaded("mbstring")) $mbt = 1;

  # mbstring �Լ��� �����ϸ� ���ڿ��� UTF-8 �� ��ȯ�Ѵ�.
  $str = $mbt ? mb_convert_encoding($str,"UTF-8") : $str;

  # $list �迭�� ���� ��ŭ for���� ���� $spam_list ���Ͽ� �����Ǿ� �ִ�
  # ���ڿ���� ��ġ�ϴ� ���ڿ��� $spam_str�� �ִ��� �˻���, ���� ���
  # �������� �Ǵ��ϰ� ������ 1�� ��ȯ��, ���� ���� 0�� ��ȯ��
  for($co = 0; $co < count($list); $co++) {
    # 2byte �� �ȵǴ� Ű����� ������ ����
    if(strlen($list[$co]) < 2) continue;

    # mbstirng �Լ��� �������� ���� ��쿡�� �ѱ� 2�� �̻� ���� 3�� �̻���� ����
    if(!$mbt && strlen($list[$co]) < 3) continue;

    # ������� �̰ų� ó���� # �� �����ϴ� ������ ����
    if(preg_match("/^#|^$/i",trim($list[$co]))) continue;

    # preg �Լ��� ����ϱ� ���� / ���ڸ� \/ �� ġȯ
    $list[$co] = str_replace("/","\/",$list[$co]);

    # mbstring �� �����ϸ� ���͸� Ű���带 UTF-8 �� ��ȯ
    $list[$co] = $mbt ? mb_convert_encoding(trim($list[$co]),"UTF-8") : $list[$co];

    if(preg_match("/$list[$co]/i", $str)) {
      return 1;
      break;
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
  if (eregi("[^\xA1-\xFEa-z0-9._\-]|\.\.",urldecode($f))) {
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
    $board[referer] = $_SERVER[HTTP_REFERER];

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

# ������� php ���� �ƴ����� �Ǵ�.
# ������� php �� ��� turn �� ��ȯ
function check_windows() {
  if(eregi("Windows",php_uname())) return 1;
  else return 0;
}

# TABLE �� ��Ȯ�ϰ� ����ߴ��� üũ�ϴ� �Լ�
# str  -> üũ�� ���ڿ�
# rep  -> 1 �� ��� �����޽��� ��� ����� echo ��
#
function check_htmltable($str,$rep='') {
  global $langs;

  # table tag �鸸 ���ΰ� ��� ����
  $s[] = "/>[^<]*</i";
  $s[] = "/#|@/i";
  $s[] = "/<(\/?(TABLE|TR|TD|TH|IFRAME))[^>]*>/i";
  $s[] = "/^[^#]*/i";
  $s[] = "/(TABLEEMARK@)[^#]*(#TABLESMARK)/i";
  $s[] = "/<[^>]*>/i";
  $s[] = "/#TABLESMARK#/i";
  $s[] = "/@TABLEEMARK@/i";
  $s[] = "/(\r?\n)+/i";

  $d[] = ">\n<";
  $d[] = "";
  $d[] = "\n#TABLESMARK#\\1@TABLEEMARK@\n";
  $d[] = "";
  $d[] = "\\1\\2";
  $d[] = "";
  $d[] = "\n<";
  $d[] = ">\n";
  $d[] = "\n";

  $str = trim(preg_replace($s,$d,$str));

  # table tag �鸸 ���� ���� \n �� �������� �迭�� ����
  $ary = explode("\n",$str);

  for($i=0;$i<sizeof($ary);$i++) {
    if(strtoupper($ary[$i]) == "<TABLE>") $opentable++;
    elseif(strtoupper($ary[$i]) == "</TABLE>") $clstable++;
    elseif(strtoupper($ary[$i]) == "<TH>") $openth++;
    elseif(strtoupper($ary[$i]) == "</TH>") $clsth++;
    elseif(strtoupper($ary[$i]) == "<TR>") $opentr++;
    elseif(strtoupper($ary[$i]) == "</TR>") $clstr++;
    elseif(strtoupper($ary[$i]) == "<TD>") $opentd++;
    elseif(strtoupper($ary[$i]) == "</TD>") $clstd++;
    elseif(strtoupper($ary[$i]) == "<IFRAME>") $openif++;
    elseif(strtoupper($ary[$i]) == "</IFRAME>") $clsif++;
  }

  if(!$rep) {
    if($opentable != $clstable) $msg = $langs[chk_tb]."\n";
    if($openth != $clsth) $msg .= $langs[chk_th]."\n";
    if($opentr != $clstr) $msg .= $langs[chk_tr]."\n";
    if($opentd != $clstd) $msg .= $langs[chk_td]."\n";
    if($openif != $clsif) $msg .= $langs[chk_if]."\n";

    $msg = $msg ? $langs[chk_ta]."\n\nError Check:\n".trim($msg)."\n" : "";

    if($msg) print_error($msg,"","",1);
  } else {
    echo "\n##  TABLE CHECK RESULT\n".
         "##  ----------------------------------------------------------------------\n".
         "##  OPEN  TABLE  TAG : $opentable\n".
         "##  CLOSE TABLE  TAG : $clstable\n##  \n".

         "##  OPEN  TH     TAG : $openth\n".
         "##  CLOSE TH     TAG : $clsth\n##  \n".

         "##  OPEN  TR     TAG : $opentr\n".
         "##  CLOSE TR     TAG : $clstr\n##  \n".

         "##  OPEN  TD     TAG : $opentd\n".
         "##  CLOSE TD     TAG : $clstd\n\n".

         "##  OPEN  IFRAME TAG : $openif\n".
         "##  CLOSE IFRAME TAG : $clsif\n\n";
  }
}

# hyper link �� ���� ������ ����
# m -> 0 : ips �� ��ϵ� Ip ������ ��ũ�� ���
#      1 : ips �� ��ϵ� Ip ������ ��ũ�� ����
#
function check_dhyper($c=0,$wips='',$m=0,$ips='') {
  global $langs;

  # $c ������ �ְ�, list read from write page ������ üũ
  if($c && preg_match("/(list|read|form|write)\.php/i",$_SERVER[PHP_SELF])) {
    # global.ph �� $board[dhyper] �� ���� �Ǿ� ������ üũ ����� ��ħ
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # ���۷��� �������� �ʰų� ips ������ ������ üũ ����
    if(!trim($ips) || !$_SERVER[HTTP_REFERER]) return;

    # ���۷����� ���� �̸��� ����
    preg_match("/^(http:\/\/)?([^\/]+)/i",$_SERVER[HTTP_REFERER],$chks);
    # ������ �̸��� ip �� ����
    $chk = gethostbyname($chks[2]);

    # chk �� �ڽŰ� �����ϸ� üũ ����
    if($chk == $_SERVER[SERVER_ADDR]) return;

    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$chk)) $val = 1;
    }

    switch($m) {
      case '1' :
        if($val) print_error($langs[chk_hy],250,250,1);
        break;
      default:
        if(!$val) print_error($langs[chk_hy],250,250,1);
        break;
    }
  }
}

# IP blocking �Լ�
#
function check_access($c=0,$wips='',$ips='') {
  global $langs;

  if($c) {
    # global.ph �� $board[ipbl] �� �����ϸ� ��ħ
    $ips = trim($wips) ? "$wips;$ips" : $ips;

    # ���� �������� �������� �ʰų� ips ������ ���ų�, �������� �ڽ��̶�� üũ ����
    if(!trim($ips) || !$_SERVER[REMOTE_ADDR] || $_SERVER[REMOTE_ADDR] == $_SERVER[SERVE_ADDR]) return;

    # spoofing üũ
    $ipchk = explode(".",$_SERVER[REMOTE_ADDR]);
    for($j=1;$j<4;$j++) $ipchk[$j] = $ipchk[$j] ? $ipchk[$j] : 0;
    # �� �ڸ��� ���� 255 ���� ũ�� ip �ּҸ� ����Ƿ� üũ 
    if($ipchk[0] > 255 || $ipchk[1] > 255 || $ipchk[2] > 255 || $ipchk[3] > 255)
      print_error($langs[chk_sp],250,250,1);
 
    $addr = explode(";",$ips);
    for($i=0;$i<sizeof($addr);$i++) {
      if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|\.$/i",$addr[$i])) $addr[$i] .= ".";
      $addr[$i] = str_replace(".","\.",$addr[$i]);
      if(preg_match("/^$addr[$i]/i",$_SERVER[REMOTE_ADDR])) $val = 1;
    }

    if($val) print_error($langs[chk_bl],250,250,1);
  }
}
?>
